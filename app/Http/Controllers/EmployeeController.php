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
use Carbon\Carbon;
use DB;

class EmployeeController extends Controller
{
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

    
        return view('employees.allemployeecard', compact(
            'employees',
            'departments',
            'designations'
        ));
    }    

    /** All Employee List */
    public function listAllEmployee()
    {
        $users = DB::table('users')
                    ->join('employees','users.user_id', 'employees.employee_id')
                    ->select('users.*','employees.birth_date','employees.gender','employees.company')
                    ->get();
        $userList = DB::table('users')->get();
        $permission_lists = DB::table('permission_lists')->get();
        return view('employees.employeelist',compact('users','userList','permission_lists'));
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
                    'pi.passport_no',
                    'pi.passport_expiry_date',
                    'pi.tel',
                    'pi.nationality',
                    'pi.religion',
                    'pi.marital_status',
                    'pi.employment_of_spouse',
                    'pi.children',
                    'pr.birth_date',
                    'pr.gender',
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

            $reportToList = DB::table('employees')->select('id', 'name')->get();

            return view('employees.employeeprofile', compact('employee', 'reportToList'));

        } catch (\Exception $e) {
            \Log::error('Error loading employee profile: ' . $e->getMessage());
            flash()->error('Failed to load employee profile: ' . $e->getMessage());
            return redirect()->back();
        }
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
}
