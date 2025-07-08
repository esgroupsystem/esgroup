<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
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
use App\Models\Joborder;
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

    public function getPhilippineHolidaysForMonth($year, $month)
    {
        $apiKey = env('CALENDARIFIC_API_KEY'); // store in .env
        $response = Http::get('https://calendarific.com/api/v2/holidays', [
            'api_key' => $apiKey,
            'country' => 'PH',
            'year' => $year,
        ]);

        $holidays = collect($response['response']['holidays']);

        return $holidays->filter(function ($holiday) use ($month) {
            return date('m', strtotime($holiday['date']['iso'])) == $month;
        });
    }
    
    /** Employee Dashboard */
    public function emDashboard()
    {
        $dt        = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();
        return view('dashboard.emdashboard',compact('todayDate'));
    }

    public function jobordersDashboard()
    {
        $todayDate = Carbon::today()->format('Y-m-d');
        $yesterdayDate = Carbon::yesterday()->format('Y-m-d');

        $todayJobs = Joborder::whereDate('job_date_filled', $todayDate)->get();
        $yesterdayJobs = Joborder::whereDate('job_date_filled', $yesterdayDate)->get();

        $fromDate = Carbon::today()->subDays(7);
        $toDate = Carbon::today()->subDays(3);

        $pastThreeToSevenDaysJobs = Joborder::whereBetween(DB::raw('DATE(job_date_filled)'), [
            $fromDate->format('Y-m-d'),
            $toDate->format('Y-m-d')
        ])->get();

        $users = User::whereIn('role_name', ['IT', 'Safety Office', 'Admin'])->get();

        // ✅ Add these
        $totalTasks = Joborder::count();
        $pendingTasks = Joborder::where('job_status', 'New')->count();
        $completedTasks = Joborder::where('job_status', 'Completed')->count();

        $year = now()->year;
        $month = now()->format('m');

        $holidaysThisMonth = $this->getPhilippineHolidaysForMonth($year, $month);

        return view('dashboard.joborders', compact(
            'todayJobs',
            'yesterdayJobs',
            'pastThreeToSevenDaysJobs',
            'users',
            'todayDate',
            'totalTasks',
            'pendingTasks',
            'completedTasks',
            'holidaysThisMonth'
        ));
    }

    public function jobordersRefresh()
    {
        $todayDate = Carbon::today()->format('Y-m-d');
        $yesterdayDate = Carbon::yesterday()->format('Y-m-d');

        // Fetch jobs
        $todayJobs = Joborder::whereDate('job_date_filled', $todayDate)->get();
        $yesterdayJobs = Joborder::whereDate('job_date_filled', $yesterdayDate)->get();

        $fromDate = Carbon::today()->subDays(7);
        $toDate = Carbon::today()->subDays(3);
        $pastThreeToSevenDaysJobs = Joborder::whereBetween(DB::raw('DATE(job_date_filled)'), [
            $fromDate->format('Y-m-d'),
            $toDate->format('Y-m-d')
        ])->get();

        // ✅ Ticket Counters
        $totalTasks = Joborder::count();
        $pendingTasks = Joborder::where('job_status', 'New')->count(); // or 'Pending' depending on your label
        $completedTasks = Joborder::where('job_status', 'Completed')->count();

        $year = now()->year;
        $month = now()->format('m');

        $holidaysThisMonth = $this->getPhilippineHolidaysForMonth($year, $month);

        return view('dashboard.joborders_partial', compact(
            'todayJobs',
            'yesterdayJobs',
            'pastThreeToSevenDaysJobs',
            'totalTasks',
            'pendingTasks',
            'completedTasks',
            'holidaysThisMonth'
        ));
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
