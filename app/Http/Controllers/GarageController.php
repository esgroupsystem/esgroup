<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Garage;
use Carbon\Carbon;
use Response;
use DB;

class GarageController extends Controller
{
    /** Display */
    public function garageIndex()
    {
        $garageview = Garage::all();

        return view('garage.garage', compact('garageview'));
    }

    public function saveGarage(Request $request){

        $request->validate([
           'garage_name',   
        ]);

        DB::beginTransaction();
        try{

            Garage::create([
            'garage_name'        => $request->garage_name,
            'garage_status'      => 'Active',

            ]);

            DB::commit();
            flash()->success('Created new garage successfully :)');
            return redirect()->back();
        } catch ( \Exception $e ){
            DB::rollback();
            flash()->error('Failed to add garage :(');
            return redirect()->back();
        }


    }
}
