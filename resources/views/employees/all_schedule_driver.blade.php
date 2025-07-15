@extends('layouts.master')

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Daily Scheduling</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('all/employee/list') }}">Driver's & Conductor's</a>
                            </li>
                            <li class="breadcrumb-item active">Scheduleeeeeeeee</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Success/Error Flash Message -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @elseif(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Filter by Month, Year, and Search -->
            <form method="GET" action="{{ route('all.schedule') }}">
                <div class="row filter-row">

                    <!-- 🔍 Search Driver -->
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <input type="text" id="driverSearch" class="form-control floating" placeholder=" "
                                name="search" value="{{ request('search') }}">
                            <label class="focus-label">Search Driver</label>
                        </div>
                    </div>

                    <!-- 📅 Filter Month -->
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus select-focus">
                            <select class="form-control floating" name="month">
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $m == $selectedMonth ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endfor
                            </select>
                            <label class="focus-label">Month</label>
                        </div>
                    </div>

                    <!-- 🗓️ Filter Year -->
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus select-focus">
                            <select class="form-control floating" name="year">
                                @for ($y = now()->year - 1; $y <= now()->year + 1; $y++)
                                    <option value="{{ $y }}" {{ $y == $selectedYear ? 'selected' : '' }}>
                                        {{ $y }}</option>
                                @endfor
                            </select>
                            <label class="focus-label">Year</label>
                        </div>
                    </div>

                    <!-- 🔘 Submit Button -->
                    <div class="col-sm-6 col-md-3">
                        <button type="submit" class="btn btn-success btn-block w-100">Search</button>
                    </div>

                </div>
            </form>

            <!-- Driver Cards -->
            <div class="row g-1">
                @foreach ($drivers as $driver)
                    <div class="col-12 col-md-6 col-lg-4 mb-2 driver-card">
                        <div class="border rounded bg-white p-1" style="font-size: 16px;">
                            <div class="d-flex justify-content-between align-items-center mb-1 px-1">
                                <div>
                                    <strong>{{ $driver['name'] }}</strong><br>
                                    <small class="text-muted">{{ $driver['designation'] }}</small>
                                </div>
                                <div>
                                    <button onclick="printCard(this)" class="btn btn-outline-primary btn-sm py-0 px-2"
                                        style="font-size: 10px;">🖨️ Print</button>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('schedule.update.log') }}" class="px-1">
                                @csrf
                                <input type="hidden" name="driver_id" value="{{ $driver['id'] }}">

                                <table class="table table-bordered table-sm text-center align-middle mb-1"
                                    style="font-size: 13px; table-layout: fixed; width: 100%;">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 16%;">Date</th>
                                            <th style="width: 16%;">Schedule</th>
                                            <th style="width: 17%;">Time In</th>
                                            <th style="width: 17%;">Time Out</th>
                                            <th style="width: 17%;">Remit</th>
                                            <th style="width: 17%;">Diesel</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($driver['schedule'] as $index => $entry)
                                            @php
                                                $date = $entry['date'] ?? '';
                                                $time = $entry['display'] ?? '';
                                                $timeIn = $entry['time_in'] ?? '';
                                                $timeOut = $entry['time_out'] ?? '';
                                                $remit = $entry['remit'];
                                                $diesel = $entry['diesel'];
                                            @endphp
                                            <tr>
                                                <td class="px-1">
                                                    {{ $date }}
                                                    <input type="hidden" name="schedules[{{ $index }}][date]"
                                                        value="{{ $date }}">
                                                </td>
                                                <td class="px-1">{{ $time }}</td>

                                                <td class="px-1">
                                                    <input type="time" name="schedules[{{ $index }}][time_in]"
                                                        class="form-control form-control-sm px-1 py-0"
                                                        style="font-size: 10px;" value="{{ $timeIn }}"
                                                        placeholder="{{ empty($timeIn) ? 'N/A' : '' }}">
                                                </td>

                                                <td class="px-1">
                                                    <input type="time" name="schedules[{{ $index }}][time_out]"
                                                        class="form-control form-control-sm px-1 py-0"
                                                        style="font-size: 10px;" value="{{ $timeOut }}"
                                                        placeholder="{{ empty($timeOut) ? 'N/A' : '' }}">
                                                </td>

                                                <td class="px-1">
                                                    <input type="number" step="0.01"
                                                        name="schedules[{{ $index }}][remit]"
                                                        class="form-control form-control-sm px-1 py-0"
                                                        style="font-size: 10px;"
                                                        value="{{ is_null($remit) ? '' : $remit }}"
                                                        placeholder="{{ is_null($remit) ? 'N/A' : '' }}">
                                                </td>

                                                <td class="px-1">
                                                    <input type="number" step="0.01"
                                                        name="schedules[{{ $index }}][diesel]"
                                                        class="form-control form-control-sm px-1 py-0"
                                                        style="font-size: 10px;"
                                                        value="{{ is_null($diesel) ? '' : $diesel }}"
                                                        placeholder="{{ is_null($diesel) ? 'N/A' : '' }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="text-end mt-1">
                                    <button type="submit" class="btn btn-success btn-sm px-2 py-0"
                                        style="font-size: 10px;">💾 Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

@section('script')
    <script>
        // Search filter
        document.getElementById('driverSearch').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const cards = document.querySelectorAll('.driver-card');
            cards.forEach(card => {
                const name = card.querySelector('strong').textContent.toLowerCase();
                card.style.display = name.includes(query) ? '' : 'none';
            });
        });

        function printCard(button) {
            const card = button.closest('.driver-card');
            window.print();
        }
    </script>
@endsection
@endsection
