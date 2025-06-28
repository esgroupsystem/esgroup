
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ----------- Public Routes -------------- //
Route::get('/', function () {
    return view('main.landing');
});

// --------- Authenticated Routes ---------- //
Route::middleware('auth')->group(function () {
    Route::get('home', function () {
        return view('home');
    });
});

Auth::routes();

Route::group(['namespace' => 'App\Http\Controllers\Auth'],function()
{
    // -----------------------------login--------------------------------------//
    Route::controller(LoginController::class)->group(function () {
        Route::get('/main-index', 'mainpages')->name('mainPages');
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'authenticate');
        Route::get('/logout', 'logout')->name('logout');
    });

    // ------------------------------ register ----------------------------------//
    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'register')->name('register');
        Route::post('/register','storeUser')->name('register');    
    });

    // ----------------------------- forget password ----------------------------//
    Route::controller(ForgotPasswordController::class)->group(function () {
        Route::get('forget-password', 'getEmail')->name('forget-password');
        Route::post('forget-password', 'postEmail')->name('forget-password');    
    });

    // ----------------------------- reset password -----------------------------//
    Route::controller(ResetPasswordController::class)->group(function () {
        Route::get('reset-password/{token}', 'getPassword');
        Route::post('reset-password', 'updatePassword');    
    });
});

