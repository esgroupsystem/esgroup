@extends('layouts.master')

@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Purchase Order Request</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Purchase Order</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-sm-12">
                    <form action="{{ route('update.requestID')}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Request ID <span class="text-danger">*</span></label>
                                    <input class="form-control" id="auto_request_id" name="request_id" value="{{ $requestDetails->request_id }}" readonly required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Garage<span class="text-danger">*</span></label>
                                    <input class="form-control" id="garage" name="garage_name" value="{{ $requestDetails->garage_name }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Purchase Order ID <span class="text-danger">(For Accounting)*</span></label>
                                    <input class="form-control" name="po_number">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Supplier<span class="text-danger"> (For Accounting)*</span></label>
                                    <select class="form-control" style="min-width:150px" id="supplier" name="supp_name" required>
                                        <option value="">Selection Area</option>
                                        @foreach ($supplier as $item)
                                            <option value="{{ $item->id }}">{{ $item->supplier_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    
                        <!-- Products Table Section -->
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-white" id="tablePurchaseOrder">
                                        <thead>
                                            <tr>
                                                <th class="col-sm-2">Category</th>
                                                <th class="col-md-1">Product Code</th>
                                                <th class="col-md-3">Product Name</th>
                                                <th class="col-md-1">Brand</th>
                                                <th class="col-md-1">Unit</th>
                                                <th class="col-md-1">Qty</th>
                                                <th class="col-md-1">Partial Qty</th>
                                                <th>Amount</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($products as $item)
                                                @php
                                                    $isDisabled = ($item->remaining_qty ?? 0) <= 0;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <input class="form-control" name="category_name[]" value="{{ $item->category_name }}" readonly>
                                                    </td>
                                                    <td>
                                                        <input class="form-control" name="product_code[]" value="{{ $item->product_code }}" readonly>
                                                    </td>
                                                    <td>
                                                        <input class="form-control" name="product_name[]" value="{{ $item->product_name }}" readonly>
                                                    </td>
                                                    <td>
                                                        <input class="form-control" name="brand[]" value="{{ $item->product_brand }}" readonly>
                                                    </td>
                                                    <td>
                                                        <input class="form-control" name="unit[]" value="{{ $item->product_unit }}" readonly>
                                                    </td>
                                                    <td>
                                                        <input class="form-control text-center"  name="qty[]" value="{{ $item->remaining_qty }}" readonly>
                                                    </td>
                                                    <td>
                                                        <input class="form-control text-center partial-input" type="number" name="partial_qty[]" value="0" min="0" max="{{ $item->remaining_qty ?? 0 }}" {{ $isDisabled ? 'readonly disabled' : '' }} oninput="validatePartialQty(this)">
                                                    </td>
                                                    <td>
                                                        <input class="form-control text-right"  type="number"step="0.01"name="amount[]" value="{{ $item->amount }}" {{ $isDisabled ? 'readonly disabled' : '' }} required>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger remove-row">Remove</button>
                                                    </td>

                                                    <input type="hidden" name="product_id[]" value="{{ $item->id }}">
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover table-white">
                                        <tbody>
                                            <tr>
                                                <tr>
                                                    <td colspan="5" style="text-align: right; font-weight: bold">
                                                        Payment Terms
                                                    </td>
                                                    <td style="font-size: 16px;width: 230px">
                                                        <input class="form-control text-right" type="text" id="payment_terms" name="payment_terms" required>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" style="text-align: right; font-weight: bold">
                                                        Remarks
                                                    </td>
                                                    <td style="font-size: 16px;width: 230px">
                                                        <textarea class="form-control text-right" type="text" id="remarks" name="remarks" placeholder="R/O"></textarea>
                                                    </td>
                                                </tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right">Total</td>
                                                <td>
                                                    <input class="form-control text-right total" type="text" id="sum_total" name="total" value="0" readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" style="text-align: right; font-weight: bold">
                                                    Grand Total
                                                </td>
                                                <td style="font-size: 16px;width: 400px">
                                                    <input class="form-control text-right" type="text" id="grand_total" name="grand_total" value="₱ 0.00" readonly>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>                               
                                </div>
                            </div>
                        </div>
                    
                        <div class="submit-section">
                            <button type="submit" class="btn btn-primary submit-btn">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
    @section ('script')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const removedItemsInput = document.createElement('input');
            removedItemsInput.type = 'hidden';
            removedItemsInput.name = 'removed_items';
            removedItemsInput.value = ''; // Store removed product IDs as a comma-separated string
            document.querySelector('form').appendChild(removedItemsInput);
    
            // Attach event listeners to remove buttons
            document.querySelectorAll('.remove-row').forEach(button => {
                button.addEventListener('click', function () {
                    const row = button.closest('tr');
                    const productIdInput = row.querySelector('input[name="product_id[]"]');
    
                    if (productIdInput) {
                        let removedItems = removedItemsInput.value ? removedItemsInput.value.split(',') : [];
                        removedItems.push(productIdInput.value);
                        removedItemsInput.value = removedItems.join(',');
                    }
    
                    row.remove(); // Remove the row from the table
                    updateTotals(); // Update totals after removing a row
                });
            });
    
            // Attach event listeners to quantity and amount fields to update totals automatically
            document.querySelectorAll('input[name="qty[]"], input[name="amount[]"]').forEach(input => {
                input.addEventListener('input', updateTotals);
            });
    
            // Function to update totals
            function updateTotals() {
                let total = 0;
    
                // Loop through all remaining rows and calculate the total
                document.querySelectorAll('#tablePurchaseOrder tbody tr').forEach(row => {
                    let qty = parseFloat(row.querySelector('input[name="qty[]"]').value) || 0;
                    let amount = parseFloat(row.querySelector('input[name="amount[]"]').value) || 0;
                    total += qty * amount;
                });
    
                // Update Total and Grand Total fields
                document.getElementById('sum_total').value = total.toFixed(2);
                document.getElementById('grand_total').value = `₱ ${total.toFixed(2)}`;
            }
        });
    </script>
    
     

    @endsection
@endsection
