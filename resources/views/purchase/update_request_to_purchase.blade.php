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
                    
                        <!-- Request Header Section -->
                        <div class="row">
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Request ID <span class="text-danger">*</span></label>
                                    <input class="form-control" id="auto_request_id" name="request_id" value="{{ $requestDetails->request_id }}" readonly required>
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
                                    <label>Garage<span class="text-danger">*</span></label>
                                    <input class="form-control" id="garage" name="garage_name" value="{{ $requestDetails->garage_name }}" readonly>
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
                                                        <input class="form-control" name="stock[]" value="{{ $item->product_stock }}" readonly>
                                                    </td>
                                                    <td>
                                                        <input class="form-control" name="qty[]" value="{{ $item->product_qty }}" readonly>
                                                    </td>
                                                    <td>
                                                        <input class="form-control" name="amount[]" value="{{ $item->amount }}" readonly>
                                                    </td>
                                                </tr>
                                            @endforeach
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
@endsection
