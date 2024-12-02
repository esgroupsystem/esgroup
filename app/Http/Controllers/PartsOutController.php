<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\PartsOut;
use App\Models\product_total_stocks;
use App\Models\ProductStockVGC;
use App\Models\ProductStockBalintawak;
use App\Models\PurchaseTransaction;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\RequestOrder;
use App\Models\ProductCategory;
use App\Models\Supplier;
use App\Models\ProductBrand;
use App\Models\ProductUnit;
use App\Models\Products;
use App\Models\Garage;
use App\Models\BusDetails;
use Carbon\Carbon;
use DB;
use Auth;

class PartsOutController extends Controller
{
    public function mainIndex(Request $request){

         $partsrecords = PartsOut::get();

         return view('partsout.partsout', compact('partsrecords'));
    }

    public function createRequest(Request $request){
        
        $garage = Garage::get();
        $product = Products::get();
        $category = ProductCategory::get();
        $brand = ProductBrand::get();
        $unit = ProductUnit::get();
        $bus = BusDetails::all();

        return view('partsout.create_parts', compact('product', 'bus', 'category', 'brand', 'unit', 'garage' ));
    }


    // Checking Part Outs ID (Auto Generated)
    public function getLatestPartOutID()
    {
        do {
            $newPartOutID = random_int(10000000, 99999999);

            $exists = PartsOut::where('partsout_id', $newPartOutID)->exists();
        } while ($exists);

        return response()->json([
            'success' => true,
            'latest_id' => $newPartOutID,
        ]);
    }

    // Fetch data associate from that product code
    public function getProductsByCategory(Request $request)
    {
        $productCode = $request->input('product_code');
            $product = Products::select(
                'products.product_name', 
                'product_brands.brand_name', 
                'product_units.unit_name',
                'products.product_parts_details',
                'products.product_serial',
                'products.id',
            )
            ->leftJoin('product_brands', 'product_brands.id', '=', 'products.product_brand')
            ->leftJoin('product_units', 'product_units.id', '=', 'products.product_unit')
            ->where('products.product_code', $productCode)
            ->first();
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found for code: ' . $productCode]);
        }
    
        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }
    
    // Fetch Product Code in select Category in view
    public function getProductCodes(Request $request){
        $categoryId = $request->input('category');
    
        $productCodes = Products::where('product_category', $categoryId)
            ->get(['id', 'product_code as code']);
    
        if ($productCodes->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No product codes found.']);
        }
    
        return response()->json(['success' => true, 'product_codes' => $productCodes]);
    }

    // Fetch total qty of each product and check what garage
    public function getStockByGarage(Request $request)
    {
        $garageName = $request->input('garage_name');
        $productId = $request->input('product_id');
    
        // Determine the table based on the garage name
        $stock = null;
        switch ($garageName) {
            case 'Mirasol':
                $stock = product_total_stocks::where('product_id', $productId)->first();
                break;
            case 'VGC':
                $stock = ProductStockVGC::where('product_id', $productId)->first();
                break;
            case 'Balintawak':
                $stock = ProductStockBalintawak::where('product_id', $productId)->first();
                break;
            default:
                return response()->json(['success' => false, 'message' => 'Invalid garage name']);
        }
    
        if ($stock) {
            // If InQty or OutQty is null, use 0 as default
            $stockQty = (($stock->InQty ?? 0) - ($stock->OutQty ?? 0)); // Calculate available stock
            return response()->json(['success' => true, 'stockQty' => $stockQty]);
        }
    
        return response()->json(['success' => false, 'message' => 'Stock not found for the selected product']);
    }
    

}
