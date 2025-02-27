@extends('layouts.master')
@section('content')
  <!-- Page Wrapper -->
  <div class="page-wrapper">
    <!-- Page Content -->
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="page-title">Mirasol Stocks</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a></li>
                        <li class="breadcrumb-item">Mirasol</li>
                        <li class="breadcrumb-item">Stocks</li>
                    </ul>
                </div>
                <div class="col-sm-6 text-right">
                    <input type="text" id="searchParts" class="form-control" placeholder="Search all parts..." >
                    <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#categoryModal">Choose Category</button>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        
        <!-- Content Starts -->
        <div class="card">
            <div class="card-body">
                @include('sidebar.sidebarreport')
            </div>
        </div>

        @foreach ($categories as $category)
        <div class="row category-card" data-category="{{ Str::slug($category->category_name) }}">
            <div class="col-md-12">
                <div class="card card-table">
                    <div class="card-header" style="background-color: #030155; color: #ffffff;">
                        <h3 class="card-title mb-0" style="text-transform: uppercase; font-weight: bold;">{{ $category->category_name }}</h3>
                    </div>                                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered product-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Code</th>
                                        <th>Product Name</th>
                                        <th>Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($category->products as $key => $product)
                                        @php
                                            $totalStock = $product->productTotalStocks->sum(fn($stock) => $stock->InQty - $stock->OutQty);
                                            $stockClass = $totalStock <= 2 ? 'stock-low' : ($totalStock < 5 ? 'stock-medium' : 'stock-high');
                                        @endphp
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $product->product_code }}</td>
                                            <td>{{ "({$product->product_serial}) {$product->product_name} - {$product->brand_name} - {$product->unit_name} - {$product->product_parts_details}" }}</td>
                                            <td><span class="stock-cell {{ $stockClass }}">{{ $totalStock }}</span></td>
                                        </tr>
                                    @endforeach
                                    @if ($category->products->isEmpty())
                                        <tr>
                                            <td colspan="4" class="text-center">No products found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                                                      
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Choose a Category</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    @foreach($categories as $category)
                        <li class="list-group-item category-option" data-category="{{ Str::slug($category->category_name) }}">{{ $category->category_name }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

@section('script')

<style>
    .product-table {
        width: 100%;
        border-collapse: collapse;
    }

    .product-table th, .product-table td {
        text-align: center;
        vertical-align: middle;
        padding: 10px;
        border: 1px solid #ddd;
    }

    .product-table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .product-table th {
        background-color: #013579
;
        color: white;
    }

    .stock-cell {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 5px;
        min-width: 40px;
    }

    .stock-low {
        background-color: #dc3545;
        color: white;
    }

    .stock-medium {
        background-color: #ffc107;
        color: black;
    }

    .stock-high {
        background-color: #28a745;
        color: white;
    }
</style>

<script>
    $(document).ready(function() {
        // Live Search Functionality
        $('#searchParts').on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".product-table tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        // Category Filter Modal
        $('.category-option').on('click', function() {
            var category = $(this).data('category');
            $('.category-card').hide();
            $('.category-card[data-category="' + category + '"]').show();
            $('#categoryModal').modal('hide');
        });
    });
</script>
@endsection
@endsection
