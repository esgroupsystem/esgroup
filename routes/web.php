
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LeavesController;
use Illuminate\Support\Facades\Artisan;

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
    // ---------------- Notification -------------- //
    Route::get('/notifications/clear', function () {
        Auth::user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.clear');

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
    // --------------------- Notification ----------------------------------//
    Route::controller(NotificationController::class)->group(function () {
        Route::get('/notifications/{id}', 'redirectToDetail')->name('notifications.read');
        Route::get('/notifications/fetch', 'fetch')->name('notifications.fetch');
    });
    // ----------------------------- main dashboard ------------------------------//
    Route::controller(HomeController::class)->group(function () {
        Route::get('/home', 'index')->name('home');
        Route::get('em/dashboard', 'emDashboard')->name('em/dashboard');
        Route::get('stock/dashboard', 'stockMirasol')->name('stock/dashboard');
        Route::get('dashboard/joborders', 'jobordersDashboard')->name('dashboard/joborders');
        Route::get('/joborders-refresh', 'jobordersRefresh')->name('joborders.refresh');
    });

    // ----------------------------- lock screen --------------------------------//
    Route::controller(LockScreen::class)->group(function () {
        Route::get('lock_screen','lockScreen')->name('lock_screen');
        Route::post('unlock', 'unlock')->name('unlock');    
    });

    // -----------------------------settings-------------------------------------//
    Route::controller(SettingController::class)
    ->middleware(['auth', 'role:Admin'])
    ->group(function () {
        Route::get('company/settings/page', 'companySettings')->name('company/settings/page'); /** index page */
        Route::post('company/settings/save', 'saveRecord')->name('company/settings/save'); /** save record or update */
        Route::get('roles/permissions/page', 'rolesPermissions')->name('roles/permissions/page');
        Route::post('roles/permissions/save', 'addRecord')->name('roles/permissions/save');
        Route::post('roles/permissions/update', 'editRolesPermissions')->name('roles/permissions/update');
        Route::post('roles/permissions/delete', 'deleteRolesPermissions')->name('roles/permissions/delete');
        Route::get('localization/page', 'localizationIndex')->name('localization/page'); /** index page localization */
        Route::get('salary/settings/page', 'salarySettingsIndex')->name('salary/settings/page'); /** index page salary settings */
        Route::get('email/settings/page', 'emailSettingsIndex')->name('email/settings/page'); /** index page email settings */
    });

    // ----------------------------- manage users -------d-----------------------//
    Route::controller(UserManagementController::class)
    ->middleware(['auth', 'role:Admin'])
    ->group(function () {
        Route::get('profile_user', 'profile')->name('profile_user');
        Route::post('profile/information/save', 'profileInformation')->name('profile/information/save');
        Route::get('userManagement', 'index')->name('userManagement');
        Route::post('user/add/save', 'addNewUserSave')->name('user/add/save');
        Route::post('form/user/update', 'update')->name('user.update');
        Route::post('user/delete', 'delete')->name('user/delete');
        Route::get('change/password', 'changePasswordView')->name('change/password');
        Route::post('change/password/db', 'changePasswordDB')->name('change/password/db');
        
        Route::post('user/profile/emergency/contact/save', 'emergencyContactSaveOrUpdate')->name('user/profile/emergency/contact/save'); /** save or update emergency contact */
        Route::get('get-users-data', 'getUsersData')->name('get-users-data'); /** get all data users */
        
    });

    // ----------------------------------manage designation-------------------------//
    Route::controller(DesignationController::class)
    ->middleware(['auth', 'role:Admin'])
    ->group(function () {
        Route::post('userDesignation', 'saveDesignation')->name('userDesignation');

        
    });

    //========================== IT DEPARTMENT JOBORDERS ====================//

    // ---------------------- IT JobOrders  -----------------------//
    Route::controller(JobOrderController::class)
    ->middleware(['auth', 'role:Admin,IT,Maintenance'])
    ->group(function () {
        Route::get('form/joborders/page', 'jobordersIndex')->name('form/joborders/page');
        Route::get('create/joborders/page', 'createJobOrderIndex')->name('create/joborders/page');  
        Route::get('view/details/{id}', 'viewSpecificDetails')->name('view/details');   
        Route::post('form/joborders/save', 'saveRecordJoborders')->name('form/joborders/save');    
        Route::post('form/joborders/update', 'updateRecordJoborders')->name('form/joborders/update');    
        Route::post('form/joborders/delete', 'deleteRecordJoborders')->name('form/joborders/delete');
        Route::post('/saving', 'Job_Files')->name('job.files');
        Route::post('joborders/delete/{id}', 'deleteVideoFiles')->name('joborders.delete');

    });

    // --------------------------------- job ---------------------------------//
    Route::controller(JobController::class)
    ->middleware(['auth', 'role:Admin,IT,Maintenance'])
    ->group(function () {
        Route::get('form/job/list','categoryIndex')->name('form/job/list');
        Route::get('form/job/view/{id}', 'jobView');
        Route::get('user/dashboard/index', 'userDashboard')->name('user/dashboard/index');    
        Route::get('jobs/dashboard/index', 'jobsDashboard')->name('jobs/dashboard/index');    
        Route::get('user/dashboard/all', 'userDashboardAll')->name('user/dashboard/all');    
        Route::get('user/dashboard/save', 'userDashboardSave')->name('user/dashboard/save');
        Route::get('user/dashboard/applied/jobs', 'userDashboardApplied')->name('user/dashboard/applied/jobs');
        Route::get('user/dashboard/interviewing', 'userDashboardInterviewing')->name('user/dashboard/interviewing');
        Route::get('user/dashboard/offered/jobs', 'userDashboardOffered')->name('user/dashboard/offered/jobs');
        Route::get('user/dashboard/visited/jobs', 'userDashboardVisited')->name('user/dashboard/visited/jobs');
        Route::get('user/dashboard/archived/jobs', 'userDashboardArchived')->name('user/dashboard/archived/jobs');
        Route::get('jobs', 'Jobs')->name('jobs');
        Route::get('job/applicants/{job_title}', 'jobApplicants');
        Route::get('job/details/{id}', 'jobDetails');
        Route::get('cv/download/{id}', 'downloadCV');
        
        Route::post('form/jobs/save', 'JobsSaveRecord')->name('form/jobs/save');
        Route::post('form/apply/job/save', 'applyJobSaveRecord')->name('form/apply/job/save');
        Route::post('form/apply/job/update', 'applyJobUpdateRecord')->name('form/apply/job/update');

        Route::get('page/manage/resumes', 'manageResumesIndex')->name('page/manage/resumes');
        Route::get('page/shortlist/candidates', 'shortlistCandidatesIndex')->name('page/shortlist/candidates');
        Route::get('page/interview/questions', 'interviewQuestionsIndex')->name('page/interview/questions');
        Route::post('save/category', 'categorySave')->name('save/category');
        Route::post('save/questions', 'questionSave')->name('save/questions');
        Route::post('questions/update', 'questionsUpdate')->name('questions/update');
        Route::post('questions/delete', 'questionsDelete')->name('questions/delete');
        Route::get('page/offer/approvals', 'offerApprovalsIndex')->name('page/offer/approvals');
        Route::get('page/experience/level', 'experienceLevelIndex')->name('page/experience/level');
        Route::get('page/candidates', 'candidatesIndex')->name('page/candidates');
        Route::get('page/schedule/timing', 'scheduleTimingIndex')->name('page/schedule/timing');
        Route::get('page/aptitude/result', 'aptituderesultIndex')->name('page/aptitude/result');

        Route::post('jobtypestatus/update', 'jobTypeStatusUpdate')->name('jobtypestatus/update');

    });
    
// ---------------------------- FORM EMPLOYEE ----------------------------//
    Route::controller(EmployeeController::class)
        ->middleware(['auth', 'role:Admin,HR,DPO'])
        ->group(function () {
            Route::get('all/employee/card', 'cardAllEmployee')->name('all/employee/card');
            Route::get('all/employee/list', 'listAllEmployee')->name('all/employee/list');
            Route::post('all/employee/save', 'saveRecord')->name('all/employee/save');
            Route::get('all/employee/view/edit/{employee_id}', 'viewRecord');
            Route::post('all/employee/update', 'updateRecord')->name('all/employee/update');
            Route::get('all/employee/delete/{employee_id}', 'deleteRecord');
            Route::post('all/employee/search', 'employeeSearch')->name('all/employee/search');
            Route::post('all/employee/list/search', 'employeeListSearch')->name('all/employee/list/search');
            Route::post('schedule/employee', 'store')->name('schedule.store');

            Route::get('form/departments/page', 'index')->name('form/departments/page');
            Route::post('form/departments/save', 'saveRecordDepartment')->name('form/departments/save');
            Route::post('form/department/update', 'updateRecordDepartment')->name('form/department/update');
            Route::post('form/department/delete', 'deleteRecordDepartment')->name('form/department/delete');

            Route::get('form/designations/page', 'designationsIndex')->name('form/designations/page');
            Route::post('form/designations/save', 'saveRecordDesignations')->name('form/designations/save');
            Route::post('form/designations/update', 'updateRecordDesignations')->name('form/designations/update');
            Route::post('form/designations/delete', 'deleteRecordDesignations')->name('form/designations/delete');

            Route::get('form/timesheet/page', 'timeSheetIndex')->name('form/timesheet/page');
            Route::post('form/timesheet/save', 'saveRecordTimeSheets')->name('form/timesheet/save');
            Route::post('form/timesheet/update', 'updateRecordTimeSheets')->name('form/timesheet/update');
            Route::post('form/timesheet/delete', 'deleteRecordTimeSheets')->name('form/timesheet/delete');

            Route::get('form/overtime/page', 'overTimeIndex')->name('form/overtime/page');
            Route::post('form/overtime/save', 'saveRecordOverTime')->name('form/overtime/save');
            Route::post('form/overtime/update', 'updateRecordOverTime')->name('form/overtime/update');
            Route::post('form/overtime/delete', 'deleteRecordOverTime')->name('form/overtime/delete');

            // Employee actions
            Route::get('employee/request-approval/{id}','requestApproval');

            // Admin approval actions
            Route::get('admin/employee-requests','viewRequests')->name('employees.request');
            Route::post('admin/employee-requests/approve/{id}','approveRequest');
            Route::post('admin/employee-requests/reject/{id}','rejectRequest');
        });

    // -------------------------- PROFILE EMPLOYEE ---------------------------//
    Route::controller(EmployeeController::class)
        ->middleware(['auth', 'role:Admin,HR,DPO'])
        ->group(function () {
            Route::get('employee/profile/{user_id}', 'profileEmployee');
            Route::post('/employee/schedule/save', 'AdminScheduleSave')->name('schedule.save');
            Route::post('/requirements/upload','uploadRequirement')->name('requirements.upload');
            Route::post('/profile/info/save','profileInformation')->name('profile/save');
            Route::post('/violation/save', 'violationSave')->name('violation/save');
        });

    // ---------------------------- FORM HOLIDAY -----------------------------//
    Route::controller(HolidayController::class)
        ->middleware(['auth', 'role:Admin,HR,DPO'])
        ->group(function () {
            Route::get('form/holidays/new', 'holiday')->name('form/holidays/new');
            Route::post('form/holidays/save', 'saveRecord')->name('form/holidays/save');
            Route::post('form/holidays/update', 'updateRecord')->name('form/holidays/update');
            Route::post('form/holidays/delete', 'deleteRecord')->name('form/holidays/delete');
        });

    // ---------------------------- FORM LEAVES ------------------------------//
    Route::controller(LeavesController::class)
        ->middleware(['auth', 'role:Admin,HR,DPO'])
        ->group(function () {
            Route::get('form/leaves/new', 'leaves')->name('form/leaves/new');
            Route::get('form/leavesemployee/new', 'leavesEmployee')->name('form/leavesemployee/new');
            Route::post('form/leaves/save', 'saveRecord')->name('form/leaves/save');
            Route::post('form/leaves/edit', 'editRecordLeave')->name('form/leaves/edit');
            Route::post('form/leaves/edit/delete', 'deleteLeave')->name('form/leaves/edit/delete');

            Route::get('form/leavesettings/page', 'leaveSettings')->name('form/leavesettings/page');
            Route::get('attendance/page', 'attendanceIndex')->name('attendance/page');
            Route::get('attendance/employee/page', 'AttendanceEmployee')->name('attendance/employee/page');
            Route::get('form/shiftscheduling/page', 'shiftScheduLing')->name('form/shiftscheduling/page');
            Route::get('form/shiftlist/page', 'shiftList')->name('form/shiftlist/page');

            Route::post('/schedule/store', 'storeSchedule')->name('schedule.store');
            Route::post('/schedule/update', 'update')->name('schedule.update');
            Route::get('/driver-profiles', 'showDriverSchedules')->name('all.schedule');
            
            Route::post('/schedule/update-log', 'updateScheduleLog')->name('schedule.update.log');

        });


    // ---------------------------- FORM PAYROLL -----------------------------//
    Route::controller(PayrollController::class)
        ->middleware(['auth', 'role:Admin,HR,DPO'])
        ->group(function () {
            Route::get('form/salary/page', 'salary')->name('form/salary/page');
            Route::post('form/salary/save', 'saveRecord')->name('form/salary/save');
            Route::post('form/salary/update', 'updateRecord')->name('form/salary/update');
            Route::post('form/salary/delete', 'deleteRecord')->name('form/salary/delete');
            Route::get('form/salary/view/{user_id}', 'salaryView');
            Route::get('form/payroll/items', 'payrollItems')->name('form/payroll/items');
            Route::get('extra/report/pdf', 'reportPDF');
            Route::get('extra/report/excel', 'reportExcel');
        });


    // ----------------------------- BIOMETRICS LOGS ---------------------------//
    Route::controller(BiometricsRecordsController::class)
        ->middleware(['auth', 'role:Admin,HR,DPO'])
        ->group(function () {
            Route::get('/biometrics/logs','index')->name('biometrics.logs');
            Route::post('/biometrics/sync', 'syncBiometrics')->name('biometrics.sync');        
        });

    // ----------------------------- REPORTS ----------------------------------//
    Route::controller(ExpenseReportsController::class)
        ->middleware(['auth', 'role:Admin,HR,DPO'])
        ->group(function () {
            Route::get('form/expense/reports/page', 'index')->name('form/expense/reports/page');
            Route::get('form/invoice/reports/page', 'invoiceReports')->name('form/invoice/reports/page');
            Route::get('form/daily/reports/page', 'dailyReport')->name('form/daily/reports/page');
            Route::get('form/leave/reports/page', 'leaveReport')->name('form/leave/reports/page');
            Route::get('form/payments/reports/page', 'paymentsReportIndex')->name('form/payments/reports/page');
            Route::get('form/employee/reports/page', 'employeeReportsIndex')->name('form/employee/reports/page');
        });

    // --------------------------- performance  -------------------------//
    Route::controller(PerformanceController::class)
    ->middleware(['auth', 'role:Admin,HR,DPO'])
    ->group(function () {
        Route::get('form/performance/indicator/page','index')->name('form/performance/indicator/page');
        Route::get('form/performance/page', 'performance')->name('form/performance/page');
        Route::get('form/performance/appraisal/page', 'performanceAppraisal')->name('form/performance/appraisal/page');
        Route::post('form/performance/indicator/save','saveRecordIndicator')->name('form/performance/indicator/save');
        Route::post('form/performance/indicator/delete','deleteIndicator')->name('form/performance/indicator/delete');
        Route::post('form/performance/indicator/update', 'updateIndicator')->name('form/performance/indicator/update');
        Route::post('form/performance/appraisal/save', 'saveRecordAppraisal')->name('form/performance/appraisal/save');
        Route::post('form/performance/appraisal/update', 'updateAppraisal')->name('form/performance/appraisal/update');
        Route::post('form/performance/appraisal/delete', 'deleteAppraisal')->name('form/performance/appraisal/delete');
    });

    // --------------------------- training  ----------------------------//
    Route::controller(TrainingController::class)
    ->middleware(['auth', 'role:Admin,HR,DPO'])
    ->group(function () {
        Route::get('form/training/list/page','index')->name('form/training/list/page');
        Route::post('form/training/save', 'addNewTraining')->name('form/training/save');
        Route::post('form/training/delete', 'deleteTraining')->name('form/training/delete');
        Route::post('form/training/update', 'updateTraining')->name('form/training/update');    
    });

    // --------------------------- trainers  ----------------------------//
    Route::controller(TrainersController::class)
    ->middleware(['auth', 'role:Admin,HR,DPO'])
    ->group(function () {
        Route::get('form/trainers/list/page', 'index')->name('form/trainers/list/page');
        Route::post('form/trainers/save', 'saveRecord')->name('form/trainers/save');
        Route::post('form/trainers/update', 'updateRecord')->name('form/trainers/update');
        Route::post('form/trainers/delete', 'deleteRecord')->name('form/trainers/delete');
    });

    // ------------------------- training type  -------------------------//
    Route::controller(TrainingTypeController::class)
    ->middleware(['auth', 'role:Admin,HR,DPO'])
    ->group(function () {
        Route::get('form/training/type/list/page', 'index')->name('form/training/type/list/page');
        Route::post('form/training/type/save', 'saveRecord')->name('form/training/type/save');
        Route::post('form//training/type/update', 'updateRecord')->name('form//training/type/update');
        Route::post('form//training/type/delete', 'deleteTrainingType')->name('form//training/type/delete');    
    });

    // ----------------------------- sales  ----------------------------//
    Route::controller(SalesController::class)
    ->middleware(['auth', 'role:Admin,HR,DPO'])
    ->group(function () {
        // -------------------- estimate  --------------------//
        Route::get('form/estimates/page', 'estimatesIndex')->name('form/estimates/page');
        Route::get('create/estimate/page', 'createEstimateIndex')->name('create/estimate/page');
        Route::get('edit/estimate/{estimate_number}', 'editEstimateIndex');
        Route::get('estimate/view/{estimate_number}', 'viewEstimateIndex');

        Route::post('create/estimate/save', 'createEstimateSaveRecord')->name('create/estimate/save');
        Route::post('create/estimate/update', 'EstimateUpdateRecord')->name('create/estimate/update');
        Route::post('estimate_add/delete', 'EstimateAddDeleteRecord')->name('estimate_add/delete');
        Route::post('estimate/delete', 'EstimateDeleteRecord')->name('estimate/delete');
        // ------------------------ payments  -------------------//
        Route::get('payments', 'Payments')->name('payments');
        Route::get('expenses/page', 'Expenses')->name('expenses/page');
        Route::post('expenses/save', 'saveRecord')->name('expenses/save');
        Route::post('expenses/update', 'updateRecord')->name('expenses/update');
        Route::post('expenses/delete', 'deleteRecord')->name('expenses/delete');
        // ---------------------- search expenses  ---------------//
        Route::get('expenses/search', 'searchRecord')->name('expenses/search');
        Route::post('expenses/search', 'searchRecord')->name('expenses/search');
        
    });

    // ==================== user profile user ===========================//

    // ---------------------- personal information ----------------------//
    Route::controller(PersonalInformationController::class)
    ->middleware(['auth', 'role:Admin,HR,DPO'])
    ->group(function () {
        Route::post('user/information/save', 'saveRecord')->name('user/information/save');
    });


    // ---------------------- bank information  -----------------------//
    Route::controller(BankInformationController::class)
    ->middleware(['auth', 'role:Admin,HR,DPO'])
    ->group(function () {
        Route::post('bank/information/save', 'saveRecord')->name('bank/information/save');
    });


    //////// ==================== Maintenance Inventory ===========================/////////////////

    // ---------------------- PRODUCTS ----------------------- //
    Route::controller(ProductController::class)
        ->middleware(['auth', 'role:Admin,Maintenance'])
        ->group(function () {
            Route::get('product/list', 'productIndex')->name('product/list');
            Route::post('form/product/saving', 'saveProduct')->name('form/product/saving');  
            Route::post('form/product/brandupdate', 'updateProduct')->name('form/product/update');   
            Route::post('form/product/branddelete', 'deleteProduct')->name('form/product/delete'); 
            Route::post('/get-product-code', 'getProductCode')->name('get.product.code');
            Route::get('category/list', 'categoryIndex')->name('category/list');
            Route::post('form/saving', 'saveCategory')->name('form/saving');  
            Route::post('form/update', 'updateCategory')->name('form/update');   
            Route::post('form/delete', 'deleteCategory')->name('form/delete');  
            Route::get('brand/list', 'brandIndex')->name('brand/list');
            Route::post('form/brand/saving', 'saveBrand')->name('form/brand/saving');  
            Route::post('form/brand/brandupdate', 'updateBrand')->name('form/brand/update');   
            Route::post('form/brand/branddelete', 'deleteBrand')->name('form/brand/delete'); 
            Route::get('unit/list', 'unitIndex')->name('unit/list');
            Route::post('form/unit/saving', 'saveUnit')->name('form/unit/saving');  
            Route::post('form/unit/brandupdate', 'updateUnit')->name('form/unit/update');   
            Route::post('form/unit/branddelete', 'deleteUnit')->name('form/unit/delete'); 
        });

    // ---------------------- GARAGE ----------------------- //
    Route::controller(GarageController::class)
        ->middleware(['auth', 'role:Admin,Maintenance'])
        ->group(function () {
            Route::get('garage/list', 'garageIndex')->name('garage/list');
            Route::post('garage/save', 'saveGarage')->name('garage/save');
        });

    // ---------------------- SUPPLIER ----------------------- //
    Route::controller(SupplierController::class)
        ->middleware(['auth', 'role:Admin,Maintenance'])
        ->group(function () {
            Route::get('supplier/list', 'supplierIndex')->name('supplier/list');
            Route::post('supplier/save', 'saveSupplier')->name('supplier/save');
            Route::post('form/supplier/update', 'updateSupplier')->name('form/supplier/update');   
            Route::post('form/supplier/delete', 'deleteSupplier')->name('form/supplier/delete'); 
        });

    // ---------------------- PURCHASE ORDER ----------------------- //
    Route::controller(PurchaseOrderController::class)
        ->middleware(['auth', 'role:Admin,Maintenance'])
        ->group(function () {
            Route::get('/mainIndex', 'mainIndex')->name('purchase.index');
            Route::get('/purchaseIndex', 'purchaseIndex')->name('request.index');
            Route::get('/requestIndex/{requestId}','requestIndex')->name('update.index');
            Route::get('/receivingIndex','receivingIndex')->name('receiving.index');
            Route::get('/fetch-purchase-order/{id}','fetchPurchaseOrder');
            Route::post('/save-received','saveReceived')->name('save.received');
            Route::post('updateRequest/form', 'updateRequest')->name('update.requestID');
            Route::get('/get-latest-request-number', 'getLatestRequestNumber');
            Route::get('/get-product-codes', 'getProductCodes');
            Route::get('/get-product-details', 'getProductDetails');
            Route::post('/saving/request', 'saveRequest')->name('save.request');
            Route::get('/stocks/Mirasol/', 'stockMirasol')->name('/stocks/Mirasol');
            Route::get('/stocks/Balintawak/', 'stockBalintawak')->name('/stocks/Balintawak');
            Route::get('/stocks/VGC/', 'stockVGC')->name('/stocks/VGC');
            Route::get('/receipt/{po_number}', 'receipt')->name('receipt');
        });

    // ---------------------- PARTS OUT ----------------------- //
    Route::controller(PartsOutController::class)
        ->middleware(['auth', 'role:Admin,Maintenance'])
        ->group(function () {
            Route::get('/part-out/product/Index', 'mainIndex')->name('view.index');
            Route::get('/create-partsout','createRequest')->name('create.parts');
            Route::get('/get-latest-partout-id', 'getLatestPartOutID');
            Route::get('/get-product-parts', 'getProductsByCategory');
            Route::get('/get-product-parts-codes', 'getProductCodes');
            Route::get('/get-stock-by-garage', 'getStockByGarage');
            Route::get('/search-products', 'search');
            Route::post('/saving-parts-outs', 'saveParts')->name('save.partsout');
        });

    // ---------------------- STOCK TRANSFER ----------------------- //
    Route::controller(StockTransferController::class)
        ->middleware(['auth', 'role:Admin,Maintenance'])
        ->group(function () {
            Route::get('/stock-transfer/index', 'transferIndex')->name('transfer.index');
            Route::get('/stock-transfer/create', 'createTransfer')->name('transfer.create');
            Route::get('/get-latest-transfer-id', 'getLatestTransferID');
            Route::get('/get-product-parts', 'getProductsByCategory');
            Route::get('/get-product-parts-codes', 'getProductCodes');
            Route::get('/get-stock-by-garage', 'getStockByGarage');
            Route::get('/search-products', 'search');
            Route::post('/stock-transfer/saving', 'saveTransfer')->name('transfer.saving');
        });

});
