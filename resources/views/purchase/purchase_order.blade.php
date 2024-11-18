
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
                        <a href="{{ route('request.index') }}" class="btn add-btn"><i class="fa fa-plus"></i> Create Job</a>
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
                                            <th>Status</th>
                                            <th>Request Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requestOrder as $item )
                                            <td id="requestID"><a href="invoice-view.html">{{ $item->request_id }}</a></td>
                                            <td id="status">{{ $item->status }}</td>
                                            <td id="requestDATE">{{ $item->request_date }}</td>
                                        @endforeach
                                        <!-- Rows go here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div id="paginationRequest" class="pagination"></div>
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
                                            <th>Item Code</th>
                                            <th>Status</th>
                                            <th>Payment Terms</th>
                                            <th>Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requestOrder as $item )
                                            <td id="reqID">{{ $item->request_id }}</td>
                                            <td id="poID"><a href="invoice-view.html">{{ $item->po_number }}</a></td>
                                            <td id="status">{{ $item->status }}</td>
                                            <td id="requestDATE">{{ $item->request_date }}</td>
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
        $(document).ready(function () {
            function setupPagination(tableId, paginationId, rowsPerPage) {
                const table = $(`#${tableId}`);
                const tbody = table.find("tbody");
                const rows = tbody.find("tr");
                const pagination = $(`#${paginationId}`);
                let currentPage = 1;
                const totalPages = Math.ceil(rows.length / rowsPerPage);
    
                // Display rows for the current page
                function displayPage(page) {
                    const start = (page - 1) * rowsPerPage;
                    const end = start + rowsPerPage;
    
                    rows.hide().slice(start, end).show();
                    updatePagination();
                }
    
                // Update pagination buttons
                function updatePagination() {
                    pagination.empty();
                    for (let i = 1; i <= totalPages; i++) {
                        const btn = $("<button></button>")
                            .text(i)
                            .addClass("page-btn")
                            .css({
                                margin: "0 5px",
                                padding: "5px 10px",
                                border: i === currentPage ? "2px solid #007bff" : "1px solid #ddd",
                                backgroundColor: i === currentPage ? "#f0f8ff" : "#fff",
                            })
                            .on("click", function () {
                                currentPage = i;
                                displayPage(currentPage);
                            });
    
                        pagination.append(btn);
                    }
                }
    
                // Initialize pagination
                displayPage(currentPage);
            }
    
            // Apply pagination to both tables
            setupPagination("requestOrderTable", "paginationRequest", 2); // 2 rows per page
            setupPagination("purchaseOrderTable", "paginationPurchase", 2); // 2 rows per page
        });
    </script>
    @endsection
@endsection
