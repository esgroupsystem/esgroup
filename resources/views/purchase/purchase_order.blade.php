
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
                        <h4>{{ $pendingCount }}</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-info not-approved">
                        <h6>Partial Request</h6>
                        <h4>{{ $partialCount }}</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-info waiting-delivery">
                        <h6>Waiting for Delivery</h6>
                        <h4>{{ $waitingDeliveryCount }}</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-info partial-received">
                        <h6>Partial Received</h6>
                        <h4>{{ $partialReceivedCount }}</h4>
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
                        <div class="card-footer">
                            <div id="paginationRequestOrder" class="pagination"></div>
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
                                                @elseif($item->status_receiving == 'Partial Delivered')
                                                    <span class="badge bg-inverse-info">Partial Delivered</span>
                                                @elseif($item->status_receiving == 'Delivered')
                                                    <span class="badge bg-inverse-success">Delivered</span>
                                                @else
                                                    <span class="badge bg-inverse-danger">Contact Admin</span>
                                                @endif
                                            </td>
                                            <td id="terms">{{ $item->payment_terms }} days</td>
                                            <td id="total">{{ $item->total_amount }}</td> 
                                            <td>
                                                <a href="{{ route('receipt', ['po_number' => $item->purchase_id]) }}">
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
        document.addEventListener("DOMContentLoaded", function () {
            let table = document.getElementById("requestOrderTable").getElementsByTagName("tbody")[0];
            let rows = table.getElementsByTagName("tr");
            let rowsPerPage = 10; // Adjust as needed
            let currentPage = 1;
            let totalPages = Math.ceil(rows.length / rowsPerPage);
            let paginationContainer = document.getElementById("paginationRequestOrder");

            function showPage(page) {
                let start = (page - 1) * rowsPerPage;
                let end = start + rowsPerPage;

                for (let i = 0; i < rows.length; i++) {
                    rows[i].style.display = i >= start && i < end ? "" : "none";
                }
                disableDoneRows(); // Apply disabling function after pagination update
                updatePaginationButtons();
            }

            function updatePaginationButtons() {
                paginationContainer.innerHTML = "";

                let prevButton = document.createElement("button");
                prevButton.innerText = "Previous";
                prevButton.disabled = currentPage === 1;
                prevButton.onclick = function () {
                    if (currentPage > 1) {
                        currentPage--;
                        showPage(currentPage);
                    }
                };
                paginationContainer.appendChild(prevButton);

                for (let i = 1; i <= totalPages; i++) {
                    let pageButton = document.createElement("button");
                    pageButton.innerText = i;
                    pageButton.className = i === currentPage ? "active" : "";
                    pageButton.onclick = function () {
                        currentPage = i;
                        showPage(currentPage);
                    };
                    paginationContainer.appendChild(pageButton);
                }

                let nextButton = document.createElement("button");
                nextButton.innerText = "Next";
                nextButton.disabled = currentPage === totalPages;
                nextButton.onclick = function () {
                    if (currentPage < totalPages) {
                        currentPage++;
                        showPage(currentPage);
                    }
                };
                paginationContainer.appendChild(nextButton);
            }

            function disableDoneRows() {
                for (let i = 0; i < rows.length; i++) {
                    let status = rows[i].getElementsByTagName("td")[2].innerText.trim(); // Adjust column index if needed

                    if (status === "Done") {
                        let link = rows[i].getElementsByTagName("td")[0].querySelector("a");
                        if (link) {
                            link.removeAttribute("href"); // Remove link if present
                        }
                        rows[i].classList.add("disabled-row"); // Apply CSS class
                    }
                }
            }

            showPage(currentPage); // Initialize pagination
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let table = document.getElementById("purchaseOrderTable").getElementsByTagName("tbody")[0];
            let rows = Array.from(table.getElementsByTagName("tr"));
            let rowsPerPage = 10; // Change as needed
            let currentPage = 1;
            let paginationContainer = document.getElementById("paginationPurchase");

            // Define sorting order (lower index = higher priority)
            let statusOrder = {
                "for delivery": 1,
                "partial delivered": 2,
                "delivered": 3
            };

            function sortTable() {
                rows.sort((a, b) => {
                    let statusA = a.cells[2].textContent.trim().toLowerCase();
                    let statusB = b.cells[2].textContent.trim().toLowerCase();
                    return (statusOrder[statusA] || 4) - (statusOrder[statusB] || 4);
                });

                // Re-append rows in sorted order
                rows.forEach(row => table.appendChild(row));
            }

            function showPage(page) {
                let start = (page - 1) * rowsPerPage;
                let end = start + rowsPerPage;

                rows.forEach((row, index) => {
                    row.style.display = index >= start && index < end ? "" : "none";
                });

                updatePaginationButtons();
            }

            function updatePaginationButtons() {
                let totalPages = Math.ceil(rows.length / rowsPerPage);
                paginationContainer.innerHTML = "";

                let prevButton = document.createElement("button");
                prevButton.innerText = "Previous";
                prevButton.disabled = currentPage === 1;
                prevButton.onclick = function () {
                    if (currentPage > 1) {
                        currentPage--;
                        showPage(currentPage);
                    }
                };
                paginationContainer.appendChild(prevButton);

                for (let i = 1; i <= totalPages; i++) {
                    let pageButton = document.createElement("button");
                    pageButton.innerText = i;
                    pageButton.className = i === currentPage ? "active" : "";
                    pageButton.onclick = function () {
                        currentPage = i;
                        showPage(currentPage);
                    };
                    paginationContainer.appendChild(pageButton);
                }

                let nextButton = document.createElement("button");
                nextButton.innerText = "Next";
                nextButton.disabled = currentPage === totalPages;
                nextButton.onclick = function () {
                    if (currentPage < totalPages) {
                        currentPage++;
                        showPage(currentPage);
                    }
                };
                paginationContainer.appendChild(nextButton);
            }

            // Sort table first, then apply pagination
            sortTable();
            showPage(currentPage);
        });
    </script>
        
        <style>
        .pagination button {
            margin: 5px;
            padding: 5px 10px;
            border: 1px solid #ccc;
            background-color: white;
            cursor: pointer;
        }
        .pagination button.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        .pagination button:disabled {
            background-color: #ddd;
            cursor: not-allowed;
        }
        </style>
    
    @endsection
@endsection
