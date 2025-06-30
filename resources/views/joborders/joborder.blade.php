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
                    <h3 class="page-title">Job Orders</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Job Orders</li>
                    </ul>
                </div>
                <div class="col-auto float-right ml-auto">
                    <a href="{{ route('create/joborders/page') }}" class="btn add-btn">
                        <i class="fa fa-plus"></i> Create Job
                    </a>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <!-- Search Filter -->
        <div class="row filter-row">
            <div class="col-sm-6 col-md-3">
                <div class="form-group form-focus">
                    <div class="cal-icon">
                        <input class="form-control floating datetimepicker" type="text" id="from_date">
                    </div>
                    <label class="focus-label">From</label>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="form-group form-focus">
                    <div class="cal-icon">
                        <input class="form-control floating datetimepicker" type="text" id="to_date">
                    </div>
                    <label class="focus-label">To</label>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="form-group form-focus select-focus">
                    <select id="status_filter" class="select floating">
                        <option value="">All Status</option>
                        <option value="New">New</option>
                        <option value="Complete">Complete</option>
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
                    <table class="table table-hover table-bordered mb-0" id="jobList" style="width: 100%">
                        <thead class="thead-dark">
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
                            @foreach ($joborderview as $item)
                            <tr>
                                <td hidden class="id">{{ $item->id }}</td>
                                <td class="j_name font-weight-bold text-primary">{{ $item->job_name }}</td>
                                <td class="j_type">{{ $item->job_type }}</td>
                                <td class="j_filled">{{ date('j M Y (h:i A)', strtotime($item->job_date_filled)) }}</td>
                                <td class="text-center">
                                    <span class="badge px-4 py-2 fs-5 fw-bold rounded-pill
                                        @if($item->job_status == 'New') badge-primary
                                        @elseif($item->job_status == 'Complete' || $item->job_status == 'Extracted') badge-success
                                        @elseif($item->job_status == 'Processing') badge-warning
                                        @else badge-danger
                                        @endif">
                                        {{ $item->job_status }}
                                    </span>
                                </td>
                                <td class="j_name">{{ $item->job_creator }}</td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-info view_joborder" title="View"
                                    href="{{ route('view/details', ['id' => \Illuminate\Support\Facades\Crypt::encryptString($item->id)]) }}">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                    <a class="btn btn-sm btn-warning text-white edit_joborder" title="Edit"
                                    href="#"
                                    data-id="{{ $item->id }}"
                                    data-job_status="{{ $item->job_status }}"
                                    data-job_assign-person="{{ $item->job_assign_person }}"
                                    data-toggle="modal"
                                    data-target="#edit_joborder">
                                        <i class="fa fa-pencil"></i>
                                    </a>

                                    <a class="btn btn-sm btn-danger delete_order" title="Delete"
                                    href="#"
                                    data-toggle="modal"
                                    data-target="#delete_order">
                                        <i class="fa fa-trash"></i>
                                    </a>
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

    <!-- Delete Modal -->
    <div class="modal custom-modal fade" id="delete_order" tabindex="-1" role="dialog" aria-labelledby="delete_order_label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Delete Job Order</h3>
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
                                <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-secondary cancel-btn">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="edit_joborder" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Job Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div><br>
                <div class="modal-body">
                    <form action="{{ route('form/joborders/update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" id="e_id">
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Status</label>
                                <select class="form-control custom-select" name="job_status" required>
                                    <option value="New">New</option>
                                    <option value="Complete">Complete</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label>Assign to</label>
                                <select class="select" name="job_assign_person" required>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->name }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div><br>
                        <div class="submit-section">
                            <button type="submit" class="btn btn-primary submit-btn">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /Page Wrapper -->

@section('script')
<script>
    // Custom Date Sorting (EU Format)
    $.fn.dataTable.ext.type.order['date-eu'] = function (data) {
        return moment(data, 'D MMM YYYY (h:mm A)').unix();
    };

    $(document).ready(function () {
        const table = $('#jobList').DataTable({
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            pageLength: 10,
            order: [[3, 'desc']],
            processing: true,
            serverSide: false,
            columnDefs: [{ type: 'date-eu', targets: 3 }]
        });

        // Custom Status Filter
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            const selectedStatus = $('#status_filter').val();
            const rowStatus = data[4].trim();
            return selectedStatus === "" || rowStatus === selectedStatus;
        });

        $('.btn_search').on('click', function () {
            table.draw();
        });

        // Delete Order Modal
        $(document).on('click', '.delete_order', function () {
            const id = $(this).closest('tr').find('.id').text().trim();
            $('.e_id').val(id);
        });

        // Edit Order Modal
        $(document).on('click', '.edit_joborder', function () {
            $('#e_id').val($(this).data('id'));
            $('select[name="job_status"]').val($(this).data('job_status'));
            $('select[name="job_assign_person"]').val($(this).data('job_assign-person'));
        });
    });
</script>
@endsection
@endsection
