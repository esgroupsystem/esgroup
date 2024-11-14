<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\Garage;
use App\Models\Products;
use App\Models\Supplier;
use App\Models\ProductCategory;
use App\Models\PurchaseDetails;
use Carbon\Carbon;
use Response;
use DB;

class PurchaseOrderController extends Controller
{
    /** Display All Purchase */
    public function purchaseIndex()
    {
        $allpurchase = PurchaseOrder::all();

        return view('purchase.purchase_order', compact('allpurchase'));
    }
    public function requestIndex()
    {
        $allProduct = Products::all();
        $allSupplier = Supplier::all();
        $allGarage = Garage::all();
        $allCategory = ProductCategory::all();
        $transaction_id = $this->generateUniqueTransactionID();

        return view('purchase.create_request', compact('allProduct', 'allSupplier', 'transaction_id', 'allGarage', 'allCategory'));
    }

    //Save
    public function purchaseSave(Request $request)
    {
       $request->validate([
          'supplier_name',   
       ]);

       DB::beginTransaction();
       try{

        PurchaseOrder::create([
           'supplier_name'        => $request->supplier_name,
           'supplier_status'      => 'Active',

           ]);

           DB::commit();
           flash()->success('Created new supplier successfully :)');
           return redirect()->back();
       } catch ( \Exception $e ){
           DB::rollback();
           flash()->error('Failed to add supplier :(');
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
    public function checkPONumber(Request $request)
    {
        $poNumber = $request->input('po_no');
        $exists = PurchaseOrder::where('po_no', $poNumber)->exists();

        return response()->json(['exists' => $exists]);
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
                          ->select('product_brand', 'product_unit', 'product_name') // Select only the relevant columns
                          ->first();
    
        // Check if product exists
        if ($product) {
            return response()->json(['product' => $product]);
        } else {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }
}
