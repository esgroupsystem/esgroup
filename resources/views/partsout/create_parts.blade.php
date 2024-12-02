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
                        <h3 class="page-title">Parts Out</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Parts Out</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-sm-12">
                    <form action="#" method="POST">
                        @csrf
                        <!-- Part-Out ID -->
                        <div class="row">
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Part-Out ID <span class="text-danger">(Auto Generated)*</span></label>
                                    <input class="form-control" id="auto_partout_id" name="partout_id" readonly required>
                                </div>
                            </div>
                        </div>

                        <!-- Bus Details and Kilometers -->
                        <div class="row">
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>Bus Details <span class="text-danger">*</span></label>
                                    <select class="select" id="bus_details" name="bus_details" required>
                                        <option value="">Select Bus</option>
                                        @foreach($bus as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }} - {{ $item->body_number }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>Garage <span class="text-danger">*</span></label>
                                    <select class="select" id="garage" name="gar_name" required>
                                        <option value="">Select Garage</option>
                                            @foreach($garage as $item)
                                                <option value="{{ $item->garage_name }}">{{ $item->garage_name }}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>Kilometers <span class="text-danger">*</span></label>
                                    <input class="form-control" type="number" name="kilometers" placeholder="Enter Kilometers" required>
                                </div>
                            </div>
                        </div>

                        <!-- Products to Part-Out -->
                        <!-- Products to Part-Out -->
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-white" id="tablePartsOut">
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>Product Code</th>
                                                <th>Serial</th>
                                                <th>Product Name</th>
                                                <th>Brand</th>
                                                <th>Unit</th>
                                                <th>Details</th>
                                                <th>Quantity</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <select class="form-control category-select" name="category_id[]" required disabled>
                                                        <option value="">Select Category</option>
                                                        @foreach($category as $item)
                                                            <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control product-code-select" name="product_code[]" required disabled>
                                                        <option value="">Select Product Code</option>
                                                    </select>
                                                </td>
                                                <td><input class="form-control" type="text" name="serial[]" readonly disabled></td>
                                                <td><input class="form-control" type="text" name="product_name[]" readonly disabled></td>
                                                <td><input class="form-control" type="text" name="brand[]" readonly disabled></td>
                                                <td><input class="form-control" type="text" name="unit[]" readonly disabled></td>
                                                <td><input class="form-control" type="text" name="details[]" readonly disabled></td>
                                                <td><input class="form-control" type="number" name="quantity[]" placeholder="Enter Quantity" required disabled></td>
                                                <td class="text-center">
                                                    <a href="javascript:void(0)" class="text-success font-18 add-row" title="Add"><i class="fa fa-plus"></i></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <!-- Submit Button -->
                        <div class="submit-section">
                            <button type="submit" class="btn btn-primary submit-btn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->

@section('script')
    <script>
            $(document).ready(function() {
                // Disable the table fields initially
                disableTableFields();

                // Fetch and set Part-Out ID
                $.getJSON('/get-latest-partout-id', function(data) {
                    if (data.success) {
                        $('#auto_partout_id').val(data.latest_id);
                    }
                });

                // Load product codes on category change
                $(document).on('change', '.category-select', function() {
                    const row = $(this).closest('tr');
                    const categoryId = $(this).val();

                    $.getJSON('/get-product-parts-codes', { category: categoryId }, function(data) {
                        const productCodeSelect = row.find('.product-code-select');
                        productCodeSelect.empty().append('<option value="">Select Product Code</option>');
                        if (data.success) {
                            data.product_codes.forEach(function(code) {
                                productCodeSelect.append(`<option value="${code.code}">${code.code}</option>`);
                            });
                        } else {
                            alert(data.message || 'No product codes found for this category.');
                        }
                    });
                });

                // Load product details on product code change
                $(document).on('change', '.product-code-select', function() {
                    const row = $(this).closest('tr'); // Get the current row
                    const productCode = $(this).val(); // Get the selected product code

                    if (productCode) {
                        $.getJSON('/get-product-parts', { product_code: productCode }, function(data) {
                            if (data.success) {
                                // Populate the fields with the fetched product details
                                row.find('input[name="serial[]"]').val(data.product.product_serial);
                                row.find('input[name="product_name[]"]').val(data.product.product_name);
                                row.find('input[name="brand[]"]').val(data.product.brand_name);
                                row.find('input[name="unit[]"]').val(data.product.unit_name);
                                row.find('input[name="details[]"]').val(data.product.product_parts_details);
                            } else {
                                alert(data.message || "Product details not found!");
                            }
                        });
                    } else {
                        // Clear the fields if no product code is selected
                        row.find('input[name="serial[]"]').val('');
                        row.find('input[name="product_name[]"]').val('');
                        row.find('input[name="brand[]"]').val('');
                        row.find('input[name="unit[]"]').val('');
                        row.find('input[name="details[]"]').val('');
                    }
                });

                // Add new row
                $(document).on('click', '.add-row', function() {
                    const newRow = $('#tablePartsOut tbody tr:first').clone();
                    newRow.find('input, select').val('').prop('disabled', true); // Disable new row inputs
                    $('#tablePartsOut tbody').append(newRow);
                });

                // Enable table when garage is selected
                $('#garage').change(function() {
                    if ($(this).val()) {
                        enableTableFields();
                    } else {
                        disableTableFields();
                    }
                });

                // Function to disable table fields
                function disableTableFields() {
                    $('#tablePartsOut tbody tr').find('input, select').prop('disabled', true);
                }

                // Function to enable table fields
                function enableTableFields() {
                    $('#tablePartsOut tbody tr').find('input, select').prop('disabled', false);
                }
                
            });
    </script>


@endsection
@endsection
