
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

        <!-- Saving JobOrder -->
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
                                            <option value="{{ $bus->cat_name.'-'.$bus->cat_busnum }}">{{ $bus->full_name }}</option>                                       
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
                                    <input type="time" class="form-control" id="j_start_time" name="job_time_start" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>End Time</label>
                                    <input type="time" class="form-control" id="j_end_time" name="job_time_end" required>
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

                                <!------- Seatting Diagram ------>
                                <center>
                                <div class="bus-container">
                                    <h3>Select Bus Seats</h3>
                                    <img src="{{ asset('assets/images/bus-layouts.jpeg') }}" alt="Bus Layout" class="bus-background">
                                    <div class="seat-arrangement">
                                        <!-- First Layer -->
                                        @for ($i = 1; $i <= 1; $i++)
                                            <button class="seat-button" data-seat-number="{{ $i }}">
                                                {{ $i }}
                                            </button>
                                                <span class="empty-seat"></span>
                                                    @for ($i = 2; $i <= 3; $i++)
                                                        <button class="seat-button" data-seat-number="{{ $i }}">
                                                             {{ $i }}
                                                        </button>
                                                    @endfor
                                                    <span class="empty-seat"></span>
                                                    @for ($i = 4; $i <= 14; $i++)
                                                        <button class="seat-button" data-seat-number="{{ $i }}">
                                                             {{ $i }}
                                                        </button>
                                                    @endfor
                                            <!-- /First Layer -->
                                            <!-- Second Layer -->
                                                    <div>
                                                    <span class="empty-seat1"></span>
                                                        @for ($i = 15; $i <= 16; $i++)
                                                            <button class="seat-button" data-seat-number="{{ $i }}">
                                                                 {{ $i }}
                                                            </button>
                                                        @endfor
                                                        <span class="empty-seat"></span>
                                                        @for ($i = 17; $i <= 19; $i++)
                                                            <button class="seat-button" data-seat-number="{{ $i }}">
                                                                 {{ $i }}
                                                            </button>
                                                        @endfor
                                                        <span class="empty-seat3"></span>
                                                        @for ($i = 20; $i <= 24; $i++)
                                                            <button class="seat-button" data-seat-number="{{ $i }}">
                                                                 {{ $i }}
                                                            </button>
                                                        @endfor
                                                    </div> 
                                            <!-- /Second Layer -->  
                                            <!-- third Layer -->
                                                    <div>
                                                    <span class="empty-seat4"></span>
                                                        @for ($i = 25; $i <= 27; $i++)
                                                            <button class="seat-button" data-seat-number="{{ $i }}">
                                                                 {{ $i }}
                                                            </button>
                                                        @endfor 
                                                        <span class="empty-seat3"></span>
                                                        @for ($i = 28; $i <= 32; $i++)
                                                            <button class="seat-button" data-seat-number="{{ $i }}">
                                                                 {{ $i }}
                                                            </button>
                                                        @endfor 
                                                    </div> 
                                            <!-- /third Layer --> 
                                             <!-- fourth Layer --> 
                                             <div>
                                            <span class="empty-seat6"></span>
                                                    @for ($i = 53; $i <= 53; $i++)
                                                        <button class="seat-button" data-seat-number="{{ $i }}">
                                                             {{ $i }}
                                                        </button>
                                                    @endfor|
                                                </div>
                                                <!-- /fourth Layer --> 
                                                <!-- fifth Layer --> 
                                             <div> 
                                            <span class="empty-seat5"></span>
                                                    @for ($i = 33; $i <= 34; $i++)
                                                        <button class="seat-button" data-seat-number="{{ $i }}">
                                                             {{ $i }}
                                                        </button>
                                                    @endfor
                                                    <span class="empty-seat"></span>
                                                    @for ($i = 35; $i <= 37; $i++)
                                                        <button class="seat-button" data-seat-number="{{ $i }}">
                                                             {{ $i }}
                                                        </button>
                                                    @endfor
                                                    <span class="empty-seat3"></span>
                                                        @for ($i = 38; $i <= 42; $i++)
                                                            <button class="seat-button" data-seat-number="{{ $i }}">
                                                                 {{ $i }}
                                                            </button>
                                                        @endfor  
                                            <!-- /fifth Layer --> 
                                            <!-- sixth Layer -->
                                                    <div>
                                                    <span class="empty-seat1"></span>
                                                        @for ($i = 43; $i <= 44; $i++)
                                                            <button class="seat-button" data-seat-number="{{ $i }}">
                                                                 {{ $i }}
                                                            </button>
                                                        @endfor
                                                        <span class="empty-seat"></span>
                                                        @for ($i = 45; $i <= 47; $i++)
                                                            <button class="seat-button" data-seat-number="{{ $i }}">
                                                                 {{ $i }}
                                                            </button>
                                                        @endfor
                                                        <span class="empty-seat3"></span>
                                                        @for ($i = 48; $i <= 52; $i++)
                                                            <button class="seat-button" data-seat-number="{{ $i }}">
                                                                 {{ $i }}
                                                            </button>
                                                        @endfor
                                                    </div> 
                                            <!-- /sixth Layer -->
                                        @endfor
                                    </div>
                                    <div class="selected-seat">
                                        <label for="selected_seat">Selected Seat:</label>
                                        <span id="selected_seat">None</span>
                                    </div>
                                </div>
                                <!------- /Seatting Diagram ------>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Save</button>
                        </div>
                        </center>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Saving JobOrder -->
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const seatButtons = document.querySelectorAll('.seat-button');
            const selectedSeatLabel = document.getElementById('selected_seat');

            seatButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const seatNumber = this.getAttribute('data-seat-number');
                    selectedSeatLabel.textContent = seatNumber; // Update label with selected seat number
                });
            });
        });
    </script>
    @endsection
@endsection
