<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Flash;

use App\Models\ApplyForJob;
use App\Models\AddJob;


class JobHiringController extends Controller
{


    public function index()
    {
        try {
            $jobs = DB::table('add_jobs')
                ->where('status', 'Open')
                ->get();

            return view('main.hiring', compact('jobs'));
        } catch (\Exception $e) {
            flash()->error('Failed to load jobs: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function showJobDetails($id)
    {
        try {
            // Fetch job and increment views
            $job = AddJob::findOrFail($id);
            $job = AddJob::withCount('applications')->find($id);
            $job->increment('views');

            return view('main.jobdetails', compact('job'));
        } catch (\Exception $e) {
            flash()->error('Failed to load job details: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function saveRecord(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validate the request
            $request->validate([
                'fullname' => 'required|string|max:255',
                'email'    => 'required|email|max:255',
                'phone'    => 'required|string|max:20',
                'position' => 'required|string|max:255', // job_title
                'job_id'   => 'required|exists:add_jobs,id', // NEW: job_id field
                'message'  => 'nullable|string',
                'resume'   => 'required|mimes:pdf,doc,docx',
            ]);

            // Ensure directory exists: public/assets/resume
            $resumeDir = public_path('assets/resume');
            if (!file_exists($resumeDir)) {
                mkdir($resumeDir, 0777, true);
            }

            $file = $request->file('resume');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($resumeDir, $filename);
            $resumePath = 'assets/resume/' . $filename;

            // Save record with job_id
            ApplyForJob::create([
                'job_id'   => $request->job_id,
                'job_title'=> $request->position,
                'name'     => $request->fullname,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'status'   => 'Open',
                'message'  => $request->message,
                'cv_upload'=> $resumePath,
            ]);

            DB::commit();

            flash()->success('Your application has been submitted successfully!');
            return redirect()->back();

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            \Log::error('Job Hiring Save Error: ' . $e->getMessage());
            flash()->error('Failed to submit your application. Please try again.');
            return redirect()->back();
        }
    }
}
