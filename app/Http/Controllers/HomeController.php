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

    public function stockMirasol(Request $request)
    {
        $categories = ProductCategory::with([
            'products' => function ($query) {
                $query->leftJoin('product_brands', 'products.product_brand', '=', 'product_brands.id')
                      ->leftJoin('product_units', 'products.product_unit', '=', 'product_units.id')
                      ->select(
                          'products.*',
                          'product_brands.brand_name as brand_name',
                          'product_units.unit_name as unit_name'
                      )
                      ->with('productTotalStocks');
            }
        ])->get();
    
        return view('purchase.stockmirasol', compact('categories'));
    }
    
    public function stockBalintawak(Request $request)
    {
        $categories = ProductCategory::with([
            'products' => function ($query) {
                $query->leftJoin('product_brands', 'products.product_brand', '=', 'product_brands.id')
                      ->leftJoin('product_units', 'products.product_unit', '=', 'product_units.id')
                      ->select(
                          'products.*',
                          'product_brands.brand_name as brand_name',
                          'product_units.unit_name as unit_name'
                      )
                      ->with('productStockBalintawak');
            }
        ])->get();
        
        return view('purchase.stockbalintawak', compact('categories'));
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
