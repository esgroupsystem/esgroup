<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\Employee;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\JobOrderNotification;
use Illuminate\Support\Facades\Mail;
use App\Notifications\GlobalUserNotification;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Http\Request;
use App\Models\Joborder;
use App\Models\User;
use App\Models\JobFiles;
use Carbon\Carbon;
use DB;
use Auth;

class JobOrderController extends Controller
{

    /** Display All Holidays */
    public function jobordersIndex()
    {
        try {
            $joborderview = Joborder::all();
            $users = User::whereIn('role_name', ['IT', 'Safety Office', 'Admin'])->get();
            return view('joborders.joborder', compact('joborderview', 'users'));
        } catch (\Exception $e) {
            Log::error('Job Order Index Error: ' . $e->getMessage());
            flash()->error('Failed to load job orders.');
            return redirect()->back();
        }
    }
        
        /** Page Create Jobs */
    public function createJobOrderIndex()
    {
        try {
            $loggedUser = Auth::user();
            $busList = DB::table('bus_details')
                ->selectRaw("id as cat_id, name as cat_name, body_number as cat_busnum, CONCAT(name, ' - (', body_number, ') - ', plate_number) as full_name")
                ->get();

            return view('joborders.createjoborder', compact('busList', 'loggedUser'));
        } catch (\Exception $e) {
            Log::error('Create Job Order Page Error: ' . $e->getMessage());
            flash()->error('Failed to load create job order page.');
            return redirect()->back();
        }
    }

        /** For View of Job Order */
    public function viewSpecificDetails($encryptedId)
    {
        try {
            $id = Crypt::decryptString($encryptedId);
            $jobDetail = Joborder::findOrFail($id);

            $FileDetails = JobFiles::where('job_id', $id)->get()->map(function ($file) {
                $file->extension = strtolower(pathinfo($file->file_path, PATHINFO_EXTENSION));
                return $file;
            });

            $relatedTasks = JobOrder::where('id', $id)
                ->whereIn('job_status', ['New', 'On Process'])
                ->get();

            return view('joborders.joborderview', compact('jobDetail', 'id', 'FileDetails', 'relatedTasks'));
        } catch (\Exception $e) {
            Log::error('View Job Order Details Error: ' . $e->getMessage());
            flash()->error('Failed to load job order details.');
            return redirect()->back();
        }
    }


        /** Save Record */
    public function saveRecordJoborders(Request $request)
    {
        $request->validate([
            'job_name' => 'required|string|max:255',
            'job_type' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $jobOrder = Joborder::create([
                'job_name' => $request->job_name,
                'job_type' => $request->job_type,
                'job_datestart' => $request->job_datestart,
                'job_time_start' => Carbon::parse($request->job_time_start)->format('H:i:s'),
                'job_time_end' => Carbon::parse($request->job_time_end)->format('H:i:s'),
                'job_sitNumber' => $request->job_sitNumber,
                'job_remarks' => $request->job_remarks,
                'job_status' => 'New',
                'job_assign_person' => 'Not assigned',
                'job_date_filled' => now(),
                'job_creator' => $request->user()->name,
            ]);

            $itUsers = User::whereIn('role_name', ['IT', 'Admin'])->get();
            foreach ($itUsers as $user) {
                $user->notify(new GlobalUserNotification(
                    'New Job Order Created',
                    'A new job order "' . $jobOrder->job_name . '" was created by ' . $jobOrder->job_creator,
                    route('view/details', ['id' => $jobOrder->id])
                ));

                try {
                    Mail::to($user->email)->queue(new JobOrderNotification($jobOrder));
                } catch (\Exception $e) {
                    Log::error("Failed to queue email for {$user->email}: " . $e->getMessage());
                }
            }

            DB::commit();
            flash()->success('Created new job order successfully :)');
            return redirect()->route('form/joborders/page');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Job Order Save Error: ' . $e->getMessage());
            flash()->error('Failed to add job order :)');
            return redirect()->back();
        }
    }

        /** Update Record */
    public function updateRecordJoborders(Request $request)
    {
        try {
            if ($request->ajax()) {
                $request->validate([
                    'id' => 'required|exists:joborders,id',
                    'job_status' => 'required|string|max:255',
                ]);

                DB::beginTransaction();
                $jobOrder = Joborder::find($request->id);
                $jobOrder->job_status = $request->job_status;
                $jobOrder->save();
                DB::commit();

                return response()->json(['success' => true, 'message' => 'Job Status updated successfully']);
            } else {
                $request->validate([
                    'id' => 'required|exists:joborders,id',
                    'job_status' => 'required|string|max:255',
                    'job_assign_person' => 'required|string|max:255',
                ]);

                DB::beginTransaction();
                Joborder::where('id', $request->id)->update([
                    'job_status' => $request->job_status,
                    'job_assign_person' => $request->job_assign_person,
                ]);
                DB::commit();

                flash()->success('Job Order updated successfully :)');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update Job Order Error: ' . $e->getMessage());
            flash()->error('Failed to update Job Order :)');
            return redirect()->back();
        }
    }
        
    /** Delete Record */
    public function deleteRecordJoborders(Request $request)
    {
        try {
            $request->validate(['id' => 'required|integer']);
            Joborder::destroy($request->id);
            flash()->success('Job Order deleted successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('Delete Job Order Error: ' . $e->getMessage());
            flash()->error('Failed to delete Job Order :)');
            return redirect()->back();
        }
    }

    public function Job_Files(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $index => $file) {
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '_' . uniqid() . '.' . $extension;

                    $destinationPath = public_path('assets/videos');
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }

                    $file->move($destinationPath, $filename);
                    $filePath = 'assets/videos/' . $filename;

                    JobFiles::create([
                        'job_id' => $request->input('job_order_id'),
                        'file_name' => $originalName,
                        'file_remarks' => $request->input('remarks')[$index] ?? '',
                        'file_notes' => $request->input('notes')[$index] ?? '',
                        'file_path' => $filePath,
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Files uploaded successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Upload error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Upload failed: ' . $e->getMessage()], 500);
        }
    }

    /*---- Deleteing Files or Video ---*/
    public function deleteVideoFiles($id)
    {
        DB::beginTransaction();
        try {
            $file = JobFiles::findOrFail($id);

            if (Storage::exists($file->file_path)) {
                Storage::delete($file->file_path);
            }

            $file->delete();
            DB::commit();

            flash()->success('File deleted successfully :)');
            return response()->json(['success' => true, 'flash' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete File Error: ' . $e->getMessage());
            flash()->error('Failed to delete the file :)');
            return response()->json(['success' => false, 'flash' => true], 500);
        }
    }     
}
