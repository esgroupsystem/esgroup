
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
                    <form action="{{ route('save.request') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Request ID <span class="text-danger">(Auto Generated)*</span></label>
                                    <input class="form-control" id="auto_request_id" name="request_id" value="" readonly required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Garage<span class="text-danger">*</span></label>
                                    <select class="select" id="garage" name="gar_name" required>
                                        <option value="">Select Garage</option>
                                            @foreach($garage as $item)
                                                <option value="{{ $item->garage_name }}">{{ $item->garage_name }}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-white" id="tablePurchaseOrder">
                                        <thead>
                                            <tr>
                                                <th class="col-sm-3">Category</th>
                                                <th class="col-md-3">Product Code</th>
                                                <th class="col-md-3">Product Name</th>
                                                <th class="col-md-1">Brand</th>
                                                <th class="col-md-1">Unit</th>
                                                <th class="col-md-1">Qty</th>
                                                <th> </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <select class="form-control category-select" style="min-width:150px" name="category[]">
                                                        <option value="">Selection Area</option>
                                                        @foreach ($category as $item)
                                                            <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                                                        @endforeach
                                                    </select>                                                 
                                                </td>
                                                <td>
                                                    <select class="form-control product-code-select" style="min-width:150px" id="p_code" name="product_code[]">
                                                        <option value="">Selection Area</option>
                                                    </select>                                                 
                                                </td>
                                                <td>
                                                    <input class="form-control" style="min-width:100px" type="text" id="p_name" name="product_name[]" readonly>
                                                </td>
                                                <td>
                                                    <input class="form-control" style="min-width:80px" type="text" id="brand" name="brand[]" readonly>
                                                </td>
                                                <td>
                                                    <input class="form-control" style="min-width:80px" type="text" id="unit" name="unit[]" readonly>
                                                </td>
                                                <td>
                                                    <input class="form-control" style="min-width:80px" type="text" id="qty" name="qty[]" placeholder="0">
                                                </td>
                                                <td class="text-center">
                                                    <a href="javascript:void(0)" class="text-success font-18" title="Add" id="addBtn"><i class="fa fa-plus"></i></a>
                                                </td>
                                            </tr>
                                        </tbody>                                        
                                    </table>
                                </div>
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
        $(document).ready(function () {
            let rowIdx = 1;

            // Function to add a new row
            function addRow(poNumber) {
                const newRow = `
                    <tr>
                        <td>
                            <select class="form-control category-select" style="min-width:150px" name="category[]">
                                <option value="">Selection Area</option>
                                @foreach ($category as $item)
                                    <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                                @endforeach
                            </select>                                                  
                        </td>
                        <td>
                            <select class="form-control product-code-select" style="min-width:150px" name="product_code[]">
                                <option value="">Selection Area</option>
                            </select>                                                  
                        </td>
                        <td>
                            <input class="form-control" style="min-width:100px" type="text" name="product_name[]" readonly>
                        </td>
                        <td>
                            <input class="form-control" style="min-width:80px" type="text" name="brand[]" readonly>
                        </td>
                        <td>
                            <input class="form-control" style="min-width:80px" type="text" name="unit[]" readonly>
                        </td>
                        <td>
                            <input class="form-control" style="min-width:80px" type="text" name="qty[]" placeholder="0">
                        </td>
                        <td>
                            <a href="javascript:void(0)" class="text-danger font-18 remove" id="trashBIN" title="Remove"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>`;

                // Append the new row to the table
                $("#tablePurchaseOrder tbody").append(newRow);
                rowIdx++;  // Increment row index
            }

            // Event listener for adding a new row
            $(document).on('click', '#addBtn', function () {
                
                $('#auto_po_id').val("");
                addRow();
            });

            // Event listener for removing a row
            $(document).on('click', '.remove', function () {
                $(this).closest('tr').remove();
                rowIdx--; // Decrement row index
            });

            // Handle category selection change
            $(document).on('change', '.category-select', function () {
                const selectedCategory = $(this).val();
                const $productCodeDropdown = $(this).closest('tr').find('.product-code-select');
                $productCodeDropdown.empty().append('<option value="">Select Product Code</option>');

                if (selectedCategory) {
                    $.ajax({
                        url: '/get-product-codes',
                        type: 'GET',
                        data: { category: selectedCategory },
                        success: function (response) {
                            if (response.success) {
                                response.product_codes.forEach(function (productCode) {
                                    $productCodeDropdown.append(
                                        `<option value="${productCode.code}">${productCode.code}</option>`
                                    );
                                });
                            } else {
                                alert('No product codes found for this category.');
                            }
                        },
                        error: function () {
                            alert('Failed to fetch product codes.');
                        }
                    });
                }
            });

            // Handle product code selection change
            $(document).on('change', '.product-code-select', function () {
                const selectedProductCode = $(this).val();
                const $row = $(this).closest('tr');
                const $productNameField = $row.find('input[name="product_name[]"]');
                const $brandField = $row.find('input[name="brand[]"]');
                const $unitField = $row.find('input[name="unit[]"]');

                if (selectedProductCode) {
                    $.ajax({
                        url: '/get-product-details',
                        type: 'GET',
                        data: { product_code: selectedProductCode },
                        success: function (response) {
                            if (response.success) {
                                const product = response.product;
                                $productNameField.val(product.product_name);
                                $brandField.val(product.brand_name);
                                $unitField.val(product.unit_name);
                            } else {
                                alert('Product details not found.');
                            }
                        },
                        error: function () {
                            alert('Failed to fetch product details.');
                        }
                    });
                } else {
                    // Clear the fields if no product is selected
                    $productNameField.val('');
                    $brandField.val('');
                    $unitField.val('');
                }
            });

            // Fetch the latest REQUEST number and set the input field when the page loads
            function fetchLatestRequestNumber(callback) {
                $.ajax({
                    url: '/get-latest-request-number',
                    type: 'GET',
                    success: function (response) {
                        if (response.success) {
                            callback(response.latest_request_id);
                        } else {
                            console.error('Error fetching request number');
                        }
                    },
                    error: function () {
                        console.error('An error occurred while fetching request number');
                    }
                });
            }

            // On page load, fetch and set the next Request ID
            fetchLatestRequestNumber(function (latestRequestId) {
                $('#auto_request_id').val(latestRequestId);
            });
        });
    </script>
     
    @endsection
@endsection
