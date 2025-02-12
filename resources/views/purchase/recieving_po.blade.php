@extends('layouts.master')

@section('content')
    {{-- message --}}
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
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
                                    <td id="receiving">
                                        @if($item->status_receiving == 'For Delivery')
                                            <span class="badge bg-inverse-warning">For Delivery</span>
                                        @elseif($item->status_receiving == 'Partial Delivered')
                                            <span class="badge bg-inverse-info">Partial Delivered</span>
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

        <!-- Edit Modal -->
        <div id="edit_joborder" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document"> 
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Received Qty</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('save.received') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="e_id">
                            <!-- Hidden purchase_id input -->
                            <input type="hidden" name="purchase_id" id="purchase_id">
                            <input type="hidden" name="garage_name" id="garage_name">

                            <!-- Status Display -->
                            <div class="status-section mb-3">
                                <strong>Status:</strong> <span id="status_receiving" class="badge bg-inverse-info">Partial Delivered</span>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover table-white" id="tablePurchaseOrder">
                                    <thead>
                                        <tr>
                                            <th>Product Code</th>
                                            <th>Product Name</th>
                                            <th>Brand</th>
                                            <th>Unit</th>
                                            <th>Ordered Qty</th>
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
        <!-- /Edit Modal -->
    </div>
<!-- /Page Wrapper -->
 
    @section('script')
    
        {{-- Pagination for the purchase list --}}
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

        {{-- Dynamic Modal Functionality --}}
        <script>
            $(document).ready(function () {
                // Update status based on received_qty
                function updateStatus() {
                    let allFullyReceived = true;
        
                    $('#tablePurchaseOrder tbody tr').each(function () {
                        const row = $(this);
                        const qty = parseInt(row.find('input[name="qty[]"]').val(), 10);
                        let receivedQty = parseInt(row.find('input[name="received_qty[]"]').val(), 10) || 0;
        
                        // Enforce the rule: received_qty cannot exceed qty
                        if (receivedQty > qty) {
                            receivedQty = qty;  // Reset to the maximum allowable value (qty)
                            row.find('input[name="received_qty[]"]').val(receivedQty);  // Update the input field
                            alert('Received quantity cannot exceed the available quantity.');
                        }
        
                        if (receivedQty < qty) {
                            allFullyReceived = false; // If any item is not fully received
                        }
                    });
        
                    // Update status_receiving text
                    const status = allFullyReceived ? 'Delivered' : 'Partial Delivered';
                    $('#status_receiving')
                        .text(status)
                        .removeClass()
                        .addClass('badge')
                        .addClass(allFullyReceived ? 'bg-inverse-success' : 'bg-inverse-info');
                }
        
                // Listen for changes in received_qty inputs
                $(document).on('input', 'input[name="received_qty[]"]', function () {
                    updateStatus(); // Recalculate status whenever received_qty is updated
                });
        
                // Load purchase order items into the modal
                $('.btn-view').on('click', function () {
                    const purchaseId = $(this).data('id');
                    console.log('Selected Purchase ID:', purchaseId);

                    const productsTable = $('#tablePurchaseOrder tbody');
                    productsTable.empty(); // Clear previous items

                    $('#purchase_id').val(purchaseId);

                    $.ajax({
                        url: `/fetch-purchase-order/${purchaseId}`,
                        method: 'GET',
                        success: function (response) {
                            console.log('Response:', response);

                            if (response.purchaseOrders) {
                                response.purchaseOrders.forEach(po => {
                                    $('#status_receiving').text(po.status_receiving);

                                    // Set the hidden input for garage_name
                                    $('#garage_name').val(po.garage_name); 

                                    po.items.forEach(item => {
                                        const remainingQty = Math.max(item.qty - item.qty_received, 0); // Ensure no negative values
                                        const disabledAttr = remainingQty === 0 ? 'disabled' : ''; 

                                        const row = `
                                            <tr>
                                                <td hidden><input type="hidden" name="product_id[]" value="${item.id}"></td>
                                                <td><input class="form-control" name="product_code[]" value="${item.product_code}" readonly></td>
                                                <td><input class="form-control" name="product_name[]" value="${item.product_name}" readonly></td>
                                                <td><input class="form-control" name="brand[]" value="${po.product_brand}" readonly></td>
                                                <td><input class="form-control" name="unit[]" value="${po.product_unit}" readonly></td>
                                                <td><input class="form-control" name="qty[]" value="${remainingQty}" readonly></td> <!-- Updated -->
                                                <td><input class="form-control" name="received_qty[]" value="0" min="0" max="${remainingQty}"></td>
                                            </tr>`;
                                        productsTable.append(row);
                                    });
                                });
                            } else {
                                alert('No data found for this Purchase Order.');
                            }
                        },
                        error: function () {
                            alert('Failed to fetch purchase order data. Please try again.');
                        },
                    });
                });
            });
        </script>        
        
    @endsection
@endsection
