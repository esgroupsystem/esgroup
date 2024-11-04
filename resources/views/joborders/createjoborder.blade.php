
@extends('layouts.master')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Create Job Order</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Create Job Order</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
              
            <div class="row">
                <div class="col-sm-12">
                    <form action="{{ route('form/joborders/save') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Bus Type <span class="text-danger">*</span></label>
                                    <select class="select" id="j_name" name="job_name" required>
                                        <option value="">-- Select Bus --</option>  
                                        @foreach( $busList as $key=>$bus )
                                            <option value="{{ $bus->cat_id }}">{{ $bus->full_name }}</option>                                       
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Type of Problem <span class="text-danger">*</span></label>
                                    <select class="select" id="j_type" name="job_type">
                                        <option>-- Select  --</option>
                                                <option value="ACCIDENT">ACCIDENT</option>
                                                <option value="CCTV DVR ISSUE">CCTV DVR ISSUE </option>
                                                <option value="CCTV MONITOR ISSUE">CCTV MONITOR ISSUE </option>
                                                <option value="COLLECTING FARE">COLLECTING FARE</option>
                                                <option value="CUTTING FARE">CUTTING FARE</option>
                                                <option value="RE- ISSUEING TICKET">RE- ISSUEING TICKET</option>
                                                <option value="TAMPERING TICKET">TAMPERING TICKET</option>
                                                <option value="UNREGISTERED TICKET">UNREGISTERED TICKET</option>
                                                <option value="DELAYING ISSUANCE OF TICKET">DELAYING ISSUANCE OF TICKET</option>
                                                <option value="ROLLING TICKETS">ROLLING TICKETS</option>
                                                <option value="REMOVING HEADSTAB OF TICKET">REMOVING HEADSTAB OF TICKET</option>
                                                <option value="USING STUB TICKET">USING STAB TICKET</option>
                                                <option value="WRONG CLOSING / OPEN">WRONG CLOSING / OPEN</option>
                                                <option value="OTHERS">OTHERS</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Date of Accident<span class="text-danger">*</span></label>
                                        <div class="cal-icon">
                                            <input class="form-control datetimepicker" type="text" id="j_datestart" name="job_datestart" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Requestor<span class="text-danger">*</span></label>
                                    <input class="form-control" id="j_creator" name="job_creator" value="{{ $loggedUser->name }}" disabled>                                
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Start Time</label>
                                    <input type="time" class="form-control" id="j_start_time" name="start_time" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>End Time</label>
                                    <input type="time" class="form-control" id="j_end_time" name="end_time" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Status <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" id="j_status" name="j_status" value="New" placeholder="New" disabled>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Assign for <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" id="j_assign_p" name="job_assign_person" value="Not assigned" placeholder="Please wait to assign. . ." disabled>
                                    </div>
                                </div>
                            </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Other Information</label>
                                            <textarea class="form-control" rows="3" id="j_remarks" name="job_remarks"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn m-r-10">Save & Send</button>
                            <button type="submit" class="btn btn-primary submit-btn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->

    @section('script')
    
    <script>
        $(document).ready(function() {
            $('#j_type').select2({
                allowClear: false,
                width: '100%',
            });
        });
    </script>


    @endsection
@endsection
