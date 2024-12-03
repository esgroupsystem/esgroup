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
                    <form action="{{ route('save.partsout') }}" method="POST">
                        @csrf
                        <!-- Part-Out ID -->
                        <div class="row">
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Part-Out ID <span class="text-danger">(Auto Generated)*</span></label>
                                    <input class="form-control" id="auto_partout_id" name="partout_id" readonly required>
                                </div>
                            </div>
                            <div class="col-md-2 float-right ml-auto">
                                <div class="form-group position-relative">
                                    <label for="product_search">Search Product Name:</label>
                                    <input type="text" class="form-control" id="product_search" placeholder="Enter Product Name" />
                                    <ul id="product-search-results" class="list-group"></ul>
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
                                    <input class="form-control" type="number" name="kilometers" placeholder="Enter Kilometers">
                                </div>
                            </div>
                        </div>

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
                                                <th class="col-md-1">Unit</th>
                                                <th>Details</th>
                                                <th class="col-md-1">Total Qty</th>
                                                <th class="col-md-1">Quantity</th>
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
                                                <td hidden><input class="form-control" type="text" name="name_id[]" readonly></td>
                                                <td><input class="form-control" type="text" name="serial[]" readonly disabled></td>
                                                <td><input class="form-control" type="text" name="product_name[]" readonly disabled></td>
                                                <td><input class="form-control" type="text" name="brand[]" readonly disabled></td>
                                                <td><input class="form-control" type="text" name="unit[]" readonly disabled></td>
                                                <td><input class="form-control" type="text" name="details[]" readonly disabled></td>
                                                <td><input class="form-control" type="text" name="total_qty[]" readonly disabled></td>
                                                <td><input class="form-control" type="number" name="quantity[]" placeholder="Enter Quantity" required disabled></td>
                                                <td class="text-center">
                                                    <a href="javascript:void(0)" class="text-success font-18 add-row" title="Add" id="addBtn"><i class="fa fa-plus"></i></a>
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
                const row = $(this).closest('tr');
                const productCode = $(this).val();
                const garageName = $('#garage').val();

                if (productCode && garageName) {
                    $.getJSON('/get-product-parts', { product_code: productCode }, function(data) {
                        if (data.success) {
                            row.find('input[name="serial[]"]').val(data.product.product_serial);
                            row.find('input[name="product_name[]"]').val(data.product.product_name);
                            row.find('input[name="brand[]"]').val(data.product.brand_name);
                            row.find('input[name="unit[]"]').val(data.product.unit_name);
                            row.find('input[name="details[]"]').val(data.product.product_parts_details);
                            row.find('input[name="name_id[]"]').val(data.product.id);

                            // Fetch stock based on the garage name
                            $.getJSON(`/get-stock-by-garage`, { garage_name: garageName, product_id: data.product.id }, function(stockData) {
                                const stockQty = stockData.success && stockData.stockQty ? stockData.stockQty : 0;
                                row.find('input[name="total_qty[]"]').val(stockQty);
                            }).fail(function() {
                                row.find('input[name="total_qty[]"]').val(0);
                            });
                        } else {
                            alert(data.message || "Product details not found!");
                        }
                    });
                } else {
                    row.find('input, select').val(''); // Clear fields if no product code or garage
                }
            });

            // Add validation to quantity input
            $(document).on('input', 'input[name="quantity[]"]', function() {
                const row = $(this).closest('tr');
                const totalQty = parseFloat(row.find('input[name="total_qty[]"]').val()) || 0;
                const inputQty = parseFloat($(this).val()) || 0;

                if (inputQty > totalQty) {
                    alert('Quantity cannot exceed the Total Quantity available.');
                    $(this).val(0); // Reset quantity to 0
                }
            });

            // Add new row
            $(document).on('click', '.add-row', function() {
                const garageName = $('#garage').val(); // Get the value of the garage field
                const newRow = $('#tablePartsOut tbody tr:first').clone(); // Clone the first row

                newRow.find('input, select').val(''); // Clear all input and select values
                newRow.find('td:last').remove(); // Remove the last column to ensure no duplicate remove button

                // Add the Remove button at the end of the new row
                newRow.append(`
                    <td>
                        <a href="javascript:void(0)" class="text-danger font-18 remove" id="trashBIN" title="Remove"><i class="fa fa-trash-o"></i></a>
                    </td>
                `);

                // Enable or disable the row fields based on the garage value
                if (garageName) {
                    newRow.find('input, select').prop('disabled', false); // Enable inputs
                } else {
                    newRow.find('input, select').prop('disabled', true); // Disable inputs
                }

                $('#tablePartsOut tbody').append(newRow); // Append the new row to the table body
            });

            // Remove row on clicking the remove button
            $(document).on('click', '.remove', function() {
                $(this).closest('tr').remove(); // Remove the row containing the clicked button
            });

            // Enable table and reset rows when garage is changed
            $('#garage').change(function() {
                const garageName = $(this).val();
                if (garageName) {
                    resetTableRows(); // Reset rows when garage changes
                    enableTableFields(); // Enable all rows
                } else {
                    disableTableFields(); // Disable all rows if garage_name is empty
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

            // Function to reset all table rows
            function resetTableRows() {
                $('#tablePartsOut tbody tr').each(function() {
                    $(this).find('input, select').val(''); // Clear all inputs and selects
                });
            }
        });
    </script>
    <script>
        $(document).ready(function() {
    // Handle the product search input
    $('#product_search').on('keyup', function() {
        const query = $(this).val();

        if (query.length > 1) { // Only trigger search if more than 2 characters are typed
            $.ajax({
                url: '/search-products', // Endpoint to handle the search
                method: 'GET',
                data: { query: query },
                success: function(data) {
                    const resultsContainer = $('#product-search-results');
                    resultsContainer.empty(); // Clear previous results
                    
                    if (data.success && data.products.length > 0) {
                        data.products.forEach(function(product) {
                            const resultItem = `
                                <li class="list-group-item" 
                                    data-id="${product.id}" 
                                    data-code="${product.product_code}" 
                                    data-name="${product.product_name}" 
                                    data-serial="${product.product_serial}" 
                                    data-brand="${product.product_brand}" 
                                    data-unit="${product.product_unit}" 
                                    data-details="${product.product_details}">
                                    ${product.product_code} - (${product.product_name}) - ${product.product_parts_details}
                                </li>`;
                            resultsContainer.append(resultItem);
                        });
                        resultsContainer.show(); // Show the results dropdown
                    } else {
                        resultsContainer.hide(); // Hide if no results found
                    }
                },
                error: function() {
                    alert('Error fetching product data.');
                }
            });
        } else {
            $('#product-search-results').hide(); // Hide if query is too short
        }
    });

    // Handle the click on a product result
    $(document).on('click', '#product-search-results li', function() {
        const selectedProduct = $(this);
        
        // Display the product details in a simple view or alert
        const productDetails = `
            <p><strong>Product Name:</strong> ${selectedProduct.data('name')}</p>
            <p><strong>Serial:</strong> ${selectedProduct.data('serial')}</p>
            <p><strong>Brand:</strong> ${selectedProduct.data('brand')}</p>
            <p><strong>Unit:</strong> ${selectedProduct.data('unit')}</p>
            <p><strong>Details:</strong> ${selectedProduct.data('details')}</p>
        `;
        
        // Show the details in a modal or a dedicated area
        $('#product-details').html(productDetails); // Assuming there's an element with id="product-details" to display this info.

        // Hide the dropdown after selection
        $('#product-search-results').hide();
    });

    // Hide dropdown if clicked outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#product_search').length) {
            $('#product-search-results').hide();
        }
    });
});
        </script>





@endsection
@endsection
