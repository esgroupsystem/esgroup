<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\product_total_stocks;
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
use Carbon\Carbon;
use DB;
use Auth;

class PurchaseOrderController extends Controller
{
        /**
     * FUNCTION FOR STOCKS
     */
    public function receipt($po_number)
    {
        $purchaseOrders = PurchaseOrder::where('po_number', $po_number)->get();
        $purchaseOrderIds = $purchaseOrders->pluck('id');
        $receipt = PurchaseOrderItem::whereIn('purchase_order_id', $purchaseOrderIds)->get();
        $purchaseOrder = $purchaseOrders->first();
    
        // Fetch supplier information
        $supplier = Supplier::find($purchaseOrder->product_supplier);
    
        return view('purchase.receipt', compact('purchaseOrder', 'receipt', 'supplier'));
    }
        
    public function stockMirasol(Request $request)
    {
    $categories = ProductCategory::with(['products.productTotalStocks'])->get();

    return view('purchase.stockmirasol', compact('categories'));
    }
      
    public function stockBalintawak(Request $request)
    {
        $categories = ProductCategory::with(['products.productStockBalintawak'])->get();
        return view('purchase.stockbalintawak', compact('categories'));
    }

    public function stockVGC(Request $request)
    {
        $categories = ProductCategory::with(['products.productStockVgc'])->get();
        return view('purchase.stockVGC', compact('categories'));
    }


    /**
     * END OF ALL STOCKS
     */
    // Index for main
    public function mainIndex(Request $request)
    {
        $poOrder = PurchaseOrder::get()->unique('request_id');
    
        foreach ($poOrder as $order) {
            $statuses = PurchaseOrder::where('request_id', $order->request_id)->pluck('status');
    
            // Determine overall status
            if ($statuses->every(fn($status) => $status == 'Pending')) {
                $order->status = 'Pending';
            } elseif ($statuses->contains('Pending')) {
                $order->status = 'Partial';
            } elseif ($statuses->every(fn($status) => $status == 'Done')) {
                $order->status = 'Done';
            } else {
                $order->status = 'Unknown';
            }
        }
    
        $requestOrder = PurchaseTransaction::get();
    
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
    
        // Filter products to show only those with "Pending" status
        $products = PurchaseOrder::where('purchase_orders.request_id', $requestId)
            ->where('purchase_orders.status', 'Pending') // Only Pending
            ->leftJoin('product_categories', 'purchase_orders.product_category', '=', 'product_categories.id')
            ->leftJoin('purchase_order_items', 'purchase_orders.id', '=', 'purchase_order_items.purchase_order_id')
            ->select('purchase_orders.*', 'product_categories.category_name', 'purchase_order_items.qty')
            ->get();
    
        $supplier = Supplier::all();
        $newPoNumber = $this->generatePoNumber();
    
        return view('purchase.update_request_to_purchase', compact('requestDetails', 'products', 'newPoNumber', 'supplier'));
    }
    

    // Viewing for Receiving all products
    public function receivingIndex(Request $request)
    {
        $purchaseReceived = PurchaseTransaction::get();

        return view('purchase.recieving_po', compact('purchaseReceived'));
    }

    // Retrieve the PO
    public function fetchPurchaseOrder($id)
    {
        // Retrieve purchase orders and their items
        $purchaseOrders = PurchaseOrder::where('po_number', $id)
            ->with(['items' => function ($query) {
                $query->select('id', 'purchase_order_id', 'product_code', 'product_name', 'qty', 'qty_received');
            }])
            ->get();
    
        if ($purchaseOrders->isEmpty()) {
            return response()->json(['error' => 'Purchase order not found.'], 404);
        }
    
        // Compute remaining qty before sending response
        foreach ($purchaseOrders as $po) {
            foreach ($po->items as $item) {
                $item->remaining_qty = max($item->qty - $item->qty_received, 0); // Prevent negative values
            }
        }
    
        return response()->json(['purchaseOrders' => $purchaseOrders]);
    }
    
