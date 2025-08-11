<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\module_permission;
use Illuminate\Http\Request;
use App\Models\EmployeeSchedule;
use App\Models\department;
use App\Models\Employee;
use App\Models\User;
use App\Models\designation;
use App\Models\PersonalInfomation;
use App\Models\Requirement;
use App\Models\ProfileInformation;
use App\Models\UserEmergencyContact;
use Carbon\Carbon;
use DB;
use Auth;

class EmployeeController extends Controller
{
    public function viewProfile($id)
    {
        try {
            $user = Auth::user();

            if ($user->role_name === 'Admin') {
                return $this->profileEmployee($id);
            }

            $approval = DB::table('employee_view_approvals')
                ->where('employee_id', $id)
                ->where('requested_by', $user->id)
                ->where('status', 'approved')
                ->where('approved_until', '>=', now())
                ->first();

            if ($approval) {
                return $this->profileEmployee($id);
            }

            return redirect()->back()->with('error', 'Access denied. Please request admin approval.');
        } catch (\Exception $e) {
            Log::error("Error in viewProfile: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while trying to view the profile.');
        }
    }

    public function requestApproval($employeeId)
    {
        try {
            $user = Auth::user();

            $existing = DB::table('employee_view_approvals')
                ->where('employee_id', $employeeId)
                ->where('requested_by', $user->id)
                ->whereIn('status', ['pending', 'approved'])
                ->where(function($q) {
                    $q->whereNull('approved_until')
                    ->orWhere('approved_until', '>=', now());
                })
                ->first();

            if ($existing) {
                return redirect()->back()->with('info', 'You already have a pending or active approval for this employee.');
            }

            DB::table('employee_view_approvals')->insert([
                'employee_id' => $employeeId,
                'requested_by' => $user->id,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Access request sent to Admin.');
        } catch (\Exception $e) {
            Log::error("Error in requestApproval: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send access request.');
        }
    }

    public function viewRequests()
    {
        try {
            $requests = DB::table('employee_view_approvals')
                ->join('employees', 'employee_view_approvals.employee_id', '=', 'employees.id')
                ->join('users as hr', 'employee_view_approvals.requested_by', '=', 'hr.id')
                ->select('employee_view_approvals.*', 'employees.name as employee_name', 'hr.name as hr_name')
                ->where('employee_view_approvals.status', 'pending')
                ->get();

            return view('employees.employee_requests', compact('requests'));
        } catch (\Exception $e) {
            Log::error("Error in viewRequests: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load requests.');
        }
    }

    public function approveRequest(Request $request, $id)
    {
        try {
            DB::table('employee_view_approvals')
                ->where('id', $id)
                ->update([
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_until' => $request->approved_until,
                    'updated_at' => now(),
                ]);

            return redirect()->back()->with('success', 'Request approved.');
        } catch (\Exception $e) {
            Log::error("Error in approveRequest: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to approve request.');
        }
    }

    public function rejectRequest($id)
    {
        try {
            DB::table('employee_view_approvals')
                ->where('id', $id)
                ->update([
                    'status' => 'rejected',
                    'approved_by' => Auth::id(),
                    'updated_at' => now(),
                ]);

            return redirect()->back()->with('success', 'Request rejected.');
        } catch (\Exception $e) {
            Log::error("Error in rejectRequest: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to reject request.');
        }
    }

    /** Employee profile */
    public function profileEmployee($id)
    {
        try {
            $employee = DB::table('employees')
                ->leftJoin('personal_information as pi', 'pi.user_id', 'employees.employee_id')
                ->leftJoin('profile_information as pr', 'pr.user_id', 'employees.employee_id')
                ->leftJoin('user_emergency_contacts as ue', 'ue.user_id', 'employees.employee_id')
                ->leftJoin('departments', 'employees.department_id', 'departments.id')
                ->leftJoin('designations', 'employees.designation_id', 'designations.id')
                ->select(
                    'employees.*',
                    'pi.philhealth',
                    'pi.sss',
                    'pi.tin_no',
                    'pi.nationality',
                    'pi.religion',
                    'pi.marital_status',
                    'pi.employment_of_spouse',
                    'pi.children',
                    'pr.address',
                    'pr.country',
                    'pr.state',
                    'pr.pin_code',
                    'pr.phone_number',
                    'pr.reports_to',
                    'departments.department as department',
                    'designations.designation as designation',
                    'ue.name_primary',
                    'ue.relationship_primary',
                    'ue.phone_primary',
                    'ue.phone_2_primary',
                    'ue.name_secondary',
                    'ue.relationship_secondary',
                    'ue.phone_secondary',
                    'ue.phone_2_secondary'
                )
                ->where('employees.id', $id)
                ->first();

            // Fetch all uploaded requirements by this employee
            $uploadedRequirements = Requirement::where('employee_id', $employee->id)
            ->get()
            ->keyBy('title');

            $reportToList = DB::table('employees')->select('id', 'name')->get();

            // Pass data to the view
            return view('employees.employeeprofile', compact('employee', 'reportToList', 'uploadedRequirements'));

        } catch (\Exception $e) {
            \Log::error('Error loading employee profile: ' . $e->getMessage());
            flash()->error('Failed to load employee profile: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function profileInformation(Request $request)
    {
        DB::beginTransaction();

        try {
            // ✅ Get employee record
            $employee = Employee::findOrFail($request->employee_id);

            // ✅ Handle profile picture upload
            if ($request->hasFile('images')) {
                $image      = $request->file('images');
                $image_name = uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('assets/employeepic'), $image_name);

                // Save new image to employee
                $employee->profile_picture = $image_name;
                $employee->save(); // << Important: Save changes to employee
            }

            // ✅ Save/Update Profile Information
            $information = ProfileInformation::updateOrCreate(
                ['user_id' => $request->user_id]
            );

            $information->name         = $request->name;
            $information->user_id      = $request->user_id;
            $information->email        = $request->email;
            $information->birth_date   = $request->birthDate;
            $information->gender       = $request->gender;
            $information->address      = $request->address;
            $information->state        = $request->state;
            $information->country      = $request->country;
            $information->pin_code     = $request->pin_code;
            $information->phone_number = $request->phone_number;
            $information->department   = $request->department;
            $information->designation  = $request->designation;
            $information->reports_to   = $request->reports_to;
            $information->save();

            DB::commit();
            flash()->success('Profile updated successfully.');
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Profile update failed: ' . $e->getMessage());
            flash()->error('Profile update failed.');
            return redirect()->back();
        }
    }

    /** Upload Requirement */
    public function uploadRequirement(Request $request)
    {
        try {
            $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'title' => 'required',
                'document' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx',
            ]);

            $employeeId = $request->employee_id;

            // Save to public/assets/admin directory
            $file = $request->file('document');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('assets/admin');
            $file->move($destinationPath, $filename);

            $filePath = 'assets/admin/' . $filename; // This will be used in the asset() call

            Requirement::updateOrCreate(
                ['employee_id' => $employeeId, 'title' => $request->title],
                [
                    'file_path' => $filePath,
                    'description' => $request->description,
                    'expires_at' => $request->expires_at,
                    'uploaded_at' => now(),
                ]
            );

            flash()->success('Requirement uploaded successfully.');
            return redirect()->back();

        } catch (\Exception $e) {
            \Log::error('Failed to upload requirement: ' . $e->getMessage());
            flash()->error('Failed to upload requirement. Please try again.');
            return redirect()->back();
        }
    }

    //Add schedule per employee
    public function store(Request $request)
    {
        DB::beginTransaction();
    
        try {
            $request->validate([
                'employee_id' => 'required|exists:users,user_id', // This is user_id like 'ESGROUP-0001'
                'month'       => 'required|date_format:Y-m',
                'start_time'  => 'required|date_format:H:i',
                'end_time'    => 'required|date_format:H:i',
            ]);
    
            // Find employee by user_id (string)
            $employee = Employee::where('employee_id', $request->employee_id)->first();
    
            if (!$employee) {
                throw new \Exception('Employee not found for user_id: ' . $request->employee_id);
            }
    
            $startOfMonth = Carbon::parse($request->month)->startOfMonth();
            $endOfMonth = Carbon::parse($request->month)->endOfMonth();
    
            for ($date = $startOfMonth; $date->lte($endOfMonth); $date->addDay()) {
                // Optional: Skip weekends
                // if ($date->isWeekend()) continue;
    
                EmployeeSchedule::updateOrCreate([
                    'employee_id' => $employee->id,
                    'work_date' => $date->toDateString(),
                ], [
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                ]);
            }
    
            DB::commit();
            flash()->success('Updated schedule successfully :)');
            return redirect()->route('all/employee/card');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating schedule: ' . $e->getMessage());
            flash()->error('Schedule update failed :(');
            return redirect()->back();
        }
    }

    public function cardAllEmployee(Request $request)
    {
        try {
            $employees = DB::table('employees')
                ->leftJoin('departments', 'employees.department_id', 'departments.id')
                ->leftJoin('designations', 'employees.designation_id', 'designations.id')
                ->select(
                    'employees.id',
                    'employees.name',
                    'employees.email',
                    'employees.phone',
                    'employees.birth_date',
                    'employees.gender',
                    'employees.employee_id',
                    'employees.company',
                    'employees.garage',
                    'employees.date_hired',
                    'employees.end_date',
                    'employees.status',
                    'employees.profile_picture',
                    'departments.department as department',
                    'designations.designation as designation'
                )
                ->get();

            $departments = department::all();
            $designations = designation::all();
            $approvals = DB::table('employee_view_approvals')
                ->where('requested_by', Auth::id())
                ->where('status', 'approved')
                ->where('approved_until', '>=', now())
                ->pluck('employee_id')
                ->toArray();

            return view('employees.allemployeecard', compact(
                'employees',
                'departments',
                'designations',
                'approvals'
            ));
        } catch (\Exception $e) {
            Log::error("Error in cardAllEmployee: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load employee cards.');
        }
    }

    /** All Employee List */
    public function listAllEmployee()
    {
        try {
            $users = DB::table('users')
                        ->join('employees','users.user_id', 'employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->get();
            $userList = DB::table('users')->get();
            $permission_lists = DB::table('permission_lists')->get();

            return view('employees.employeelist',compact('users','userList','permission_lists'));
        } catch (\Exception $e) {
            Log::error("Error in listAllEmployee: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load employee list.');
        }
    }

    /** Save Data Employee */
    public function saveRecord(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|string|email',
            'phone'       => 'required|string|max:15',
            'birthDate'   => 'required|date',
            'gender'      => 'required|string|max:10',
            'employee_id' => 'required|string|max:255',
            'company'     => 'required|string|max:255',
            'department'  => 'required|exists:departments,id',
            'designation' => 'required|exists:designations,id',
            'garage'      => 'required|string|max:255',
            'date_hired'  => 'required|date',
            'status'      => 'required|string|max:50',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        DB::beginTransaction();
        try {

            $existingEmployee = Employee::where('email', $request->email)->first();
            
            if (!$existingEmployee) {
                $employee = new Employee();
                $employee->name         = $request->name;
                $employee->email        = $request->email;
                $employee->birth_date   = $request->birthDate;
                $employee->gender       = $request->gender;
                $employee->employee_id  = $request->employee_id;
                $employee->company      = $request->company;
                $employee->phone        = $request->phone;
                $employee->date_hired   = $request->date_hired; 
                $employee->status       = $request->status; 
                $employee->department_id = $request->department; 
                $employee->designation_id = $request->designation; 
                $employee->garage       = $request->garage;

                if ($request->hasFile('profile_picture')) {
                    $file = $request->file('profile_picture');
                    $filename = time().'_'.uniqid().'_'.$file->getClientOriginalName();
                    $file->move(public_path('assets/employeepic'), $filename);
                    $employee->profile_picture = $filename; 
                } else {

                    $employee->profile_picture = 'default.png'; 
                }

                $employee->save();

                DB::commit();
                flash()->success('Add new employee successfully :)');
                return redirect()->route('all/employee/card');
            } else {
                DB::rollback();
                flash()->error('Employee already exists. ');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Failed to add new employee: ' . $e->getMessage());
            return redirect()->back();
        }
    }
    
    /** Edit Record */
    public function viewRecord($employee_id)
    {
        try {
            $employees = DB::table('employees')
                ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
                ->leftJoin('designations', 'employees.designation_id', '=', 'designations.id')
                ->select(
                    'employees.*',
                    'departments.department as department',
                    'designations.designation as designation'
                )
                ->where('employees.employee_id', $employee_id)
                ->first();

            $permission = DB::table('module_permissions')
                ->where('employee_id', $employee_id)
                ->get();

            $departments = department::all();
            $designations = designation::all();

            return view('employees.edit.editemployee', compact('employees', 'permission', 'departments', 'designations'));
        } catch (\Exception $e) {
            Log::error("Error in viewRecord: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load employee record.');
        }
    }

    /** Update Record */
    public function updateRecord( Request $request)
    {
        $request->validate([
            'id' => 'required|exists:employees,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'birth_date' => 'required|date',
            'gender' => 'required|string',
            'employee_id' => 'required|string|max:100',
            'company' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'department' => 'required|exists:departments,id', 
            'designation' => 'required|exists:designations,id',
            'garage' => 'required|string|max:255',
            'date_hired' => 'required|date',
            'status' => 'required|string',
        ]);

        DB::beginTransaction();
        try {

            // update table Employee
            $updateEmployee = [
                'id'              => $request->id,
                'name'            => $request->name,
                'email'           => $request->email,
                'birth_date'      => $request->birth_date,
                'gender'          => $request->gender,
                'employee_id'     => $request->employee_id,
                'company'         => $request->company,
                'phone'           => $request->phone,
                'department_id'   => $request->department,
                'designation_id'  => $request->designation,
                'garage'          => $request->garage,
                'date_hired'      => $request->date_hired,
                'status'          => $request->status,
            ];

            // update table user
            $updateUser = [
                'id'=>$request->id,
                'name'=>$request->name,
                'email'=>$request->email,
                'status'=>$request->status,
            ];

            User::where('id',$request->id)->update($updateUser);
            Employee::where('id',$request->id)->update($updateEmployee);
        
            DB::commit();
            flash()->success('Updated record successfully :)');
            return redirect()->route('all/employee/card');
        }catch(\Exception $e){
            DB::rollback();
            Log::error('Error updating record: ' . $e->getMessage());
            flash()->error('Updated record fail :)');
            return redirect()->back();
        }
    }

    /** Delete Record */
    public function deleteRecord($employee_id)
    {
        DB::beginTransaction();
        try{
            Employee::where('employee_id',$employee_id)->delete();
            module_permission::where('employee_id',$employee_id)->delete();

            DB::commit();
            flash()->success('Delete record successfully :)');
            return redirect()->route('all/employee/card');
        }catch(\Exception $e){
            DB::rollback();
            flash()->error('Delete record fail :)');
            return redirect()->back();
        }
    }

    /** employee search */
    public function employeeSearch(Request $request)
    {
        $users = DB::table('users')
                    ->join('employees','users.user_id','employees.employee_id')
                    ->select('users.*','employees.birth_date','employees.gender','employees.company')->get();
        $permission_lists = DB::table('permission_lists')->get();
        $userList = DB::table('users')->get();

        // search by id
        if($request->employee_id)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')->get();
        }
        // search by name
        if($request->name)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->where('users.name','LIKE','%'.$request->name.'%')->get();
        }
        // search by name
        if($request->position)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->where('users.position','LIKE','%'.$request->position.'%')->get();
        }

        // search by name and id
        if($request->employee_id && $request->name)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->get();
        }
        // search by position and id
        if($request->employee_id && $request->position)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date', 'employees.gender', 'employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->where('users.position','LIKE','%'.$request->position.'%')->get();
        }
        // search by name and position
        if($request->name && $request->position)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->where('users.position','LIKE','%'.$request->position.'%')->get();
        }
        // search by name and position and id
        if($request->employee_id && $request->name && $request->position)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->where('users.position','LIKE','%'.$request->position.'%')->get();
        }
        return view('employees.allemployeecard',compact('users','userList','permission_lists'));
    }

    /** List Search */
    public function employeeListSearch(Request $request)
    {
        $users = DB::table('users')
                    ->join('employees','users.user_id','employees.employee_id')
                    ->select('users.*','employees.birth_date','employees.gender','employees.company')->get(); 
        $permission_lists = DB::table('permission_lists')->get();
        $userList         = DB::table('users')->get();

        // search by id
        if($request->employee_id)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')->get();
        }

        // search by name
        if($request->name)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->where('users.name','LIKE','%'.$request->name.'%')->get();
        }

        // search by name
        if($request->position)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->where('users.position','LIKE','%'.$request->position.'%')->get();
        }

        // search by name and id
        if($request->employee_id && $request->name)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->where('users.name','LIKE','%'.$request->name.'%')->get();
        }

        // search by position and id
        if($request->employee_id && $request->position)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->where('users.position','LIKE','%'.$request->position.'%')->get();
        }

        // search by name and position
        if($request->name && $request->position)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->where('users.position','LIKE','%'.$request->position.'%')->get();
        }

