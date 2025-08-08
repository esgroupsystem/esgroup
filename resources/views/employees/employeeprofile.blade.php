@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Profile</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- /Page Header -->
            <div class="card mb-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="profile-view">
                                <div class="profile-img-wrap text-center mb-3">
                                    <div class="profile-img">
                                        <a href="#">
                                            <img class="user-profile rounded-circle border" width="120" height="120"
                                                src="{{ URL::to('/assets/employeepic/' . ($employee->profile_picture ?? 'default.png')) }}"
                                                alt="{{ $employee->name }}">
                                        </a>
                                    </div>
                                </div>

                                <div class="profile-basic">
                                    <div class="row">
                                        <!-- LEFT COLUMN -->
                                        <div class="col-md-5">
                                            <div class="profile-info-left">
                                                @php
                                                    $status = strtolower($employee->status ?? 'inactive');
                                                    $statusColors = [
                                                        'active' => 'success',
                                                        'inactive' => 'secondary',
                                                        'on leave' => 'warning',
                                                        'resigned' => 'danger',
                                                    ];
                                                    $badgeClass = 'bg-' . ($statusColors[$status] ?? 'secondary');
                                                @endphp

                                                <h3 class="user-name fw-bold mb-1 d-flex align-items-center">
                                                    {{ $employee->name }}
                                                    <span
                                                        class="badge {{ $badgeClass }} px-3 py-1 rounded-pill text-capitalize"
                                                        style="font-size: 0.75rem; margin-left: 1rem;">
                                                        {{ $employee->status ?? 'Inactive' }}
                                                    </span>
                                                </h3>
                                                <div class="staff-id mb-2 text-muted">Employee ID:
                                                    {{ $employee->employee_id }}</div>

                                                <div class="mb-2">
                                                    <span class="badge bg-primary">{{ $employee->department }}</span>
                                                    <span class="text-muted">({{ $employee->designation }})</span>
                                                </div>

                                                <div class="mb-2 text-muted">
                                                    <i class="fas fa-building me-1"></i> {{ $employee->company }}
                                                </div>

                                                <div class="small text-muted mb-2">
                                                    <i class="far fa-calendar-alt me-1"></i> Date Hired:
                                                    {{ \Carbon\Carbon::parse($employee->date_hired)->format('F d, Y') }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- RIGHT COLUMN -->
                                        <div class="col-md-7">
                                            <ul class="personal-info list-unstyled">
                                                <li class="mb-2">
                                                    <strong>Phone:</strong>
                                                    <span class="ms-2">{{ $employee->phone ?? 'N/A' }}</span>
                                                </li>
                                                <li class="mb-2">
                                                    <strong>Email:</strong>
                                                    <span class="ms-2">{{ $employee->email ?? 'N/A' }}</span>
                                                </li>
                                                <li class="mb-2">
                                                    <strong>Birthday:</strong>
                                                    <span class="ms-2">
                                                        @if (!empty($employee->birth_date))
                                                            {{ \Carbon\Carbon::parse($employee->birth_date)->format('F d, Y') }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </span>
                                                </li>
                                                <li class="mb-2">
                                                    <strong>Address:</strong>
                                                    <span class="ms-2">
                                                        @php
                                                            $addressParts = array_filter([
                                                                $employee->address ?? null,
                                                                $employee->state ?? null,
                                                                $employee->country ?? null,
                                                                $employee->pin_code ?? null,
                                                            ]);
                                                        @endphp

                                                        {{ count($addressParts) ? implode(', ', $addressParts) : 'N/A' }}
                                                    </span>
                                                </li>
                                                <li class="mb-2">
                                                    <strong>Gender:</strong>
                                                    <span class="ms-2">{{ $employee->gender ?? 'N/A' }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="pro-edit position-absolute top-0 end-0 mt-3 me-3">
                                    <a data-target="#profile_info" data-toggle="modal"
                                        class="edit-icon text-decoration-none" href="#">
                                        <i class="fa fa-pencil fa-lg text-secondary"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card tab-box">
                <div class="row user-tabs">
                    <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                        <ul class="nav nav-tabs nav-tabs-bottom">
                            <li class="nav-item"><a href="#emp_profile" data-toggle="tab"
                                    class="nav-link active">Profile</a></li>
                            <li class="nav-item"><a href="#emp_projects" data-toggle="tab" class="nav-link">Required Files</a>
                            </li>
                            {{-- <li class="nav-item"><a href="#bank_statutory" data-toggle="tab" class="nav-link">Bank &
                                    Statutory <small class="text-danger">(Admin Only)</small></a></li>
                            <li class="nav-item"><a href="#bio_logs" data-toggle="tab" class="nav-link">Bio Logs</a></li>
                            <li class="nav-item"><a href="#schedule" data-toggle="tab" class="nav-link">Schedule</a></li> --}}
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Profile Info Tab -->
                <div id="emp_profile" class="pro-overview tab-pane fade show active">
                    <div class="row">
                        <div class="col-md-6 d-flex">
                            <div class="card profile-box flex-fill">
                                <div class="card-body">
                                    <h3 class="card-title">Personal Informations <a href="#" class="edit-icon"
                                            data-toggle="modal" data-target="#personal_info_modal"><i
                                                class="fa fa-pencil"></i></a></h3>
                                    <ul class="personal-info">
                                        <li>
                                            <div class="title">PhilHealth</div>
                                            @if (!empty($employee->philhealth))
                                                <div class="text">{{ $employee->philhealth }}</div>
                                            @else
                                                <div class="text">N/A</div>
                                            @endif
                                        </li>
                                        <li>
                                            <div class="title">SSS</div>
                                            @if (!empty($employee->sss))
                                                <div class="text">{{ $employee->sss }}</div>
                                            @else
                                                <div class="text">N/A</div>
                                            @endif
                                        </li>
                                        <li>
                                            <div class="title">TIN No.</div>
                                            @if (!empty($employee->tin_no))
                                                <div class="text">{{ $employee->tin_no }}</div>
                                            @else
                                                <div class="text">N/A</div>
                                            @endif
                                        </li>
                                        <li>
                                            <div class="title">Nationality</div>
                                            @if (!empty($employee->nationality))
                                                <div class="text">{{ $employee->nationality }}</div>
                                            @else
                                                <div class="text">N/A</div>
                                            @endif
                                        </li>
                                        <li>
                                            <div class="title">Religion</div>
                                            @if (!empty($employee->religion))
                                                <div class="text">{{ $employee->religion }}</div>
                                            @else
                                                <div class="text">N/A</div>
                                            @endif
                                        </li>
                                        <li>
                                            <div class="title">Marital status</div>
                                            @if (!empty($employee->marital_status))
                                                <div class="text">{{ $employee->marital_status }}</div>
                                            @else
                                                <div class="text">N/A</div>
                                            @endif
                                        </li>
                                        <li>
                                            <div class="title">Employment of spouse</div>
                                            @if (!empty($employee->employment_of_spouse))
                                                <div class="text">{{ $employee->employment_of_spouse }}</div>
                                            @else
                                                <div class="text">N/A</div>
                                            @endif
                                        </li>
                                        <li>
                                            <div class="title">No. of children</div>
                                            @if ($employee->children != null)
                                                <div class="text">{{ $employee->children }}</div>
                                            @else
                                                <div class="text">N/A</div>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex">
                            <div class="card profile-box flex-fill">
                                <div class="card-body">
                                    <h3 class="card-title">Emergency Contact <a href="#" class="edit-icon"
                                            data-toggle="modal" data-target="#emergency_contact_modal"><i
                                                class="fa fa-pencil"></i></a></h3>
                                    <h5 class="section-title">Primary</h5>
                                    <ul class="personal-info">
                                        <li>
                                            <div class="title">Name</div>
                                            @if (!empty($employee->name_primary))
                                                <div class="text">{{ $employee->name_primary }}</div>
                                            @else
                                                <div class="text">N/A</div>
                                            @endif
                                        </li>
                                        <li>
                                            <div class="title">Relationship</div>
                                            @if (!empty($employee->relationship_primary))
                                                <div class="text">{{ $employee->relationship_primary }}</div>
                                            @else
                                                <div class="text">N/A</div>
                                            @endif
                                        </li>
                                        <li>
                                            <div class="title">Phone </div>
                                            @if (!empty($employee->phone_primary) && !empty($employee->phone_2_primary))
                                                <div class="text">
                                                    {{ $employee->phone_primary }},{{ $employee->phone_2_primary }}</div>
                                            @else
                                                <div class="text">N/A</div>
                                            @endif
                                        </li>
                                    </ul>
                                    <hr>
                                    <h5 class="section-title">Secondary</h5>
                                    <ul class="personal-info">
                                        <li>
                                            <div class="title">Name</div>
                                            @if (!empty($employee->name_secondary))
                                                <div class="text">{{ $employee->name_secondary }}</div>
                                            @else
                                                <div class="text">N/A</div>
                                            @endif
                                        </li>
                                        <li>
                                            <div class="title">Relationship</div>
                                            @if (!empty($employee->relationship_secondary))
                                                <div class="text">{{ $employee->relationship_secondary }}</div>
                                            @else
                                                <div class="text">N/A</div>
                                            @endif
                                        </li>
                                        <li>
                                            <div class="title">Phone </div>
                                            @if (!empty($employee->phone_secondary) && !empty($employee->phone_2_secondary))
                                                <div class="text">
                                                    {{ $employee->phone_secondary }},{{ $employee->phone_2_secondary }}
                                                </div>
                                            @else
                                                <div class="text">N/A</div>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-md-6 d-flex">
                            <div class="card profile-box flex-fill">
                                <div class="card-body">
                                    <h3 class="card-title">Bank information
                                        <a href="#" class="edit-icon" data-toggle="modal"
                                            data-target="#bank_information_modal">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    </h3>
                                    <ul class="personal-info">
                                        <li>
                                            <div class="title">Bank name</div>
                                            <div class="text">ICICI Bank</div>
                                        </li>
                                        <li>
                                            <div class="title">Bank account No.</div>
                                            <div class="text">159843014641</div>
                                        </li>
                                        <li>
                                            <div class="title">IFSC Code</div>
                                            <div class="text">ICI24504</div>
                                        </li>
                                        <li>
                                            <div class="title">PAN No</div>
                                            <div class="text">TC000Y56</div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex">
                            <div class="card profile-box flex-fill">
                                <div class="card-body">
                                    <h3 class="card-title">Family Informations <a href="#" class="edit-icon"
                                            data-toggle="modal" data-target="#family_info_modal"><i
                                                class="fa fa-pencil"></i></a></h3>
                                    <div class="table-responsive">
                                        <table class="table table-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Relationship</th>
                                                    <th>Date of Birth</th>
                                                    <th>Phone</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Leo</td>
                                                    <td>Brother</td>
                                                    <td>Feb 16th, 2019</td>
                                                    <td>9876543210</td>
                                                    <td class="text-right">
                                                        <div class="dropdown dropdown-action">
                                                            <a aria-expanded="false" data-toggle="dropdown"
                                                                class="action-icon dropdown-toggle" href="#"><i
                                                                    class="material-icons">more_vert</i></a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a href="#" class="dropdown-item"><i
                                                                        class="fa fa-pencil m-r-5"></i> Edit</a>
                                                                <a href="#" class="dropdown-item"><i
                                                                        class="fa fa-trash-o m-r-5"></i> Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <div class="row">
                        <div class="col-md-6 d-flex">
                            <div class="card profile-box flex-fill">
                                <div class="card-body">
                                    <h3 class="card-title">Education Informations <a href="#" class="edit-icon"
                                            data-toggle="modal" data-target="#education_info"><i
                                                class="fa fa-pencil"></i></a></h3>
                                    <div class="experience-box">
                                        <ul class="experience-list">
                                            <li>
                                                <div class="experience-user">
                                                    <div class="before-circle"></div>
                                                </div>
                                                <div class="experience-content">
                                                    <div class="timeline-content">
                                                        <a href="#/" class="name">International College of Arts and
                                                            Science (UG)</a>
                                                        <div>Bsc Computer Science</div>
                                                        <span class="time">2000 - 2003</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="experience-user">
                                                    <div class="before-circle"></div>
                                                </div>
                                                <div class="experience-content">
                                                    <div class="timeline-content">
                                                        <a href="#/" class="name">International College of Arts and
                                                            Science (PG)</a>
                                                        <div>Msc Computer Science</div>
                                                        <span class="time">2000 - 2003</span>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex">
                            <div class="card profile-box flex-fill">
                                <div class="card-body">
                                    <h3 class="card-title">Experience <a href="#" class="edit-icon"
                                            data-toggle="modal" data-target="#experience_info"><i
                                                class="fa fa-pencil"></i></a></h3>
                                    <div class="experience-box">
                                        <ul class="experience-list">
                                            <li>
                                                <div class="experience-user">
                                                    <div class="before-circle"></div>
                                                </div>
                                                <div class="experience-content">
                                                    <div class="timeline-content">
                                                        <a href="#/" class="name">Web Designer at Zen
                                                            Corporation</a>
                                                        <span class="time">Jan 2013 - Present (5 years 2 months)</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="experience-user">
                                                    <div class="before-circle"></div>
                                                </div>
                                                <div class="experience-content">
                                                    <div class="timeline-content">
                                                        <a href="#/" class="name">Web Designer at Ron-tech</a>
                                                        <span class="time">Jan 2013 - Present (5 years 2 months)</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="experience-user">
                                                    <div class="before-circle"></div>
                                                </div>
                                                <div class="experience-content">
                                                    <div class="timeline-content">
                                                        <a href="#/" class="name">Web Designer at Dalt
                                                            Technology</a>
                                                        <span class="time">Jan 2013 - Present (5 years 2 months)</span>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Profile Info Tab -->

                <!-- Requirements Tab -->
                <div class="tab-pane fade" id="emp_projects">
                        @php
                            $requiredDocuments = [
                                ['title' => 'NBI Clearance', 'description' => 'Uploaded copy of NBI clearance for record validation.'],
                                ['title' => 'Police Clearance', 'description' => 'Recent police clearance for background check.'],
                                ['title' => 'Birth Certificate', 'description' => 'Official PSA-issued birth certificate.'],
                                ['title' => 'Resume / CV', 'description' => 'Latest resume submitted by the employee.'],
                                ['title' => 'Barangay Clearance', 'description' => 'Proof of good moral standing within the community.'],
                                ['title' => 'Diploma', 'description' => 'Graduation certificate from university or school.'],
                                ['title' => 'Transcript of Records', 'description' => 'Complete academic record submitted for HR.'],
                                ['title' => 'Pag-IBIG', 'description' => 'Membership record or form.'],
                                ['title' => 'PhilHealth', 'description' => 'PhilHealth Member Data Record (MDR).'],
                                ['title' => 'SSS', 'description' => 'Social Security System record or E-1 form.'],
                                ['title' => 'TIN', 'description' => 'Tax Identification Number record or BIR Form 1902.'],
                            ];
                        @endphp

                        <div class="row g-4">
                            @foreach ($requiredDocuments as $doc)
                                @php $uploaded = $uploadedRequirements[$doc['title']] ?? null; @endphp
                                <div class="col-lg-4 col-sm-6 col-md-4 col-xl-3">
                                    <div class="card h-100 d-flex flex-column justify-content-between">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title">{{ $doc['title'] }}</h5>
                                                @if ($uploaded)
                                                    <div class="dropdown profile-action">
                                                        <a class="action-icon dropdown-toggle" href="#" data-toggle="dropdown">
                                                            <i class="material-icons">more_vert</i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item upload-btn" href="#" data-title="{{ $doc['title'] }}">
                                                                <i class="fa fa-pencil m-r-5"></i> Replace
                                                            </a>
                                                            <!-- You can implement delete functionality later -->
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            @if ($uploaded)
                                                <p class="text-muted big mb-2">
                                                    Uploaded: <strong>{{ \Carbon\Carbon::parse($uploaded->uploaded_at)->format('d M Y') }}</strong>
                                                </p>
                                                <p class="text-muted big mb-2">
                                                    {{ $uploaded->description ?? $doc['description'] }}
                                                </p>
                                                <p class="text-muted big mb-2">
                                                    Expiry: <strong>{{ $uploaded->expires_at ? \Carbon\Carbon::parse($uploaded->expires_at)->format('d M Y') : 'N/A' }}</strong>
                                                </p>
                                            @else
                                                <p class="text-muted">{{ $doc['description'] }}</p>
                                            @endif
                                        </div>

                                        <div class="card-footer text-center">
                                            @if ($uploaded && isset($uploaded->file_path))
                                                <a href="{{ asset($uploaded->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
                                                <a href="{{ asset($uploaded->file_path) }}" download class="btn btn-sm btn-outline-success">Download</a>
                                            @else
                                                <span class="text-muted d-block mb-2">No file uploaded</span>
                                            @endif

                                            <button class="btn btn-sm btn-outline-{{ $uploaded && isset($uploaded->file_path) ? 'secondary' : 'primary' }} upload-btn"
                                                data-title="{{ $doc['title'] }}">
                                                {{ $uploaded && isset($uploaded->file_path) ? 'Replace File' : 'Upload' }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        <!-- /Requirements Tab -->
                    </div>
                </div>
                <!-- /Page Content -->

                <!-- Upload Requirement Modal -->
                <div class="modal fade" id="uploadRequirementModal" tabindex="-1" role="dialog" aria-labelledby="uploadRequirementLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form id="requirementUploadForm" method="POST" enctype="multipart/form-data" action="{{ route('requirements.upload') }}">
                            @csrf
                            <input type="hidden" name="title" id="requirementTitle">
                            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Upload Requirement</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Description (Optional)</label>
                                        <input type="text" name="description" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Expiry Date (Optional)</label>
                                        <input type="date" name="expires_at" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Upload File</label>
                                        <input type="file" name="document" class="form-control-file" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Upload</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /Upload Requirement Modal -->

                <!-- Profile Modal -->
                <div id="profile_info" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Profile Information</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('profile/information/save') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="profile-img-wrap edit-img">
                                                <img class="inline-block"
                                                    src="{{ URL::to('/assets/employeepic/' . $employee->profile_picture) }}"
                                                    alt="{{ $employee->name }}">
                                                <div class="fileupload btn">
                                                    <span class="btn-text">edit</span>
                                                    <input class="upload" type="file" id="image" name="images">
                                                    @if (!empty($employee))
                                                        <input type="hidden" name="hidden_image" id="e_image"
                                                            value="{{ $employee->profile_picture }}">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Full Name</label>
                                                        <input type="text" class="form-control" id="name"
                                                            name="name" value="{{ $employee->name }}">
                                                        <input type="hidden" class="form-control" id="user_id"
                                                            name="user_id" value="{{ $employee->employee_id }}">
                                                        <input type="hidden" class="form-control" id="email"
                                                            name="email" value="{{ $employee->email }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Birth Date</label>
                                                        <div class="cal-icon">
                                                            @if (!empty($employee))
                                                                <input class="form-control datetimepicker" type="text"
                                                                    id="birthDate" name="birthDate"
                                                                    value="{{ $employee->birth_date }}">
                                                            @else
                                                                <input class="form-control datetimepicker" type="text"
                                                                    id="birthDate" name="birthDate">
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Gender</label>
                                                        <select class="select form-control" id="gender"
                                                            name="gender">
                                                            @if (!empty($employee))
                                                                <option value="{{ $employee->gender }}"
                                                                    {{ $employee->gender == $employee->gender ? 'selected' : '' }}>
                                                                    {{ $employee->gender }} </option>
                                                                <option value="Male">Male</option>
                                                                <option value="Female">Female</option>
                                                            @else
                                                                <option value="Male">Male</option>
                                                                <option value="Female">Female</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Address</label>
                                                @if (!empty($employee))
                                                    <input type="text" class="form-control" id="address"
                                                        name="address" value="{{ $employee->address }}">
                                                @else
                                                    <input type="text" class="form-control" id="address"
                                                        name="address">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>State</label>
                                                @if (!empty($employee))
                                                    <input type="text" class="form-control" id="state"
                                                        name="state" value="{{ $employee->state }}">
                                                @else
                                                    <input type="text" class="form-control" id="state"
                                                        name="state">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Country</label>
                                                @if (!empty($employee))
                                                    <input type="text" class="form-control" id=""
                                                        name="country" value="{{ $employee->country }}">
                                                @else
                                                    <input type="text" class="form-control" id=""
                                                        name="country">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Pin Code</label>
                                                @if (!empty($employee))
                                                    <input type="text" class="form-control" id="pin_code"
                                                        name="pin_code" value="{{ $employee->pin_code }}">
                                                @else
                                                    <input type="text" class="form-control" id="pin_code"
                                                        name="pin_code">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Phone Number</label>
                                                @if (!empty($employee))
                                                    <input type="text" class="form-control" id="phoneNumber"
                                                        name="phone_number" value="{{ $employee->phone_number }}">
                                                @else
                                                    <input type="text" class="form-control" id="phoneNumber"
                                                        name="phone_number">
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Department <span class="text-danger">*</span></label>
                                                <select class="select" id="department" name="department">
                                                    @if (!empty($employee))
                                                        <option value="{{ $employee->department }}"
                                                            {{ $employee->department == $employee->department ? 'selected' : '' }}>
                                                            {{ $employee->department }} </option>
                                                        <option value="Web Development">Web Development</option>
                                                        <option value="IT Management">IT Management</option>
                                                        <option value="Marketing">Marketing</option>
                                                    @else
                                                        <option value="Web Development">Web Development</option>
                                                        <option value="IT Management">IT Management</option>
                                                        <option value="Marketing">Marketing</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Designation <span class="text-danger">*</span></label>
                                                <select class="select" id="designation" name="designation">
                                                    @if (!empty($employee))
                                                        <option value="{{ $employee->designation }}"
                                                            {{ $employee->designation == $employee->designation ? 'selected' : '' }}>
                                                            {{ $employee->designation }} </option>
                                                        <option value="Web Designer">Web Designer</option>
                                                        <option value="Web Developer">Web Developer</option>
                                                        <option value="Android Developer">Android Developer</option>
                                                    @else
                                                        <option value="Web Designer">Web Designer</option>
                                                        <option value="Web Developer">Web Developer</option>
                                                        <option value="Android Developer">Android Developer</option>
                                                    @endif
                                                </select>
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
                <!-- /Profile Modal -->

                <!-- Personal Info Modal -->
                <div id="personal_info_modal" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Personal Information</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('user/information/save') }}" method="POST">
                                    @csrf
                                    <input type="hidden" class="form-control" name="user_id"
                                        value="{{ $employee->employee_id }}" readonly>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>PhilHealth</label>
                                                <input type="text"
                                                    class="form-control @error('philhealth') is-invalid @enderror"
                                                    name="philhealth" value="{{ $employee->philhealth }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>SSS</label>
                                                <input class="form-control @error('sss') is-invalid @enderror"
                                                    type="text" name="sss" value="{{ $employee->sss }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>TIN No</label>
                                                <input class="form-control @error('tin_no') is-invalid @enderror"
                                                    type="text" name="tin_no" value="{{ $employee->tin_no }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nationality <span class="text-danger">*</span></label>
                                                <input class="form-control @error('nationality') is-invalid @enderror"
                                                    type="text" name="nationality"
                                                    value="{{ $employee->nationality }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Religion</label>
                                                <div class="form-group">
                                                    <input class="form-control @error('religion') is-invalid @enderror"
                                                        type="text" name="religion"
                                                        value="{{ $employee->religion }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Marital status <span class="text-danger">*</span></label>
                                                <select
                                                    class="select form-control @error('marital_status') is-invalid @enderror"
                                                    name="marital_status">
                                                    <option value="{{ $employee->marital_status }}"
                                                        {{ $employee->marital_status == $employee->marital_status ? 'selected' : '' }}>
                                                        {{ $employee->marital_status }} </option>
                                                    <option value="Single">Single</option>
                                                    <option value="Married">Married</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Employment of spouse</label>
                                                <input
                                                    class="form-control @error('employment_of_spouse') is-invalid @enderror"
                                                    type="text" name="employment_of_spouse"
                                                    value="{{ $employee->employment_of_spouse }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>No. of children </label>
                                                <input class="form-control @error('children') is-invalid @enderror"
                                                    type="text" name="children" value="{{ $employee->children }}">
                                            </div>
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
                <!-- /Personal Info Modal -->

                <!-- Bank information Modal -->
                <div id="bank_information_modal" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Bank Information</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    @csrf
                                    <input type="hidden" class="form-control" name="user_id"
                                        value="{{ Session::get('employee_id') }}" readonly>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Bank name</label>
                                                <input type="text"
                                                    class="form-control @error('bank_name') is-invalid @enderror"
                                                    name="bank_name" value="{{ old('bank_name') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Bank account No</label>
                                                <input type="text"
                                                    class="form-control @error('bank_account_no') is-invalid @enderror"
                                                    name="bank_account_no"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                    value="{{ old('bank_account_no') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>IFSC Code</label>
                                                <input type="text"
                                                    class="form-control @error('ifsc_code') is-invalid @enderror"
                                                    name="ifsc_code" value="{{ old('ifsc_code') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>PAN No</label>
                                                <input type="text"
                                                    class="form-control @error('pan_no') is-invalid @enderror"
                                                    name="pan_no" value="{{ old('pan_no') }}">
                                            </div>
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
                <!-- /Bank information Modal -->

                <!-- Family Info Modal -->
                <div id="family_info_modal" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"> Family Informations</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-scroll">
                                        <div class="card">
                                            <div class="card-body">
                                                <h3 class="card-title">Family Member <a href="javascript:void(0);"
                                                        class="delete-icon"><i class="fa fa-trash-o"></i></a></h3>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Name <span class="text-danger">*</span></label>
                                                            <input class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Relationship <span class="text-danger">*</span></label>
                                                            <input class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Date of birth <span class="text-danger">*</span></label>
                                                            <input class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Phone <span class="text-danger">*</span></label>
                                                            <input class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-body">
                                                <h3 class="card-title">Education Informations <a
                                                        href="javascript:void(0);" class="delete-icon"><i
                                                            class="fa fa-trash-o"></i></a></h3>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Name <span class="text-danger">*</span></label>
                                                            <input class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Relationship <span class="text-danger">*</span></label>
                                                            <input class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Date of birth <span class="text-danger">*</span></label>
                                                            <input class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Phone <span class="text-danger">*</span></label>
                                                            <input class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="add-more">
                                                    <a href="javascript:void(0);"><i class="fa fa-plus-circle"></i> Add
                                                        More</a>
                                                </div>
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
                <!-- /Family Info Modal -->

                <!-- Emergency Contact Modal -->
                <div id="emergency_contact_modal" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Personal Information</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="validation" action="{{ route('user/profile/emergency/contact/save') }}"
                                    method="POST">
                                    @csrf
                                    <input type="text" class="form-control" name="user_id"
                                        value="{{ $employee->employee_id }}">
                                    <div class="card">
                                        <div class="card-body">
                                            <h3 class="card-title">Primary Contact</h3>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Name <span class="text-danger">*</span></label>
                                                        @if (!empty($employee->name_primary))
                                                            <input type="text" class="form-control"
                                                                name="name_primary"
                                                                value="{{ $employee->name_primary }}">
                                                        @else
                                                            <input type="text" class="form-control"
                                                                name="name_primary">
                                                        @endif
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Relationship <span class="text-danger">*</span></label>
                                                        @if (!empty($employee->relationship_primary))
                                                            <input type="text" class="form-control"
                                                                name="relationship_primary"
                                                                value="{{ $employee->relationship_primary }}">
                                                        @else
                                                            <input type="text" class="form-control"
                                                                name="relationship_primary">
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Phone <span class="text-danger">*</span></label>
                                                        @if (!empty($employee->phone_primary))
                                                            <input type="text" class="form-control"
                                                                name="phone_primary"
                                                                value="{{ $employee->phone_primary }}">
                                                        @else
                                                            <input type="text" class="form-control"
                                                                name="phone_primary">
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Phone 2</label>
                                                        @if (!empty($employee->phone_2_primary))
                                                            <input type="text" class="form-control"
                                                                name="phone_2_primary"
                                                                value="{{ $employee->phone_2_primary }}">
                                                        @else
                                                            <input type="text" class="form-control"
                                                                name="phone_2_primary">
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <h3 class="card-title">Secondary Contact</h3>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Name <span class="text-danger">*</span></label>
                                                        @if (!empty($employee->name_secondary))
                                                            <input type="text" class="form-control"
                                                                name="name_secondary"
                                                                value="{{ $employee->name_secondary }}">
                                                        @else
                                                            <input type="text" class="form-control"
                                                                name="name_secondary">
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Relationship <span class="text-danger">*</span></label>
                                                        @if (!empty($employee->relationship_secondary))
                                                            <input type="text" class="form-control"
                                                                name="relationship_secondary"
                                                                value="{{ $employee->relationship_secondary }}">
                                                        @else
                                                            <input type="text" class="form-control"
                                                                name="relationship_secondary">
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Phone <span class="text-danger">*</span></label>
                                                        @if (!empty($employee->phone_secondary))
                                                            <input type="text" class="form-control"
                                                                name="phone_secondary"
                                                                value="{{ $employee->phone_secondary }}">
                                                        @else
                                                            <input type="text" class="form-control"
                                                                name="phone_secondary">
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Phone 2</label>
                                                        @if (!empty($employee->phone_2_secondary))
                                                            <input type="text" class="form-control"
                                                                name="phone_2_secondary"
                                                                value="{{ $employee->phone_2_secondary }}">
                                                        @else
                                                            <input type="text" class="form-control"
                                                                name="phone_2_secondary">
                                                        @endif
                                                    </div>
                                                </div>
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
                <!-- /Emergency Contact Modal -->

                <!-- Education Modal -->
                <div id="education_info" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"> Education Informations</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-scroll">
                                        <div class="card">
                                            <div class="card-body">
                                                <h3 class="card-title">Education Informations <a
                                                        href="javascript:void(0);" class="delete-icon"><i
                                                            class="fa fa-trash-o"></i></a></h3>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group form-focus focused">
                                                            <input type="text" value="Oxford University"
                                                                class="form-control floating">
                                                            <label class="focus-label">Institution</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-focus focused">
                                                            <input type="text" value="Computer Science"
                                                                class="form-control floating">
                                                            <label class="focus-label">Subject</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-focus focused">
                                                            <div class="cal-icon">
                                                                <input type="text" value="01/06/2002"
                                                                    class="form-control floating datetimepicker">
                                                            </div>
                                                            <label class="focus-label">Starting Date</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-focus focused">
                                                            <div class="cal-icon">
                                                                <input type="text" value="31/05/2006"
                                                                    class="form-control floating datetimepicker">
                                                            </div>
                                                            <label class="focus-label">Complete Date</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-focus focused">
                                                            <input type="text" value="BE Computer Science"
                                                                class="form-control floating">
                                                            <label class="focus-label">Degree</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-focus focused">
                                                            <input type="text" value="Grade A"
                                                                class="form-control floating">
                                                            <label class="focus-label">Grade</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-body">
                                                <h3 class="card-title">Education Informations <a
                                                        href="javascript:void(0);" class="delete-icon"><i
                                                            class="fa fa-trash-o"></i></a></h3>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group form-focus focused">
                                                            <input type="text" value="Oxford University"
                                                                class="form-control floating">
                                                            <label class="focus-label">Institution</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-focus focused">
                                                            <input type="text" value="Computer Science"
                                                                class="form-control floating">
                                                            <label class="focus-label">Subject</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-focus focused">
                                                            <div class="cal-icon">
                                                                <input type="text" value="01/06/2002"
                                                                    class="form-control floating datetimepicker">
                                                            </div>
                                                            <label class="focus-label">Starting Date</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-focus focused">
                                                            <div class="cal-icon">
                                                                <input type="text" value="31/05/2006"
                                                                    class="form-control floating datetimepicker">
                                                            </div>
                                                            <label class="focus-label">Complete Date</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-focus focused">
                                                            <input type="text" value="BE Computer Science"
                                                                class="form-control floating">
                                                            <label class="focus-label">Degree</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-focus focused">
                                                            <input type="text" value="Grade A"
                                                                class="form-control floating">
                                                            <label class="focus-label">Grade</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="add-more">
                                                    <a href="javascript:void(0);"><i class="fa fa-plus-circle"></i> Add
                                                        More</a>
                                                </div>
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
                <!-- /Education Modal -->

                <!-- Experience Modal -->
                <div id="experience_info" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Experience Informations</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-scroll">
                                        <div class="card">
                                            <div class="card-body">
                                                <h3 class="card-title">Experience Informations <a
                                                        href="javascript:void(0);" class="delete-icon"><i
                                                            class="fa fa-trash-o"></i></a></h3>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group form-focus">
                                                            <input type="text" class="form-control floating"
                                                                value="Digital Devlopment Inc">
                                                            <label class="focus-label">Company Name</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-focus">
                                                            <input type="text" class="form-control floating"
                                                                value="United States">
                                                            <label class="focus-label">Location</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-focus">
                                                            <input type="text" class="form-control floating"
                                                                value="Web Developer">
                                                            <label class="focus-label">Job Position</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-focus">
                                                            <div class="cal-icon">
                                                                <input type="text"
                                                                    class="form-control floating datetimepicker"
                                                                    value="01/07/2007">
                                                            </div>
                                                            <label class="focus-label">Period From</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-focus">
                                                            <div class="cal-icon">
                                                                <input type="text"
                                                                    class="form-control floating datetimepicker"
                                                                    value="08/06/2018">
                                                            </div>
                                                            <label class="focus-label">Period To</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-body">
                                                <h3 class="card-title">Experience Informations <a
                                                        href="javascript:void(0);" class="delete-icon"><i
                                                            class="fa fa-trash-o"></i></a></h3>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group form-focus">
                                                            <input type="text" class="form-control floating"
                                                                value="Digital Devlopment Inc">
                                                            <label class="focus-label">Company Name</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-focus">
                                                            <input type="text" class="form-control floating"
                                                                value="United States">
                                                            <label class="focus-label">Location</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-focus">
                                                            <input type="text" class="form-control floating"
                                                                value="Web Developer">
                                                            <label class="focus-label">Job Position</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-focus">
                                                            <div class="cal-icon">
                                                                <input type="text"
                                                                    class="form-control floating datetimepicker"
                                                                    value="01/07/2007">
                                                            </div>
                                                            <label class="focus-label">Period From</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group form-focus">
                                                            <div class="cal-icon">
                                                                <input type="text"
                                                                    class="form-control floating datetimepicker"
                                                                    value="08/06/2018">
                                                            </div>
                                                            <label class="focus-label">Period To</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="add-more">
                                                    <a href="javascript:void(0);"><i class="fa fa-plus-circle"></i> Add
                                                        More</a>
                                                </div>
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
                <!-- /Experience Modal -->
                <!-- /Page Content -->
            </div>
        @section('script')
            <script>
                $('#validation').validate({
                    rules: {
                        name_primary: 'required',
                        relationship_primary: 'required',
                        phone_primary: 'required',
                        phone_2_primary: 'required',
                        name_secondary: 'required',
                        relationship_secondary: 'required',
                        phone_secondary: 'required',
                        phone_2_secondary: 'required',
                    },
                    messages: {
                        name_primary: 'Please input name primary',
                        relationship_primary: 'Please input relationship primary',
                        phone_primary: 'Please input phone primary',
                        phone_2_primary: 'Please input phone 2 primary',
                        name_secondary: 'Please input name secondary',
                        relationship_secondary: 'Please input relationship secondary',
                        phone_secondaryr: 'Please input phone secondary',
                        phone_2_secondary: 'Please input phone 2 secondary',
                    },
                    submitHandler: function(form) {
                        form.submit();
                    }
                });
            </script>

            <script>
                document.getElementById('scheduleForm').addEventListener('submit', function(e) {
                    e.preventDefault();

                    const startDate = new Date(this.start_date.value);
                    const endDate = new Date(this.end_date.value);
                    const timeIn = this.time_in.value;
                    const timeOut = this.time_out.value;
                    const isRestDay = document.getElementById('restDayCheck').checked;

                    if (startDate > endDate) {
                        alert('Start date must be before or equal to end date');
                        return;
                    }

                    let current = new Date(startDate);
                    let scheduleRows = '';

                    while (current <= endDate) {
                        const dateStr = current.toISOString().split('T')[0];
                        const dayName = current.toLocaleDateString('en-US', {
                            weekday: 'long'
                        });
                        const displayDate = current.toLocaleDateString('en-US', {
                            month: 'long',
                            day: 'numeric',
                            year: 'numeric'
                        });

                        scheduleRows += `<tr data-date="${dateStr}" data-rest="${isRestDay}" data-in="${timeIn}" data-out="${timeOut}">
            <td>${displayDate}</td>
            <td>${dayName}</td>`;

                        if (isRestDay) {
                            scheduleRows += `<td colspan="2" class="text-muted">Rest Day</td>`;
                        } else {
                            scheduleRows += `<td>${formatTime(timeIn)}</td><td>${formatTime(timeOut)}</td>`;
                        }

                        scheduleRows += `</tr>`;
                        current.setDate(current.getDate() + 1);
                    }

                    document.querySelector('#scheduleTable tbody').insertAdjacentHTML('beforeend', scheduleRows);
                    $('#plotScheduleModal').modal('hide');
                    this.reset();
                });

                function formatTime(timeStr) {
                    if (!timeStr) return '';
                    const [h, m] = timeStr.split(':');
                    const hour = parseInt(h);
                    const ampm = hour >= 12 ? 'PM' : 'AM';
                    const displayHour = hour % 12 || 12;
                    return `${displayHour}:${m} ${ampm}`;
                }

                document.getElementById('restDayCheck').addEventListener('change', function() {
                    const disabled = this.checked;
                    document.getElementById('timeIn').disabled = disabled;
                    document.getElementById('timeOut').disabled = disabled;
                });
            </script>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const uploadButtons = document.querySelectorAll('.upload-btn');
                    uploadButtons.forEach(button => {
                        button.addEventListener('click', function () {
                            const title = this.getAttribute('data-title');
                            document.getElementById('requirementTitle').value = title;
                            $('#uploadRequirementModal').modal('show');
                        });
                    });
                });
            </script>

            <style>
                #emp_projects .card {
                    background-color: #fdfdfd;
                    border: 1px solid #e0e0e0;
                    border-radius: 10px;
                    padding: 24px;
                    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
                    transition: box-shadow 0.3s ease-in-out;
                    min-height: 320px;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                    position: relative;
                }

                #emp_projects .card:hover {
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
                }

                #emp_projects .card-body {
                    display: flex;
                    flex-direction: column;
                    flex-grow: 1;
                    padding: 0;
                }

                #emp_projects .project-title {
                    font-size: 16px;
                    font-weight: 600;
                    margin-bottom: 10px;
                }

                #emp_projects .text-muted {
                    color: #6c757d !important;
                }

                #emp_projects small {
                    font-size: 13px;
                    margin-bottom: 10px;
                    display: block;
                }

                #emp_projects .pro-deadline .sub-title {
                    font-size: 13px;
                    font-weight: 500;
                }

                #emp_projects .btn {
                    padding: 4px 10px;
                    font-size: 13px;
                }

                #emp_projects .card-footer {
                    margin-top: auto;
                    text-align: center;
                }

                #emp_projects .card-footer .btn+.btn {
                    margin-left: 8px;
                }

                #emp_projects .dropdown.profile-action {
                    position: absolute;
                    top: 10px;
                    right: 10px;
                }
                #emp_projects .row > [class*='col-'] {
                    margin-bottom: 20px;
                    padding-left: 10px;
                    padding-right: 10px;
                }
                #emp_projects .row {
                    margin-left: -10px;
                    margin-right: -10px;
                }
            </style>
        @endsection
    @endsection
