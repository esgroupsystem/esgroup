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
                    <h3 class="page-title">Balintawak Stocks</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a></li>
                        <li class="breadcrumb-item">Balintawak</li>
                        <li class="breadcrumb-item">Stocks</li>
                    </ul>
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
        <div class="row">
            <div class="col-md-12">
                <div class="card card-table">
                    <div class="card-header" style="background-color: #add8e6; color: #333;">
                        <h3 class="card-title mb-0" style="text-transform: uppercase; font-weight: bold;">{{ $category->category_name }}</h3>
                    </div>                                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-nowrap custom-table mb-0">
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
                                            $totalStock = $product->productStockBalintawak->sum('InQty');
                                            $stockClass = '';

                                            // Assign class based on stock quantity
                                            if ($totalStock <= 2) {
                                                $stockClass = 'bg-danger text-white'; // Red for stock <= 2
                                            } elseif ($totalStock > 2 && $totalStock < 5) {
                                                $stockClass = 'bg-warning text-dark'; // Yellow for stock 3-5
                                            } else {
                                                $stockClass = 'bg-success text-white'; // Green for stock > 5
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $product->product_code }}</td>
                                            <td>{{ $product->product_name }}</td>
                                            <td class="{{ $stockClass }}">{{ $totalStock }}</td> <!-- Apply class here -->
                                        </tr>
                                    @endforeach
                                    @if ($category->products->isEmpty())
                                        <tr>
                                            <td colspan="5" class="text-center">No products found</td>
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
    

    
    
        <!-- /Content End -->
    </div>
    <!-- /Page Content -->
</div>
<!-- /Page Wrapper -->
@endsection