        // search by name and position and id
        if($request->employee_id && $request->name && $request->position)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->where('users.position','LIKE','%'.$request->position.'%')->get();
        }
        return view('employees.employeelist',compact('users','userList','permission_lists'));
    }

    /** Page Departments */
    public function index()
    {
        $departments = DB::table('departments')->get();
        return view('employees.departments',compact('departments'));
    }

    /** Save Record of Deparments */
    public function saveRecordDepartment(Request $request)
    {
        $request->validate([
            'department' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {

            $department = department::where('department',$request->department)->first();
            if ($department === null)
            {
                $department = new department;
                $department->department = $request->department;
                $department->save();
    
                DB::commit();
                flash()->success('Add new department successfully :)');
                return redirect()->back();
            } else {
                DB::rollback();
                flash()->error('Add new department exits :)');
                return redirect()->back();
            }
        } catch(\Exception $e) {
            DB::rollback();
            flash()->error('Add new department fail :)');
            return redirect()->back();
        }
    }

    /** Update Record */
    public function updateRecordDepartment(Request $request)
    {
        DB::beginTransaction();
        try {
            // update table departments
            $department = [
                'id'         => $request->id,
                'department' => $request->department,
            ];
            department::where('id',$request->id)->update($department);
            DB::commit();
            flash()->success('Updated record successfully :)');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            flash()->success('Updated record fail :)');
            return redirect()->back();
        }
    }

    /** Delete Record */
    public function deleteRecordDepartment(Request $request) 
    {
        try {
            department::destroy($request->id);
            flash()->success('Department deleted successfully :)');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            flash()->error('Department delete fail :)');
            return redirect()->back();
        }
    }

    /** Page Designations Area */
    public function designationsIndex()
    {
        $designation = DB::table('designations')->get();
        return view('employees.designations', compact('designation'));
    }

    /** Update Record */
    public function updateRecordDesignations(Request $request)
       {
            $request->validate([
                'id' => 'required|exists:designations,id', // validate id exists
                'designation' => 'required|string|max:255',
            ]);

           DB::beginTransaction();
           try {
               // update table designations
               $designation = [
                   'designation' => $request->designation,
               ];
               designation::where('id',$request->id)->update($designation);

               DB::commit();
               flash()->success('Updated record successfully :)');
               return redirect()->back();
           } catch(\Exception $e) {
               DB::rollback();
               flash()->success('Updated record fail :)');
               return redirect()->back();
           }
       }
   
    /** Delete Record */
    public function deleteRecordDesignations(Request $request) 
       {
           try {
               Designation::destroy($request->id);
               flash()->success('Designation deleted successfully :)');
               return redirect()->back();
           } catch(\Exception $e) {
               DB::rollback();
               flash()->error('Designation delete fail :)');
               return redirect()->back();
           }
       }
   

    /** Page Time Sheet */
    public function timeSheetIndex()
    {
        return view('employees.timesheet');
    }

    /** Page Overtime */
    public function overTimeIndex()
    {
        return view('employees.overtime');
    }

    public function AdminScheduleSave(Request $request)
    {
        foreach ($request->schedule as $entry) {
            EmployeeSchedule::updateOrCreate(
                [
                    'employee_id' => $request->employee_id,
                    'work_date' => $entry['date'],
                ],
                [
                    'start_time' => $entry['start_time'],
                    'end_time' => $entry['end_time'],
                ]
            );
        }

        return response()->json(['message' => 'Schedule saved successfully.']);
    }

}
