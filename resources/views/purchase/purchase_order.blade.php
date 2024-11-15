
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
                        <a href="{{ route('/create/request') }}" class="btn add-btn"><i class="fa fa-plus"></i> Create Job</a>
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
            
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0 datatable">
                            <thead>
                                <tr>
                                    <th hidden class="id">id</th>
                                    <th>Purchase No</th>
                                    <th>Product Name</th>
                                    <th>Supplier</th>
                                    <th>Garage</th>
                                    <th>Requestor by</th>
                                    <th>Date Request</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($allpurchase as $item )
                                <tr>
                                    <td hidden class="id"> {{ $item->id }}</td>
                                    <td class="po_no">{{ $item->po_no }}</td>
                                    <td class="po_product">{{ $item->product_id }}</td>
                                    <td class="po_supp">{{ $item->supplier_id }}</td>
                                    <td class="po_garage">{{ $item->garage_id }}</td>
                                    <td class="po_requestor">{{ $item->requestor_id }}</td>
                                    <td class="po_garage">{{ date('j M Y (h:i A)', strtotime($item->request_date)) }}</td>
                                    <td class="text-center">
                                        <div class="action-label">
                                            <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                                                @if($item->isapproved == 0)
                                                    <i class="fa fa-dot-circle-o text-warning"></i> Pending
                                                @elseif($item->isapproved == 1)
                                                    <i class="fa fa-dot-circle-o text-danger"></i> Not Approved
                                                @elseif($item->isapproved == 2)
                                                    <i class="fa fa-dot-circle-o text-info"></i> Wait for Delivery
                                                @elseif($item->isapproved == 3)
                                                    <i class="fa fa-dot-circle-o text-primary"></i> Partial Received
                                                @elseif($item->isapproved == 4)
                                                    <i class="fa fa-dot-circle-o text-success"></i> Received
                                                @else
                                                    <i class="fa fa-dot-circle-o text-secondary"></i> Unknown
                                                @endif
                                            </a>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_leave"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_approve"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
              
        </div>
        <!-- /Page Content -->
        
        <!-- Edit Leave Modal -->
        <div id="edit_leave" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Leave</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label>Leave Type <span class="text-danger">*</span></label>
                                <select class="select">
                                    <option>Select Leave Type</option>
                                    <option>Casual Leave 12 Days</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>From <span class="text-danger">*</span></label>
                                <div class="cal-icon">
                                    <input class="form-control datetimepicker" value="01-01-2019" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>To <span class="text-danger">*</span></label>
                                <div class="cal-icon">
                                    <input class="form-control datetimepicker" value="01-01-2019" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Number of days <span class="text-danger">*</span></label>
                                <input class="form-control" readonly type="text" value="2">
                            </div>
                            <div class="form-group">
                                <label>Remaining Leaves <span class="text-danger">*</span></label>
                                <input class="form-control" readonly value="12" type="text">
                            </div>
                            <div class="form-group">
                                <label>Leave Reason <span class="text-danger">*</span></label>
                                <textarea rows="4" class="form-control">Going to hospital</textarea>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Leave Modal -->
        
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
@endsection
