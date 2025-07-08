<div class="row">
<div class="col-lg-8 col-md-8">
    <section class="dash-section">
        <h1 class="dash-sec-title">Today</h1>
            <div class="dash-sec-content">
                @forelse ($todayJobs as $item)
                    <div class="dash-info-list">
                        <a href="#" class="dash-card">
                            <div class="dash-card-container">
                                <div class="dash-card-icon">
                                    @if ($item->job_type === 'ACCIDENT')
                                        <i class="fa fa-car-burst"></i>
                                    @elseif ($item->job_type === 'CCTV DVR ISSUE')
                                        <i class="fa-solid fa-server"></i>
                                    @elseif ($item->job_type === 'CCTV MONITOR ISSUE')
                                        <i class="fa fa-tv"></i>
                                    @else
                                        <i class="fa-solid fa-ticket"></i>
                                    @endif
                                </div>
                                <div class="dash-card-content">
                                    @if ($item->job_type === 'ACCIDENT')
                                        <p>{{ $item->job_name }} is involving ACCIDENT</p>
                                    @elseif ($item->job_type === 'CCTV DVR ISSUE')
                                        <p>{{ $item->job_name }} reported for checking DVR ISSUE</p>
                                    @elseif ($item->job_type === 'CCTV MONITOR ISSUE')
                                        <p>{{ $item->job_name }} reported for checking MONITOR ISSUE</p>
                                    @else
                                        <p>{{ $item->job_name }} reported a task</p>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="dash-info-list">
                        <a href="#" class="dash-card">
                            <div class="dash-card-container">
                                <div class="dash-card-icon">
                                    <i class="fa fa-times text-danger" aria-hidden="true"></i>
                                </div>
                                <div class="dash-card-content">
                                    <p>No job orders for today.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforelse
            </div>
    </section>

    <section class="dash-section">
        <h1 class="dash-sec-title">Today</h1>
            <div class="dash-sec-content">
                @forelse ($yesterdayJobs as $item)
                    <div class="dash-info-list">
                        <a href="#" class="dash-card">
                            <div class="dash-card-container">
                                <div class="dash-card-icon">
                                    @if ($item->job_type === 'ACCIDENT')
                                        <i class="fa fa-car-burst"></i>
                                    @elseif ($item->job_type === 'CCTV DVR ISSUE')
                                        <i class="fa-solid fa-server"></i>
                                    @elseif ($item->job_type === 'CCTV MONITOR ISSUE')
                                        <i class="fa fa-tv"></i>
                                    @else
                                        <i class="fa-solid fa-ticket"></i>
                                    @endif
                                </div>
                                <div class="dash-card-content">
                                    @if ($item->job_type === 'ACCIDENT')
                                        <p>{{ $item->job_name }} is involving ACCIDENT</p>
                                    @elseif ($item->job_type === 'CCTV DVR ISSUE')
                                        <p>{{ $item->job_name }} reported for checking DVR ISSUE</p>
                                    @elseif ($item->job_type === 'CCTV MONITOR ISSUE')
                                        <p>{{ $item->job_name }} reported for checking MONITOR ISSUE</p>
                                    @else
                                        <p>{{ $item->job_name }} reported a task</p>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="dash-info-list">
                        <a href="#" class="dash-card">
                            <div class="dash-card-container">
                                <div class="dash-card-icon">
                                    <i class="fa fa-times text-danger" aria-hidden="true"></i>
                                </div>
                                <div class="dash-card-content">
                                    <p>No pending task yesterday.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforelse
            </div>
    </section>

    <section class="dash-section">
        <h1 class="dash-sec-title">Today</h1>
            <div class="dash-sec-content">
                @forelse ($pastThreeToSevenDaysJobs as $item)
                    <div class="dash-info-list">
                        <a href="#" class="dash-card">
                            <div class="dash-card-container">
                                <div class="dash-card-icon">
                                    @if ($item->job_type === 'ACCIDENT')
                                        <i class="fa fa-car-burst"></i>
                                    @elseif ($item->job_type === 'CCTV DVR ISSUE')
                                        <i class="fa-solid fa-server"></i>
                                    @elseif ($item->job_type === 'CCTV MONITOR ISSUE')
                                        <i class="fa fa-tv"></i>
                                    @else
                                        <i class="fa-solid fa-ticket"></i>
                                    @endif
                                </div>
                                <div class="dash-card-content">
                                    @if ($item->job_type === 'ACCIDENT')
                                        <p>{{ $item->job_name }} is involving ACCIDENT</p>
                                    @elseif ($item->job_type === 'CCTV DVR ISSUE')
                                        <p>{{ $item->job_name }} reported for checking DVR ISSUE</p>
                                    @elseif ($item->job_type === 'CCTV MONITOR ISSUE')
                                        <p>{{ $item->job_name }} reported for checking MONITOR ISSUE</p>
                                    @else
                                        <p>{{ $item->job_name }} reported a task</p>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="dash-info-list">
                        <a href="#" class="dash-card">
                            <div class="dash-card-container">
                                <div class="dash-card-icon">
                                    <i class="fa fa-times text-danger" aria-hidden="true"></i>
                                </div>
                                <div class="dash-card-content">
                                    <p>No pending task past seven days.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforelse
            </div>
    </section>
