<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Requirement;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $requirements = Requirement::where('employee_id', $employeeId)->get();
        return view('employee.profile', compact('employee', 'requirements'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'title' => 'required|string',
            'file' => 'required|file',
            'uploaded_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
        ]);

        $filePath = $request->file('file')->store('requirements', 'public');

        Requirement::create([
            'employee_id' => $request->employee_id,
            'title' => $request->title,
            'description' => $request->description,
            'uploaded_at' => $request->uploaded_at,
            'expires_at' => $request->expires_at,
            'file_path' => $filePath,
        ]);

        return back()->with('success', 'Requirement uploaded.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Download the specified requirement file.
     */
    public function download(Requirement $requirement)
    {
        return Storage::disk('public')->download($requirement->file_path);
    }
}
