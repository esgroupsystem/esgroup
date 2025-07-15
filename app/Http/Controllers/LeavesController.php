<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeavesAdmin;
use App\Models\Employee;
use App\Models\EmployeeSchedule;
use App\Models\ScheduleLock;
use Carbon\Carbon;
use App\Models\User;
use DateTime;
use DB;

class LeavesController extends Controller
{
    /** leaves page */
    public function leaves()
    {
        $leaves = DB::table('leaves_admins')->join('users', 'users.user_id','leaves_admins.user_id')->select('leaves_admins.*', 'users.position','users.name','users.avatar')->get();
        return view('employees.leaves',compact('leaves'));
    }

    /** Save Record */
    public function saveRecord(Request $request)
    {
        $request->validate([
            'leave_type'   => 'required|string|max:255',
            'from_date'    => 'required|string|max:255',
            'to_date'      => 'required|string|max:255',
            'leave_reason' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {

            $from_date = new DateTime($request->from_date);
            $to_date   = new DateTime($request->to_date);
            $day       = $from_date->diff($to_date);
            $days      = $day->d;

            $leaves = new LeavesAdmin;
            $leaves->user_id       = $request->user_id;
            $leaves->leave_type    = $request->leave_type;
            $leaves->from_date     = $request->from_date;
            $leaves->to_date       = $request->to_date;
            $leaves->day           = $days;
            $leaves->leave_reason  = $request->leave_reason;
            $leaves->save();
            
            DB::commit();
            flash()->success('Create new Leaves successfully :)');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            flash()->error('Add Leaves fail :)');
            return redirect()->back();
        }
    }

    /** Edit Record */
    public function editRecordLeave(Request $request)
    {
        DB::beginTransaction();
        try {

            $from_date = new DateTime($request->from_date);
            $to_date   = new DateTime($request->to_date);
            $day       = $from_date->diff($to_date);
            $days      = $day->d;

            $update = [
                'id'           => $request->id,
                'leave_type'   => $request->leave_type,
                'from_date'    => $request->from_date,
                'to_date'      => $request->to_date,
                'day'          => $days,
                'leave_reason' => $request->leave_reason,
            ];

            LeavesAdmin::where('id',$request->id)->update($update);
            DB::commit();
            flash()->success('Updated Leaves successfully :)');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            flash()->error('Update Leaves fail :)');
            return redirect()->back();
        }
    }

    /** Delete Record */
    public function deleteLeave(Request $request)
    {
        try {
            LeavesAdmin::destroy($request->id);
            flash()->success('Leaves admin deleted successfully :)');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            flash()->error('Leaves admin delete fail :)');
            return redirect()->back();
        }
    }

    /** Leave Settings Page */
    public function leaveSettings()
    {
        return view('employees.leavesettings');
    }

    /** Attendance Admin */
    public function attendanceIndex()
    {
        return view('employees.attendance');
    }

    /** Attendance Employee */
    public function AttendanceEmployee()
    {
        return view('employees.attendanceemployee');
    }

    /** Leaves Employee */
    public function leavesEmployee()
    {
        return view('employees.leavesemployee');
    }

    /** Shift List */
    public function shiftList()
    {
        return view('employees.shiftlist');
    }

    /** Shift Scheduling */
    public function shiftScheduLing()
    {
        $year = now()->year;
        $month = now()->month;

        $busList = DB::Table('assets_category')
            ->selectRaw("cat_id, cat_name, cat_busnum, CONCAT(cat_name, ' - (', cat_busnum, ') - ', cat_busplate) as full_name")
            ->get();

        $drivers = DB::table('employees')
                ->leftJoin('designations', 'employees.designation_id', '=', 'designations.id')
                ->where('designations.designation', 'Driver')
                ->select('employees.*', 'designations.designation')
                ->get();

        $daysInMonth = now()->daysInMonth;
        $days = collect();
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = Carbon::create($year, $month, $i);
            $days->push($date);
        }

        $schedules = DB::table('employee_schedules')
            ->leftJoin('assets_category', 'employee_schedules.bus_id', '=', 'assets_category.cat_id')
            ->whereMonth('work_date', $month)
            ->whereYear('work_date', $year)
            ->select(
                'employee_schedules.*',
                'assets_category.cat_name',
                'assets_category.cat_busnum'
            )
            ->get()
            ->groupBy(fn($s) => $s->employee_id . '_' . Carbon::parse($s->work_date)->format('Y-m-d'));

        return view('employees.shiftscheduling', compact('drivers', 'days', 'schedules', 'busList'));
    }

    /** Save Scheduling */
    public function storeSchedule(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'work_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'bus_id' => 'required|exists:assets_category,cat_id',
        ]);

        DB::beginTransaction();

