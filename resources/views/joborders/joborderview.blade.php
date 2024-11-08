
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

        <!-- Delete Confirmation Modal / UI-UX -->
            <div class="modal custom-modal fade" id="delete_order" tabindex="-1" role="dialog" aria-labelledby="delete_order_label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-header">
                                <h3>Delete File</h3>
                                <p>Are you sure you want to delete this file?</p>
                            </div>
                            <input type="hidden" name="id" class="file-id" value="">
                            <div class="row">
                                <div class="col-6">
                                    <button type="button" class="btn btn-primary continue-btn submit-btn" id="confirm-delete-btn">Delete</button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-primary cancel-btn" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                                            <th>Files<span class="text-danger">*</span></th>
                                            <th>Remarks<span class="text-danger">*</span></th>
                                            <th>Notes<span class="text-danger">*</span></th>
                                            <th style="width: 64px;">
                                                <button type="button" class="btn btn-primary btn-add-row">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="table_alterations_tbody">
                                        @if($FileDetails->isEmpty())
                                            <tr class="no-data">
                                                <td colspan="5" class="text-center text-muted">No video uploaded.</td>
                                            </tr>
                                        @else
                                            @foreach($FileDetails as $key => $file)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>
                                                        @if(in_array(pathinfo($file->file_name, PATHINFO_EXTENSION), ['mp4', 'mp3', 'asf']))
                                                            <!-- Show file name as clickable link -->
                                                            <a href="#" class="view-video" data-file-path="{{ asset('storage/' . $file->file_path) }}">
                                                                {{ $file->file_name }} <p class="text-muted">(Click the link above to watch video)</p>
                                                            </a>
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
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-success mt-3">Upload Files</button>
                            </div>
                        </div>
                    </div>
                </form>
            </section>

            <!-- To Watch Video Modal -->
            <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="videoModalLabel">Video Preview</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <video id="videoPreview" width="100%" height="auto" controls>
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    </div>
                </div>
            </div>

    <!---- /Modal for Saving Video and File --->
            <section class="review-section professional-excellence">
                <div class="review-header text-center">
                    <h3 class="review-title">Related Job Order</h3>
                    <p class="text-muted"></p>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered review-table mb-0" id="jobList">
                                <thead>
                                    <tr>
                                        <th style="width:40px;">#</th>
                                        <th>Bus name</th>
                                        <th>Type of Issue</th>
                                        <th>Status</th>
                                        <th>Reported by</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($relatedTasks->isEmpty())
                                        <tr class="no-data">
                                            <td colspan="5" class="text-center text-muted">No pending task for this bus.</td>
                                        </tr>
                                    @else
                                    @foreach($relatedTasks as $index => $task)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $task->job_name }}</td>
                                            <td>{{ $task->job_type }}</td>
                                            <td><span class="badge badge-large
                                                @if($task->job_status == 'New') 
                                                    bg-inverse-primary 
                                                @elseif($task->job_status == 'Complete' || $item->job_status == 'Extracted') 
                                                    bg-inverse-success 
                                                @elseif($task->job_status == 'Processing') 
                                                    bg-inverse-warning 
                                                @else
                                                    bg-inverse-danger 
                                                @endif">
                                                {{ $task->job_status }}
                                            </span></td>
                                            <td>{{ $task->job_creator }}</td>
                                        </tr>
                                    @endforeach
                                    @endif
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
                    var noDataRow = tableBody.querySelector('.no-data');
                    
                    if (noDataRow) {
                        noDataRow.style.display = 'none';
                    }

                    var rowCount = tableBody.querySelectorAll('tr.data-row').length + 1;
                    var newRow = document.createElement('tr');
                    newRow.classList.add('data-row');
                    newRow.innerHTML = `
                        <td>${rowCount}</td>
                        <td><input type="file" class="form-control" name="files[]" accept=".mp4, .mp3, .asf" required></td>
                        <td><input type="text" class="form-control" name="remarks[]" required></td>
                        <td><input type="text" class="form-control" name="notes[]" required></td>
                        <td><button type="button" class="btn btn-danger btn-remove-row"><i class="fa fa-minus"></i></button></td>
                    `;
                    tableBody.appendChild(newRow);
                    newRow.querySelector('.btn-remove-row').addEventListener('click', function () {
                    newRow.remove();
                        if (tableBody.querySelectorAll('tr.data-row').length === 0) {
                            if (noDataRow) {
                                noDataRow.style.display = 'table-row';
                            }
                        }
                    });
                });
                    document.getElementById('uploadForm').addEventListener('submit', function() {
                    document.getElementById('loader-wrapper-files').style.display = 'block';
                });
            </script>

            {{--- Deleting Modal or Files ----}}
            <script>

                let fileIdToDelete = null;

                document.querySelectorAll('.delete-file-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        fileIdToDelete = this.getAttribute('data-id');
                        document.querySelector('.file-id').value = fileIdToDelete;
                        $('#delete_order').modal('show');
                    });
                });
                document.getElementById('confirm-delete-btn').addEventListener('click', function () {
                    if (fileIdToDelete) {
                        $.ajax({
                            url: "{{ route('joborders.delete', ':id') }}".replace(':id', fileIdToDelete),
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: fileIdToDelete
                            },
                            success: function(response) {
                                if (response.success) {
                                    location.reload();
                                } else {
                                    alert('Failed to delete the file.');
                                }
                            },
                            error: function(xhr, status, error) {
                                alert('Failed to delete the file. Please try again.');
                            }
                        });
                    }
                    $('#delete_order').modal('hide');
                });

                $('#delete_order').on('hidden.bs.modal', function () {
                    fileIdToDelete = null;
                });
            </script>

            <script>
                $(document).ready(function() {
                    const table = $('#jobList').DataTable({
                        pageLength: 10,
                        processing: true,
                        serverSide: false,
                        ordering: true,
                        dom: 't<"bottom"p>',
                    });

                    // Optional: Trigger search on a button click
                    $('.btn_search').on('click', function() {
                        table.draw();
                    });
                });
            </script>
    @endsection
@endsection
