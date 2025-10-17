<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\PurchaseRequestOrder;
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
        // Fetch purchase transaction records by purchase_id
        $transactions = PurchaseTransaction::where('purchase_id', trim($po_number))->get();

        if ($transactions->isEmpty()) {
            return redirect()->back()->with('error', 'No transaction found for #' . $po_number);
        }

        // Get the first record for header info
        $transaction = $transactions->first();

        // Detect supplier
        $supplierValue = $transaction->supplier_name;
        if (is_numeric($supplierValue)) {
            $supplier = Supplier::find($supplierValue);
        } else {
            $supplier = Supplier::where('supplier_name', $supplierValue)->first();
        }

        return view('purchase.receipt', [
            'transaction' => $transaction,
            'transactions' => $transactions, // list of all products under this PO
            'supplier' => $supplier,
        ]);
    }
        
    // All Stocks
    public function stockMirasol(Request $request)
    {
        $categories = ProductCategory::with([
            'products' => function ($query) {
                $query->leftJoin('product_brands', 'products.product_brand', '=', 'product_brands.id')
                      ->leftJoin('product_units', 'products.product_unit', '=', 'product_units.id')
                      ->select(
                          'products.*',
                          'product_brands.brand_name as brand_name',
                          'product_units.unit_name as unit_name'
                      )
                      ->with('productTotalStocks'); // Keep the relationship for stock calculations
            }
        ])->get();
    
        return view('purchase.stockmirasol', compact('categories'));
    }
    
    // All Stocks
    public function stockBalintawak(Request $request)
    {
        $categories = ProductCategory::with([
            'products' => function ($query) {
                $query->leftJoin('product_brands', 'products.product_brand', '=', 'product_brands.id')
                      ->leftJoin('product_units', 'products.product_unit', '=', 'product_units.id')
                      ->select(
                          'products.*',
                          'product_brands.brand_name as brand_name',
                          'product_units.unit_name as unit_name'
                      )
                      ->with('productStockBalintawak'); // Keep the relationship for stock calculations
            }
        ])->get();
        
        return view('purchase.stockbalintawak', compact('categories'));
    }

    // All Stocks
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
        $poOrder = PurchaseRequestOrder::get()->unique('request_id');

        $pendingCount = 0;
        $partialCount = 0;

        // ✅ Fix: Count "Waiting for Delivery" and "Partial Received" correctly
        $waitingDeliveryCount = PurchaseTransaction::where('status_receiving', 'For Delivery')->distinct('purchase_id')->count('purchase_id');
        $partialReceivedCount = PurchaseTransaction::where('status_receiving', 'Partial Delivered')->distinct('purchase_id')->count('purchase_id');

        foreach ($poOrder as $order) {
            $statuses = PurchaseOrder::where('request_id', $order->request_id)->pluck('status');

            if ($statuses->every(fn($status) => $status == 'Pending')) {
                $order->status = 'Pending';
                $pendingCount++;
            } elseif ($statuses->contains('Pending')) {
                $order->status = 'Partial';
                $partialCount++;
            } elseif ($statuses->every(fn($status) => $status == 'Done')) {
                $order->status = 'Done';
            } else {
                $order->status = 'Unknown';
            }
        }

        $poOrder = $poOrder->sortBy(function ($item) {
            return array_search($item->request_status, ['Pending', 'Partial', 'Done']);
        });

        // ✅ Fix: Only one per PO number
        $requestOrder = PurchaseTransaction::select(
                'purchase_id',
                'request_id',
                'status_receiving',
                'payment_terms',
                DB::raw('SUM(total_amount) as total_amount')
            )
            ->groupBy('purchase_id', 'request_id', 'status_receiving', 'payment_terms')
            ->orderBy('purchase_id', 'desc')
            ->get();

        return view('purchase.purchase_order', compact(
            'poOrder',
            'requestOrder',
            'pendingCount',
            'partialCount',
            'waitingDeliveryCount',
            'partialReceivedCount'
        ));
    }
    
    // View for Request
    public function purchaseIndex(Request $request)
    {

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
        // Get request details (header info)
        $requestDetails = PurchaseRequestOrder::where('request_id', $requestId)
            ->leftJoin('product_categories', 'purchase_request_orders.product_category', '=', 'product_categories.id')
            ->select(
                'purchase_request_orders.request_id',
                'purchase_request_orders.garage_name',
                'product_categories.category_name',
                'purchase_request_orders.request_date',
                'purchase_request_orders.request_status'
            )
            ->first();

        // Fetch all product lines for this request
        $products = PurchaseRequestOrder::where('purchase_request_orders.request_id', $requestId)
            ->leftJoin('product_categories', 'purchase_request_orders.product_category', '=', 'product_categories.id')
            ->select(
                'purchase_request_orders.id',
                'purchase_request_orders.request_id',
                'purchase_request_orders.garage_name',
                'product_categories.category_name',
                'purchase_request_orders.product_name',
                'purchase_request_orders.product_code',
                'purchase_request_orders.product_serial',
                'purchase_request_orders.product_brand',
                'purchase_request_orders.product_unit',
                'purchase_request_orders.product_qty',
                'purchase_request_orders.product_qty_sold',
                'purchase_request_orders.product_details',
                'purchase_request_orders.request_date',
                'purchase_request_orders.request_status',
                'purchase_request_orders.created_at'
            )
            ->orderBy('purchase_request_orders.id', 'asc')
            ->get()
            ->map(function ($item) {
                // Compute remaining quantity
                $item->remaining_qty = max(($item->product_qty ?? 0) - ($item->product_qty_sold ?? 0), 0);
                return $item;
            });

        // If you have a supplier table
        $supplier = Supplier::all();

        // Optional PO number generator
        $newPoNumber = $this->generatePoNumber();

        return view('purchase.update_request_to_purchase', compact(
            'requestDetails',
            'products',
            'newPoNumber',
            'supplier'
        ));
    }


    // Viewing for Receiving all products
    public function receivingIndex(Request $request)
    {
        // Get all PO items with relevant status
        $purchaseItems = PurchaseTransaction::select(
                'purchase_id',
                'request_id',
                'garage_name',
                'supplier_name',
                'payment_terms',
                'status_receiving'
            )
            ->whereIn('status_receiving', ['For Delivery', 'Partial Delivered'])
            ->orderByDesc('purchase_id')
            ->get();

        // Group by purchase_id
        $purchaseGrouped = $purchaseItems->groupBy('purchase_id');

        // Pick only the “highest” status per PO
        $purchaseReceived = $purchaseGrouped->map(function ($items) {
            // Check if any item is Partial Delivered
            $partial = $items->firstWhere('status_receiving', 'Partial Delivered');

            if ($partial) {
                return $partial; // show Partial Delivered
            } else {
                return $items->first(); // show For Delivery
            }
        });

        return view('purchase.recieving_po', ['purchaseReceived' => $purchaseReceived]);
    }


    public function fetchPurchaseOrder($id)
    {
        $transactions = PurchaseTransaction::where('purchase_id', $id)->get();

        if ($transactions->isEmpty()) {
            return response()->json(['error' => 'Purchase order not found.'], 404);
        }

        $header = $transactions->first();

        return response()->json([
            'purchaseOrder' => [
                'purchase_id' => $header->purchase_id,
                'status_receiving' => $header->status_receiving,
                'garage_name' => $header->garage_name,
                'supplier_name' => $header->supplier_name,
                'payment_terms' => $header->payment_terms,
                'remarks' => $header->remarks,
                'date_received' => $header->date_received,
                'items' => $transactions,
            ]
        ]);
    }


    // Generate New PO Number
    public function generatePoNumber()
    {
        $lastPo = PurchaseTransaction::where('purchase_id', 'like', 'PO-%')
            ->orderByRaw('CAST(SUBSTRING(purchase_id, 4) AS UNSIGNED) DESC')
            ->first();
    
        $latestNumber = 9908;
    
        if ($lastPo && preg_match('/^PO-(\d{4})$/', $lastPo->purchase_id, $matches)) {
            $latestNumber = (int) $matches[1];
        }

        $formattedNumber = str_pad($latestNumber + 1, 4, '0', STR_PAD_LEFT);
        $nextPoNumber = "PO-" . $formattedNumber;
    
        return $nextPoNumber;
    }

    // Generate New Request Number
    public function getLatestRequestNumber() 
    {
        $latestRequest = PurchaseRequestOrder::where('request_id', 'like', 'Request-%')
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

    public function getProductCodes(Request $request)
    {
        $categoryId = $request->input('category');
    
        $productCodes = Products::where('product_category', $categoryId)
            ->get(['id', 'product_name as pname', 'product_serial as serial']);
    
        if ($productCodes->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No product codes found.']);
        }
    
        return response()->json([
            'success' => true,
            'product_names' => $productCodes
        ]);
    }

    public function getProductDetails(Request $request)
    {
        $productId = $request->input('product_id');

        if (!$productId) {
            return response()->json(['success' => false, 'message' => 'Missing product_id']);
        }

        $product = Products::select('products.product_code', 'product_brands.brand_name', 'product_units.unit_name')
            ->leftJoin('product_brands', 'product_brands.id', '=', 'products.product_brand')
            ->leftJoin('product_units', 'product_units.id', '=', 'products.product_unit')
            ->where('products.id', $productId)
            ->first();

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }

        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }

    public function saveRequest(Request $request)
    {

        DB::beginTransaction();

        try {
            foreach ($request->product_code as $key => $product_code) {
                PurchaseRequestOrder::create([
                    'request_id'        => $request->request_id,
                    'garage_name'       => $request->gar_name,
                    'product_category'  => $request->category[$key],
                    'product_name'      => $request->product_name[$key],
                    'product_code'      => $product_code,
                    'product_unit'      => $request->unit[$key],
                    'product_qty'       => $request->qty[$key],
                    'request_date'      => now()->toDateString(),
                ]);
            }

            DB::commit();
            flash()->success('Successfully saved request!');
            return redirect()->route('purchase.index');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Save request failed: ' . $e->getMessage());
            flash()->error('Failed to save request.');
            return redirect()->back()->withInput();
        }
    }

    // This is process of purchasing a items
    public function updateRequest(Request $request)
    {
        DB::beginTransaction();

        try {
            foreach ($request->product_id as $key => $productId) {
                $partialQty = isset($request->partial_qty[$key]) ? (float)$request->partial_qty[$key] : 0;
                $amount     = isset($request->amount[$key]) ? (float)$request->amount[$key] : 0;

                if ($partialQty <= 0) continue; // skip empty rows

                $itemTotal = $partialQty * $amount;

                $requestOrder = PurchaseRequestOrder::find($productId);

                if ($requestOrder) {
                    $newQtySold = ($requestOrder->product_qty_sold ?? 0) + $partialQty;
                    $requestOrder->update([
                        'product_qty_sold' => $newQtySold,
                        'updated_at' => now(),
                    ]);
                }

                PurchaseTransaction::create([
                    'purchase_id'           => $request->po_number,
                    'request_id'            => $request->request_id,
                    'garage_name'           => $request->garage_name,
                    'supplier_name'         => $request->supp_name,
                    'product_code'          => $request->product_code[$key],
                    'product_complete_name' => $request->product_name[$key],
                    'product_qty'           => $partialQty,
                    'grand_total'           => $itemTotal,
                    'total_amount'          => str_replace(['₱', ',', ' '], '', $request->grand_total),
                    'payment_terms'         => $request->payment_terms,
                    'status_receiving'      => 'For Delivery',
                    'remarks'               => $request->remarks,
                    'date_received'         => null,
                    'created_at'            => now(),
                ]);
            }

            DB::commit();
            flash()->success('Purchase transaction saved successfully!');
            return redirect()->route('purchase.index');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Update request failed: ' . $e->getMessage());
            flash()->error('Failed to save purchase transaction.');
            return redirect()->back()->withInput();
        }
    }


    // Function for receiving a items
    public function saveReceived(Request $request)
    {
        DB::beginTransaction();

        try {
            // ✅ Validation
            $validated = $request->validate([
                'product_id' => 'required|array',
                'product_id.*' => 'integer|exists:purchase_transactions,id',
                'received_qty' => 'required|array',
                'received_qty.*' => 'nullable|integer|min:0',
                'purchase_id' => 'required|exists:purchase_transactions,purchase_id',
                'garage_name' => 'required|in:Mirasol,VGC,Balintawak',
            ]);

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

            $anyItemReceived = false;

            // 1️⃣ Update only the items that were actually received
            foreach ($request->product_id as $index => $productId) {
                $receivedQty = $request->received_qty[$index] ?? 0;
                if ($receivedQty <= 0) continue;

                $orderItem = PurchaseTransaction::findOrFail($productId);

                // Skip items already delivered
                if ($orderItem->status_receiving === 'Delivered') continue;

                $anyItemReceived = true;

                $newQty = $orderItem->product_qty_received + $receivedQty;
                if ($newQty > $orderItem->product_qty) {
                    return redirect()->back()->withErrors(
                        "Received quantity cannot exceed ordered quantity for {$orderItem->product_code}."
                    );
                }

                // ✅ Update received quantity and item-level status
                $orderItem->product_qty_received = $newQty;
                $orderItem->status_receiving = $newQty == $orderItem->product_qty ? 'Delivered' : 'Partial Delivered';
                $orderItem->save();

                // ✅ Update stock table
                $product = Products::where('product_code', $orderItem->product_code)->first();
                if (!$product) {
                    return redirect()->back()->withErrors("Product not found: {$orderItem->product_code}");
                }

                $existingRecord = DB::table($tableName)->where('product_id', $product->id)->first();
                if ($existingRecord) {
                    DB::table($tableName)
                        ->where('product_id', $product->id)
                        ->update(['InQty' => $existingRecord->InQty + $receivedQty]);
                } else {
                    DB::table($tableName)->insert([
                        'product_id' => $product->id,
                        'InQty' => $receivedQty,
                        'OutQty' => 0,
                    ]);
                }

                // ✅ Log to received_transactions
                DB::table('received_transactions')->insert([
                    'po_id' => $request->purchase_id,
                    'product_code' => $orderItem->product_code,
                    'product_name' => $request->product_name[$index] ?? $orderItem->product_complete_name,
                    'product_brand' => $orderItem->product_brand,
                    'product_unit' => $orderItem->product_unit,
                    'qty_received' => $receivedQty,
                    'status' => 'Received',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (!$anyItemReceived) {
                return redirect()->back()->withErrors('No quantity entered for receiving.');
            }

            // 2️⃣ Update **all items in the same PO** to ensure consistent PO-level status
            $poItems = PurchaseTransaction::where('purchase_id', $request->purchase_id)->get();

            $allDelivered = $poItems->every(fn($item) => $item->product_qty_received >= $item->product_qty);

            foreach ($poItems as $item) {
                // Update status for each item
                if ($allDelivered) {
                    $item->status_receiving = 'Delivered';
                } elseif ($item->product_qty_received > 0) {
                    $item->status_receiving = 'Partial Delivered';
                } else {
                    $item->status_receiving = 'For Delivery';
                }
                $item->save();
            }

            // ✅ Optionally update a PO-level reference (if you have one)
            // Here we use the first item as PO reference
            $purchase = $poItems->first();
            $purchase->status_receiving = $allDelivered ? 'Delivered' : 'Partial Delivered';
            $purchase->date_received = now();
            $purchase->save();

            DB::commit();
            flash()->success('Successfully saved received items and updated PO status.');
            return redirect()->route('receiving.index');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('❌ Failed to save received items', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            flash()->error('Failed to update receiving. Please try again.');
            return redirect()->back();
        }
    }

    // View for received items
    public function receivedList()
    {
        $receivedTransactions = DB::table('received_transactions')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('purchase.received_items', compact('receivedTransactions'));
    }
}
