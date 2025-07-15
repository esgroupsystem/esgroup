@extends('layouts.master')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Locked Schedule View</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Locked Monthly Schedule</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="row">
            <div class="col-md-12">
                <div class="p-4 border rounded shadow-sm" style="background-color: #fff;">
                    <h1 class="text-center font-weight-bold mb-4">
                        Schedule for <span class="text-primary">{{ $days->first()->format('F Y') }}</span>
                    </h1>

                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>Driver</th>
                                    @foreach($days as $day)
                                        <th>{{ $day->format('D d') }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($drivers as $driver)
                                <tr>
                                    <td class="text-left">
                                        <strong>{{ $driver->name }}</strong><br>
                                        <small>{{ $driver->designation->designation ?? '' }}</small>
                                    </td>
                                    @foreach($days as $day)
                                        @php
                                            $key = $driver->id . '_' . $day->format('Y-m-d');
                                            $sched = $schedules[$key][0] ?? null;
                                        @endphp
                                        <td style="min-width: 160px; padding: 8px;">
                                            @if($sched)
                                                @if($sched->is_dayoff)
                                                    <span class="text-danger font-weight-bold" style="font-size: 18px;">REST DAY</span>
                                                @else
                                                    <div>
                                                        <div class="text-dark font-weight-bold">
                                                            {{ \Carbon\Carbon::parse($sched->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($sched->end_time)->format('g:i A') }}
                                                        </div>
                                                        <div class="text-muted small">
                                                            {{ $sched->cat_name }} - ({{ $sched->cat_busnum }})
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-muted">No Record</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
