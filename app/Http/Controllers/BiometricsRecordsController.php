<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use App\Models\BiometricLog;
use App\Jobs\SyncBiometricJob;
use Carbon\Carbon;
use Toastr;
use Session;
use Hash;
use Auth;
use DB;


class BiometricsRecordsController extends Controller
{
    public function index(Request $request)
    {
        $employeeQuery = trim($request->input('employee'));
        $cutoff = $request->input('cutoff');
        $records = collect();

        if (!$cutoff) {
            $today = Carbon::now();
            if ($today->day >= 11 && $today->day <= 25) {
                $cutoffStart = Carbon::create($today->year, $today->month, 11)->startOfDay();
                $cutoffEnd = Carbon::create($today->year, $today->month, 25)->endOfDay();
            } elseif ($today->day >= 26) {
                $cutoffStart = Carbon::create($today->year, $today->month, 26)->startOfDay();
                $cutoffEnd = Carbon::create($today->copy()->addMonth()->year, $today->copy()->addMonth()->month, 10)->endOfDay();
            } else {
                $cutoffStart = Carbon::create($today->copy()->subMonth()->year, $today->copy()->subMonth()->month, 26)->startOfDay();
                $cutoffEnd = Carbon::create($today->year, $today->month, 10)->endOfDay();
            }

            $cutoff = $cutoffStart->format('Y-m-d') . '|' . $cutoffEnd->format('Y-m-d');
        }

        if ($employeeQuery && $cutoff) {
            [$start, $end] = explode('|', $cutoff);
            $start = Carbon::parse($start)->startOfDay();
            $end = Carbon::parse($end)->endOfDay();

            $dateRange = [];
            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                $dateRange[] = $date->format('Y-m-d');
            }

            $logs = BiometricLog::select(
                    'employee_name',
                    'employee_id',
                    DB::raw('DATE(log_time) as log_date'),
                    DB::raw('MIN(log_time) as time_in'),
                    DB::raw('MAX(log_time) as time_out')
                )
                ->where(function ($q) use ($employeeQuery) {
                    $q->whereRaw('LOWER(employee_id) LIKE ?', ["%".strtolower($employeeQuery)."%"])
                    ->orWhereRaw('LOWER(employee_name) LIKE ?', ["%".strtolower($employeeQuery)."%"]);
                })
                ->whereBetween('log_time', [$start, $end])
                ->groupBy('employee_name', 'employee_id', DB::raw('DATE(log_time)'))
                ->orderBy('log_date', 'asc')
                ->get()
                ->groupBy('log_date');

            foreach ($dateRange as $date) {
                $dailyLogs = $logs->get($date);
                if ($dailyLogs && $dailyLogs->count()) {
                    foreach ($dailyLogs as $log) {
                        $records->push([
                            'employee_name' => $log->employee_name,
                            'employee_id'   => $log->employee_id,
                            'log_date'      => $date,
                            'time_in'       => Carbon::parse($log->time_in)->format('H:i:s'),
                            'time_out'      => Carbon::parse($log->time_out)->format('H:i:s'),
                        ]);
                    }
                } else {
                    $records->push([
                        'employee_name' => '',
                        'employee_id'   => '',
                        'log_date'      => $date,
                        'time_in'       => '',
                        'time_out'      => '',
                    ]);
                }
            }
        }

        return view('payroll.biometricsrecords', [
            'logs' => $records,
            'employeeQuery' => $employeeQuery,
            'cutoff' => $cutoff,
        ]);
    }

    /**
     * Sync biometrics logs from CrossChex API.
     */
    public function syncBiometrics()
    {
        \Log::info('SyncBiometrics triggered');

        try {
            Artisan::queue('crosschex:sync-logs');

            return response()->json(['success' => true, 'message' => 'Sync started in background']);
        } catch (\Exception $e) {
            \Log::error('Sync failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
