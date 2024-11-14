
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
                    <form action="{{ route('create/estimate/save') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Transaction ID <span class="text-danger">*</span></label>
                                    <input class="form-control" id="auto_transaction_id" name="transaction_id" value="<?= $transaction_id ?>" readonly required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Store / Supplier <span class="text-danger">*</span></label>
                                    <select class="select" id="supp_name" name="supplier_name" required>
                                        <option value="" > -- Select Supplier -- </option>
                                        @foreach($allSupplier as $item)
                                            <option value="{{ $item->supplier_name}}">{{ $item->supplier_name }}</option>
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
                                                <th class="col-md-1">Stock</th>
                                                <th class="col-md-1">Qty</th>
                                                <th >Amount</th>
                                                <th> </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="auto_po_id"></td>
                                                <td hidden >1</td>
                                                <td>
                                                    <select class="form-control category-select" style="min-width:150px" name="category[]">
                                                        <option value=""> --Choose Category-- </option>
                                                        @foreach ($allCategory as $item )
                                                            <option value="{{ $item->id }}">{{ $item->category_name }}</option>  
                                                        @endforeach
                                                    </select>                                                
                                                </td>
                                                <td>
                                                    <select class="form-control product-code-select" style="min-width:150px" id="p_code" name="product_code[]">
                                                        <option value=""> --Choose Product Code-- </option>
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
                                                    <input class="form-control total" style="min-width:120px" type="text" id="stock" name="stock[]" value="0" readonly>
                                                </td>
                                                <td>
                                                    <input class="form-control qty" style="min-width:80px" type="text" id="qty" name="qty[]" readonly>
                                                </td>
                                                <td>
                                                    <input class="form-control total" style="min-width:120px" type="text" id="amount" name="amount[]" value="0" readonly>
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
                            <button class="btn btn-primary submit-btn m-r-10">Save & Send</button>
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
        var rowIdx = 1;
        $("#addBtn").on("click", function () {
            // Generate a unique PO number
            async function generateUniquePONumber() {
                let poNumber = generatePONumber();

                // Loop until a unique PO number is generated
                while (await isPONumberExists(poNumber)) {
                    poNumber = generatePONumber();
                }

                return poNumber;
            }

            // Function to generate a PO number
            function generatePONumber() {
                const date = new Date();
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const randomNum = Math.floor(1000 + Math.random() * 9000);
                return `PO-${year}${month}${day}-${randomNum}`; // Default Format of PO Number
            }

            // Function to check if the PO number already exists
            async function isPONumberExists(poNumber) {
                try {
                    const response = await fetch(`/check-po-number?po_no=${poNumber}`);
                    if (!response.ok) {
                        console.error('Network response was not ok', response.statusText);
                        return true;
                    }
                    const data = await response.json();
                    return data.exists;
                } catch (error) {
                    console.error('Error checking PO number:', error);
                    return true;
                }
            }

            generateUniquePONumber().then(poNumber => {
                // Adding a row inside the tbody.
                $("#tablePurchaseOrder tbody").append(`
                <tr id="R${++rowIdx}">
                    <td hidden class="row-index text-center"><p> ${rowIdx}</p></td>
                    <td class="auto_po_id">${poNumber}</td>  <!-- Display PO number here -->
                    <td>
                        <select class="form-control category-select" style="min-width:150px" name="category[]">
                            <option value=""> --Choose Category-- </option>
                            @foreach ($allCategory as $item )
                                <option value="{{ $item->id }}">{{ $item->category_name }}</option>  
                            @endforeach
                        </select>                                                
                    </td>
                    <td>
                        <select class="form-control product-code-select" style="min-width:150px" id="p_code" name="product_code[]">
                            <option value=""> --Choose Product Code-- </option>
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
                        <input class="form-control total" style="min-width:120px" type="text" id="stock" name="stock[]" value="0" readonly>
                    </td>
                    <td>
                        <input class="form-control qty" style="min-width:80px" type="text" id="qty" name="qty[]" readonly>
                    </td>
                    <td>
                        <input class="form-control total" style="min-width:120px" type="text" id="amount" name="amount[]" value="0" readonly>
                    </td>
                    <td><a href="javascript:void(0)" class="text-danger font-18 remove" title="Remove"><i class="fa fa-trash-o"></i></a></td>
                </tr>`);
            });
        });

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

        $("#tablePurchaseOrder tbody").on("input", ".unit_price", function () {
            var unit_price = parseFloat($(this).val());
            var qty = parseFloat($(this).closest("tr").find(".qty").val());
            var total = $(this).closest("tr").find(".total");
            total.val(unit_price * qty);

            calc_total();
        });

        $("#tablePurchaseOrder tbody").on("input", ".qty", function () {
            var qty = parseFloat($(this).val());
            var unit_price = parseFloat($(this).closest("tr").find(".unit_price").val());
            var total = $(this).closest("tr").find(".total");
            total.val(unit_price * qty);
            calc_total();
        });
        function calc_total() {
            var sum = 0;
            $(".total").each(function () {
            sum += parseFloat($(this).val());
            });
            $(".subtotal").text(sum);
            
            var amounts = sum;
            var tax     = 100;
            $(document).on("change keyup blur", "#qty", function() 
            {
                var qty = $("#qty").val();
                var discount = $(".discount").val();
                $(".total").val(amounts * qty);
                $("#sum_total").val(amounts * qty);
                $("#tax_1").val((amounts * qty)/tax);
                $("#grand_total").val((parseInt(amounts)) - (parseInt(discount)));
            }); 
        }
    </script>
