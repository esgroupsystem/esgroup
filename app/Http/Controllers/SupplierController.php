<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use Carbon\Carbon;
use Response;
use DB;

class SupplierController extends Controller
{
   /** Display */
   public function supplierIndex()
   {
       $supplierview = Supplier::all();

       return view('Supplier.supplier', compact('supplierview'));
   }

   public function saveSupplier(Request $request){

       $request->validate([
          'supplier_name',   
       ]);

       DB::beginTransaction();
        try{
            Supplier::create([
            'supplier_name'        => $request->supplier_name,
            'supplier_status'      => 'Active',
            ]);

            DB::commit();
            flash()->success('Created new supplier successfully :)');
            return redirect()->back();
        } catch ( \Exception $e ){
            DB::rollback();
            flash()->error('Failed to add supplier :(');
            return redirect()->back();
       }
   }
}
