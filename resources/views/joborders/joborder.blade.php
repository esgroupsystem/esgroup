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
                            <option value="Pending">Pending</option>
                            <option value="Completed">Completed</option>
                        </select>
                        <label class="focus-label">Status</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <button class="btn btn-success btn-block btn_search"> Search </button>
                </div>
            </div>
            <!-- /Search Filter -->

        <!-- Job Orders Table -->
        <div class="card border-0 shadow-sm rounded-3 mt-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="jobList" style="width: 100%">
                        <thead class="table-light">
                            <tr>
                                <th hidden>ID</th>
                                <th>Bus Number</th>
                                <th>Type</th>
                                <th>Date Issue</th>
                                <th>Status</th>
                                <th>Reported by</th>
                                <th>Date Filled</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($joborderview as $item)
                                <tr>
                                    <td hidden>{{ $item->id }}</td>
                                    <td class="fw-semibold text-primary">{{ $item->job_name }}</td>
                                    <td>{{ $item->job_type }}</td>
                                    <td data-order="{{ $item->job_datestart }}">
                                        {{ date('j M Y', strtotime($item->job_datestart)) }}
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill px-3 py-2 fs-6 
                                            @if ($item->job_status == 'New') bg-primary
                                            @elseif($item->job_status == 'Completed' || $item->job_status == 'Extracted') bg-success
                                            @elseif($item->job_status == 'Pending') bg-warning text-dark
                                            @else bg-secondary @endif">
                                            {{ $item->job_status }}
                                        </span>
                                    </td>
                                    <td>{{ $item->job_creator }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td class="text-end">
                                        <a class="btn btn-outline-info btn-sm" title="View"
                                           href="{{ route('view/details', ['id' => Crypt::encryptString($item->id)]) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @if ((Auth::user()->role_name === 'Admin' || Auth::user()->role_name === 'IT') && $item->job_status !== 'Completed')
                                            <a class="btn btn-outline-warning btn-sm edit_joborder text-dark" 
                                               title="Edit"
                                               href="#"
                                               data-id="{{ $item->id }}"
                                               data-job_status="{{ $item->job_status }}"
                                               data-job_assign-person="{{ $item->job_assign_person }}"
                                               data-bs-toggle="modal" 
                                               data-bs-target="#edit_joborder">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        @endif
                                        @if (Auth::user()->role_name === 'Admin')
                                            <a class="btn btn-outline-danger btn-sm delete_order" 
                                               title="Delete"
                                               href="#"
                                               data-bs-toggle="modal" 
                                               data-bs-target="#delete_order">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /Page Content -->

        <!-- Delete Modal -->
        <div class="modal custom-modal fade" id="delete_order" tabindex="-1" role="dialog"
            aria-labelledby="delete_order_label" aria-hidden="true">
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
                                    <a href="javascript:void(0);" data-dismiss="modal"
                                        class="btn btn-secondary cancel-btn">Cancel</a>
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
                                        <option value="Pending">New</option>
                                        <option value="Completed">Complete</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label>Assign to</label>
                                    <!-- Hidden input to actually submit the ID -->
                                    <input type="hidden" name="job_assign_person" value="{{ Auth::id() }}">

                                    <!-- Readonly input just to display the name -->
                                    <input type="text" class="form-control" value="{{ Auth::user()->name }}"
                                        readonly>
                                </div>
                            </div>
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
        $.fn.dataTable.ext.type.order['date-eu'] = function(data) {
            return moment(data, 'D MMM YYYY (h:mm A)').unix();
        };

        $(document).ready(function() {
            const table = $('#jobList').DataTable({
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                pageLength: 10,
                order: [
                    [3, 'desc']
                ],
                processing: true,
                serverSide: false,
                columnDefs: [{
                    type: 'date-eu',
                    targets: 3
                }]
            });

            // 🔹 Set default filter to show only Pending on load
            $('#status_filter').val('Pending');
            table.column(4).search('Pending').draw();

            // 🔹 Custom Status Filter
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const selectedStatus = $('#status_filter').val();
                const rowStatus = data[4].trim();
                return selectedStatus === "" || rowStatus === selectedStatus;
            });

            // 🔹 Search button click event
            $('.btn_search').on('click', function() {
                const selectedStatus = $('#status_filter').val();

                if (selectedStatus) {
                    table.column(4).search(selectedStatus).draw();
                } else {
                    table.column(4).search('').draw();
                }
            });

            // 🔹 Delete Order Modal
            $(document).on('click', '.delete_order', function() {
                const id = $(this).closest('tr').find('.id').text().trim();
                $('.e_id').val(id);
            });

            // 🔹 Edit Order Modal
            $(document).on('click', '.edit_joborder', function() {
                $('#e_id').val($(this).data('id'));
                $('select[name="job_status"]').val($(this).data('job_status'));
                $('select[name="job_assign_person"]').val($(this).data('job_assign-person'));
            });
        });
    </script>
@endsection
@endsection
