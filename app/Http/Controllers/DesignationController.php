<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\designation;
use Carbon\Carbon;
use Toastr;
use Session;
use Hash;
use Auth;
use DB;

class DesignationController extends Controller
{
    /** Saving designation */
    function saveDesignation(Request $request)
    {
        
        $request->validate([
            'designation' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {

            $designation = designation::where('designation',$request->designation)->first();
            if ($designation === null)
            {
                $designation = new designation;
                $designation->designation = $request->designation;
                $designation->save();
    
                DB::commit();
                flash()->success('Add new designation successfully :)');
                return back();
            } else {
                DB::rollback();
                flash()->error('Add new designation exits :)');
                return redirect()->back();
            }
        } catch(\Exception $e) {
            DB::rollback();
            flash()->error('Add new designation fail :)');
            return redirect()->back();
        }
    }
}
