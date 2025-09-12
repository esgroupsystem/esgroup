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

        <div class="container my-5">
            <div class="row">
                <!-- Left Job Details -->
                <div class="col-md-8">
                    <div class="job-info job-widget">
                        <h3 class="job-title">{{ $job->job_title }}</h3>
                        <span class="job-dept">{{ $job->department }}</span>
                        <ul class="list-unstyled d-flex gap-4 mt-3">
                            <li class="d-flex align-items-center gap-2">
                                <i class="bi bi-person text-primary fs-5"></i>
                                Applications: <span class="fw-bold text-dark">{{ $job->applications_count }}</span>
                            </li>
                            <li class="d-flex align-items-center gap-2">
                                <i class="bi bi-eye text-primary fs-5"></i>
                                Views: <span class="fw-bold text-dark">{{ $job->views }}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="job-content job-widget">
                        <div class="job-desc-title">
                            <h4>Job Description</h4>
                        </div>
                        <div class="job-description">
                            <p>{!! nl2br($job->description) !!}</p>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="col-md-4">
                    <div class="job-det-info job-widget">
                        <div class="info-list">
                            <span><i class="bi bi-bar-chart"></i></span>
                            <div class="info-text">
                                <h5>Job Type</h5>
                                <p>{{ $job->job_type }}</p>
                            </div>
                        </div>

                        <div class="info-list">
                            <span><i class="bi bi-cash-coin"></i></span>
                            <div class="info-text">
                                <h5>Salary</h5>
                                <p>{{ $job->salary_from }}₱ - {{ $job->salary_to }}₱</p>
                            </div>
                        </div>

                        <div class="info-list">
                            <span><i class="bi bi-briefcase"></i></span>
                            <div class="info-text">
                                <h5>Experience</h5>
                                <p>{{ $job->experience }}</p>
                            </div>
                        </div>

                        <div class="info-list">
                            <span><i class="bi bi-people"></i></span>
                            <div class="info-text">
                                <h5>Vacancy</h5>
                                <p>{{ $job->no_of_vacancies }}</p>
                            </div>
                        </div>

                        <div class="info-list">
                            <span><i class="bi bi-geo-alt"></i></span>
                            <div class="info-text">
                                <h5>Location</h5>
                                <p>{!! nl2br($job->job_location) !!}</p>
                            </div>
                        </div>

                        <div class="info-list">
                            <span><i class="bi bi-telephone"></i></span>
                            <div class="info-text">
                                <h5>Contact</h5>
                                <p>
                                    +63 96-2984-5018<br>
                                    <a href="mailto:hrd.esgroup@gmail.com">hrd.esgroup@gmail.com</a><br>
                                    <a href="mailto:support@esgroup.com.ph">support@esgroup.com.ph</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="apply-now-container">
                        <a href="{{ url('/form/job/hiring/page') }}">
                            <button class="apply-now-btn d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-hand-index-thumb-fill fs-4 hand-right"></i>
                                <span>Apply Now</span>
                            </button>
                        </a>
                    </div>
                </div>
                <!-- /Right Sidebar -->
            </div>
        </div>
        <!-- /Page Content -->

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
    <script src="https://cdn.jsdelivr.net/npm/@srexi/purecounterjs/dist/purecounter_vanilla.js"></script>
    <script src="{{ URL::to('assets/js/main.js') }}"></script>

    <style>
        /* === General Container Fix === */
        .row {
            margin-top: 40px;
        }

        /* === Job Widget Card === */
        .job-widget {
            background: #fff;
            border: 1px solid #e4e4e4;
            border-radius: 12px;
            padding: 28px;
            margin-bottom: 25px;
            box-shadow: 0 3px 14px rgba(0, 0, 0, 0.08);
        }

        /* === Job Title / Department === */
        .job-title {
            font-size: 26px;
            font-weight: 700;
            color: #222;
            margin-bottom: 8px;
        }

        .job-dept {
            font-size: 16px;
            font-weight: 500;
            color: #6c757d;
            margin-bottom: 18px;
            display: block;
        }

        /* === Job Post Details === */
        .job-post-det {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .job-post-det li {
            font-size: 16px;
            color: #444;
            margin-bottom: 10px;
        }

        .job-post-det li i {
            margin-right: 10px;
            color: #0d6efd;
            font-size: 18px;
        }

        /* === Job Content === */
        .job-content h4 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 14px;
            color: #333;
        }

        .job-description p {
            font-size: 16px;
            line-height: 1.8;
            color: #555;
            margin-bottom: 16px;
        }

        /* === Side Info Panel === */
        .job-det-info {
            padding: 24px;
        }

        /* === Info List === */
        .info-list {
            display: flex;
            align-items: flex-start;
            /* Align icon with top of text */
            margin-bottom: 22px;
            /* More space between each block */
        }

        .info-list span i {
            font-size: 2rem;
            /* Bigger icons */
            color: #0d6efd;
            /* Bootstrap primary blue */
            margin-right: 14px;
            /* More gap between icon and text */
            line-height: 1;
        }

        .info-list h5 {
            font-size: 16px;
            font-weight: 600;
            margin: 0 0 6px 0;
            /* Space between label and value */
            color: #333;
        }

        .info-list p {
            font-size: 15px;
            margin: 0;
            /* Reset default margins */
            color: #555;
            line-height: 1.5;
        }

        /* === Apply Button === */
        .job-btn {
            display: block;
            width: 100%;
            padding: 14px;
            text-align: center;
            background: linear-gradient(135deg, #0d6efd, #4a8fff);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-weight: 600;
            font-size: 16px;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-bottom: 24px;
        }

        .job-btn:hover {
            background: linear-gradient(135deg, #0b5ed7, #367ae8);
            color: #fff;
        }

        /* === Application Ends Text === */
        .app-ends {
            display: inline-block;
            font-size: 14px;
            color: #dc3545;
            font-weight: 600;
            text-decoration: none;
            margin-top: 12px;
        }

        .apply-now-container {
            margin-top: 10px;
        }

        .apply-now-btn {
            width: 100%;
            height: 50px;
            background-color: #064975;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            padding: 0 16px;
            transition: background 0.3s ease;
        }

        .apply-now-btn:hover {
            background-color: #04385a;
            /* slightly darker on hover */
        }

        .hand-right {
            transform: rotate(90deg);
            /* rotates the hand to point right */
        }
    </style>

</body>

</html>
