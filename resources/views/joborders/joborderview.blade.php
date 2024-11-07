
@extends('layouts.master')
@section('content')
    {{-- message --}}
    
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Job Order View</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">View</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            
            <section class="review-section information">
                <div class="review-header text-center">
                    <h2 class="review-title">{{ $jobDetail->job_name }}</h2>
                    <p class="text-muted">Bus Name</p>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-nowrap review-table mb-0">
                                <tbody>
                                    <tr>
                                        <td>
                                            <form>
                                                <div class="form-group">
                                                    <label for="name">Bus Name</label>
                                                    <input type="text" class="form-control" id="name" value="{{ $jobDetail->job_name }}" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="depart3">Concern</label>
                                                    <input type="text" class="form-control" id="depart3" value="{{ $jobDetail->job_type }}" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="departa">Date of Accident</label>
                                                    <input type="text" class="form-control" id="departa" value="{{ date('j M Y', strtotime($jobDetail->job_datestart)) }}" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="qualif">Technician / Extractor </label>
                                                    <input type="text" class="form-control" id="qualif" value="{{ $jobDetail->job_assign_person }}" readonly>
                                                </div>
                                            </form>
                                        </td>
                                        <td>
                                            <form>
                                                <div class="form-group">
                                                    <label for="doj">Start Time</label>
                                                    <input type="text" class="form-control" value="{{ $jobDetail->job_time_start }}" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="doj">Date of Join</label>
                                                    <input type="text" class="form-control" id="doj" value="{{ $jobDetail->job_time_end }}" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="doc">Sit Number</label>
                                                    <input type="text" class="form-control" id="doc" value="{{ $jobDetail->job_sitNumber }}" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="qualif1">Remarks</label>
                                                    <textarea type="text" class="form-control" rows="4" id="qualif1" readonly>{{ $jobDetail->job_remarks }}</textarea>
                                                    </div>
                                            </form>
                                        </td>
                                        <td>
                                            <form>
                                                <div class="form-group">
                                                    <input hidden type="text" class="form-control" id="name1" value="{{ $jobDetail->job_status}}" readonly>
                                                </div>
                                                <center>
                                                <div class="logo-container">
                                                    @if($jobDetail->job_status === 'Complete')
                                                        <img src="{{ asset('assets/img/completelogo.png') }}" alt="Complete Logo" class="img-fluid custom-com-logo" disabled>
                                                    @elseif($jobDetail->job_status === 'New')
                                                        <img src="{{ asset('assets/img/newlogo.png') }}" alt="Complete Logo" class="img-fluid custom-new-logo" disabled>
                                                    @endif
                                                </div>
                                                </center>
                                            </form>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>	

    <!---- Modal for Saving Video and File ---> 

            <section class="review-section">
                <div class="review-header text-center">
                    <h3 class="review-title">Video Files / Documents Files</h3>
                    <p class="text-muted">Allowed file types: MP4, MP3, ASF.</p>
                </div>
                <form id="uploadForm" action="{{ route('job.files') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="job_order_id" value="{{ $jobDetail->id }}">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-review review-table mb-0" id="table_alterations">
                                    <thead>
                                        <tr>
                                            <th style="width:40px;">#</th>
                                            <th>Files</th>
                                            <th>Remarks</th>
                                            <th>Notes</th>
                                            <th style="width: 64px;">
                                                <button type="button" class="btn btn-primary btn-add-row">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="table_alterations_tbody">
                                        @foreach($FileDetails as $key => $file)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    @if(in_array(pathinfo($file->file_name, PATHINFO_EXTENSION), ['mp4', 'mp3', 'asf']))
                                                        <video width="320" height="240" controls>
                                                            <source src="{{ asset('storage/' . $file->file_path) }}" type="video/mp4">
                                                            <source src="{{ asset('storage/' . $file->file_path . '.asf') }}" type="video/x-ms-asf">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    @else
                                                        <img src="{{ asset('storage/' . $file->file_path) }}" alt="{{ $file->file_name }}" class="img-fluid" />
                                                    @endif
                                                </td>
                                                <td>{{ $file->file_remarks }}</td>
                                                <td>{{ $file->file_notes }}</td>
                                                <td>
                                            <!-- Delete Button inside the table -->
                                                <form id="delete-form-{{ $file->id }}">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $file->id }}">
                                                        <button type="button" class="btn btn-danger btn-sm delete-file-btn" data-id="{{ $file->id }}">
                                                            <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-success mt-3">Upload Files</button>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
    <!---- /Modal for Saving Video and File --->
            <section class="review-section professional-excellence">
                <div class="review-header text-center">
                    <h3 class="review-title">Videos Files</h3>
                    <p class="text-muted"></p>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered review-table mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:40px;">#</th>
                                        <th>Key Result Area</th>
                                        <th>Key Performance Indicators</th>
                                        <th>Weightage</th>
                                        <th>Percentage achieved <br>( self Score )</th>
                                        <th>Points Scored <br>( self )</th>
                                        <th>Percentage achieved <br>( RO's Score )</th>
                                        <th>Points Scored <br>( RO )</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td rowspan="2">1</td>
                                        <td rowspan="2">Production</td>
                                        <td>Quality</td>
                                        <td><input type="text" class="form-control" readonly value="30"></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>TAT (turn around time)</td>
                                        <td><input type="text" class="form-control" readonly value="30"></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Process Improvement</td>
                                        <td>PMS,New Ideas</td>
                                        <td><input type="text" class="form-control" readonly value="10"></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Team Management</td>
                                        <td>Team Productivity,dynaics,attendance,attrition</td>
                                        <td><input type="text" class="form-control" readonly value="5"></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Knowledge Sharing</td>
                                        <td>Sharing the knowledge for team productivity </td>
                                        <td><input type="text" class="form-control" readonly value="5"></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>Reporting and Communication</td>
                                        <td>Emails/Calls/Reports and Other Communication</td>
                                        <td><input type="text" class="form-control" readonly value="5"></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-center">Total </td>
                                        <td><input type="text" class="form-control" readonly value="85"></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
    @section ('script')

    {{--- Saving Files ---}}
            <script>
                document.querySelector('.btn-add-row').addEventListener('click', function () {
                    var tableBody = document.getElementById('table_alterations_tbody');
                    var rowCount = tableBody.rows.length + 1;

                    var newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td>${rowCount}</td>
                        <td><input type="file" class="form-control" name="files[]" accept=".mp4, .mp3, .asf" required></td>
                        <td><input type="text" class="form-control" name="remarks[]"></td>
                        <td><input type="text" class="form-control" name="notes[]"></td>
                        <td><button type="button" class="btn btn-danger btn-remove-row"><i class="fa fa-minus"></i></button></td>
                    `;

                    tableBody.appendChild(newRow);

                    newRow.querySelector('.btn-remove-row').addEventListener('click', function () {
                        newRow.remove();
                    });
                });
            </script>

            {{--- Deleting Modal or Files ----}}
            <script>
                $(document).on('click', '.delete-file-btn', function(e) {
                    e.preventDefault();
                    var fileId = $(this).data('id');

                    if (confirm('Are you sure you want to delete this file?')) {
                        $.ajax({
                            url: "{{ route('joborders.delete', ':id') }}".replace(':id', fileId),
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: fileId
                            },
                            success: function(response) {
                                if (response.success) {
                                    alert(response.message);
                                    location.reload(); 
                                } else {
                                    alert(response.message);
                                }
                            },
                            error: function(xhr, status, error) {
                                alert('Failed to delete the file. Please try again.');
                            }
                        });
                    }
                });
            </script>

    @endsection
@endsection
