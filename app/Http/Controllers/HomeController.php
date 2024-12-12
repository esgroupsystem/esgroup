<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Models\product_total_stocks;
use App\Models\PurchaseTransaction;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\RequestOrder;
use App\Models\ProductCategory;
use App\Models\Supplier;
use App\Models\ProductBrand;
use App\Models\ProductUnit;
use App\Models\Products;
use App\Models\Garage;
use Carbon\Carbon;
use PDF;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
    /** Main Dashboard */
    public function index()
    {
        // Count active users
        $activeUserCount = User::where('status', 'active')->count();

        // Pass the active user count to the view
        return view('dashboard.dashboard', compact('activeUserCount'));
    }
    
    /** Employee Dashboard */
    public function emDashboard()
    {
        $dt        = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();
        return view('dashboard.emdashboard',compact('todayDate'));
    }

    public function stockDashboard(Request $request)
    {
        $tab = $request->get('tab', 'mirasol'); // Default to 'mirasol'
    
        // Fetch stocks based on tab
        $stocks = collect();
        if ($tab === 'mirasol') {
            $stocks = ProductCategory::with(['products.productTotalStocks'])->get();
        } elseif ($tab === 'balintawak') {
            $stocks = ProductCategory::with(['products.productStockBalintawak'])->get();
        } elseif ($tab === 'vgc') {
            $stocks = ProductCategory::with(['products.productStockVgc'])->get();
        }
    
        $todayDate = Carbon::now()->toDayDateTimeString(); // Current date
    
        return view('dashboard.stockdashboard', compact('stocks', 'todayDate', 'tab'));
    }
    

    /** Generate PDF */
    public function generatePDF(Request $request)
    {
        // $data = ['title' => 'Welcome to ItSolutionStuff.com'];
        // $pdf = PDF::loadView('payroll.salaryview', $data);
        // return $pdf->download('text.pdf');
        // selecting PDF view
        $pdf = PDF::loadView('payroll.salaryview');
        // download pdf file
        return $pdf->download('pdfview.pdf');
    }
}
