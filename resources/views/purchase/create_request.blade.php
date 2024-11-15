
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
                        <h3 class="page-title">Request Purchase Order</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Request</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
              
            <div class="row">
                <div class="col-sm-12">
                    <form action="{{ route('request.items') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Transaction ID <span class="text-danger">*</span></label>
                                    <input class="form-control" id="auto_transaction_id" name="transaction_id" value="{{ $transaction_id }}" readonly required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Store / Supplier <span class="text-danger">*</span></label>
                                    <select class="select" id="supp_name" name="supplier_name[]" required>
                                        <option value="" > -- Select Supplier -- </option>
                                        @foreach($allSupplier as $item)
                                            <option value="{{ $item->supplier_name }}">{{ $item->supplier_name }}</option>
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
                                                <th class="po_id">P/O No</th>
                                                <th hidden style="width: 20px">#</th>
                                                <th class="col-sm-2">Category</th>
                                                <th class="col-md-2">Product Code</th>
                                                <th class="col-md-3">Product Name</th>
                                                <th class="col-md-1">Brand</th>
                                                <th class="col-md-1">Unit</th>
                                                <th class="col-md-2">Unit</th>
                                                <th class="col-md-1">Stock</th>
                                                <th class="col-md-1">Qty</th>
                                                <th >Amount</th>
                                                <th> </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><input type="" name="po_no[]" class="auto_po_id" value="{{ $poNO }}"></td>
                                                <td hidden >1</td>
                                                <td>
                                                    <select class="form-control category-select" style="min-width:150px" name="category[]">
                                                        <option value="">Selection Area</option>
                                                        @foreach ($allCategory as $item )
                                                            <option value="{{ $item->id }}">{{ $item->category_name }}</option>  
                                                        @endforeach
                                                    </select>                                                
                                                </td>
                                                <td>
                                                    <select class="form-control product-code-select" style="min-width:150px" id="p_code" name="product_code[]">
                                                        <option value="">Selection Area </option>
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
                                                    <select class="form-control" style="min-width:150px" id="garage" name="garage_name[]">
                                                        <option value="">Selection Area</option>
                                                        @foreach ($allGarage as $item )
                                                            <option value="{{ $item->garage_name }}">{{ $item->garage_name }}</option>  
                                                        @endforeach
                                                    </select>  
                                                </td>
                                                <td>
                                                    <input class="form-control" style="min-width:120px" type="text" id="stock" name="stock[]" value="0" readonly>
                                                </td>
                                                <td>
                                                    <input class="form-control" style="min-width:80px" type="text" id="qty" name="qty[]">
                                                </td>
                                                <td>
                                                    <input class="form-control" style="min-width:120px" type="text" id="amount" name="amount[]" value="0">
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0)" class="text-success font-18" title="Add" id="addBtn"><i class="fa fa-plus"></i></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover table-white">
                                        <tbody>
                                            <tr>
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
                                                <td colspan="5" class="text-right">Tax</td>
                                                <td>
                                                    <input class="form-control text-right"type="text" id="tax_1" name="tax_1" value="0" readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="text-right">
                                                    Discount %
                                                </td>
                                                <td>
                                                    <input class="form-control text-right discount" type="text" id="discount" name="discount" value="0">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" style="text-align: right; font-weight: bold">
                                                    Grand Total
                                                </td>
                                                <td style="font-size: 16px;width: 230px">
                                                    <input class="form-control text-right" type="text" id="grand_total" name="grand_total" value="₱ 0.00" readonly>
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

        {{-- add multiple row --}}
        <script>
            $(document).ready(function() {
                // Category selection change event
                $(document).on('change', '.category-select', function() {
                    var categoryId = $(this).val();
                    var $productCodeSelect = $(this).closest('tr').find('.product-code-select');
                    var $brand = $(this).closest('tr').find('.brand');
                    var $unit = $(this).closest('tr').find('.unit');
                    var $productName = $(this).closest('tr').find('.product_name');

                    // Clear previous selections
                    $productCodeSelect.empty().append('<option value=""> --Choose Product Code-- </option>');
                    $brand.val('');
                    $unit.val('');
                    $productName.val('');

                    if (categoryId) {
                        $.ajax({
                            url: '/getCategory/' + categoryId,
                            method: 'GET',
                            success: function(data) {
                                if (data.products) {
                                    $.each(data.products, function(index, product) {
                                        $productCodeSelect.append(`<option value="${product.product_code}">${product.product_code}</option>`);
                                    });
                                }
                            },
                            error: function() {
                                alert('Failed to fetch product details.');
                            }
                        });
                    }
                });
        
                // Product code selection change event
                $(document).on('change', '.product-code-select', function() {
                    var productCode = $(this).val();
                    var $brand = $(this).closest('tr').find('#brand');
                    var $unit = $(this).closest('tr').find('#unit');
                    var $productName = $(this).closest('tr').find('#p_name');

                    if (productCode) {
                        $.ajax({
                            url: '/getProductDetails/' + productCode,
                            method: 'GET',
                            success: function(data) {
                                if (data.product) {
                                    $brand.val(data.product.product_brand);
                                    $unit.val(data.product.product_unit);
                                    $productName.val(data.product.product_name);
                                }
                            },
                            error: function() {
                                alert('Failed to fetch product details.');
                            }
                        });
                    }
                });
        
                // Adding a new row
                var rowIdx = 1;
                $("#addBtn").on("click", async function () {
                    // Function to fetch a unique PO number from the server
                    async function fetchPONumber() {
                        try {
                            const response = await fetch('/check-po-number');  // Call the route that generates PO numbers
                            const data = await response.json();
                            return data.po_no;  // Expecting the PO number from the backend
                        } catch (error) {
                            console.error('Error fetching PO number:', error);
                            return 'PO-ERROR';  // In case of error, use a placeholder
                        }
                    }
        
                    // Fetch the unique PO number
                    const poNumber = await fetchPONumber();
                    console.log("Generated PO Number:", poNumber);
        
                    // Create a new row with the fetched PO number
                    const newRow = `
                        <tr id="R${++rowIdx}">
                            <td hidden class="row-index text-center"><p> ${rowIdx}</p></td>
                            <td><input type="" name="po_no[]" class="auto_po_id" value="${poNumber}"></td>  <!-- Display PO number here -->
                            <td>
                                <select class="form-control category-select" style="min-width:150px" name="category[]">
                                    <option value="">Selection Area</option>
                                    @foreach ($allCategory as $item )
                                        <option value="{{ $item->id }}">{{ $item->category_name }}</option>  
                                    @endforeach
                                </select>                                                 
                            </td>
                            <td>
                                <select class="form-control product-code-select" style="min-width:150px" id="p_code" name="product_code[]">
                                    <option value="">Selection Area </option>
                                </select>                                                 
                            </td>
                            <td>
                                <input class="form-control unit_price" style="min-width:100px" type="text" id="p_name" name="product_name[]" readonly>
                            </td>
                            <td>
                                <input class="form-control qty" style="min-width:80px" type="text" id="brand" name="brand[]" readonly>
                            </td>
                            <td>
                                <input class="form-control qty" style="min-width:80px" type="text" id="unit" name="unit[]" readonly>
                            </td>
                            <td>
                                <select class="form-control" style="min-width:150px" id="garage" name="garage_name[]">
                                    <option value="">Selection Area</option>
                                    @foreach ($allGarage as $item )
                                        <option value="{{ $item->id }}">{{ $item->garage_name }}</option>  
                                    @endforeach
                                </select>  
                            </td>
                            <td>
                                <input class="form-control total" style="min-width:120px" type="text" id="stock" name="stock[]" value="0" readonly>
                            </td>
                            <td>
                                <input class="form-control qty" style="min-width:80px" type="text" id="qty" name="qty[]">
                            </td>
                            <td>
                                <input class="form-control total" style="min-width:120px" type="text" id="amount" name="amount[]" value="0">
                            </td>
                            <td><a href="javascript:void(0)" class="text-danger font-18 remove" title="Remove"><i class="fa fa-trash-o"></i></a></td>
                        </tr>`;
        
                    // Append the new row
                    $("#tablePurchaseOrder tbody").append(newRow);
                });
        
                // Remove row logic
                $("#tablePurchaseOrder tbody").on("click", ".remove", function () {
                    var child = $(this).closest("tr").nextAll();
                    child.each(function () {
                        var id = $(this).attr("id");
                        var idx = $(this).children(".row-index").children("p");
                        var dig = parseInt(id.substring(1));
                        idx.html(`${dig - 1}`);
                        $(this).attr("id", `R${dig - 1}`);
                    });
                    $(this).closest("tr").remove();
                    rowIdx--;
                });
            });
        </script>
    @endsection
@endsection
