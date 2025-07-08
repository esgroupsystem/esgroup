
@extends('layouts.master')
@section('content')
    @section('style')
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" >
    <!-- checkbox style -->
    <link rel="stylesheet" href="{{ URL::to('assets/css/checkbox-style.css') }}">
    @endsection
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-lists-center">
                    <div class="col">
                        <h3 class="page-title">Employee</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Employee</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_employee"><i class="fa fa-plus"></i> Add Employee</a>
                        <div class="view-icons">
                            <a href="{{ route('all/employee/card') }}" class="grid-view btn btn-link active"><i class="fa fa-th"></i></a>
                            <a href="{{ route('all/employee/list') }}" class="list-view btn btn-link"><i class="fa fa-bars"></i></a>
                        </div>
                    </div>
                </div>
            </div>
			<!-- /Page Header -->

            <!-- Search Filter -->
            <form action="{{ route('all/employee/search') }}" method="POST">
                @csrf
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-3">  
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" name="employee_id">
                            <label class="focus-label">Employee ID</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">  
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" name="name">
                            <label class="focus-label">Employee Name</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3"> 
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" name="position">
                            <label class="focus-label">Position</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">  
                        <button type="sumit" class="btn btn-success btn-block"> Search </button>  
                    </div>
                </div>
            </form>
            <!-- Search Filter -->
              
            <div class="row staff-grid-row">
                @foreach ($employees as $employee)
                <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3">
                    <div class="profile-widget">
                        @php
                            $user = Auth::user();
                            $approved = in_array($employee->id, $approvals ?? []); // Make sure $approvals is passed from controller
                        @endphp

                            <!-- Status badge -->
                        <span class="badge badge-{{ $employee->status === 'Active' ? 'success' : 'secondary' }} position-absolute" style="top: 10px; right: 10px;">
                            {{ $employee->status }}
                        </span>

                        <div class="profile-img">
                            @if ($user->role_name === 'Admin' || $approved)
                                <a href="{{ url('employee/profile/' . $employee->id) }}" class="avatar">
                                    <img class="user-profile" 
                                        src="{{ asset('assets/employeepic/' . ($employee->profile_picture ?? 'default.png')) }}" 
                                        alt="{{ $employee->name }}">
                                </a>
                            @else
                                <a href="{{ url('employee/request-approval/' . $employee->id) }}" class="avatar">
                                    <img class="user-profile" 
                                        src="{{ asset('assets/employeepic/default.png') }}" 
                                        alt="Restricted">
                                </a>
                            @endif
                        </div>
                        <h4 class="user-name m-t-10 mb-0 text-ellipsis">
                                <a href="#">
                                    {{ $employee->name }}
                                </a>
                            <div class="small text-muted">{{ $employee->designation }}</div>
                        </h4>
                        @if ($user->role_name === 'Admin' || $approved)

                        @else
                            <a href="{{ url('employee/request-approval/' . $employee->id) }}" class="btn btn-warning btn-sm">Request Access</a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>                                  
        </div>
        <!-- /Page Content -->

        <!-- Add Employee Modal -->
        <div id="add_employee" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Employee</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('all/employee/save') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">  
                                    <div class="form-group">
                                        <label class="col-form-label">Employee ID <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="employee_id" name="employee_id" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Full Name <span class="text-danger">*</span></label>
                                        <input class="form-control" style="width: 100%;" tabindex="-1" id="name" name="name" required oninput="capitalizeWords(this)">
                                    </div>
                                </div>
                            
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                        <input class="form-control" type="email" id="email" name="email" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Birth Date <span class="text-danger">*</span></label>
                                        <div class="cal-icon">
                                            <input class="form-control datetimepicker" type="text" id="birthDate" name="birthDate" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Gender <span class="text-danger">*</span></label>
                                        <select class="select form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="gender" name="gender" required>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Line Manager <span class="text-danger">*</span></label>
                                        <input class="select select2s-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="company" name="company" required>
                                    </div>
                                </div> --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Phone <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" id="phone" name="phone" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Company <span class="text-danger">*</span></label>
                                        <select class="select form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" name="company" required>
                                            <option value="ES Transports">Jell Transports</option>
                                            <option value="Earth Star">Earth Star</option>
                                            <option value="Jell Transports">Jell Transports</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Department <span class="text-danger">*</span></label>
                                        <select class="select form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" name="department" required>
                                            <option value="">Select Department</option>
                                            @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}">{{ $department->department }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Designation <span class="text-danger">*</span></label>
                                        <select class="select form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" name="designation" required>
                                        <option value="">Select Designation</option>
                                            @foreach ($designations as $designation)
                                                    <option value="{{ $designation->id }}">{{ $designation->designation }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Garage <span class="text-danger">*</span></label>
                                        <select class="select form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" name="garage" required>
                                            <option value="Mirasol">Mirasol</option>
                                            <option value="Balintawak">Balintawak</option>
                                            <option value="VGC">VGC</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date Hired <span class="text-danger">*</span></label>
                                        <div class="cal-icon">
                                            <input class="form-control datetimepicker" type="text" name="date_hired" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <select class="select form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" name="status" required>
                                            <option value="Active">Active</option>
                                            <option value="Resigned">Resigned</option>
                                            <option value="AWOL">AWOL</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Profile Picture</label>
                                        <input type="file" class="form-control" name="profile_picture" accept="image/*" onchange="previewProfilePic(this)">
                                        <br>
                                        <img id="profilePicPreview" src="{{ asset('assets/employeepic/default.png') }}" alt="Profile Picture Preview" width="100" style="display: none; border: 1px solid #ccc; padding: 3px;">
                                    </div>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Employee Modal -->
        
    </div>
    <!-- /Page Wrapper -->
    @section('script')
    <script>
        $("input:checkbox").on('click', function()
        {
            var $box = $(this);
            if ($box.is(":checked"))
            {
                var group = "input:checkbox[class='" + $box.attr("class") + "']";
                $(group).prop("checked", false);
                $box.prop("checked", true);
            } else {
                $box.prop("checked", false);
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.select2s-hidden-accessible').select2({
                closeOnSelect: false
            });
        });
    </script>

    <script>
        function capitalizeWords(input) {
            let words = input.value.toLowerCase().split(' ');
            for (let i = 0; i < words.length; i++) {
                if (words[i].length > 0) {
                    words[i] = words[i][0].toUpperCase() + words[i].substr(1);
                }
            }
            input.value = words.join(' ');
        }
    </script>

    <script>
        function previewProfilePic(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    let img = document.getElementById('profilePicPreview');
                    img.src = e.target.result;
                    img.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    @endsection
@endsection
