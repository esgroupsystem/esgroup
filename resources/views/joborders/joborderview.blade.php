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


            <section id="printable-area" class="review-section information">
                <!-- Print Button -->
                <div class="text-end mb-3 no-print">
                    <button onclick="printSection()" class="btn btn-primary">
                        <i class="fa fa-print"></i> Print
                    </button>
                </div>
                <!-- Printable Area -->
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
                                            <div class="form-group">
                                                <label for="name">Bus Name</label>
                                                <input type="text" class="form-control" id="name"
                                                    value="{{ $jobDetail->job_name }}" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="depart3">Concern</label>
                                                <input type="text" class="form-control" id="depart3"
                                                    value="{{ $jobDetail->job_type }}" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="departa">Date of Accident</label>
                                                <input type="text" class="form-control" id="departa"
                                                    value="{{ date('j M Y', strtotime($jobDetail->job_datestart)) }}"
                                                    readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="qualif">Technician / Extractor </label>
                                                <input type="text" class="form-control" id="qualif"
                                                    value="{{ $jobDetail->job_assign_person }}" readonly>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <label for="doj">Start Time</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $jobDetail->job_time_start }}" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="doj">Date of Join</label>
                                                <input type="text" class="form-control" id="doj"
                                                    value="{{ $jobDetail->job_time_end }}" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="doc">Sit Number</label>
                                                <input type="text" class="form-control" id="doc"
                                                    value="{{ $jobDetail->job_sitNumber }}" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="qualif1">Remarks</label>
                                                <textarea type="text" class="form-control" rows="4" id="qualif1" readonly>{{ $jobDetail->job_remarks }}</textarea>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input hidden type="text" class="form-control" id="name1"
                                                    value="{{ $jobDetail->job_status }}" readonly>
                                            </div>
                                            <center>
                                                <div class="logo-container">
                                                    @if ($jobDetail->job_status === 'Complete' || $jobDetail->job_status == 'Extracted')
                                                        <img src="{{ asset('assets/img/completelogo.png') }}"
                                                            alt="Complete Logo" class="img-fluid custom-com-logo" disabled>
                                                    @elseif($jobDetail->job_status === 'New')
                                                        <img src="{{ asset('assets/img/newlogo.png') }}"
                                                            alt="Complete Logo" class="img-fluid custom-new-logo" disabled>
                                                    @endif
                                                </div>
                                            </center>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Delete Confirmation Modal / UI-UX -->
            <div class="modal custom-modal fade" id="delete_order" tabindex="-1" role="dialog"
                aria-labelledby="delete_order_label" aria-hidden="true">
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
                                    <button type="button" class="btn btn-primary continue-btn submit-btn"
                                        id="confirm-delete-btn">Delete</button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-primary cancel-btn"
                                        data-dismiss="modal">Cancel</button>
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
                    <p class="text-muted">Allowed file types: MP4, MP3, ASF, JPG, PNG.</p>
                </div>

                <form id="uploadForm" method="POST" action="{{ route('job.files') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="job_order_id" value="{{ $jobDetail->id }}">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>File</th>
                                <th>Remarks</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody id="table_alterations_tbody">
                            <tr>
                                <td><input type="file" class="form-control" name="files[]" accept=".mp4,.mp3,.asf,.jpg,.jpeg,.png" required></td>
                                <td><input type="text" class="form-control" name="remarks[]" placeholder="Remarks"></td>
                                <td><input type="text" class="form-control" name="notes[]" placeholder="Notes"></td>
                            </tr>
                        </tbody>
                    </table>

                    <button type="submit" class="btn btn-primary" id="submitBtn">Upload</button>

                    <div id="uploadProgressContainer" class="mt-3" style="display: none;">
                        <div class="progress">
                            <div class="progress-bar" id="uploadProgressBar" role="progressbar" style="width: 0%;">0%</div>
                        </div>
                    </div>
                </form>
            </section>


            {{-- Show Only Video/Audio Files --}}
            @if ($FileDetails->whereIn('extension', ['mp4', 'asf', 'mp3'])->count() > 0)
                <hr>
                <div class="review-header text-center mt-4">
                    <h4 class="review-title">Uploaded Video Files</h4>
                    <p class="text-muted">Click filename to preview the file.</p>
                </div>

                <ul class="list-group mb-4">
                    @foreach ($FileDetails->whereIn('extension', ['mp4', 'asf', 'mp3']) as $index => $file)
                        <li class="list-group-item">
                            <a href="javascript:void(0);" onclick="toggleMedia('media_{{ $index }}')">
                                📁 {{ $file->file_name }}
                            </a>
                            <div id="media_{{ $index }}" style="display: none; margin-top: 10px;">
                                @if (in_array($file->extension, ['mp4', 'asf']))
                                    <video width="100%" controls>
                                        <source src="{{ asset($file->file_path) }}" type="video/{{ $file->extension }}">
                                        Your browser does not support the video tag.
                                    </video>
                                @elseif($file->extension === 'mp3')
                                    <audio controls style="width: 100%;">
                                        <source src="{{ asset($file->file_path) }}" type="audio/mp3">
                                        Your browser does not support the audio element.
                                    </audio>
                                @endif
                                <p class="mt-2 mb-0"><strong>Remarks:</strong> {{ $file->file_remarks }}</p>
                                <p><strong>Notes:</strong> {{ $file->file_notes }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted text-center mt-4">No uploaded videos for this job order.</p>
            @endif
            <hr>

            {{-- Show Only Image Files --}}
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
                                        <th>Bus name</th>
                                        <th>Type of Issue</th>
                                        <th>Status</th>
                                        <th>Reported by</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($relatedTasks->isEmpty())
                                        <tr class="no-data">
                                            <td colspan="5" class="text-center text-muted">No pending task for this
                                                bus.</td>
                                        </tr>
                                    @else
                                        @foreach ($relatedTasks as $task)
                                            <tr>
                                                <td>{{ $task->job_name ?? '-' }}</td>
                                                <td>{{ $task->job_type ?? '-' }}</td>
                                                <td>
                                                    <span
                                                        class="badge badge-large
                                                    @if ($task->job_status == 'New') bg-inverse-primary 
                                                    @elseif($task->job_status == 'Complete') 
                                                        bg-inverse-success 
                                                    @else
                                                        bg-inverse-danger @endif">
                                                        {{ $task->job_status ?? 'Unknown' }}
                                                    </span>
                                                </td>
                                                <td>{{ $task->job_creator ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

        @section('script')
            {{-- - Saving Files - --}}
            <script>
document.addEventListener('DOMContentLoaded', function () {
    const submitBtn = document.getElementById('submitBtn');
    const progressBar = document.getElementById('uploadProgressBar');
    const progressContainer = document.getElementById('uploadProgressContainer');

    submitBtn.addEventListener('click', function (e) {
        e.preventDefault();
        const form = document.getElementById('uploadForm');
        const formData = new FormData(form);

        const hasFile = Array.from(form.querySelectorAll('input[type="file"]'))
            .some(input => input.files.length > 0);

        if (!hasFile) {
            alert('Please select at least one file.');
            return;
        }

        progressBar.style.width = '0%';
        progressBar.innerText = '0%';
        progressBar.setAttribute('aria-valuenow', 0);
        progressBar.className = 'progress-bar bg-info';
        progressContainer.style.display = 'block';

        const xhr = new XMLHttpRequest();
        xhr.open("POST", form.action, true);
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.setRequestHeader("X-CSRF-TOKEN", '{{ csrf_token() }}');

        xhr.upload.onprogress = function (event) {
            if (event.lengthComputable) {
                const percent = Math.round((event.loaded / event.total) * 100);
                progressBar.style.width = percent + '%';
                progressBar.innerText = percent + '%';
                progressBar.setAttribute('aria-valuenow', percent);
            }
        };

        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        progressBar.className = 'progress-bar bg-success';
                        progressBar.innerText = 'Upload Complete';
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        throw new Error(response.message);
                    }
                } catch (err) {
                    progressBar.className = 'progress-bar bg-danger';
                    progressBar.innerText = 'Upload Failed';
                    console.error('Response error:', err.message);
                }
            } else {
                progressBar.className = 'progress-bar bg-danger';
                progressBar.innerText = 'Upload Failed';
                console.error('XHR failed:', xhr.responseText);
            }
        };

        xhr.onerror = function () {
            progressBar.className = 'progress-bar bg-danger';
            progressBar.innerText = 'Network Error';
            console.error('Network error');
        };

        xhr.send(formData);
    });
});
</script>



            {{-- - Deleting Modal or Files -- --}}
            <script>
                let fileIdToDelete = null;

                document.querySelectorAll('.delete-file-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        fileIdToDelete = this.getAttribute('data-id');
                        document.querySelector('.file-id').value = fileIdToDelete;
                        $('#delete_order').modal('show');
                    });
                });
                document.getElementById('confirm-delete-btn').addEventListener('click', function() {
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

                $('#delete_order').on('hidden.bs.modal', function() {
                    fileIdToDelete = null;
                });
            </script>

            <script>
                document.querySelectorAll('.view-video').forEach(function(link) {
                    link.addEventListener('click', function(event) {
                        event.preventDefault(); // Prevent the default link behavior
                        const filePath = this.getAttribute(
                            'data-file-path'); // Get the file path from the data attribute
                        const modal = new bootstrap.Modal(document.getElementById('videoModal')); // Bootstrap modal
                        const videoElement = document.getElementById('videoPreview');

                        console.log('File Path: ', filePath);

                        videoElement.src = filePath; // Set the source of the video element
                        modal.show(); // Show the modal
                    });
                });
            </script>

            <!-- Print Script -->
            <script>
                function printSection() {
                    var printContents = document.getElementById('printable-area').innerHTML;
                    var originalContents = document.body.innerHTML;

                    document.body.innerHTML = printContents;
                    window.print();
                    document.body.innerHTML = originalContents;

                    location.reload(); // Optional: restores page functionality
                }
            </script>

            <script>
                function toggleMedia(id) {
                    const el = document.getElementById(id);
                    el.style.display = (el.style.display === "none") ? "block" : "none";
                }
            </script>

            <!-- Print Styles -->
            <style>
                @media print {
                    body * {
                        visibility: hidden !important;
                    }

                    #printable-area,
                    #printable-area * {
                        visibility: visible !important;
                    }

                    #printable-area {
                        position: absolute;
                        left: 0;
                        top: 0;
                        width: 100%;
                        padding: 20px;
                        background: white;
                    }

                    .no-print,
                    .btn {
                        display: none !important;
                    }

                    input,
                    textarea {
                        border: none;
                        outline: none;
                        background: transparent;
                        box-shadow: none;
                    }

                    label {
                        font-weight: bold;
                    }

                    .table td,
                    .table th {
                        border: 1px solid #000 !important;
                        padding: 8px;
                        vertical-align: top;
                    }

                    .custom-com-logo,
                    .custom-new-logo {
                        max-width: 120px;
                    }

                    #uploadProgressBar {
                        background-color: red !important;
                    }

                    #uploadProgressContainer {
                        display: block !important;
                    }
                }
            </style>
        @endsection
    @endsection
