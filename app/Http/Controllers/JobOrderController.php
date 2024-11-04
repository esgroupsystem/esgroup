<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Joborder;
use DB;
use Auth;

class JobOrderController extends Controller
{

        /** Display All Holidays */
        public function jobordersIndex()
        {
            $joborderview = Joborder::all();
            return view('joborders.joborder', compact('joborderview'));
        }

        /** Page Create Estimates */
        public function createJobOrderIndex()
        {
            $loggedUser = Auth::user();
            $busList = DB::Table('assets_category')
            ->selectRaw("CONCAT(cat_name, ' - (', cat_busnum, ') - ', cat_busplate) as full_name, cat_id")
            ->get();
            return view('joborders.createjoborder', compact('busList','loggedUser'));
        }

        /** Save Record */
        public function saveRecordJoborders(Request $request)
        {
            $request->validate([
                'job_name'             => 'required|string|max:255',
                'job_type'             => 'required|string|max:255',
                'job_datestart',
                'job_time_start',
                'job_time_end',
                'job_sitNumber',
                'job_remarks',
                'job_status',
                'job_assign_person',
                'job_date_filled',
                'job_creator',
            ]);
            
            DB::beginTransaction();
            try {
                Holiday::create([
                    'job_name'                 => $request->job_name,
                    'job_type'                 => $request->job_type,
                    'job_datestart'            => $request->job_datestart,
                    'job_time_start'           => $request->job_time_start,
                    'job_time_end'              => $request->job_time_end,
                    'job_sitNumber'             => $request->job_sitNumber,
                    'job_remarks'               => $request->job_remarks,
                    'job_status'                => $request->job_status,
                    'job_assign_person'         => $request->job_assign_person,
                    'job_date_filled'           => $request->job_date_filled,
                    'job_creator'               => $request->job_creator,
                ]);
                
                DB::commit();
                flash()->success('Created new job order successfully :)');
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollback();
                flash()->error('Failed to add job order :)');
                return redirect()->back();
            }
        }

    /** Update Record */
    public function updateRecordJoborders(Request $request)
    {
        $request->validate([
            'id'           => 'required|integer|exists:holidays,id',
            'holidayName'  => 'required|string|max:255',
            'holidayDate'  => 'required',
        ]);

        DB::beginTransaction();
        try {
            Holiday::where('id', $request->id)->update([
                'name_holiday' => $request->holidayName,
                'date_holiday' => $request->holidayDate,
            ]);
            
            DB::commit();
            flash()->success('Holiday updated successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Failed to update holiday :)');
            return redirect()->back();
        }
    }

    /** Delete Record */
    public function deleteRecordJoborders(Request $request)
    {
        $request->validate(['id' => 'required|integer']);

        try {
            Holiday::destroy($request->id);
            flash()->success('Holiday deleted successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e); // Log the error for debugging
            flash()->error('Failed to delete Holiday :)');
            return redirect()->back();
        }
    }
}
