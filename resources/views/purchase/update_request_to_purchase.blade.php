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
                    <form action="#" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Request ID <span class="text-danger">*</span></label>
                                    <input class="form-control" id="auto_request_id" name="request_id" value="{{ $request->first()->request_id }}" readonly required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Purchase Order ID <span class="text-danger">(For Accounting)*</span></label>
                                    <input class="form-control" id="auto_po_id" name="po_number" value="PO-{{ $newPoNumber }}" readonly>
                                </div>
                            </div>
                            <!-- Other form fields go here -->
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-white" id="tablePurchaseOrder">
                                        <thead>
                                            <tr>
                                                <th class="col-sm-2">Category</th>
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
                                            @foreach($request as $order)
                                                @foreach($order->items as $item)
                                                    <tr>
                                                        <td>
                                                            <select class="form-control category-select" style="min-width:150px" name="category[]">
                                                                <option value="{{ $item->category_id }}">{{ $item->category_name }}</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-control product-code-select" style="min-width:150px" name="product_code[]">
                                                                <option value="{{ $item->product_code }}">{{ $item->product_code }}</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input class="form-control" style="min-width:100px" type="text" name="product_name[]" value="{{ $item->product_name }}" readonly>
                                                        </td>
                                                        <td>
                                                            <input class="form-control" style="min-width:80px" type="text" name="brand[]" value="{{ $item->brand }}" readonly>
                                                        </td>
                                                        <td>
                                                            <input class="form-control" style="min-width:80px" type="text" name="unit[]" value="{{ $item->unit }}" readonly>
                                                        </td>
                                                        <td>
                                                            <input class="form-control" style="min-width:120px" type="text" name="stock[]" value="{{ $item->stock }}" readonly>
                                                        </td>
                                                        <td>
                                                            <input class="form-control" style="min-width:80px" type="text" name="qty[]" value="{{ $item->qty }}" readonly>
                                                        </td>
                                                        <td>
                                                            <input class="form-control" style="min-width:120px" type="text" name="amount[]" value="{{ $item->amount }}" readonly>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="submit-section">
                            <button type="submit" class="btn btn-primary submit-btn">Updated</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
@endsection
