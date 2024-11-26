<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product_total_stocks;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\RequestOrder;
use App\Models\ProductCategory;
use App\Models\Supplier;
use App\Models\ProductBrand;
use App\Models\ProductUnit;
use App\Models\Products;
use App\Models\Garage;
use Carbon\Carbon;
use DB;
use Auth;

class PurchaseOrderController extends Controller
{
    // Index for main
    public function mainIndex(Request $request)
    {
        $requestOrder = RequestOrder::get();

        $poOrder = PurchaseOrder::get();
        $poOrder = $poOrder->unique('request_id');
    
        return view('purchase.purchase_order', compact('poOrder', 'requestOrder'));
    }

    // View for Request
    public function purchaseIndex(Request $request){

        $garage = Garage::get();
        $category = ProductCategory::get();
        $brand = ProductBrand::get();
        $unit = ProductUnit::get();
        $supplier = Supplier::get();
        $requestOrder = RequestOrder::get();
        $poOrder = PurchaseOrder::get();
        $product = Products::get();

        return view('purchase.create_request', compact('garage','category', 'brand', 'unit', 'supplier', 'product', 'requestOrder', 'poOrder'));
    }

    // View for updating request
    public function requestIndex(Request $request, $requestId)
    {
        $requestDetails = PurchaseOrder::where('request_id', $requestId)
            ->leftJoin('product_categories', 'purchase_orders.product_category', '=', 'product_categories.id')
            ->select('purchase_orders.*', 'product_categories.category_name')
            ->first();
    
            $products = PurchaseOrder::where('purchase_orders.request_id', $requestId)
            ->leftJoin('product_categories', 'purchase_orders.product_category', '=', 'product_categories.id')
            ->leftJoin('purchase_order_items', 'purchase_orders.id', '=', 'purchase_order_items.purchase_order_id') // Join with purchase_order_item table
            ->select('purchase_orders.*', 'product_categories.category_name', 'purchase_order_items.qty') // Select the qty from purchase_order_item
            ->get();
    
        $supplier = Supplier::all();
        $newPoNumber = $this->generatePoNumber();
    
        return view('purchase.update_request_to_purchase', compact('requestDetails', 'products', 'newPoNumber', 'supplier'));
    }
  
    // Generate New PO Number
    public function generatePoNumber()
    {
        $lastPo = PurchaseOrder::orderBy('created_at', 'desc')->first();
        $lastPoNumber = $lastPo ? (int) substr($lastPo->po_number, 3) : 0;

        $newPoNumber = $lastPoNumber + 1;
        $newPoNumberFormatted = 'PO-' . str_pad($newPoNumber, 4, '0', STR_PAD_LEFT);

        return $newPoNumberFormatted;
    }

    // Generate New Request Number
    public function getLatestRequestNumber() {
        $latestRequest = PurchaseOrder::where('request_id', 'like', 'Request-%')
            ->orderByRaw('CAST(SUBSTRING(request_id, 9) AS UNSIGNED) DESC')
            ->first();
        
        $latestNumber = 0;
        
        if ($latestRequest && preg_match('/Request-(\d+)/', $latestRequest->request_id, $matches)) {
            $latestNumber = (int)$matches[1];
        }
        
        $formattedNumber = str_pad($latestNumber + 1, 3, '0', STR_PAD_LEFT);
        $nextRequestId = "Request-" . $formattedNumber;
        
        return response()->json([
            'success' => true,
            'latest_request_id' => $nextRequestId
        ]);
    }

    public function getProductCodes(Request $request){
        $categoryId = $request->input('category');
    
        $productCodes = Products::where('product_category', $categoryId)
            ->get(['id', 'product_code as code']);
    
        if ($productCodes->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No product codes found.']);
        }
    
        return response()->json(['success' => true, 'product_codes' => $productCodes]);
    }

    public function getProductDetails(Request $request)
    {
        $productCode = $request->input('product_code');
        $query = Products::where('product_code', $productCode);
        $product = Products::select('products.product_name', 'product_brands.brand_name', 'product_units.unit_name')
                ->leftJoin('product_brands', 'product_brands.id', '=', 'products.product_brand')
                ->leftJoin('product_units', 'product_units.id', '=', 'products.product_unit')
                ->where('products.product_code', $productCode)
                ->first();
    
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found.']);
        }
    
        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }

    public function saveRequest(Request $request){

        $request->validate([
            'request_id'      => 'required|string|max:255',
            'gar_name'        => 'required|string|max:255',
            'category'        => 'required|array',
            'category.*'      => 'required|string|max:255',
            'product_code'    => 'required|array',
            'product_code.*'  => 'required|string|max:255',
            'product_name'    => 'required|array',
            'product_name.*'  => 'required|string|max:255',
            'brand'           => 'required|array',
            'brand.*'         => 'required|string|max:255',
            'unit'            => 'required|array',
            'unit.*'          => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try{
            foreach ($request->product_code as $key => $product_code) {

                $purchaseOrder = PurchaseOrder::create([
                    'request_id'        => $request->request_id,
                    'garage_name'       => $request->gar_name,
                    'product_code'      => $product_code,
                    'product_name'      => $request->product_name[$key],
                    'product_category'  => $request->category[$key],
                    'product_brand'     => $request->brand[$key],
                    'product_unit'      => $request->unit[$key],
                    'status'            => 'Pending',
                    'request_date'      => now()->toDateString(),
                ]);

                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'request_id'        => $purchaseOrder->request_id,
                    'product_code'      => $product_code,
                    'product_name'      => $request->product_name[$key],
                    'qty'               => $request->qty[$key],
                    // 'amount'            => $request->amount[$key],
                ]);
            }
            
            DB::commit();
            flash()->success('Successfully submitted, please for conformation :)');
            return redirect()->route('purchase.index');
        }catch (\Exception $e) {
            DB::rollback();
            \Log::error('Request failed: ' . $e->getMessage());
            flash()->error('Failed to request :(');
            return redirect()->back();
        }
    }


    public function updateRequest(Request $request)
    {

        DB::beginTransaction();
        try {

            PurchaseOrder::where('request_id', $request->request_id)->update([
                'po_number'       => $request->po_number,
                'product_supplier'=> $request->supp_name,
                'payment_terms'   => $request->payment_terms,
                'remarks'         => $request->remarks,
                'status'          => 'Done',
                'purchase_date'   => now()->toDateString(),
            ]);
    
            foreach ($request->product_code as $key => $productCode) {

                $product = Products::where('product_code', $productCode)->first();

                if (!$product) {
                    flash()->success('No products save in this IDs.');
                    continue;
                }
                
                $itemTotal = $request->qty[$key] * $request->amount[$key];

                $updateCount = PurchaseOrderItem::where('request_id', $request->request_id)
                        ->where('product_code', $productCode) // Use product_code here
                        ->update([
                            'amount'       => $request->amount[$key],
                            'total_amount' => $itemTotal, // Update total amount for this product
                        ]);

                product_total_stocks::create([
                    'product_id' => $product->id,
                    'InQty'      => $request->qty[$key],
                    'OutQty'     => 0,
                ]);
    
                \Log::info("Inserted stock record for product_id: {$product->id} with InQty: {$request->qty[$key]}");
            }
    
            DB::commit();
            flash()->success('Successfully updated the purchase order.');
            return redirect()->route('purchase.index');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Update request failed: ' . $e->getMessage());
            flash()->error('Failed to update the purchase order.');
            return redirect()->back();
        }
    }    
}
