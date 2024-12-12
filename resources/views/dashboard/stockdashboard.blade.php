@extends('layouts.master')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Stock Items</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Items</a></li>
                        <li class="breadcrumb-item active">Stocks</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Page Tab -->
        <div class="page-menu">
            <div class="row">
                <div class="col-sm-12">
                    <ul class="nav nav-tabs nav-tabs-bottom">
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == 'mirasol' ? 'active' : '' }}" href="{{ route('stock/dashboard', ['tab' => 'mirasol']) }}">Mirasol</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == 'balintawak' ? 'active' : '' }}" href="{{ route('stock/dashboard', ['tab' => 'balintawak']) }}">Balintawak</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == 'vgc' ? 'active' : '' }}" href="{{ route('stock/dashboard', ['tab' => 'vgc']) }}">VGC</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Tab -->

        <!-- Tab Content -->
        <div class="tab-content">
            <div class="tab-pane show active" id="tab_{{ $tab }}">
                @foreach($stocks as $category)
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title">{{ $category->category_name }}</h4>
                    </div>
                    <div class="card-body">
                        @if($category->products->isEmpty())
                            <p>No products available in this category.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover table-radius" id="productList_{{ Str::slug($category->category_name) }}">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Total Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($category->products as $product)
                                            <tr>
                                                <td>
                                                    <strong>{{ $product->product_code }}</strong><br>
                                                    {{ $product->product_name }}
                                                    <br><small>
                                                        Serial: {{ $product->product_serial ?? 'N/A' }}<br>
                                                        Brand: {{ $product->brand->brand_name ?? 'N/A' }}<br>
                                                        Unit: {{ $product->unit->unit_name ?? 'N/A' }}<br>
                                                        Parts: {{ $product->product_parts_details ?? 'N/A' }}
                                                    </small>
                                                </td>
                                                <td>{{ $category->category_name }}</td>
                                                <td>
                                                    @if($tab === 'mirasol')
                                                        {{ $product->productTotalStocks?->sum('InQty') ?? 0 }}
                                                    @elseif($tab === 'balintawak')
                                                        {{ $product->productStockBalintawak?->sum('InQty') ?? 0 }}
                                                    @elseif($tab === 'vgc')
                                                        {{ $product->productStockVgc?->sum('InQty') ?? 0 }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <!-- /Tab Content -->
    </div>
</div>
@section('script')
    <script>
        $(document).ready(function() {
            // Apply DataTable to each product list table dynamically
            @foreach($stocks as $category)
                // Directly use the sanitized category name to target the table ID
                $('#productList_{{ Str::slug($category->category_name) }}').DataTable({
                    "paging": true, // Enable pagination for each category
                    "pageLength": 10, // Number of items per page
                    "lengthChange": false, // Disable length change
                    "searching": true, // Enable search
                    "ordering": true, // Enable sorting
                    "info": true // Show info (e.g., "Showing 1 to 10 of 100 entries")
                });
            @endforeach
        });
    </script>
@endsection

@endsection