<!--*
    *
    * FUNCTION FOR AUTO DISABLE ALL READONLY
    * FUNCTION FOR AUTO DISABLE ALL READONLY
    * FUNCTION FOR AUTO DISABLE ALL READONLY
    *
    *-->
    <script>
        $(document).ready(function() {
            $('table').find('.qty, .brand, #unit, #p_name').prop('readonly', true);
    
            $('.category-select').on('change', function() {
                var $row = $(this).closest('tr');
                var $qty = $row.find('.qty');
                var $brand = $row.find('#brand');
                var $unit = $row.find('#unit');
                var $p_name = $row.find('#p_name');
                
                if ($(this).val()) {
                    $qty.prop('readonly', false);
                } else {
                    $qty.prop('readonly', true);
                }
    
                // Ensure brand, unit, and product name stay readonly
                $brand.prop('readonly', true);
                $unit.prop('readonly', true);
                $p_name.prop('readonly', true);
            });
        });
    </script>
    
    

<!--*
    *
    * AUTO COMPLETE EVERY PRODUCTS
    * AUTO COMPLETE EVERY PRODUCTS
    * AUTO COMPLETE EVERY PRODUCTS
    *
    *-->
    <script>
        $(document).ready(function() {
            // Category selection change event
            $('.category-select').on('change', function() {
                var categoryId = $(this).val();
                var $productCodeSelect = $(this).closest('tr').find('.product-code-select');
                var $brand = $(this).closest('tr').find('#brand');
                var $unit = $(this).closest('tr').find('#unit');
                var $productName = $(this).closest('tr').find('#p_name');
                
                // Clear previous selections
                $productCodeSelect.empty().append('<option value=""> --Choose Product Code-- </option>');
                $brand.val('');
                $unit.val('');
                $productName.val('');

                if (categoryId) {
                    $.ajax({
                        url: '/getProduct/' + categoryId,
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
                            alert('Failed to fetch product aaaa details.');
                        }
                    });
                }
            });
        });
    </script>


    <!--*
        *
        * Auto Generate P-O NUMBER 
        * Auto Generate P-O NUMBER 
        * Auto Generate P-O NUMBER 
        *
        * 
        -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            async function generateUniquePONumber() {
                let poNumber = generatePONumber();

                // Loop until a unique PO number is generated
                while (await isPONumberExists(poNumber)) {
                    poNumber = generatePONumber();
                }

                // Set the unique PO number to elements with the class "auto_po_id"
                document.querySelectorAll('.auto_po_id').forEach(el => {
                    el.textContent = poNumber;
                });
            }

            // Function to generate a PO number
            function generatePONumber() {
                const date = new Date();
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const randomNum = Math.floor(1000 + Math.random() * 9000);
                return `PO-${year}${month}${day}-${randomNum}`; // Default Format of PO Number
            }

            async function isPONumberExists(poNumber) {
                try {
                    const response = await fetch(`/check-po-number?po_no=${poNumber}`);
                    if (!response.ok) {
                        console.error('Network response was not ok', response.statusText);
                        return true;
                    }
                    const data = await response.json();
                    return data.exists;
                } catch (error) {
                    console.error('Error checking PO number:', error);
                    return true;
                }
            }
            generateUniquePONumber();
        });
    </script>

    @endsection
@endsection
