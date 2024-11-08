
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
                        <h3 class="page-title">Job Orders</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Job Orders</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="{{ route('create/joborders/page') }}" class="btn add-btn"><i class="fa fa-plus"></i> Create Job</a>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            
            <!-- Search Filter -->
            <div class="row filter-row">
                <div class="col-sm-6 col-md-3">  
                    <div class="form-group form-focus">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker" type="text">
                        </div>
                        <label class="focus-label">From</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">  
                    <div class="form-group form-focus">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker" type="text">
                        </div>
                        <label class="focus-label">To</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <select class="select floating"> 
                            <option>Select Status</option>
                            <option>Accepted</option>
                            <option>Declined</option>
                            <option>Expired</option>
                        </select>
                        <label class="focus-label">Status</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">  
                    <button class="btn btn-success btn-block btn_search"> Search </button>  
                </div>     
            </div>
            <!-- /Search Filter -->
            
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0" id="jobList" style="width: 100%">
                            <thead>
                                <tr>
                                    <th hidden>ID</th> 
                                    <th>Bus Number</th>
                                    <th>Type</th>
                                    <th>Date Issue</th>
                                    <th>Status</th>
                                    <th>Reported by</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($joborderview as $item )
                                <tr>
                                    <td hidden class="id"> {{ $item->id }}</td>
                                    <td class="j_name">{{ $item->job_name }}</td>
                                    <td class="j_type">{{ $item->job_type }}</td>
                                    <td class="j_filled">{{ date('j M Y (h:i A)', strtotime($item->job_date_filled)) }}</td>
                                    <td>
                                        <span class="badge badge-large
                                                @if($item->job_status == 'New') 
                                                    bg-inverse-primary 
                                                @elseif($item->job_status == 'Complete' || $item->job_status == 'Extracted') 
                                                    bg-inverse-success 
                                                @elseif($item->job_status == 'Processing') 
                                                    bg-inverse-warning 
                                                @else
                                                    bg-inverse-danger 
                                                @endif">
                                                {{ $item->job_status }}
                                            </span>
                                        </td>
                                    <td class="j_name">{{ $item->job_creator }}</td>
                                    <td class="text-right">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                <i class="material-icons">more_vert</i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item view_joborder" href="{{ route('view/details', ['id' => \Illuminate\Support\Facades\Crypt::encryptString($item->id)]) }}"><i class="fa fa-eye m-r-5"></i> View </a>                                                
                                                <a class="dropdown-item edit_joborder" href="#"
                                                    data-id="{{ $item->id }}" 
                                                    data-job_status="{{ $item->job_status }}" 
                                                    data-job_assign-person="{{ $item->job_assign_person }}" 
                                                    data-toggle="modal" 
                                                    data-target="#edit_joborder"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                <a class="dropdown-item delete_order" href="#" data-toggle="modal"  data-target="#delete_order"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                            </div>
                                        </div>
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
        
        <!-- Delete Estimate Modal -->
        <div class="modal custom-modal fade" id="delete_order" tabindex="-1" role="dialog" aria-labelledby="delete_order_label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Estimate</h3>
                            <p>Are you sure you want to delete this Job Order?</p>
                        </div>
                        <form action="{{ route('form/joborders/delete') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" class="e_id" value="">
                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary continue-btn submit-btn">Delete</button>
                                </div>
                                <div class="col-6">
                                    <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete Estimate Modal -->
         
        <!-- Edit User Modal -->
        <div id="edit_joborder" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Job Order</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <br>
                    <div class="modal-body">
                        <form action="{{ route('form/joborders/update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="e_id">
                            <div class="row"> 
                                <div class="col-sm-6"> 
                                    <label>Status</label>
                                    <select class="form-control custom-select" tabindex="1" name="job_status" required>
                                        <option>New</option>
                                        <option value="Complete">Complete</option>
                                        <option value="Extracted">Extracted</option>
                                        <option value="Processing">On Process</option>
                                        <option value="DVR Problem">DVR Problem</option>
                                        <option value="No Record Found">No Record Found</option>
                                        <option value="Unit Not Available">Unit Not Available</option>
                                    </select>
                                </div>
                                <div class="col-sm-6"> 
                                    <label>Assign to</label>
                                    <select class="select" name="job_assign_person" required>
                                        @foreach ($users as $user )
                                            <option value="{{ $user->name }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            <br>
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
            $.fn.dataTable.ext.type.order['date-eu'] = function (data) {
                return moment(data, 'DD MMMM, YYYY').unix();
            };

            $(document).ready(function() {
                const table = $('#jobList').DataTable({
                    lengthMenu: [
                        [10, 25, 50, 100, 150],
                        [10, 25, 50, 100, 150]
                    ],
                    pageLength: 10,
                    order: [[3, 'desc']],
                    processing: true,
                    serverSide: false,
                    ordering: true,
                    searching: true,
                    columnDefs: [
                        { type: 'date-eu', targets: 3 },
                        
                    ]
                });
                
                $('.btn_search').on('click', function() {
                    table.draw();
                });
            });
        </script>

         {{-- delete model --}}
         <script>
            $(document).on('click','.delete_order',function()
            {
                var _this = $(this).parents('tr');
                $('.e_id').val(_this.find('.id').text());
            });
        </script>

        {{-- Edit/Update Modal --}}
        <script>
            $(document).on('click', '.edit_joborder', function() {
                var joborderId = $(this).data('id');
                var jobStatus = $(this).data('job_status');
                var assignedPerson = $(this).data('job_assign-person');  // Correct the attribute name

                $('#edit_joborder #e_id').val(joborderId);
                $('#edit_joborder select[name="job_status"]').val(jobStatus);
                $('#edit_joborder select[name="job_assign_person"]').val(assignedPerson);

                $('#edit_joborder').modal('show');
            });
        </script>

    @endsection
@endsection
