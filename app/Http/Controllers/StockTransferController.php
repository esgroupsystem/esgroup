<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockTransferRecords;
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

class StockTransferController extends Controller
{
    public function transferIndex (Request $request){

        $viewTransfer = StockTransferRecords::leftJoin('product_categories', 'stock_transfer_records.product_category', '=', 'product_categories.id')
            ->select('stock_transfer_records.*', 'product_categories.category_name as category_name')
            ->get();

        return view('transfer.stocktransfer', compact('viewTransfer'));
    }

    public function createTransfer(Request $request){
        
        $garage = Garage::get();
        $product = Products::get();
        $category = ProductCategory::get();
        $brand = ProductBrand::get();
        $unit = ProductUnit::get();
        $bus = BusDetails::all();

        return view('transfer.createtransfer', compact('product', 'bus', 'category', 'brand', 'unit', 'garage' ));
    }

    // Checking Part Outs ID (Auto Generated)
    public function getLatestTransferID()
    {
        do {
            $newTransferID = random_int(10000000, 99999999);

            $exists = StockTransferRecords::where('transfer_id', $newTransferID)->exists();
        } while ($exists);

        return response()->json([
            'success' => true,
            'latest_id' => $newTransferID,
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

            $stockQty = (($stock->InQty ?? 0) - ($stock->OutQty ?? 0));
            return response()->json(['success' => true, 'stockQty' => $stockQty]);
        }
    
        return response()->json(['success' => false, 'message' => 'Stock not found for the selected product']);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
    
        // Search for products by name (modify as necessary)
        $products = Products::where('product_name', 'like', '%' . $query . '%')->get();
    
        return response()->json([
            'success' => true,
            'products' => $products,
        ]);
    }

    // POST adding Parts-Out Transaction
    public function saveTransfer(Request $request)
    {
        DB::beginTransaction();

        try {
            // Map garages to stock models
            $stockModels = [
                'Mirasol'    => \App\Models\product_total_stocks::class,
                'VGC'        => \App\Models\ProductStockVGC::class,
                'Balintawak' => \App\Models\ProductStockBalintawak::class,
            ];

            $sourceGarage = $request->gar_name;   // Source garage
            $targetGarage = $request->gar_name_2; // Target garage

            // Validate garage names
            if (!isset($stockModels[$sourceGarage]) || !isset($stockModels[$targetGarage])) {
                throw new \Exception('Invalid garage name(s).');
            }

            $sourceModel = $stockModels[$sourceGarage];
            $targetModel = $stockModels[$targetGarage];

            foreach ($request->product_code as $key => $productCode) {
                // Create PartsOut record
                $partOut = StockTransferRecords::create([
                    'transfer_id'      => $request->partout_id,
                    'sourceGarage'     => $request->gar_name,
                    'receiverGarage'   => $request->gar_name_2,
                    'product_category' => $request->category_id[$key],
                    'product_code'     => $productCode,
                    'product_serial'   => $request->serial[$key] ?? null,
                    'product_name'     => $request->product_name[$key],
                    'product_brand'    => $request->brand[$key],
                    'product_unit'     => $request->unit[$key],
                    'product_details'  => $request->details[$key] ?? null,
                    'product_outqty'   => $request->quantity[$key],
                    'status'           => 'Done',
                    'date_transfer'    => Carbon::now(),
                ]);

                // Handle stock update in source garage
                $sourceStock = $sourceModel::where('product_id', $request->name_id[$key])->first();

                if ($sourceStock) {
                    // Deduct from OutQty
                    $sourceStock->OutQty += $request->quantity[$key];
                    $sourceStock->save();
                } else {
                    throw new \Exception("Product ID {$request->name_id[$key]} not found in source garage.");
                }

                // Handle stock update in target garage
                $targetStock = $targetModel::where('product_id', $request->name_id[$key])->first();

                if ($targetStock) {
                    // Add to InQty
                    $targetStock->InQty += $request->quantity[$key];
                    $targetStock->save();
                } else {
                    // Create new record in target garage
                    $targetModel::create([
                        'product_id' => $request->name_id[$key],
                        'InQty'      => $request->quantity[$key],
                        'OutQty'     => '0',   
                    ]);
                }
            }

            DB::commit();
            flash()->success('Successfully transferred the stock.');
            return redirect()->route('transfer.index');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Stock transfer failed: ' . $e->getMessage());
            flash()->error('Failed to transfer the stock. Please try again.');
            return redirect()->back()->withInput();
        }
    }

}