    // Generate New PO Number
    public function generatePoNumber()
    {
        $lastPo = PurchaseOrder::where('po_number', 'like', 'PO-%')
            ->orderByRaw('CAST(SUBSTRING(po_number, 4) AS UNSIGNED) DESC')
            ->first();
    
        $latestNumber = 0;
    
        if ($lastPo && preg_match('/^PO-(\d{4})$/', $lastPo->po_number, $matches)) {
            $latestNumber = (int) $matches[1];
        }

        $formattedNumber = str_pad($latestNumber + 1, 4, '0', STR_PAD_LEFT);
        $nextPoNumber = "PO-" . $formattedNumber;
    
        return $nextPoNumber;
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

    // This function is for saving REQUEST
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
                ]);
            }
            
            DB::commit();
            flash()->success('Successfully submitted, please for confirmation :)');
            return redirect()->route('purchase.index');
        }catch (\Exception $e) {
            DB::rollback();
            \Log::error('Request failed: ' . $e->getMessage());
            flash()->error('Failed to request :(');
            return redirect()->back();
        }
    }

    // This is process of purchasing a items
    public function updateRequest(Request $request)
    {
        DB::beginTransaction();
        try {
            // Get removed items from request
            $removedProductIds = $request->removed_items ? explode(',', $request->removed_items) : [];

            // Loop through displayed items and update them
            foreach ($request->product_id as $key => $productId) {

                // Skip removed items
                if (in_array($productId, $removedProductIds)) {
                    continue;
                }

                // Update purchase order details for each item
                PurchaseOrder::where('id', $productId)->update([
                    'po_number'        => $request->po_number,
                    'product_supplier' => $request->supp_name,
                    'payment_terms'    => $request->payment_terms,
                    'remarks'          => $request->remarks,
                    'status'           => 'Done',
                    'purchase_date'    => now()->toDateString(),
                ]);

                $productCode = $request->product_code[$key];
                $itemTotal = $request->qty[$key] * $request->amount[$key];

                // Ensure the product exists before updating
                $product = Products::where('product_code', $productCode)->first();
                if (!$product) {
                    flash()->error("Product not found: $productCode");
                    continue;
                }

                // Update purchase order items
                PurchaseOrderItem::where('request_id', $request->request_id)
                    ->where('product_code', $productCode)
                    ->update([
                        'amount'       => $request->amount[$key],
                        'total_amount' => $itemTotal,
                    ]);
            }

            // Remove deleted products from the order
            if (!empty($removedProductIds)) {
                PurchaseOrderItem::whereIn('id', $removedProductIds)->delete();
            }

            // Create purchase transaction
            PurchaseTransaction::create([
                'purchase_id'   => $request->po_number,
                'request_id'    => $request->request_id,
                'total_amount'  => $request->grand_total,
                'payment_terms' => $request->payment_terms,
            ]);

            DB::commit();
            flash()->success('Successfully saved purchase order, please wait for delivery.');
            return redirect()->route('purchase.index');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Update request failed: ' . $e->getMessage());
            flash()->error('Failed to update the purchase order.');
            return redirect()->back();
        }
    }      

    // Function for receiving a items
    public function saveReceived(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'product_id' => 'required|array',
                'product_id.*' => 'integer|exists:purchase_order_items,id',
                'received_qty' => 'required|array',
                'received_qty.*' => 'integer|min:0',
                'purchase_id' => 'required|exists:purchase_transactions,purchase_id',
                'garage_name' => 'required|in:Mirasol,VGC,Balintawak',
            ]);
    
            if (count($request->product_id) !== count($request->received_qty)) {
                return redirect()->back()->withErrors('Mismatched product and quantity arrays.');
            }
    
            $allItemsFullyReceived = true;
            $garageName = $request->input('garage_name');
    
            $tableMapping = [
                'Mirasol'    => 'product_total_stocks',
                'VGC'        => 'product_stock_v_g_c_s',
                'Balintawak' => 'product_stock_balintawaks',
            ];
    
            $tableName = $tableMapping[$garageName] ?? null;
    
            if (!$tableName) {
                return redirect()->back()->withErrors('Invalid garage name.');
            }
    
            foreach ($request->product_id as $index => $productId) {

                $orderItem = PurchaseOrderItem::findOrFail($productId);
                $newQty = $orderItem->qty_received + $request->received_qty[$index];
    
                if ($newQty > $orderItem->qty) {
                    return redirect()->back()->withErrors('Received quantity cannot exceed the ordered quantity.');
                }
    
                $orderItem->qty_received = $newQty;
                $orderItem->save();
    
                $product = Products::where('product_code', $orderItem->product_code)->first();
    
                if (!$product) {
                    return redirect()->back()->withErrors('Product not found in the products table.');
                }
    
                $existingRecord = DB::table($tableName)->where('product_id', $product->id)->first();
                if ($existingRecord) {
                    DB::table($tableName)
                        ->where('product_id', $product->id)
                        ->update([
                            'InQty' => $existingRecord->InQty + $request->received_qty[$index],
                        ]);
                } else {
                    DB::table($tableName)->insert([
                        'product_id' => $product->id,
                        'InQty' => $request->received_qty[$index],
                        'OutQty' => 0,
                    ]);
                }

                if ($newQty < $orderItem->qty) {
                    $allItemsFullyReceived = false;
                }
            }

            $purchaseTransaction = PurchaseTransaction::where('purchase_id', $request->purchase_id)->first();
            if (!$purchaseTransaction) {
                return redirect()->back()->withErrors('Purchase transaction not found.');
            }
            $purchaseTransaction->status_receiving = $allItemsFullyReceived ? 'Delivered' : 'Partial Delivered';
            $purchaseTransaction->save();
    
            DB::commit();
            flash()->success('Successfully saved the records :)');
            return redirect()->route('receiving.index');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Failed to save received items', ['error' => $e->getMessage()]);
            flash()->error('Failed to update the request order.');
            return redirect()->back();
        }
    }

}