</div>

<div class="col-lg-4 col-md-4">
    <div class="dash-sidebar">
        <section>
            <h5 class="dash-title">Ticket Concern</h5>
            <div class="card">
                <div class="card-body">
                    <div class="time-list">
                        <div class="dash-stats-list">
                            <h4>{{ $totalTasks }}</h4>
                            <p>Total Tasks</p>
                        </div>
                        <div class="dash-stats-list">
                            <h4>{{ $pendingTasks }}</h4>
                            <p>Pending Tasks</p>
                        </div>
                        <div class="dash-stats-list">
                            <h4>{{ $completedTasks }}</h4>
                            <p>Completed Tasks</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {{-- <section>
            <h5 class="dash-title">Your Leave</h5>
            <div class="card">
                <div class="card-body">
                    <div class="time-list">
                        <div class="dash-stats-list">
                            <h4>4.5</h4>
                            <p>Leave Taken</p>
                        </div>
                        <div class="dash-stats-list">
                            <h4>12</h4>
                            <p>Remaining</p>
                        </div>
                    </div>
                    <div class="request-btn">
                        <a class="btn btn-primary" href="#">Apply Leave</a>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <h5 class="dash-title">Your time off allowance</h5>
            <div class="card">
                <div class="card-body">
                    <div class="time-list">
                        <div class="dash-stats-list">
                            <h4>5.0 Hours</h4>
                            <p>Approved</p>
                        </div>
                        <div class="dash-stats-list">
                            <h4>15 Hours</h4>
                            <p>Remaining</p>
                        </div>
                    </div>
                    <div class="request-btn">
                        <a class="btn btn-primary" href="#">Apply Time Off</a>
                    </div>
                </div>
            </div>
        </section> --}}
        @if($holidaysThisMonth->isNotEmpty())
        <section>
            <h5 class="dash-title">Upcoming Holidays ({{ now()->format('F') }})</h5>
            @foreach($holidaysThisMonth as $holiday)
            <div class="card mb-2">
                <div class="card-body text-center">
                    <h4 class="holiday-title mb-0">
                        {{ \Carbon\Carbon::parse($holiday['date']['iso'])->format('D M d, Y') }} – {{ $holiday['name'] }}
                    </h4>
                </div>
            </div>
            @endforeach
        </section>
        @else
        <section>
            <h5 class="dash-title">Upcoming Holidays</h5>
            <div class="card">
                <div class="card-body text-center">
                    <h4 class="holiday-title mb-0">No holidays this month.</h4>
                </div>
            </div>
        </section>
        @endif
    </div>
</div>