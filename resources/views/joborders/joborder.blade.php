
@extends('layouts.master')
@section('content')

    {{-- message --}}
    
    <!-- Page Wrapper -->
    <div class="page-wrapper">

        <!-- Page Content -->
        <div class="content container-fluid">
        
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
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
                                    <th>Bus Number</th>
                                    <th>Type</th>
                                    <th>Date Issue</th>
                                    <th>Date Incident</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Remarks</th>
                                    <th>Assign To</th>
                                    <th>Status</th>
                                    <th>Reported by</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($joborderview as $item )
                                <tr>
                                    <td class="j_name">{{ $item->job_name }}</td>
                                    <td class="j_type">{{ $item->job_type }}</td>
                                    <td class="j_filled">{{ date('d F, Y H:i:s', strtotime($item->job_date_filled)) }}</td>
                                    <td class="j_issue">{{date('d F, Y',strtotime($item->job_datestart)) }}</td>
                                    <td class="j_time_start">{{ $item->job_time_start }}</td>
                                    <td class="j_time_end">{{ $item->job_time_end }}</td>
                                    <td class="j_remarks" title="{{ $item->job_remarks }}">{{ Str::limit($item->job_remarks, 50, '...') }}</td>
                                    <td class="j_ass_per">{{ $item->job_assign_person }}</td>
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
                                                <a class="dropdown-item" href="{{ url('edit/estimate/'.$item->estimate_number) }}"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                <a class="dropdown-item delete_estimate" href="#" data-toggle="modal" data-target="#delete_estimate"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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
        <div class="modal custom-modal fade" id="delete_estimate" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Estimate</h3>
                            <p>Are you sure want to delete?</p>
                        </div>
                        <form action="{{ route('form/joborders/delete') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" class="e_id" value="">
                            <input type="hidden" name="estimate_number" class="estimate_number" value="">
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
                    order: [[2, 'desc']],
                    processing: true,
                    serverSide: false,
                    ordering: true,
                    searching: true,
                    columnDefs: [
                        { type: 'date-eu', targets: 2 }
                    ]
                });
                
                $('.btn_search').on('click', function() {
                    table.draw();
                });
            });
        </script>

         {{-- delete model --}}
         <script>
            $(document).on('click','.delete_estimate',function()
            {
                var _this = $(this).parents('tr');
                $('.e_id').val(_this.find('.ids').text());
                $('.estimate_number').val(_this.find('.estimate_number').text());
            });
        </script>


    @endsection
@endsection
