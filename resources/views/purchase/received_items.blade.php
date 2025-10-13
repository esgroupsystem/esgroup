@extends('layouts.master')

@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Received Items</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Received Transactions</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0" id="receivedList" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>PO Number</th>
                                    <th>Product Code</th>
                                    <th>Product Name</th>
                                    <th>Brand</th>
                                    <th>Unit</th>
                                    <th>Received Qty</th>
                                    <th>Status</th>
                                    <th>Date Received</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($receivedTransactions as $item)
                                <tr>
                                    <td>{{ $item->po_id }}</td>
                                    <td>{{ $item->product_code }}</td>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->product_brand ?? '-' }}</td>
                                    <td>{{ $item->product_unit ?? '-' }}</td>
                                    <td>{{ $item->qty_received }}</td>
                                    <td>
                                        @if($item->status == 'Received')
                                            <span class="badge bg-inverse-success">Received</span>
                                        @elseif($item->status == 'Partial')
                                            <span class="badge bg-inverse-info">Partial</span>
                                        @else
                                            <span class="badge bg-inverse-warning">{{ $item->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('M d, Y h:i A') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->

    @section('script')
    <script>
        $(document).ready(function() {
            $('#receivedList').DataTable({
                pageLength: 20,
                order: [[7, "desc"]], // Sort by date received
                dom: 't<"bottom"p>',
            });
        });
    </script>
    @endsection
@endsection
