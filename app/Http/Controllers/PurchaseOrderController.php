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
    public function mainIndex(Request $request)
    {
        $requestOrder = RequestOrder::get();

        $poOrder = PurchaseOrder::get();
        $poOrder = $poOrder->unique('request_id');
    
        return view('purchase.purchase_order', compact('poOrder', 'requestOrder'));
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
        // Fetch the latest request_id
        $latestRequest = PurchaseOrder::where('request_id', 'like', '#Request-%')
            ->orderBy('request_id', 'desc')
            ->first();
        
        $latestNumber = 0;
    
        // Extract the numeric part from the request_id
        if ($latestRequest && preg_match('/#Request-(\d+)/', $latestRequest->request_id, $matches)) {
            $latestNumber = (int)$matches[1];
        }
    
        // Increment and format the next number
        $formattedNumber = str_pad($latestNumber + 1, 3, '0', STR_PAD_LEFT);
        $nextRequestId = "#Request-" . $formattedNumber;
    
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

                PurchaseOrder::create([
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
}
