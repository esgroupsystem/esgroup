<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>ES Group Company - Apply Now</title>

    <!-- Favicons -->
    <link href="{{ URL::to('assets/img/favicon.png') }}" rel="icon">
    <link href="{{ URL::to('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Inter:wght@400;600;700&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ URL::to('assets/css/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('assets/css/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ URL::to('assets/css/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ URL::to('assets/css/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('assets/css/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ URL::to('assets/css/main.css') }}" rel="stylesheet">

    <style>
        /* Hiring banner */
        .hiring-banner {
            /* background: url('{{ URL::to('assets/img/we_are_hiring.png') }}') center/cover no-repeat; */
            padding: 3rem;
            color: #fff;
            text-align: center;
            border-radius: 12px;
            position: relative;
            overflow: hidden;
        }

        .hiring-banner::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            border-radius: 12px;
        }

        .hiring-banner h2 {
            position: relative;
            z-index: 2;
            font-size: 2.2rem;
            font-weight: 700;
        }

        /* Job cards */
        .job-card {
            background: #fff;
            border-radius: 12px;
            padding: 1rem;
            transition: 0.3s;
            cursor: pointer;
            border: 1px solid #eee;
        }

        .job-card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transform: translateY(-3px);
        }

        .job-card i {
            font-size: 1.8rem;
            color: #007bff;
        }

        /* Application form */
        .apply-form {
            background: #fff;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .apply-form h3 {
            font-weight: 700;
            margin-bottom: 1rem;
        }

        /* Submit button */
        .btn-submit {
            background: linear-gradient(135deg, #007bff, #00c6ff);
            border: none;
            padding: 12px 25px;
            border-radius: 30px;
            font-weight: 600;
            color: #fff;
            transition: 0.3s;
        }

        .btn-submit:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 123, 255, 0.3);
        }

        /* Spacing */
        .container.py-5 {
            padding-top: 4rem !important;
            padding-bottom: 4rem !important;
        }
    </style>
</head>

<body class="starter-page-page">

    <header id="header" class="header d-flex align-items-center fixed-top">
        <div
            class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

            <a href="{{ route('mainPages') }}" class="logo d-flex align-items-center me-auto me-xl-0">
                <img src="{{ URL::to('assets/img/ESGroup-Logo.png') }}" alt="">
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="#hero" class="active">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            <a class="btn-getstarted" href="{{ route('mainPages') }}">Back</a>

        </div>
    </header>

    <main class="main">

        <div class="page-title light-background">
            <div class="container">
                <h1>Apply for a Job</h1>
                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="{{ route('mainPages') }}">Home</a></li>
                        <li class="current">Apply</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="container py-5">
            <div class="row g-4">
                <!-- LEFT: Jobs -->
                <div class="col-lg-5">
                    <div class="hiring-banner mb-4">
                        <h2>🚀 We’re Hiring!</h2>
                    </div>

                    <div class="container my-5">
                        <div class="row">
                            @foreach ($jobs as $job)
                                <div class="col-md-4">
                                    <a href="{{ route('form/job/details/page', $job->id) }}"
                                        class="text-decoration-none text-dark">
                                        <div class="job-card mb-3 p-3 d-flex flex-column align-items-start shadow-sm rounded"
                                            style="cursor: pointer; transition: transform 0.2s ease;">

                                            {{-- Dynamic Icon based on job title --}}
                                            @php
                                                $icon = 'bi-briefcase';
                                                if (stripos($job->job_title, 'developer') !== false) {
                                                    $icon = 'bi-code-slash';
                                                } elseif (stripos($job->job_title, 'designer') !== false) {
                                                    $icon = 'bi-brush';
                                                } elseif (stripos($job->job_title, 'manager') !== false) {
                                                    $icon = 'bi-people';
                                                } elseif (stripos($job->job_title, 'engineer') !== false) {
                                                    $icon = 'bi-gear';
                                                } elseif (stripos($job->job_title, 'account') !== false) {
                                                    $icon = 'bi-cash-coin';
                                                }
                                            @endphp

                                            <i class="bi {{ $icon }} fs-1 text-primary mb-2"></i>
                                            <h6 class="fw-semibold">{{ $job->job_title }}</h6>
                                            <small class="text-muted">{{ $job->job_type }} |
                                                {{ $job->job_location }}</small>
                                            <small class="d-block mt-1 text-center"
                                                style="color: #b0b0b0; font-size: 0.8rem;">
                                                Click for details
                                            </small>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- RIGHT: Form -->
                <div class="col-lg-7">
                    <div class="apply-form" id="applyForm">
                        <h3>Apply Now</h3>
                        <p class="text-muted">Fill out the form below to apply for your desired position.</p>

                        <form action="{{ route('form/job/hiring/save') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="job_id" value="{{ $job->id }}">
                            <div class="mb-3">
                                <label for="position" class="form-label">Position</label>
                                <select name="position" id="position" class="form-select" required>
                                    <option value="">-- Select Position --</option>
                                    @foreach ($jobs as $job)
                                        <option value="{{ $job->job_title }}">{{ $job->job_title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="fullname" class="form-label">Full Name</label>
                                <input type="text" name="fullname" id="fullname" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="sample@gmail.com" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" placeholder="+63" required>
                            </div>

                            <div class="mb-3">
                                <label for="resume" class="form-label">Upload Resume</label>
                                <input type="file" name="resume" id="resume" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit Application</button>
                        </form>
                    </div>
                </div>

            </div>
            <div class="modal fade" id="jobModal" tabindex="-1" aria-labelledby="jobModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 id="jobTitle" class="modal-title"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p id="jobDescription"></p>
                            <h6>Requirements:</h6>
                            <ul id="jobRequirements"></ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="scrollToApply()">Apply
                                Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <footer>
        <div class="container text-center mt-4">
            <p>© <span>Copyright</span> <strong class="px-1 sitename">ES Group</strong> <span>All Rights
                    Reserved</span></p>
        </div>
    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <!-- Vendor JS -->
    <script src="{{ URL::to('assets/css/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ URL::to('assets/css/aos/aos.js') }}"></script>
    <script src="{{ URL::to('assets/css/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ URL::to('assets/css/swiper/swiper-bundle.min.js') }}"></script>


    <style>
        .job-card {
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
            background: #fff;
        }

        .job-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
            border-color: #007bff;
        }
    </style>

</body>

</html>
