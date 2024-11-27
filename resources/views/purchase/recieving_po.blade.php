
@extends('layouts.master')
@section('content')
    {{-- message --}}
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row ">
                    <div class="col">
                        <h3 class="page-title">Receiving Purchase Order</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Receiving</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0" id="purchaseList" style="width: 100%">
                            <thead>
                                <tr>
                                    <th hidden>ID</th>
                                    <th>Purchase Order Number</th>
                                    <th>Request ID</th>
                                    <th>Date Received</th>
                                    <th>Status</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchaseReceived as $item)
                                <tr>
                                    <td hidden class="id">{{ $item->id }}</td>
                                    <td class="p_id">{{ $item->purchase_id }}</td>
                                    <td class="request_id">{{ $item->request_id }}</td>
                                    <td class="po_date">{{ date('j M Y (h:i A)', strtotime($item->date_received)) }}</td>
                                    <td id="receving">
                                        @if($item->status_receiving == 'For Delivery')
                                            <span class="badge bg-inverse-warning">Waiting for delivery</span>
                                        @elseif($item->status_receiving == 'Partial Delivery')
                                            <span class="badge bg-inverse-info">Partial Delivery</span>
                                        @elseif($item->status_receiving == 'Delivered')
                                            <span class="badge bg-inverse-success">Delivered</span>
                                        @else
                                            <span class="badge bg-inverse-danger">Contact Admin</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <button class="btn btn-primary btn-sm btn-view" data-id="{{ $item->purchase_id }}" data-toggle="modal" data-target="#edit_joborder">View</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
        <!-- /Page Content -->    
         
        <!-- Edit User Modal -->
        <div id="edit_joborder" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document"> 
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Job Order</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('save.received') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="e_id">
                            <div class="table-responsive">
                                <table class="table table-hover table-white" id="tablePurchaseOrder">
                                    <thead>
                                        <tr>
                                            <th>Product Code</th>
                                            <th>Product Name</th>
                                            <th>Brand</th>
                                            <th>Unit</th>
                                            <th>Qty</th>
                                            <th>Received Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Products will be dynamically added here -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Edit Model -->
    </div>
    <!-- /Page Wrapper -->
 
    @section('script')
    
        {{-- pagination for joblist --}}
        <script>
            $(document).ready(function() {
                $('#purchaseList').DataTable({
                    pageLength: 5,
                    processing: true,
                    serverSide: false,
                    ordering: true,
                    dom: 't<"bottom"p>',
                });
            });
        </script>

        {{-- Edit/Update Modal --}}
        <script>
            $(document).ready(function () {
                $('.btn-view').on('click', function () {
                    const purchaseId = $(this).data('id');
                    const productsTable = $('#tablePurchaseOrder tbody');
                    productsTable.empty();
        
                    $.ajax({
                        url: `/fetch-purchase-order/${purchaseId}`,
                        method: 'GET',
                        success: function (response) {
                            if (response.purchaseOrders) {
                                const purchaseOrders = response.purchaseOrders;
                                purchaseOrders.forEach(order => {
                                    order.items.forEach(item => {
                                        const existingRow = productsTable.find(`tr input[value="${item.product_code}"]`);
                                        if (existingRow.length === 0) {
                                            const row = `
                                                <tr>
                                                    <td><input class="form-control" name="product_code[]" value="${item.product_code || ''}" readonly></td>
                                                    <td><input class="form-control" name="product_name[]" value="${item.product_name || ''}" readonly></td>
                                                    <td><input class="form-control" name="brand[]" value="${order.product_brand || ''}" readonly></td>
                                                    <td><input class="form-control" name="unit[]" value="${order.product_unit || ''}" readonly></td>
                                                    <td><input class="form-control" name="qty[]" value="${item.qty || ''}" readonly></td>
                                                    <td><input class="form-control" name="received_qty[]" value="${item.received_qty || ''}"></td>
                                                    <input type="hidden" name="product_id[]" value="${item.id}">
                                                </tr>
                                            `;
                                            productsTable.append(row);
                                        }
                                    });
                                });
                            } else {
                                alert('No related purchase orders found.');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('AJAX error:', error);
                            alert('Failed to fetch purchase order data. Please try again.');
                        }
                    });
                });
            });
        </script>   
    @endsection
@endsection
