
@extends('layouts.master')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Purchase Order<span id="year"></span></h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Request</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="{{ route('request.index') }}" class="btn add-btn"><i class="fa fa-plus"></i> Request Items</a>
                    </div>
                </div>
            </div>
            
            <!-- Leave Statistics -->
            <div class="row">
                <div class="col-md-3">
                    <div class="stats-info pending">
                        <h6>Pending</h6>
                        <h4>0</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-info not-approved">
                        <h6>Not Approved</h6>
                        <h4>0</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-info waiting-delivery">
                        <h6>Waiting for Delivery</h6>
                        <h4>0</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-info partial-received">
                        <h6>Partial Received</h6>
                        <h4>0</h4>
                    </div>
                </div>
            </div>
            <!-- /Leave Statistics -->
            
            <!-- /Statistics Widget -->
            <div class="row">
                <div class="col-md-6 d-flex">
                    <div class="card card-table flex-fill">
                        <div class="card-header">
                            <h3 class="card-title mb-0">Request Order</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="requestOrderTable" class="table table-nowrap custom-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Request ID</th>
                                            <th>Garage</th>
                                            <th>Status</th>
                                            <th>Request Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($poOrder as $item)
                                            <tr>
                                                <td id="requestID">
                                                    <a href="{{ route('update.index', ['requestId' => $item->request_id]) }}">
                                                        {{ $item->request_id }}
                                                    </a>
                                                </td>
                                                <td id="requestGarage">{{ $item->garage_name }}</td>
                                                <td>
                                                    @if($item->status == 'Pending')
                                                        <span class="badge bg-inverse-warning">Pending</span>
                                                    @elseif($item->status == 'Done')
                                                        <span class="badge bg-inverse-success">Done</span>
                                                    @elseif($item->status == 'Partial')
                                                        <span class="badge bg-inverse-info">Partial</span>
                                                    @else
                                                        <span class="badge bg-inverse-info">Unknown</span>
                                                    @endif
                                                </td>
                                                <td id="requestDATE">
                                                    {{ $item->request_date ? \Carbon\Carbon::parse($item->request_date)->format('F j, Y') : 'N/A' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="col-md-6 d-flex">
                    <div class="card card-table flex-fill">
                        <div class="card-header">
                            <h3 class="card-title mb-0">Purchase Order</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="purchaseOrderTable" class="table custom-table table-nowrap mb-0">
                                    <thead>
                                        <tr>
                                            <th>PO Number</th>
                                            <th>Request No</th>
                                            <th>Status</th>
                                            <th>Payment Terms</th>
                                            <th>Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requestOrder as $item)
                                        <tr> 
                                            <td id="poID">{{ $item->purchase_id }}</td> 
                                            <td id="item_code">{{ $item->request_id }}</td> 
                                            <td id="receving">
                                                @if($item->status_receiving == 'For Delivery')
                                                    <span class="badge bg-inverse-warning">For Delivery</span>
                                                @elseif($item->status_receiving == 'Partial Delivery')
                                                    <span class="badge bg-inverse-info">Partial Delivery</span>
                                                @elseif($item->status_receiving == 'Delivered')
                                                    <span class="badge bg-inverse-success">Delivered</span>
                                                @else
                                                    <span class="badge bg-inverse-danger">Contact Admin</span>
                                                @endif
                                            </td>
                                            <td id="terms">{{ $item->payment_terms }} days</td>
                                            <td id="total">{{ $item->total_amount }}</td> 
                                            <td>
                                                <a href="{{ route('receipt', ['po_number' => $item->purchase_id]) }}" target="_blank">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div id="paginationPurchase" class="pagination"></div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- /Page Content -->
        
        <!-- Delete Leave Modal -->
        <div class="modal custom-modal fade" id="delete_approve" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Leave</h3>
                            <p>Are you sure want to Cancel this leave?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
                                </div>
                                <div class="col-6">
                                    <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete Leave Modal -->
    </div>
    <!-- /Page Wrapper -->
    @section('script')

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#requestOrderTable').DataTable({
                pageLength: 5,
                processing: true,
                serverSide: false,
                ordering: true,
                dom: 't<"bottom"p>',
                order: [[2, 'desc']],
                columnDefs: [
                    {
                        "targets": 2,
                        "orderDataType": "dom-status" 
                    }
                ]
            });
            $.fn.dataTable.ext.order['dom-status'] = function(settings, colIdx) {
                return this.api()
                    .column(colIdx, { order: 'index' })
                    .nodes()
                    .map(function(td) {
                        const statusText = $(td).text().trim();
                        switch (statusText) {
                            case 'Pending': return 1;
                            case 'Done': return 2;
                            case 'Not Approved': return 3;
                            case 'Not all Approved': return 4;
                            default: return 5;
                        }
                    });
            };
            function disableDoneRows() {
                $('#requestOrderTable tbody tr').each(function() {
                    var status = $(this).find('td:eq(2)').text().trim();
    
                    if (status === 'Done') {
                        $(this).find('td:eq(0) a').removeAttr('href'); 
                        $(this).addClass('disabled-row');  
                    }
                });
            }
            table.on('draw', function() {
                disableDoneRows();
            });

            disableDoneRows();
        });
    </script>

    <script>
        $(document).ready(function() {
            const table = $('#purchaseOrderTable').DataTable({
                pageLength: 5,
                processing: true,
                serverSide: false,
                ordering: true,
                dom: 't<"bottom"p>',
            });
        });
    </script>
    
    @endsection
@endsection
