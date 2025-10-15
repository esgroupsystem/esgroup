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
                                <th>Date of Incident</th>
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
                                    <td data-order="{{ \Carbon\Carbon::parse($item->job_datestart)->timestamp }}">
                                        {{ \Carbon\Carbon::parse($item->job_datestart)->format('j M Y') }}
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
                                    <td data-order="{{ \Carbon\Carbon::parse($item->created_at)->timestamp }}">
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('j M Y (h:i A)') }}
                                    </td>
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
<style>
    /* Make DataTable export buttons use Bootstrap colors properly */
    .dt-buttons .btn {
        color: #fff !important;
        border: none !important;
        font-weight: 500;
        border-radius: 6px;
        margin-right: 6px;
        padding: 6px 12px;
    }

    .dt-buttons .buttons-excel {
        background-color: #28a745 !important; /* green */
    }

    .dt-buttons .buttons-pdf {
        background-color: #dc3545 !important; /* red */
    }

    .dt-buttons .buttons-csv {
        background-color: #17a2b8 !important; /* teal/blue */
    }

    .dt-buttons .buttons-print {
        background-color: #6c757d !important; /* gray */
    }

    /* Optional: hover effect for nicer UI */
    .dt-buttons .btn:hover {
        opacity: 0.9;
    }
</style>
<script>
$(document).ready(function() {

    $.fn.dataTable.ext.order['dom-data-order'] = function(settings, col) {
        return this.api().column(col, { order: 'index' }).nodes().map(function(td) {
            return $(td).attr('data-order') * 1 || 0;
        });
    };

    // ✅ Initialize DataTable with export buttons
    const table = $('#jobList').DataTable({
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        pageLength: 10,
        order: [[6, 'desc']],
        columnDefs: [
            { targets: [0], visible: false },
            { orderDataType: 'dom-data-order', targets: [3] }
        ],
        dom: 'Bfrtip', // ✅ add buttons container
        buttons: [
            { extend: 'excelHtml5', text: '<i class="fa fa-file-excel-o"></i> Excel', className: 'btn btn-success btn-sm text-white' },
            { extend: 'pdfHtml5', text: '<i class="fa fa-file-pdf-o"></i> PDF', className: 'btn btn-danger btn-sm text-white', orientation: 'landscape', pageSize: 'A4', exportOptions: { columns: ':visible:not(:last-child)' } },
            { extend: 'csvHtml5', text: '<i class="fa fa-file-text-o"></i> CSV', className: 'btn btn-info btn-sm text-white' },
            { extend: 'print', text: '<i class="fa fa-print"></i> Print', className: 'btn btn-secondary btn-sm text-white' }
        ]
    });

    // ✅ Filter logic
    $('#status_filter').val('Pending');
    table.column(4).search('Pending').draw();

    $('.btn_search').on('click', function() {
        const status = $('#status_filter').val();
        table.column(4).search(status || '').draw();
    });

    // ✅ Edit Modal
    $(document).on('click', '.edit_joborder', function() {
        $('#e_id').val($(this).data('id'));
        $('select[name="job_status"]').val($(this).data('job_status'));
        $('select[name="job_assign_person"]').val($(this).data('job_assign-person'));
    });

    // ✅ Delete Modal
    $(document).on('click', '.delete_order', function() {
        const id = $(this).closest('tr').find('td:first').text().trim();
        $('.e_id').val(id);
    });
});

</script>
@endsection

@endsection
