@extends('layouts.master')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Daily Scheduling</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('all/employee/list') }}">Employees</a></li>
                            <li class="breadcrumb-item active">Shift Scheduling</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        {{-- <a href="{{ route('schedule.lock') }}" class="btn btn-danger m-r-5">Lock Plotting</a> --}}
                    </div>
                </div>
            </div>
            <!-- /Page Header -->


            <!-- Content Starts -->
            <!-- Search Filter -->
            <div class="row filter-row">
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating">
                        <label class="focus-label">Employee</label>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus select-focus">
                        <select class="select floating">
                            <option>All Department</option>
                            <option value="1">Finance</option>
                            <option value="2">Finance and Management</option>
                            <option value="3">Hr & Finance</option>
                            <option value="4">ITech</option>
                        </select>
                        <label class="focus-label">Department</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-2">
                    <div class="form-group form-focus focused">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker" type="text">
                        </div>
                        <label class="focus-label">From</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-2">
                    <div class="form-group form-focus focused">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker" type="text">
                        </div>
                        <label class="focus-label">To</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-2">
                    <a href="#" class="btn btn-success btn-block"> Search </a>
                </div>
            </div>
            <!-- Search Filter -->


            <!-- Content Start -->
            <div class="row">
                <div class="col-md-12">
                    <div class="p-4 border rounded shadow-sm" style="border: 2px solid #ccc; background-color: #fff;">
                        <h1 class="text-center font-weight-bold mb-4">
                            This is the month of <span style="color: red">{{ $days->first()->format('F Y') }}</span>
                            Schedule
                        </h1>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Driver</th>
                                        @foreach ($days as $day)
                                            <th>{{ $day->format('D d') }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($drivers as $driver)
                                        <tr>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#" class="avatar">
                                                        <img alt=""
                                                            src="{{ asset('assets/employeepic/' . $driver->profile_picture) }}">
                                                    </a>
                                                    <a href="#">{{ $driver->name }}
                                                        <span>{{ $driver->designation->designation ?? '' }}</span></a>
                                                </h2>
                                            </td>
                                            @foreach ($days as $day)
                                                @php
                                                    $key = $driver->id . '_' . $day->format('Y-m-d');
                                                    $sched = $schedules[$key][0] ?? null;
                                                @endphp
                                                <td>
                                                    <div class="user-add-shedule-list">
                                                        @if ($sched)
                                                            <h2>
                                                                <a href="#" data-toggle="modal"
                                                                    data-target="#edit_schedule"
                                                                    data-id="{{ $sched->id }}"
                                                                    data-date="{{ $day->toDateString() }}"
                                                                    data-start="{{ $sched->start_time }}"
                                                                    data-end="{{ $sched->end_time }}"
                                                                    data-driver="{{ $driver->id }}"
                                                                    data-name="{{ $driver->name }}"
                                                                    data-bus_id="{{ $sched->bus_id }}"
                                                                    data-is_dayoff="{{ $sched->is_dayoff }}"
                                                                    style="border:2px dashed {{ $sched->is_dayoff ? '#dc3545' : '#1eb53a' }};
                                                            display: block;
                                                            text-align: center;
                                                            padding: 10px;
                                                            min-height: 70px;
                                                            min-width: 175px;">
                                                                    @if ($sched->is_dayoff)
                                                                        <div class="text-center">
                                                                            <span
                                                                                class="text-danger font-weight-bold d-block"
                                                                                style="font-size: 30px; padding-top: 5px;">REST
                                                                                DAY</span>
                                                                        </div>
                                                                    @else
                                                                        <span class="username-info m-b-10">
                                                                            {{ \Carbon\Carbon::parse($sched->start_time)->format('g:i A') }}
                                                                            -
                                                                            {{ \Carbon\Carbon::parse($sched->end_time)->format('g:i A') }}
                                                                        </span>
                                                                        <div>
                                                                            {{ $sched->cat_name }} -
                                                                            ({{ $sched->cat_busnum }})
                                                                        </div>
                                                                    @endif
                                                                </a>
                                                            </h2>
                                                        @else
                                                            <a href="#" data-toggle="modal"
                                                                data-target="#add_schedule"
                                                                data-date="{{ $day->toDateString() }}"
                                                                data-driver="{{ $driver->id }}"
                                                                data-name="{{ $driver->name }}">
                                                                <span><i class="fa fa-plus"></i></span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Content End -->
        </div>
        <!-- /Page Content -->

        <!-- Add Schedule Modal -->
        <div id="add_schedule" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Schedule</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('schedule.store') }}">
                            @csrf
                            <input type="hidden" name="employee_id" id="schedule_employee_id">

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Employee Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="schedule_employee_name"
                                            name="employee_display_name" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Bus Unit# <span class="text-danger">*</span></label>
                                        <select class="form-control select2-bus" name="bus_id" required>
                                            <option value="">Select</option>
                                            @foreach ($busList as $bus)
                                                <option value="{{ $bus->cat_id }}">{{ $bus->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Date</label>
                                        <input type="date" name="work_date" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Start Time <span
                                                class="text-danger">*</span></label>
                                        <input type="time" name="start_time" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="col-form-label">End Time <span class="text-danger">*</span></label>
                                        <input type="time" name="end_time" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label><input type="checkbox" name="apply_full_month"> Apply to whole
                                            month?</label>
                                    </div>
                                </div>
                            </div>

                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Schedule Modal -->


        <!-- Edit Schedule Modal -->
        <div id="edit_schedule" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Schedule</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('schedule.update') }}">
                            @csrf
                            <input type="hidden" name="schedule_id" id="edit_schedule_id">
                            <input type="hidden" name="employee_id" id="edit_employee_id">
                            <input type="hidden" name="work_date" id="edit_work_date">

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Employee Name</label>
                                        <input type="text" class="form-control" id="edit_employee_name" disabled>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Bus Unit</label>
                                        <select class="form-control select2" name="bus_id" id="edit_bus_id">
                                            <option value="">Select Bus</option>
                                            @foreach ($busList as $bus)
                                                <option value="{{ $bus->cat_id }}">{{ $bus->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Start Time</label>
                                        <input type="time" name="start_time" id="edit_start_time"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>End Time</label>
                                        <input type="time" name="end_time" id="edit_end_time" class="form-control">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_dayoff" id="edit_is_dayoff"
                                            class="form-check-input">
                                        <label class="form-check-label" for="edit_is_dayoff">Mark this as Day Off / Rest
                                            Day</label>
                                    </div>
                                </div>
                            </div>

                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Update Schedule</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Page Wrapper -->
@section('script')
    <script>
        $('#add_schedule').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const employeeId = button.data('driver');
            const employeeName = button.data('name');
            const workDate = button.data('date');

            console.log("Opening modal for:", employeeId, employeeName, workDate);

            const modal = $(this);
            modal.find('#schedule_employee_id').val(employeeId);
            modal.find('#schedule_employee_name').val(employeeName);
            modal.find('input[name="work_date"]').val(workDate);
        });

        $('#edit_schedule').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);

            const scheduleId = button.data('id');
            const employeeId = button.data('driver');
            const employeeName = button.data('name');
            const workDate = button.data('date');
            const startTime = button.data('start');
            const endTime = button.data('end');
            const busId = button.data('bus_id');
            const isDayoff = button.data('is_dayoff');

            const modal = $(this);
            modal.find('#edit_schedule_id').val(scheduleId);
            modal.find('#edit_employee_id').val(employeeId);
            modal.find('#edit_employee_name').val(employeeName);
            modal.find('#edit_work_date').val(workDate);
            modal.find('#edit_start_time').val(startTime);
            modal.find('#edit_end_time').val(endTime);
            modal.find('#edit_bus_id').val(busId).trigger('change'); // ✅ Set selected bus
            modal.find('#edit_is_dayoff').prop('checked', isDayoff == 1); // ✅ Set dayoff
        });

        $('#edit_is_dayoff').on('change', function() {
            const disabled = $(this).is(':checked');
            $('#edit_start_time, #edit_end_time').prop('disabled', disabled);
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.select2-bus').select2({
                placeholder: "Select a Bus Unit",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endsection
@endsection
