<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\Garage;
use App\Models\Products;
use App\Models\Supplier;
use App\Models\Stock;
use App\Models\ProductCategory;
use App\Models\PurchaseDetails;
use Carbon\Carbon;
use Auth;
use Response;
use DB;

class PurchaseOrderController extends Controller
{
    /** Display All Purchase */
    public function purchaseIndex()
    {
        $allpurchase = PurchaseOrder::leftJoin('garages', 'purchase_orders.garage_id', '=', 'garages.id')
            ->select('purchase_orders.*', 'garages.garage_name')
            ->get();
    
        return view('purchase.purchase_order', compact('allpurchase'));
    }
    public function requestIndex()
    {
        $loggedUser = Auth::user();
        $allProduct = Products::all();
        $allSupplier = Supplier::all();
        $allGarage = Garage::all();
        $allCategory = ProductCategory::all();
        $transaction_id = $this->generateUniqueTransactionID();
        $poNO = $this->checkPONumber();

        return view('purchase.create_request', compact('allProduct', 'allSupplier', 'transaction_id', 'allGarage', 'allCategory', 'poNO', 'loggedUser'));
    }

    //Save
    public function purchaseSave(Request $request)
    {
        $arrayLength = count($request->category);

        DB::beginTransaction();
        try {
            foreach ($request->category as $key => $category) {
                \Log::info('Processing category', ['category' => $category]);
    
                if (isset($request->po_no[$key], $request->product_name[$key], $request->product_code[$key], 
                          $request->garage_name[$key], $request->amount[$key], $request->qty[$key])) 
                    {
                        $categoryModel = ProductCategory::find($category);
                        $categoryName = $categoryModel->category_name;
        
                        // Save the PurchaseOrder record
                        PurchaseOrder::create([
                            'po_no' => $request->po_no[$key],
                            'product_name' => $request->product_name[$key],
                            'product_id' => $request->product_code[$key],
                            'supplier_id' => $request->supplier_name,
                            'garage_id' => $request->garage_name[$key],
                            'total_amount' => $request->amount[$key],
                            'requestor_id' => $request->user()->name,
                            'request_date' => now()->format('Y-m-d H:i:s'),
                            'isapproved' => '0',
                            'category_name' => $categoryName,
                        ]);
                    
                    // Save the PurchaseDetails record
                        PurchaseDetails::create([
                            'transaction_id' => $request->transaction_id,
                            'po_no_id' => $request->po_no[$key],
                            'store_id' => $request->garage_name[$key],
                            'order_qty' => $request->qty[$key],
                            'product_id' => $request->product_code[$key],
                        ]);
                } else {
                    \Log::error('Missing data for key: ' . $key, ['key' => $key]);
                    flash()->error('Missing data for key: ' . $key);
                    DB::rollback();
                    return redirect()->back();
                }
            }
    
            DB::commit();
            flash()->success('Request successfully submitted, Please wait for approval :)');
            return redirect()->route('purchase.list');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Failed to request items', ['error_message' => $e->getMessage(), 'stack_trace' => $e->getTraceAsString()]);
            flash()->error('Failed to request items :(');
            return redirect()->back();
        }
    }
    
    

    //Generate TransactionID
    private function generateUniqueTransactionID()
    {
        do {
            $transactionID = 'TRANS - ' . mt_rand(100000000, 999999999);
        
            $exists = PurchaseDetails::where('transaction_id', $transactionID)->exists();
        } while ($exists);
    
        return $transactionID;
    }

    //Generate PO NO
    public function checkPONumber()
    {
        do {
            $date = Carbon::now();
            $year = $date->year;
            $month = $date->month < 10 ? '0' . $date->month : $date->month;
            $day = $date->day < 10 ? '0' . $date->day : $date->day;
            $randomNum = mt_rand(1000, 9999);
    
            $poID = "PO-{$year}{$month}{$day}-{$randomNum}";
    
            $exists = PurchaseOrder::where('po_no', $poID)->exists();
        } while ($exists);
    
        // Return the PO number as JSON response
        return response()->json(['po_no' => $poID]);
    }

    public function getProductsByCategory($categoryId)
    {
        $products = Products::leftJoin('product_brands', 'product_brands.id', '=', 'products.product_brand')
            ->leftJoin('product_units', 'product_units.id', '=', 'products.product_unit')
            ->where('products.product_category', $categoryId)
            ->select(
                'products.id',
                'products.product_code',
                'product_brands.brand_name as product_brand',
                'product_units.unit_name as product_unit',
                'products.product_name'
            )
            ->get();
    
        return response()->json([
            'products' => $products
        ]);
    }
    
    public function getProductDetails($productCode)
    {
        // Retrieve the product with the required fields
        $product = Products::where('product_code', $productCode)
                          ->leftJoin('product_brands', 'product_brands.id', '=', 'products.product_brand')
                          ->leftJoin('product_units', 'product_units.id', '=', 'products.product_unit')
                          ->select('product_brands.brand_name as product_brand', 'product_units.unit_name as product_unit', 'products.product_name')
                          ->first();
    
        // Check if product exists
        if ($product) {
            return response()->json(['product' => $product]);
        } else {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }
}
