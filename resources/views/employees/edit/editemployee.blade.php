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
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Employee View</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Employee View Edit</li>
                        </ul>
                    </div>
                </div>
            </div>
			<!-- /Page Header -->
              
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Employee edit</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('all/employee/update') }}" method="POST">
                                @csrf
                                <input type="hidden" class="form-control" id="id" name="id" value="{{ $employees->id }}">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Employee ID</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" id="employee_id" name="employee_id" value="{{ $employees->employee_id }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Full Name</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" id="name" name="name" value="{{ $employees->name }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Phone</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" id="phone" name="phone" value="{{ $employees->phone }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Email</label>
                                    <div class="col-md-10">
                                        <input type="email" class="form-control" id="email" name="email" value="{{ $employees->email }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Birth Date</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control datetimepicker" id="birth_date" name="birth_date" value="{{ $employees->birth_date }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Gender</label>
                                    <div class="col-md-10">
                                        <select class="select form-control" id="gender" name="gender">
                                            <option value="{{ $employees->gender }}" {{ ( $employees->gender == $employees->gender) ? 'selected' : '' }}>{{ $employees->gender }} </option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Company</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" id="company" name="company" value="{{ $employees->company }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Garage</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" id="garage" name="garage" value="{{ $employees->garage }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Department</label>
                                    <div class="col-md-10">
                                        <select class="select form-control" id="department" name="department">
                                            <option value="">Select Department</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}" {{ ($employees->department_id == $department->id) ? 'selected' : '' }}>
                                                    {{ $department->department }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Designation</label>
                                    <div class="col-md-10">
                                        <select class="select form-control" id="designation" name="designation">
                                            <option value="">Select Designation</option>
                                            @foreach ($designations as $designation)
                                                <option value="{{ $designation->id }}" {{ ($employees->designation_id == $designation->id) ? 'selected' : '' }}>
                                                    {{ $designation->designation }}
                                                </option>
                                            @endforeach
                                        </select>                                   
                                        </div>
                                    </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Date Hired</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" id="date_hired" name="date_hired" value="{{ \Carbon\Carbon::parse($employees->date_hired)->format('d F Y') }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Status</label>
                                    <div class="col-md-10">
                                        <select class="select form-control" id="status" name="status">
                                                <option value="{{ $employees->status }}" {{ ( $employees->status == $employees->status) ? 'selected' : '' }}>{{ $employees->status }} </option>
                                                <option value="Active" {{ ($employees->status == 'Active') ? 'selected' : '' }}>Active</option>
                                                <option value="InActive" {{ ($employees->status == 'InActive') ? 'selected' : '' }}>InActive</option>
                                                <option value="Resigned" {{ ($employees->status == 'Resigned') ? 'selected' : '' }}>Resigned</option>
                                                <option value="AWOL" {{ ($employees->status == 'AWOL') ? 'selected' : '' }}>AWOL</option>
                                            </select>                                    
                                        </div>
                                    </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2"></label>
                                    <div class="col-md-10">
                                        <button type="submit" class="btn btn-primary submit-btn">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
        
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
            }
            else
            {
                $box.prop("checked", false);
            }
        });
    </script>
    @endsection

@endsection
