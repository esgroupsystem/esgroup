<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
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
    public function mainIndex(Request $request){

        $requestOrder = RequestOrder::get();
        $poOrder = PurchaseOrder::get();

        return view('purchase.purchase_order', compact('requestOrder', 'poOrder'));
    }

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

    public function getLatestRequestNumber() {
        $latestRequest = PurchaseOrder::orderBy('id', 'desc')->first();
        $latestNumber = 0;
    
        if ($latestRequest && preg_match('/#Request-(\d+)/', $latestRequest->po_number, $matches)) {
            $latestNumber = (int) $matches[1];
        }
        $formattedNumber = str_pad($latestNumber + 1, 3, '0', STR_PAD_LEFT); // Increment the latest number
    
        return response()->json([
            'success' => true,
            'latest_request_number' => $formattedNumber // Match the key name
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
}