Route::group(['namespace' => 'App\Http\Controllers'],function()
{
    // ----------------------------- main dashboard ------------------------------//
    Route::controller(HomeController::class)->group(function () {
        Route::get('/home', 'index')->name('home');
        Route::get('em/dashboard', 'emDashboard')->name('em/dashboard');
        Route::get('stock/dashboard', 'stockMirasol')->name('stock/dashboard');
    });

    // ----------------------------- lock screen --------------------------------//
    Route::controller(LockScreen::class)->group(function () {
        Route::get('lock_screen','lockScreen')->middleware('auth')->name('lock_screen');
        Route::post('unlock', 'unlock')->name('unlock');    
    });

    // -----------------------------settings-------------------------------------//
    Route::controller(SettingController::class)->group(function () {
        Route::get('company/settings/page', 'companySettings')->middleware('auth')->name('company/settings/page'); /** index page */
        Route::post('company/settings/save', 'saveRecord')->middleware('auth')->name('company/settings/save'); /** save record or update */
        Route::get('roles/permissions/page', 'rolesPermissions')->middleware('auth')->name('roles/permissions/page');
        Route::post('roles/permissions/save', 'addRecord')->middleware('auth')->name('roles/permissions/save');
        Route::post('roles/permissions/update', 'editRolesPermissions')->middleware('auth')->name('roles/permissions/update');
        Route::post('roles/permissions/delete', 'deleteRolesPermissions')->middleware('auth')->name('roles/permissions/delete');
        Route::get('localization/page', 'localizationIndex')->middleware('auth')->name('localization/page'); /** index page localization */
        Route::get('salary/settings/page', 'salarySettingsIndex')->middleware('auth')->name('salary/settings/page'); /** index page salary settings */
        Route::get('email/settings/page', 'emailSettingsIndex')->middleware('auth')->name('email/settings/page'); /** index page email settings */
    });

    // ----------------------------- manage users -------d-----------------------//
    Route::controller(UserManagementController::class)->group(function () {
        Route::get('profile_user', 'profile')->middleware('auth')->name('profile_user');
        Route::post('profile/information/save', 'profileInformation')->name('profile/information/save');
        Route::get('userManagement', 'index')->middleware('auth')->name('userManagement');
        Route::post('user/add/save', 'addNewUserSave')->name('user/add/save');
        Route::post('form/user/update', 'update')->middleware('auth')->name('user.update');
        Route::post('user/delete', 'delete')->middleware('auth')->name('user/delete');
        Route::get('change/password', 'changePasswordView')->middleware('auth')->name('change/password');
        Route::post('change/password/db', 'changePasswordDB')->name('change/password/db');
        
        Route::post('user/profile/emergency/contact/save', 'emergencyContactSaveOrUpdate')->name('user/profile/emergency/contact/save'); /** save or update emergency contact */
        Route::get('get-users-data', 'getUsersData')->name('get-users-data'); /** get all data users */
        
    });

    // ----------------------------------manage designation-------------------------//
    Route::controller(DesignationController::class)->group(function () {
        Route::post('userDesignation', 'saveDesignation')->name('userDesignation');

        
    });

    //========================== IT DEPARTMENT JOBORDERS ====================//

    // ---------------------- IT JobOrders  -----------------------//
    Route::controller(JobOrderController::class)->group(function () {
        Route::get('form/joborders/page', 'jobordersIndex')->middleware('auth')->name('form/joborders/page');
        Route::get('create/joborders/page', 'createJobOrderIndex')->middleware('auth')->name('create/joborders/page');  
        Route::get('view/details/{id}', 'viewSpecificDetails')->middleware('auth')->name('view/details');   
        Route::post('form/joborders/save', 'saveRecordJoborders')->middleware('auth')->name('form/joborders/save');    
        Route::post('form/joborders/update', 'updateRecordJoborders')->middleware('auth')->name('form/joborders/update');    
        Route::post('form/joborders/delete', 'deleteRecordJoborders')->middleware('auth')->name('form/joborders/delete');
        Route::post('/saving', 'Job_Files')->middleware('auth')->name('job.files');
        Route::post('joborders/delete/{id}', 'deleteVideoFiles')->middleware('auth')->name('joborders.delete');
    });

    // --------------------------------- job ---------------------------------//
    Route::controller(JobController::class)->group(function () {
        Route::get('form/job/list','categoryIndex')->name('form/job/list');
        Route::get('form/job/view/{id}', 'jobView');
        Route::get('user/dashboard/index', 'userDashboard')->middleware('auth')->name('user/dashboard/index');    
        Route::get('jobs/dashboard/index', 'jobsDashboard')->middleware('auth')->name('jobs/dashboard/index');    
        Route::get('user/dashboard/all', 'userDashboardAll')->middleware('auth')->name('user/dashboard/all');    
        Route::get('user/dashboard/save', 'userDashboardSave')->middleware('auth')->name('user/dashboard/save');
        Route::get('user/dashboard/applied/jobs', 'userDashboardApplied')->middleware('auth')->name('user/dashboard/applied/jobs');
        Route::get('user/dashboard/interviewing', 'userDashboardInterviewing')->middleware('auth')->name('user/dashboard/interviewing');
        Route::get('user/dashboard/offered/jobs', 'userDashboardOffered')->middleware('auth')->name('user/dashboard/offered/jobs');
        Route::get('user/dashboard/visited/jobs', 'userDashboardVisited')->middleware('auth')->name('user/dashboard/visited/jobs');
        Route::get('user/dashboard/archived/jobs', 'userDashboardArchived')->middleware('auth')->name('user/dashboard/archived/jobs');
        Route::get('jobs', 'Jobs')->middleware('auth')->name('jobs');
        Route::get('job/applicants/{job_title}', 'jobApplicants')->middleware('auth');
        Route::get('job/details/{id}', 'jobDetails')->middleware('auth');
        Route::get('cv/download/{id}', 'downloadCV')->middleware('auth');
        
        Route::post('form/jobs/save', 'JobsSaveRecord')->name('form/jobs/save');
        Route::post('form/apply/job/save', 'applyJobSaveRecord')->name('form/apply/job/save');
        Route::post('form/apply/job/update', 'applyJobUpdateRecord')->name('form/apply/job/update');

        Route::get('page/manage/resumes', 'manageResumesIndex')->middleware('auth')->name('page/manage/resumes');
        Route::get('page/shortlist/candidates', 'shortlistCandidatesIndex')->middleware('auth')->name('page/shortlist/candidates');
        Route::get('page/interview/questions', 'interviewQuestionsIndex')->middleware('auth')->name('page/interview/questions'); // view page
        Route::post('save/category', 'categorySave')->name('save/category'); // save record category
        Route::post('save/questions', 'questionSave')->name('save/questions'); // save record questions
        Route::post('questions/update', 'questionsUpdate')->name('questions/update'); // update question
        Route::post('questions/delete', 'questionsDelete')->middleware('auth')->name('questions/delete'); // delete question
        Route::get('page/offer/approvals', 'offerApprovalsIndex')->middleware('auth')->name('page/offer/approvals');
        Route::get('page/experience/level', 'experienceLevelIndex')->middleware('auth')->name('page/experience/level');
        Route::get('page/candidates', 'candidatesIndex')->middleware('auth')->name('page/candidates');
        Route::get('page/schedule/timing', 'scheduleTimingIndex')->middleware('auth')->name('page/schedule/timing');
        Route::get('page/aptitude/result', 'aptituderesultIndex')->middleware('auth')->name('page/aptitude/result');

        Route::post('jobtypestatus/update', 'jobTypeStatusUpdate')->name('jobtypestatus/update'); // update status job type ajax

    });
    
    // ---------------------------- form employee ---------------------------//
    Route::controller(EmployeeController::class)->group(function () {
        Route::get('all/employee/card', 'cardAllEmployee')->middleware('auth')->name('all/employee/card');
        Route::get('all/employee/list', 'listAllEmployee')->middleware('auth')->name('all/employee/list');
        Route::post('all/employee/save', 'saveRecord')->middleware('auth')->name('all/employee/save');
        Route::get('all/employee/view/edit/{employee_id}', 'viewRecord');
        Route::post('all/employee/update', 'updateRecord')->middleware('auth')->name('all/employee/update');
        Route::get('all/employee/delete/{employee_id}', 'deleteRecord')->middleware('auth');
        Route::post('all/employee/search', 'employeeSearch')->name('all/employee/search');
        Route::post('all/employee/list/search', 'employeeListSearch')->name('all/employee/list/search');
        Route::post('schedule/employee', 'store')->middleware('auth')->name('schedule.store');

        Route::get('form/departments/page', 'index')->middleware('auth')->name('form/departments/page');    
        Route::post('form/departments/save', 'saveRecordDepartment')->middleware('auth')->name('form/departments/save');    
        Route::post('form/department/update', 'updateRecordDepartment')->middleware('auth')->name('form/department/update');    
        Route::post('form/department/delete', 'deleteRecordDepartment')->middleware('auth')->name('form/department/delete');  
        // Route::post('form/department/view', 'getAllActiveDepartment')->middleware('auth')->name('form/department/view');  
        
        Route::get('form/designations/page', 'designationsIndex')->middleware('auth')->name('form/designations/page');    
        Route::post('form/designations/save', 'saveRecordDesignations')->middleware('auth')->name('form/designations/save');    
        Route::post('form/designations/update', 'updateRecordDesignations')->middleware('auth')->name('form/designations/update');    
        Route::post('form/designations/delete', 'deleteRecordDesignations')->middleware('auth')->name('form/designations/delete');
        
        Route::get('form/timesheet/page', 'timeSheetIndex')->middleware('auth')->name('form/timesheet/page');    
        Route::post('form/timesheet/save', 'saveRecordTimeSheets')->middleware('auth')->name('form/timesheet/save');    
        Route::post('form/timesheet/update', 'updateRecordTimeSheets')->middleware('auth')->name('form/timesheet/update');    
        Route::post('form/timesheet/delete', 'deleteRecordTimeSheets')->middleware('auth')->name('form/timesheet/delete');
        
        Route::get('form/overtime/page', 'overTimeIndex')->middleware('auth')->name('form/overtime/page');    
        Route::post('form/overtime/save', 'saveRecordOverTime')->middleware('auth')->name('form/overtime/save');    
        Route::post('form/overtime/update', 'updateRecordOverTime')->middleware('auth')->name('form/overtime/update');    
        Route::post('form/overtime/delete', 'deleteRecordOverTime')->middleware('auth')->name('form/overtime/delete');  
    });

    // ------------------------- profile employee --------------------------//
    Route::controller(EmployeeController::class)->group(function () {
        Route::get('employee/profile/{user_id}', 'profileEmployee')->middleware('auth');
    });

    // --------------------------- form holiday ---------------------------//
    Route::middleware('auth')->group(function () {
        Route::controller(HolidayController::class)->group(function () {
            Route::get('form/holidays/new', 'holiday')->name('form/holidays/new');
            Route::post('form/holidays/save', 'saveRecord')->name('form/holidays/save');
            Route::post('form/holidays/update', 'updateRecord')->name('form/holidays/update');    
            Route::post('form/holidays/delete', 'deleteRecord')->name('form/holidays/delete');    
        });
    });

    // -------------------------- form leaves ----------------------------//
    Route::controller(LeavesController::class)->group(function () {
        Route::get('form/leaves/new', 'leaves')->middleware('auth')->name('form/leaves/new');
        Route::get('form/leavesemployee/new', 'leavesEmployee')->middleware('auth')->name('form/leavesemployee/new');
        Route::post('form/leaves/save', 'saveRecord')->middleware('auth')->name('form/leaves/save');
        Route::post('form/leaves/edit', 'editRecordLeave')->middleware('auth')->name('form/leaves/edit');
        Route::post('form/leaves/edit/delete','deleteLeave')->middleware('auth')->name('form/leaves/edit/delete');    
    });

    // ------------------------ form attendance  -------------------------//
    Route::controller(LeavesController::class)->group(function () {
        Route::get('form/leavesettings/page', 'leaveSettings')->middleware('auth')->name('form/leavesettings/page');
        Route::get('attendance/page', 'attendanceIndex')->middleware('auth')->name('attendance/page');
        Route::get('attendance/employee/page', 'AttendanceEmployee')->middleware('auth')->name('attendance/employee/page');
        Route::get('form/shiftscheduling/page', 'shiftScheduLing')->middleware('auth')->name('form/shiftscheduling/page');
        Route::get('form/shiftlist/page', 'shiftList')->middleware('auth')->name('form/shiftlist/page');    
    });

    // ------------------------ form payroll  ----------------------------//
    Route::controller(PayrollController::class)->group(function () {
        Route::get('form/salary/page', 'salary')->middleware('auth')->name('form/salary/page');
        Route::post('form/salary/save','saveRecord')->middleware('auth')->name('form/salary/save');
        Route::post('form/salary/update', 'updateRecord')->middleware('auth')->name('form/salary/update');
        Route::post('form/salary/delete', 'deleteRecord')->middleware('auth')->name('form/salary/delete');
        Route::get('form/salary/view/{user_id}', 'salaryView')->middleware('auth');
        Route::get('form/payroll/items', 'payrollItems')->middleware('auth')->name('form/payroll/items');    
        Route::get('extra/report/pdf', 'reportPDF')->middleware('auth');    
        Route::get('extra/report/excel', 'reportExcel')->middleware('auth');    
    });

    // ---------------------------- reports  ----------------------------//
    Route::controller(ExpenseReportsController::class)->group(function () {
        Route::get('form/expense/reports/page', 'index')->middleware('auth')->name('form/expense/reports/page');
        Route::get('form/invoice/reports/page', 'invoiceReports')->middleware('auth')->name('form/invoice/reports/page');
        Route::get('form/daily/reports/page', 'dailyReport')->middleware('auth')->name('form/daily/reports/page');
        Route::get('form/leave/reports/page','leaveReport')->middleware('auth')->name('form/leave/reports/page');
        Route::get('form/payments/reports/page','paymentsReportIndex')->middleware('auth')->name('form/payments/reports/page');
        Route::get('form/employee/reports/page','employeeReportsIndex')->middleware('auth')->name('form/employee/reports/page');
    });

    // --------------------------- performance  -------------------------//
    Route::controller(PerformanceController::class)->group(function () {
        Route::get('form/performance/indicator/page','index')->middleware('auth')->name('form/performance/indicator/page');
        Route::get('form/performance/page', 'performance')->middleware('auth')->name('form/performance/page');
        Route::get('form/performance/appraisal/page', 'performanceAppraisal')->middleware('auth')->name('form/performance/appraisal/page');
        Route::post('form/performance/indicator/save','saveRecordIndicator')->middleware('auth')->name('form/performance/indicator/save');
        Route::post('form/performance/indicator/delete','deleteIndicator')->middleware('auth')->name('form/performance/indicator/delete');
        Route::post('form/performance/indicator/update', 'updateIndicator')->middleware('auth')->name('form/performance/indicator/update');
        Route::post('form/performance/appraisal/save', 'saveRecordAppraisal')->middleware('auth')->name('form/performance/appraisal/save');
        Route::post('form/performance/appraisal/update', 'updateAppraisal')->middleware('auth')->name('form/performance/appraisal/update');
        Route::post('form/performance/appraisal/delete', 'deleteAppraisal')->middleware('auth')->name('form/performance/appraisal/delete');
    });

    // --------------------------- training  ----------------------------//
    Route::controller(TrainingController::class)->group(function () {
        Route::get('form/training/list/page','index')->middleware('auth')->name('form/training/list/page');
        Route::post('form/training/save', 'addNewTraining')->middleware('auth')->name('form/training/save');
        Route::post('form/training/delete', 'deleteTraining')->middleware('auth')->name('form/training/delete');
        Route::post('form/training/update', 'updateTraining')->middleware('auth')->name('form/training/update');    
    });

    // --------------------------- trainers  ----------------------------//
    Route::controller(TrainersController::class)->group(function () {
        Route::get('form/trainers/list/page', 'index')->middleware('auth')->name('form/trainers/list/page');
        Route::post('form/trainers/save', 'saveRecord')->middleware('auth')->name('form/trainers/save');
        Route::post('form/trainers/update', 'updateRecord')->middleware('auth')->name('form/trainers/update');
        Route::post('form/trainers/delete', 'deleteRecord')->middleware('auth')->name('form/trainers/delete');
    });

    // ------------------------- training type  -------------------------//
    Route::controller(TrainingTypeController::class)->group(function () {
        Route::get('form/training/type/list/page', 'index')->middleware('auth')->name('form/training/type/list/page');
        Route::post('form/training/type/save', 'saveRecord')->middleware('auth')->name('form/training/type/save');
        Route::post('form//training/type/update', 'updateRecord')->middleware('auth')->name('form//training/type/update');
        Route::post('form//training/type/delete', 'deleteTrainingType')->middleware('auth')->name('form//training/type/delete');    
    });

    // ----------------------------- sales  ----------------------------//
    Route::controller(SalesController::class)->group(function () {

        // -------------------- estimate  --------------------//
        Route::get('form/estimates/page', 'estimatesIndex')->middleware('auth')->name('form/estimates/page');
        Route::get('create/estimate/page', 'createEstimateIndex')->middleware('auth')->name('create/estimate/page');
        Route::get('edit/estimate/{estimate_number}', 'editEstimateIndex')->middleware('auth');
        Route::get('estimate/view/{estimate_number}', 'viewEstimateIndex')->middleware('auth');

        Route::post('create/estimate/save', 'createEstimateSaveRecord')->middleware('auth')->name('create/estimate/save');
        Route::post('create/estimate/update', 'EstimateUpdateRecord')->middleware('auth')->name('create/estimate/update');
        Route::post('estimate_add/delete', 'EstimateAddDeleteRecord')->middleware('auth')->name('estimate_add/delete');
        Route::post('estimate/delete', 'EstimateDeleteRecord')->middleware('auth')->name('estimate/delete');
        // ------------------------ payments  -------------------//
        Route::get('payments', 'Payments')->middleware('auth')->name('payments');
        Route::get('expenses/page', 'Expenses')->middleware('auth')->name('expenses/page');
        Route::post('expenses/save', 'saveRecord')->middleware('auth')->name('expenses/save');
        Route::post('expenses/update', 'updateRecord')->middleware('auth')->name('expenses/update');
        Route::post('expenses/delete', 'deleteRecord')->middleware('auth')->name('expenses/delete');
        // ---------------------- search expenses  ---------------//
        Route::get('expenses/search', 'searchRecord')->middleware('auth')->name('expenses/search');
        Route::post('expenses/search', 'searchRecord')->middleware('auth')->name('expenses/search');
        
    });

    // ==================== user profile user ===========================//

    // ---------------------- personal information ----------------------//
    Route::controller(PersonalInformationController::class)->group(function () {
        Route::post('user/information/save', 'saveRecord')->middleware('auth')->name('user/information/save');
    });

    // ---------------------- bank information  -----------------------//
    Route::controller(BankInformationController::class)->group(function () {
        Route::post('bank/information/save', 'saveRecord')->middleware('auth')->name('bank/information/save');
    });


    // ==================== Maintenance Inventory ===========================//

    //------------------ Products -----------------//
    Route::controller(ProductController::class)->group(function () {
        // --- All Products -- //
        Route::get('product/list', 'productIndex')->middleware('auth')->name('product/list');
        Route::post('form/product/saving', 'saveProduct')->middleware('auth')->name('form/product/saving');  
        Route::post('form/product/brandupdate', 'updateProduct')->middleware('auth')->name('form/product/update');   
        Route::post('form/product/branddelete', 'deleteProduct')->middleware('auth')->name('form/product/delete'); 
        Route::post('/get-product-code', 'getProductCode')->name('get.product.code');
        // --- Category -- //
        Route::get('category/list', 'categoryIndex')->middleware('auth')->name('category/list');
        Route::post('form/saving', 'saveCategory')->middleware('auth')->name('form/saving');  
        Route::post('form/update', 'updateCategory')->middleware('auth')->name('form/update');   
        Route::post('form/delete', 'deleteCategory')->middleware('auth')->name('form/delete');  
        // -- Brand -- //
        Route::get('brand/list', 'brandIndex')->middleware('auth')->name('brand/list');
        Route::post('form/brand/saving', 'saveBrand')->middleware('auth')->name('form/brand/saving');  
        Route::post('form/brand/brandupdate', 'updateBrand')->middleware('auth')->name('form/brand/update');   
        Route::post('form/brand/branddelete', 'deleteBrand')->middleware('auth')->name('form/brand/delete'); 
        // -- Unit -- //
        Route::get('unit/list', 'unitIndex')->middleware('auth')->name('unit/list');
        Route::post('form/unit/saving', 'saveUnit')->middleware('auth')->name('form/unit/saving');  
        Route::post('form/unit/brandupdate', 'updateUnit')->middleware('auth')->name('form/unit/update');   
        Route::post('form/unit/branddelete', 'deleteUnit')->middleware('auth')->name('form/unit/delete'); 
    });
        // ---------------------- garage information  -----------------------//
    Route::controller(GarageController::class)->group(function () {
        Route::get('garage/list', 'garageIndex')->middleware('auth')->name('garage/list');
        Route::post('garage/save', 'saveGarage')->middleware('auth')->name('garage/save');
        Route::post('form/product/brandupdate', 'updateProduct')->middleware('auth')->name('form/product/update');   
        Route::post('form/product/branddelete', 'deleteProduct')->middleware('auth')->name('form/product/delete'); 
    });
    // ---------------------- supplier information  -----------------------//
    Route::controller(SupplierController::class)->group(function () {
        Route::get('supplier/list', 'supplierIndex')->middleware('auth')->name('supplier/list');
        Route::post('supplier/save', 'saveSupplier')->middleware('auth')->name('supplier/save');
        Route::post('form/supplier/update', 'updateSupplier')->middleware('auth')->name('form/supplier/update');   
        Route::post('form/supplier/delete', 'deleteSupplier')->middleware('auth')->name('form/supplier/delete'); 
    });
    // ---------------------- purchase order information  -----------------------//
    Route::controller(PurchaseOrderController::class)->group(function () {
        Route::get('/mainIndex', 'mainIndex')->middleware('auth')->name('purchase.index');
        Route::get('/purchaseIndex', 'purchaseIndex')->middleware('auth')->name('request.index');
        Route::get('/requestIndex/{requestId}','requestIndex')->middleware('auth')->name('update.index');
        Route::get('/receivingIndex','receivingIndex')->middleware('auth')->name('receiving.index');
        Route::get('/fetch-purchase-order/{id}','fetchPurchaseOrder')->middleware('auth');
        Route::post('/save-received','saveReceived')->middleware('auth')->name('save.received');
        Route::post('updateRequest/form', 'updateRequest')->middleware('auth')->name('update.requestID');
        Route::get('/get-latest-request-number', 'getLatestRequestNumber')->middleware('auth');
        Route::get('/get-product-codes', 'getProductCodes')->middleware('auth');
        Route::get('/get-product-details', 'getProductDetails')->middleware('auth');
        Route::post('/saving/request', 'saveRequest')->middleware('auth')->name('save.request');

        /**Routes for reports summary */
        Route::get('/stocks/Mirasol/', 'stockMirasol')->middleware('auth')->name('/stocks/Mirasol');
        Route::get('/stocks/Balintawak/', 'stockBalintawak')->middleware('auth')->name('/stocks/Balintawak');
        Route::get('/stocks/VGC/', 'stockVGC')->middleware('auth')->name('/stocks/VGC');

        /**RECEIPT FORMAT ALL */
        Route::get('/receipt/{po_number}', 'receipt')->middleware('auth')->name('receipt');
    });
            // ---------------------- Parts Out information  -----------------------//
    Route::controller(PartsOutController::class)->group(function () {
        Route::get('/part-out/product/Index', 'mainIndex')->middleware('auth')->name('view.index');
        Route::get('/create-partsout','createRequest')->middleware('auth')->name('create.parts');
        Route::get('/get-latest-partout-id', 'getLatestPartOutID')->middleware('auth');
        Route::get('/get-product-parts', 'getProductsByCategory')->middleware('auth');
        Route::get('/get-product-parts-codes', 'getProductCodes')->middleware('auth');
        Route::get('/get-stock-by-garage', 'getStockByGarage')->middleware('auth');
        Route::get('/search-products', 'search');
        Route::post('/saving-parts-outs', 'saveParts')->middleware('auth')->name('save.partsout');
    });
                // ---------------------- Stock Tranfers information  -----------------------//
    Route::controller(StockTransferController::class)->group(function () {
        Route::get('/stock-transfer/index', 'transferIndex')->middleware('auth')->name('transfer.index');
        Route::get('/stock-transfer/create', 'createTransfer')->middleware('auth')->name('transfer.create');
        Route::get('/get-latest-transfer-id', 'getLatestTransferID')->middleware('auth');
        Route::get('/get-product-parts', 'getProductsByCategory')->middleware('auth');
        Route::get('/get-product-parts-codes', 'getProductCodes')->middleware('auth');
        Route::get('/get-stock-by-garage', 'getStockByGarage')->middleware('auth');
        Route::get('/search-products', 'search');
        Route::post('/stock-transfer/saving', 'saveTransfer')->middleware('auth')->name('transfer.saving');
    });

});
