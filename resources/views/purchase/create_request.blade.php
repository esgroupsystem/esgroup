
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
                                    <input class="form-control" id="auto_po_id" name="request_id" value="" readonly required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Purchase Order ID <span class="text-danger">*</span></label>
                                    <input class="form-control" id="auto_transaction_id" name="po_number" value="" readonly required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Garage<span class="text-danger">*</span></label>
                                    <select class="select" id="gar_name" name="gar_name" required>
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
                                                <th hidden class="po_id">P/O No</th>
                                                <th hidden style="width:20px">#</th>
                                                <th class="col-sm-2">Category</th>
                                                <th class="col-md-2">Product Code</th>
                                                <th class="col-md-3">Product Name</th>
                                                <th class="col-md-1">Brand</th>
                                                <th class="col-md-1">Unit</th>
                                                <th class="col-md-2">Supplier</th>
                                                <th class="col-md-1">Stock</th>
                                                <th class="col-md-1">Qty</th>
                                                <th >Amount</th>
                                                <th> </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td hidden ><input type="text" name="po_no[]" class="auto_po_id" value="" readonly></td>
                                                <td hidden>1</td>
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
                                                    <select class="form-control" style="min-width:150px" id="garage" name="garage_name[]">
                                                        <option value="">Selection Area</option>
                                                        @foreach ($supplier as $item)
                                                            <option value="{{ $item->id }}">{{ $item->supplier_name }}</option>
                                                        @endforeach
                                                    </select>  
                                                </td>
                                                <td>
                                                    <input class="form-control" style="min-width:120px" type="text" id="stock" name="stock[]" value="0" readonly>
                                                </td>
                                                <td>
                                                    <input class="form-control" style="min-width:80px" type="text" id="qty" name="qty[]" placeholder="0">
                                                </td>
                                                <td>
                                                    <input class="form-control" style="min-width:120px" type="text" id="amount" name="amount[]" placeholder="0">
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
    <script>
        $(document).ready(function () {
            function generatePONumber() {
                $.ajax({
                    url: '/get-latest-request-number',
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            const latestNumber = response.latest_po_number || 0;
                            const nextNumber = parseInt(latestNumber) + 1;
                            const paddedNumber = String(nextNumber).padStart(4, '0');
                            const poNumber = `s -${paddedNumber}`;
                            $('#auto_po_id').val(poNumber);
                        } else {
                            alert('Error fetching PO number');
                        }
                    },
                    error: function () {
                        alert('Unable to fetch data');
                    }
                });
            }
            generatePONumber();
        });
    </script>

    <script>
        $(document).ready(function () {
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

            $(document).on('change', '.product-code-select', function () {
                const selectedProductCode = $(this).val();
                const $row = $(this).closest('tr');
                const $productNameField = $row.find('#p_name');
                const $brandField = $row.find('#brand');
                const $unitField = $row.find('#unit');

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
        });
    </script>

    <script>
        var rowIdx = 1;
        $("#addBtn").on("click", async function () {
            const poNumber = await fetchPONumber();
            const newRow = `
                <tr id="R${++rowIdx}">
                    <td hidden class="row-index text-center"><p> ${rowIdx}</p></td>
                    <td hidden ><input type="" name="po_no[]" class="auto_po_id" value="${poNumber}" readonly></td>
                    <td>
                        <select class="form-control category-select" style="min-width:150px" name="category[]">
                            <option value="">Selection Area</option>
                            @foreach ($category as $item )
                                <option value="{{ $item->category_name }}">{{ $item->category_name }}</option>  
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
                            @foreach ($supplier as $item )
                                <option value="{{ $item->id }}">{{ $item->supplier_name }}</option>  
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
    </script>        
    @endsection
@endsection
