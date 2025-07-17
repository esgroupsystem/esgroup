@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Biometric Logs</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Logs</li>
                        </ul>
                    </div>
                </div>
            </div>
            <form action="{{ route('biometrics.logs') }}" method="GET">
                <div class="row filter-row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Employee Name or Bio ID</label>
                            <input type="text" name="employee" class="form-control" value="{{ request('employee') }}">
                        </div>
                    </div>
                    @php
                        use Carbon\Carbon;

                        $cutoffPeriods = [];
                        $year = now()->year;
                        $month = 1;
                        $today = now();

                        while ($month <= 12) {
                            // 11–25
                            $start1 = Carbon::createFromDate($year, $month, 11)->startOfDay();
                            $end1 = Carbon::createFromDate($year, $month, 25)->endOfDay();
                            if ($start1->lte($today)) {
                                $cutoffPeriods[] = [
                                    'value' => $start1->format('Y-m-d') . '|' . $end1->format('Y-m-d'),
                                    'label' => $start1->format('F d, Y') . ' to ' . $end1->format('F d, Y'),
                                ];
                            }

                            // 26–10 (crosses into next month)
                            $start2 = Carbon::createFromDate($year, $month, 26)->startOfDay();
                            $end2 = $start2->copy()->addMonth()->day(10)->endOfDay();
                            if ($start2->lte($today)) {
                                $cutoffPeriods[] = [
                                    'value' => $start2->format('Y-m-d') . '|' . $end2->format('Y-m-d'),
                                    'label' => $start2->format('F d, Y') . ' to ' . $end2->format('F d, Y'),
                                ];
                            }

                            $month++;
                        }

                        $selected = request('cutoff');
                    @endphp
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Select Cutoff Period</label>
                            <select name="cutoff" class="form-control">
                                <option value="">-- Select Cutoff Period --</option>
                                @foreach ($cutoffPeriods as $period)
                                    <option value="{{ $period['value'] }}"
                                        {{ $selected == $period['value'] ? 'selected' : '' }}>
                                        {{ $period['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">Search</button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Employee ID</th>
                                    <th>Date</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>{{ $log['employee_name'] ?: '-' }}</td>
                                        <td>{{ $log['employee_id'] ?: '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($log['log_date'])->format('F d, Y') }}</td>

                                        {{-- Time In --}}
                                        <td>
                                            @if ($log['time_in'])
                                                @php
                                                    $timeIn = \Carbon\Carbon::createFromFormat(
                                                        'H:i:s',
                                                        $log['time_in'],
                                                    );
                                                    $timeInFormatted = $timeIn->format('h:i A');
                                                    $isAm = $timeIn->format('A') === 'AM';
                                                @endphp
                                                <span style="color: {{ $isAm ? 'green' : 'orange' }};">
                                                    {{ $timeInFormatted }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>

                                        {{-- Time Out --}}
                                        <td>
                                            @if ($log['time_out'])
                                                @php
                                                    $timeOut = \Carbon\Carbon::createFromFormat(
                                                        'H:i:s',
                                                        $log['time_out'],
                                                    );
                                                    $timeOutFormatted = $timeOut->format('h:i A');
                                                    $isPm = $timeOut->format('A') === 'PM';
                                                @endphp
                                                <span style="color: {{ $isPm ? 'orange' : 'green' }};">
                                                    {{ $timeOutFormatted }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No records found for this cutoff period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="syncModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center">
                    <div class="modal-body p-5">
                        <h5>Syncing Biometric Logs...</h5>
                        <div class="progress my-3" style="height: 25px;">
                            <div id="syncProgressBar"
                                class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                role="progressbar" style="width: 0%">0%</div>
                        </div>
                        <p id="syncMessage">Please wait while we sync logs...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

