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
                                    <input class="form-control" id="auto_po_id" name="po_number" value="{{ $newPoNumber }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Supplier<span class="text-danger"> (For Accounting)*</span></label>
                                    <select class="form-control" style="min-width:150px" id="supplier" name="supp_name">
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
                                                <th class="col-sm-1.5">Category</th>
                                                <th class="col-md-2">Product Code</th>
                                                <th class="col-md-3">Product Name</th>
                                                <th class="col-md-1">Brand</th>
                                                <th class="col-md-1">Unit</th>
                                                <th class="col-md-1">Stock</th>
                                                <th class="col-md-1">Qty</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($products as $item)
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
                                                        <input class="form-control" name="stock[]" value="{{ $item->product_stock ?? 0 }}" readonly>
                                                    </td>
                                                    <td>
                                                        <input class="form-control" name="qty[]" value="{{ $item->qty ?? 0 }}" readonly>
                                                    </td>
                                                    <td>
                                                        <input class="form-control" name="amount[]" value="{{ $item->amount }}">
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
                                                        <input class="form-control text-right" type="text" id="payment_terms" name="payment_terms">
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

            const amountInputs = document.querySelectorAll('input[name="amount[]"]');
            const sumTotalInput = document.getElementById('sum_total');
            const grandTotalInput = document.getElementById('grand_total');

            function updateTotals() {
                let totalAmount = 0;

                amountInputs.forEach(function (input) {
                    totalAmount += parseFloat(input.value) || 0;
                });

                sumTotalInput.value = totalAmount.toFixed(2);
                grandTotalInput.value = '₱ ' + totalAmount.toFixed(2);
            }

            amountInputs.forEach(function (input) {
                input.addEventListener('input', updateTotals);
            });
            // Initialize totals on page load
            updateTotals();
        });
    </script>    

    @endsection
@endsection