        try {
            $employeeId = $request->employee_id;
            $busId = $request->bus_id;
            $startTime = $request->start_time;
            $endTime = $request->end_time;
            $workDate = Carbon::parse($request->work_date);

            if ($request->has('apply_full_month')) {
                $startOfMonth = $workDate->copy()->startOfMonth();
                $endOfMonth = $workDate->copy()->endOfMonth();

                foreach ($startOfMonth->daysUntil($endOfMonth) as $date) {
                    EmployeeSchedule::updateOrCreate(
                        [
                            'employee_id' => $employeeId,
                            'work_date' => $date->toDateString(),
                        ],
                        [
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'bus_id' => $busId,
                        ]
                    );
                }
            } else {
                EmployeeSchedule::updateOrCreate(
                    [
                        'employee_id' => $employeeId,
                        'work_date' => $workDate->toDateString(),
                    ],
                    [
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'bus_id' => $busId,
                    ]
                );
            }

            DB::commit();
            return redirect()->back()->with('success', 'Schedule saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to save schedule: ' . $e->getMessage());
        }
    }

    /** Update Scheduling */
    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'schedule_id' => 'required|exists:employee_schedules,id',
                'employee_id' => 'required|exists:employees,id',
                'work_date' => 'required|date',
                'bus_id' => 'nullable|exists:assets_category,cat_id',
                'start_time' => 'nullable|date_format:H:i',
                'end_time' => 'nullable|date_format:H:i',
            ]);

            $isDayoff = $request->has('is_dayoff');

            $schedule = EmployeeSchedule::findOrFail($request->schedule_id);
            $schedule->employee_id = $request->employee_id;
            $schedule->work_date = $request->work_date;
            $schedule->start_time = $isDayoff ? null : $request->start_time;
            $schedule->end_time = $isDayoff ? null : $request->end_time;
            $schedule->bus_id = $request->bus_id;
            $schedule->is_dayoff = $isDayoff;
            $schedule->save();

            DB::commit();
            return redirect()->back()->with('success', 'Schedule updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Schedule Update Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update schedule!');
        }
    }

    /** Show Driver Schedules */
    public function showDriverSchedules(Request $request)
    {
        $today = now();
        $selectedMonth = $request->input('month', $today->month);
        $selectedYear = $request->input('year', $today->year);

        $employees = DB::table('employees')
            ->join('designations', 'employees.designation_id', '=', 'designations.id')
            ->where('designations.designation', 'Driver')
            ->select('employees.*', 'designations.designation')
            ->get();

        $driverList = [];

        foreach ($employees as $emp) {
            $employeeSchedules = EmployeeSchedule::where('employee_id', $emp->id)
                ->whereMonth('work_date', $selectedMonth)
                ->whereYear('work_date', $selectedYear)
                ->orderBy('work_date', 'asc')
                ->get();

            if ($employeeSchedules->isEmpty()) {
                continue;
            }

            $logs = $employeeSchedules->keyBy(function ($item) {
                return Carbon::parse($item->work_date)->format('Y-m-d');
            });

            $schedules = [];

            foreach ($employeeSchedules as $sched) {
                $date = Carbon::parse($sched->work_date)->format('Y-m-d');
                $displayDate = Carbon::parse($sched->work_date)->format('F j');

                $timeSlot = $sched->is_dayoff
                    ? 'Rest Day'
                    : (
                        ($sched->start_time ? Carbon::parse($sched->start_time)->format('g:i A') : '') .
                        ($sched->end_time ? ' to ' . Carbon::parse($sched->end_time)->format('g:i A') : '')
                    );

                $log = $logs[$date] ?? null;

                $schedules[] = [
                    'display' => $displayDate . ' - ' . $timeSlot,
                    'date' => $date,
                    'time_in' => $log->time_in ?? null,
                    'time_out' => $log->time_out ?? null,
                    'remit' => $log->remit ?? null,
                    'diesel' => $log->diesel ?? null,
                ];
            }

            $driverList[] = [
                'id' => $emp->id,
                'name' => $emp->name,
                'designation' => $emp->designation,
                'schedule' => $schedules,
            ];
        }

        if (empty($driverList)) {
            return redirect()->back()->with('error', 'Please plot a schedule.');
        }

        return view('employees.all_schedule_driver', [
            'drivers' => $driverList,
            'date' => $today->format('Y-m-d'),
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
        ]);
    }

    /** Update Schedule Log */
    public function updateScheduleLog(Request $request)
    {
        DB::beginTransaction();
        $request->validate([
            'schedules' => 'required|array',
            'driver_id' => 'required|exists:employees,id',
        ]);

        try {
            foreach ($request->schedules as $schedule) {
                $validated = \Validator::make($schedule, [
                    'date' => 'required|date',
                    'time_in' => 'nullable|date_format:H:i',
                    'time_out' => 'nullable|date_format:H:i',
                    'remit' => 'nullable|numeric',
                    'diesel' => 'nullable|numeric',
                ])->validate();

                EmployeeSchedule::updateOrCreate(
                    ['employee_id' => $request->driver_id, 'work_date' => $schedule['date']],
                    [
                        'time_in' => $schedule['time_in'] ?? null,
                        'time_out' => $schedule['time_out'] ?? null,
                        'remit' => $schedule['remit'] ?? null,
                        'diesel' => $schedule['diesel'] ?? null,
                    ]
                );
            }
            DB::commit();
            flash()->success('Update successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            flash()->error('Error Updating :)' . $e->getMessage());
            \Log::error('Error Updating :) ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
