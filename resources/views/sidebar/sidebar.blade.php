<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Main</span>
                </li>

                <!--*
                    *
                    * DASHBOARD
                    *
                    *-->
                <li class="{{ set_active(['home', 'em/dashboard', 'stock/dashboard', 'dashboard/joborders']) }} submenu">
                    <a href="#"
                        class="{{ set_active(['home', 'em/dashboard', 'dashboard/joborders']) ? 'noti-dot' : '' }}">
                        <i class="la la-dashboard"></i>
                        <span> Dashboard</span> <span class="menu-arrow"></span>
                    </a>
                    <ul style="{{ request()->is('/*') ? 'display: block;' : 'display: none;' }}">

                        @if(in_array(Auth::user()->role_name, ['HR','Admin']))
                            <li>
                                {!! privilege_link('home', ['HR', 'Admin', 'DPO'], 'Admin Dashboard', set_active(['home'])) !!}
                            </li>
                        @endif

                        @if(in_array(Auth::user()->role_name, ['HR','Admin','DPO']))
                            <li>
                                {!! privilege_link('em/dashboard', ['HR', 'Admin', 'DPO'], 'Employee Dashboard', set_active(['em/dashboard'])) !!}
                            </li>
                        @endif

                        @if(in_array(Auth::user()->role_name, ['Admin','Maintenance']))
                            <li>
                                {!! privilege_link('stock/dashboard', ['Maintenance', 'Admin'],'Stocks Dashboard', set_active(['stock/dashboard']),) !!}
                            </li>
                        @endif

                        @if(in_array(Auth::user()->role_name, ['Admin','IT']))
                            <li>
                                {!! privilege_link('dashboard/joborders',['IT', 'Admin'],'Job Orders Dashboard',set_active(['dashboard/joborders']),) !!}
                            </li>
                        @endif

                    </ul>
                </li>

                <!--*
                    *
                    * IT DEPARTMENT
                    *
                    *-->
                @if(in_array(Auth::user()->role_name, ['Admin','IT']))
                <li class="menu-title"><span>IT Department</span></li>
                <li
                    class="{{ set_active(['form/joborders/page', 'form/joborders/save', 'form/joborders/update', 'form/joborders/delete']) }} submenu">
                    <a href="#"
                        class="{{ set_active(['form/joborders/page', 'form/joborders/save', 'form/joborders/update', 'form/joborders/delete']) ? 'noti-dot' : '' }}">
                        <i class="la la-car-crash"></i> <span>Bus Concern</span> <span class="menu-arrow"></span>
                    </a>
                    <ul style="{{ request()->is('/*') ? 'display: block;' : 'display: none;' }}">
                        <li>
                            {!! privilege_link(
                                'form/joborders/page',
                                ['IT', 'Admin', 'Maintenance'],
                                'Job Orders',
                                set_active(['form/joborders/page']),
                            ) !!}
                        </li>
                    </ul>
                </li>
                @endif

                <!--*
                    *
                    * EMPLOYEE LISTING
                    *
                    *-->

                @if(in_array(Auth::user()->role_name, ['HR','Admin','DPO']))
                <li class="menu-title"> <span>Employees</span> </li>
                <li
                    class="{{ set_active([
                        'all/employee/list',
                        'all/employee/card',
                        'form/holidays/new',
                        'form/leaves/new',
                        'form/leavesemployee/new',
                        'form/leavesettings/page',
                        'attendance/page',
                        'attendance/employee/page',
                        'form/departments/page',
                        'form/designations/page',
                        'form/timesheet/page',
                        'form/shiftscheduling/page',
                        'form/overtime/page',
                        'admin/employee-requests',
                    ]) }} submenu">
                    <a href="#"
                        class="{{ set_active([
                            'all/employee/list',
                            'all/employee/card',
                            'form/holidays/new',
                            'form/leaves/new',
                            'form/leavesemployee/new',
                            'form/leavesettings/page',
                            'attendance/page',
                            'attendance/employee/page',
                            'form/departments/page',
                            'form/designations/page',
                            'form/timesheet/page',
                            'form/shiftscheduling/page',
                            'form/overtime/page',
                            'admin/employee-requests',
                        ])
                            ? 'noti-dot'
                            : '' }}">
                        <i class="la la-user"></i> <span> Employees</span> <span class="menu-arrow"></span>
                    </a>
                    <ul style="{{ request()->is('/*') ? 'display: block;' : 'display: none;' }}">
                        <li>{!! privilege_link(
                            'all/employee/card',
                            ['HR', 'Admin', 'DPO'],
                            'All Employees',
                            set_active(['all/employee/list', 'all/employee/card']),
                        ) !!}</li>
                        <li>{!! privilege_link('form/holidays/new', ['HR', 'Admin', 'DPO'], 'Holidays', set_active(['form/holidays/new'])) !!}</li>
                        <li>{!! privilege_link(
                            'form/leaves/new',
                            ['HR', 'Admin', 'DPO'],
                            'Leaves (Admin) <span class="badge badge-pill bg-primary float-right">1</span>',
                            set_active(['form/leaves/new']),
                        ) !!}</li>
                        <li>{!! privilege_link(
                            'form/leavesemployee/new',
                            ['HR', 'Admin', 'DPO'],
                            'Leaves (Employee)',
                            set_active(['form/leavesemployee/new']),
                        ) !!}</li>
                        <li>{!! privilege_link(
                            'form/leavesettings/page',
                            ['HR', 'Admin', 'DPO'],
                            'Leave Settings',
                            set_active(['form/leavesettings/page']),
                        ) !!}</li>
                        <li>{!! privilege_link('attendance/page', ['HR', 'Admin'], 'Attendance (Admin)', set_active(['attendance/page'])) !!}</li>
                        <li>{!! privilege_link(
                            'attendance/employee/page',
                            ['HR', 'Admin', 'DPO'],
                            'Attendance (Employee)',
                            set_active(['attendance/employee/page']),
                        ) !!}</li>
                        <li>{!! privilege_link(
                            'form/departments/page',
                            ['HR', 'Admin', 'DPO'],
                            'Departments',
                            set_active(['form/departments/page']),
                        ) !!}</li>
                        <li>{!! privilege_link(
                            'form/designations/page',
                            ['HR', 'Admin', 'DPO'],
                            'Designations',
                            set_active(['form/designations/page']),
                        ) !!}</li>
                        <li>{!! privilege_link(
                            'form/timesheet/page',
                            ['HR', 'Admin', 'DPO'],
                            'Timesheet',
                            set_active(['form/timesheet/page']),
                        ) !!}</li>
                        <li>{!! privilege_link(
                            'form/shiftscheduling/page',
                            ['HR', 'Admin', 'DPO'],
                            'Shift & Schedule',
                            set_active(['form/shiftscheduling/page']),
                        ) !!}</li>
                        <li>{!! privilege_link('all.schedule', ['HR', 'Admin', 'DPO'], 'All Schedule', set_active(['all.schedule'])) !!}</li>
                        <li>{!! privilege_link('form/overtime/page', ['HR', 'Admin', 'DPO'], 'Overtime', set_active(['form/overtime/page'])) !!}</li>
                    </ul>
                </li>
                @endif

                <!--*
                    *
                    * ---------------- ADMINISTRATION LISTING -------------
                    *
                    *-->
                @if(in_array(Auth::user()->role_name, ['HR','Admin','DPO']))
                <li class="menu-title"> <span>Administration</span> </li>
                <li
                    class="{{ set_active([
                        'user/dashboard/index',
                        'jobs/dashboard/index',
                        'user/dashboard/all',
                        'user/dashboard/applied/jobs',
                        'user/dashboard/interviewing',
                        'user/dashboard/offered/jobs',
                        'user/dashboard/visited/jobs',
                        'user/dashboard/archived/jobs',
                        'user/dashboard/save',
                        'jobs',
                        'job/applicants',
                        'job/details',
                        'page/manage/resumes',
                        'page/shortlist/candidates',
                        'page/interview/questions',
                        'page/offer/approvals',
                        'page/experience/level',
                        'page/candidates',
                        'page/schedule/timing',
                        'page/aptitude/result',
                    ]) }} submenu">

                    <a href="#"
                        class="{{ set_active([
                            'user/dashboard/index',
                            'jobs/dashboard/index',
                            'user/dashboard/all',
                            'user/dashboard/save',
                            'jobs',
                            'job/applicants',
                            'job/details',
                        ])
                            ? 'noti-dot'
                            : '' }}">
                        <i class="la la-briefcase"></i>
                        <span> Jobs </span> <span class="menu-arrow"></span>
                    </a>

                    <ul
                        style="{{ request()->is('/*') ? 'display: block;' : 'display: none;' }} {{ request()->is('job/applicants/*') ? 'display: block;' : 'display: none;' }}">
                        <li>{!! privilege_link(
                            'user/dashboard/index',
                            ['Admin', 'HR', 'DPO'],
                            'User Dashboard',
                            set_active([
                                'user/dashboard/index',
                                'user/dashboard/all',
                                'user/dashboard/applied/jobs',
                                'user/dashboard/interviewing',
                                'user/dashboard/offered/jobs',
                                'user/dashboard/visited/jobs',
                                'user/dashboard/archived/jobs',
                                'user/dashboard/save',
                            ]),
                        ) !!}</li>
                        <li>{!! privilege_link(
                            'jobs/dashboard/index',
                            ['Admin', 'HR', 'DPO'],
                            'Jobs Dashboard',
                            set_active(['jobs/dashboard/index']),
                        ) !!}</li>
                        <li>{!! privilege_link(
                            'jobs',
                            ['Admin', 'HR', 'DPO'],
                            'Manage Jobs',
                            set_active(['jobs', 'job/applicants', 'job/details']) .
                                ' ' .
                                (request()->is('job/applicants/*', 'job/details/*') ? 'active' : ''),
                        ) !!}</li>
                        <li>{!! privilege_link(
                            'page/manage/resumes',
                            ['Admin', 'HR', 'DPO'],
                            'Manage Resumes',
                            set_active(['page/manage/resumes']),
                        ) !!}</li>
                        <li>{!! privilege_link(
                            'page/shortlist/candidates',
                            ['Admin', 'HR', 'DPO'],
                            'Shortlist Candidates',
                            set_active(['page/shortlist/candidates']),
                        ) !!}</li>
                        <li>{!! privilege_link(
                            'page/interview/questions',
                            ['Admin', 'HR', 'DPO'],
                            'Interview Questions',
                            set_active(['page/interview/questions']),
                        ) !!}</li>
                        <li>{!! privilege_link(
                            'page/offer/approvals',
                            ['Admin', 'HR', 'DPO'],
                            'Offer Approvals',
                            set_active(['page/offer/approvals']),
                        ) !!}</li>
                        <li>{!! privilege_link(
                            'page/experience/level',
                            ['Admin', 'HR', 'DPO'],
                            'Experience Level',
                            set_active(['page/experience/level']),
                        ) !!}</li>
                        <li>{!! privilege_link(
                            'page/candidates',
                            ['Admin', 'HR', 'DPO'],
                            'Candidates List',
                            set_active(['page/candidates']),
                        ) !!}</li>
                        <li>{!! privilege_link(
                            'page/schedule/timing',
                            ['Admin', 'HR', 'DPO'],
                            'Schedule Timing',
                            set_active(['page/schedule/timing']),
                        ) !!}</li>
                        <li>{!! privilege_link(
                            'page/aptitude/result',
                            ['Admin', 'HR', 'DPO'],
                            'Aptitude Results',
                            set_active(['page/aptitude/result']),
                        ) !!}</li>
                    </ul>
                </li>

                <li class="menu-title"> <span>HR</span> </li>
                <li
                    class="{{ set_active(['create/estimate/page', 'form/estimates/page', 'payments', 'expenses/page']) }} submenu">
                    <a href="#"
                        class="{{ set_active(['create/estimate/page', 'form/estimates/page', 'payments', 'expenses/page']) ? 'noti-dot' : '' }}">
                        <i class="la la-files-o"></i> <span> Sales </span> <span class="menu-arrow"></span>
                    </a>
                    <ul style="{{ request()->is('/*') ? 'display: block;' : 'display: none;' }}">
                        <li>{!! privilege_link(
                            'form/estimates/page',
                            ['HR', 'Admin', 'DPO'],
                            'Estimates',
                            set_active(['create/estimate/page', 'form/estimates/page']),
                        ) !!}</li>
                        <li>{!! privilege_link('payments', ['HR', 'Admin', 'DPO'], 'Payments', set_active(['payments'])) !!}</li>
                        <li>{!! privilege_link('expenses/page', ['HR', 'Admin', 'DPO'], 'Expenses', set_active(['expenses/page'])) !!}</li>
                    </ul>
                </li>

                <li class="{{ set_active(['form/salary/page', 'form/payroll/items']) }} submenu">
                    <a href="#"
                        class="{{ set_active(['form/salary/page', 'form/payroll/items']) ? 'noti-dot' : '' }}">
                        <i class="la la-money"></i> <span> Payroll </span> <span class="menu-arrow"></span>
                    </a>
                    <ul style="{{ request()->is('/*') ? 'display: block;' : 'display: none;' }}">
                        <li>{!! privilege_link('form/salary/page', ['HR', 'Admin'], 'Employee Salary', set_active(['form/salary/page'])) !!}</li>
                        <li>{!! privilege_link('form/salary/page', ['HR', 'Admin'], 'Payslip', set_active(['form/salary/page'])) !!}</li>
                        <li>{!! privilege_link('form/payroll/items', ['HR', 'Admin'], 'Payroll Items', set_active(['form/payroll/items'])) !!}</li>
                        <li>{!! privilege_link('biometrics.logs', ['HR', 'Admin'], 'Biometrics Logs', set_active(['biometrics.logs'])) !!}</li>
                    </ul>
                </li>

                <li
                    class="{{ set_active(['form/expense/reports/page', 'form/invoice/reports/page', 'form/leave/reports/page', 'form/daily/reports/page', 'form/payments/reports/page', 'form/employee/reports/page']) }} submenu">
                    <a href="#"
                        class="{{ set_active(['form/expense/reports/page', 'form/invoice/reports/page', 'form/leave/reports/page', 'form/daily/reports/page', 'form/payments/reports/page', 'form/employee/reports/page']) ? 'noti-dot' : '' }}">
                        <i class="la la-pie-chart"></i> <span> Reports </span> <span class="menu-arrow"></span>
                    </a>
                    <ul style="{{ request()->is('/*') ? 'display: block;' : 'display: none;' }}">
                        <li>{!! privilege_link(
                            'form/expense/reports/page',
                            ['HR', 'Admin'],
                            'Expense Report',
                            set_active(['form/expense/reports/page']),
                        ) !!}</li>
                        <li>{!! privilege_link(
                            'form/invoice/reports/page',
                            ['HR', 'Admin'],
                            'Invoice Report',
                            set_active(['form/invoice/reports/page']),
                        ) !!}</li>
                        <li>{!! privilege_link(
                            'form/payments/reports/page',
                            ['HR', 'Admin'],
                            'Payments Report',
                            set_active(['form/payments/reports/page']),
                        ) !!}</li>
                        <li>{!! privilege_link(
                            'form/employee/reports/page',
                            ['HR', 'Admin'],
                            'Employee Report',
                            set_active(['form/employee/reports/page']),
                        ) !!}</li>
                        <li><a href="payslip-reports.html">Payslip Report</a></li>
                        <li><a href="attendance-reports.html">Attendance Report</a></li>
                        <li>{!! privilege_link(
                            'form/leave/reports/page',
                            ['HR', 'Admin'],
                            'Leave Report',
                            set_active(['form/leave/reports/page']),
                        ) !!}</li>
                        <li>{!! privilege_link(
                            'form/daily/reports/page',
                            ['HR', 'Admin'],
                            'Daily Report',
                            set_active(['form/daily/reports/page']),
                        ) !!}</li>
                    </ul>
                </li>

                <li class="menu-title"> <span>Performance</span> </li>
                <li
                    class="{{ set_active(['form/performance/indicator/page', 'form/performance/page', 'form/performance/appraisal/page']) }} submenu">
                    <a href="#"
                        class="{{ set_active(['form/performance/indicator/page', 'form/performance/page', 'form/performance/appraisal/page']) ? 'noti-dot' : '' }}">
                        <i class="la la-graduation-cap"></i> <span> Performance </span> <span class="menu-arrow"></span>
                    </a>
                    <ul style="{{ request()->is('/*') ? 'display: block;' : 'display: none;' }}">
                        <li>{!! privilege_link(
                            'form/performance/indicator/page',
                            ['HR', 'Admin', 'DPO'],
                            'Performance Indicator',
                            set_active(['form/performance/indicator/page']),
                        ) !!}</li>
                        <li>{!! privilege_link(
                            'form/performance/page',
                            ['HR', 'Admin', 'DPO'],
                            'Performance Review',
                            set_active(['form/performance/page']),
                        ) !!}</li>
                        <li>{!! privilege_link(
                            'form/performance/appraisal/page',
                            ['HR', 'Admin', 'DPO'],
                            'Performance Appraisal',
                            set_active(['form/performance/appraisal/page']),
                        ) !!}</li>
                    </ul>
                </li>

                <li class="{{ set_active(['form/training/list/page', 'form/trainers/list/page']) }} submenu">
                    <a href="#"
                        class="{{ set_active(['form/training/list/page', 'form/trainers/list/page']) ? 'noti-dot' : '' }}">
                        <i class="la la-edit"></i> <span> Training </span> <span class="menu-arrow"></span>
                    </a>
                    <ul style="{{ request()->is('/*') ? 'display: block;' : 'display: none;' }}">
                        <li>{!! privilege_link(
                            'form/training/list/page',
                            ['HR', 'Admin', 'DPO'],
                            'Training List',
                            set_active(['form/training/list/page']),
                        ) !!}</li>
                        <li>{!! privilege_link(
                            'form/trainers/list/page',
                            ['HR', 'Admin', 'DPO'],
                            'Trainers',
                            set_active(['form/trainers/list/page']),
                        ) !!}</li>
                        <li>{!! privilege_link(
                            'form/training/type/list/page',
                            ['HR', 'Admin', 'DPO'],
                            'Training Type',
                            set_active(['form/training/type/list/page']),
                        ) !!}</li>
                    </ul>
                </li>
                @endif

                @if(in_array(Auth::user()->role_name, ['Admin','Maintenance']))
                <!--*
                    *
                    * PURCHASE ORDER LISTING
                    *
                    *-->
                <li class="menu-title"> <span>Request P/O</span> </li>
                <li class="{{ set_active(['purchase.index', 'mainIndex']) }} submenu">
                    <a href="#" class="{{ set_active(['purchase.index', 'mainIndex']) ? 'noti-dot' : '' }}">
                        <i class="la la-money-bill-wave"></i> <span>Purchase Order</span> <span
                            class="menu-arrow"></span>
                    </a>
                    <ul style="{{ request()->is('/*') ? 'display: block;' : 'display: none;' }}">
                        <li>
                            {!! privilege_link('purchase.index', ['Maintenance', 'Admin'], 'Manage Request', set_active(['purchase.index'])) !!}
                        </li>
                    </ul>
                </li>

                <li class="{{ set_active(['receiving.index']) }} submenu">
                    <a href="#" class="{{ set_active(['receiving.index']) ? 'noti-dot' : '' }}">
                        <i class="la la-truck"></i> <span>Receving</span> <span class="menu-arrow"></span>
                    </a>
                    <ul style="{{ request()->is('/*') ? 'display: block;' : 'display: none;' }}">
                        <li>
                            {!! privilege_link(
                                'receiving.index',
                                ['Maintenance', 'Admin'],
                                'Manage Orders',
                                set_active(['receiving.index']),
                            ) !!}
                        </li>
                    </ul>
                </li>

                <li class="{{ set_active(['view.index']) }} submenu">
                    <a href="#" class="{{ set_active(['view.index']) ? 'noti-dot' : '' }}">
                        <i class="la la-tools"></i> <span>Parts Out</span> <span class="menu-arrow"></span>
                    </a>
                    <ul style="{{ request()->is('/*') ? 'display: block;' : 'display: none;' }}">
                        <li>
                            {!! privilege_link('view.index', ['Maintenance', 'Admin'], 'Manage Parts', set_active(['view.index'])) !!}
                        </li>
                    </ul>
                </li>

                <li class="{{ set_active(['transfer.index']) }} submenu">
                    <a href="#" class="{{ set_active(['transfer.index']) ? 'noti-dot' : '' }}">
                        <i class="la la-retweet"></i> <span>Stock Transfers</span> <span class="menu-arrow"></span>
                    </a>
                    <ul style="{{ request()->is('/*') ? 'display: block;' : 'display: none;' }}">
                        <li>
                            {!! privilege_link(
                                'transfer.index',
                                ['Maintenance', 'Admin'],
                                'Transfer Records',
                                set_active(['transfer.index']),
                            ) !!}
                        </li>
                    </ul>
                </li>

                <li class="{{ set_active(['/stocks/Mirasol']) }} submenu">
                    <a href="#" class="{{ set_active(['/stocks/Mirasol']) ? 'noti-dot' : '' }}">
                        <i class="la la-chart-bar"></i> <span>Stock</span> <span class="menu-arrow"></span>
                    </a>
                    <ul style="{{ request()->is('/*') ? 'display: block;' : 'display: none;' }}">
                        <li>
                            {!! privilege_link(
                                '/stocks/Mirasol',
                                ['Maintenance', 'Admin'],
                                'Stocks Garage',
                                set_active(['/stocks/Mirasol']),
                            ) !!}
                        </li>
                    </ul>
                </li>
                <!--*
                    *
                    *   MAINTENANCE LISTING
                    *
                    *-->
                <li class="menu-title"><span>Maintenance</span></li>

                <li
                    class="{{ set_active([
                        'category/list',
                        'form/saving',
                        'form/delete',
                        'brand/list',
                        'form/brand/saving',
                        'form/brand/update',
                        'form/brand/delete',
                        'unit/list',
                        'form/unit/saving',
                        'form/unit/update',
                        'form/unit/delete',
                        'product/list',
                        'form/product/saving',
                        'form/product/update',
                        'form/product/delete',
                        'getProductCode',
                        'garageIndex',
                    ]) }} submenu">
                    <a href="#"
                        class="{{ set_active(['product/list', 'category/list', 'brand/list', 'unit/list']) ? 'noti-dot' : '' }}">
                        <i class="la la-cube"></i> <span>Products</span> <span class="menu-arrow"></span>
                    </a>
                    <ul style="{{ request()->is('/*') ? 'display: block;' : 'display: none;' }}">
                        <li>{!! privilege_link('product/list', ['Maintenance', 'Admin'], 'All Product', set_active(['product/list'])) !!}</li>
                        <li>{!! privilege_link('category/list', ['Maintenance', 'Admin'], 'Category', set_active(['category/list'])) !!}</li>
                        <li>{!! privilege_link('brand/list', ['Maintenance', 'Admin'], 'Brand', set_active(['brand/list'])) !!}</li>
                        <li>{!! privilege_link('unit/list', ['Maintenance', 'Admin'], 'Unit', set_active(['unit/list'])) !!}</li>
                    </ul>
                </li>

                <li class="{{ set_active(['garageIndex', 'garage/list']) }} submenu">
                    <a href="#" class="{{ set_active(['garageIndex', 'garage/list']) ? 'noti-dot' : '' }}">
                        <i class="la la-building"></i> <span>Garage</span> <span class="menu-arrow"></span>
                    </a>
                    <ul style="{{ request()->is('/*') ? 'display: block;' : 'display: none;' }}">
                        <li>{!! privilege_link('garage/list', ['Maintenance', 'Admin'], 'List', set_active(['garage/list'])) !!}</li>
                    </ul>
                </li>

                <li class="{{ set_active(['supplierIndex', 'supplier/list']) }} submenu">
                    <a href="#" class="{{ set_active(['supplierIndex', 'supplier/list']) ? 'noti-dot' : '' }}">
                        <i class="la la-box"></i> <span>Supplier</span> <span class="menu-arrow"></span>
                    </a>
                    <ul style="{{ request()->is('/*') ? 'display: block;' : 'display: none;' }}">
                        <li>{!! privilege_link('supplier/list', ['Maintenance', 'Admin'], 'List', set_active(['supplier/list'])) !!}</li>
                    </ul>
                </li>
                @endif

                @if(in_array(Auth::user()->role_name, ['Admin',]))
                <!--*
                *
                * AUTHENTICATION LISTING
                *
                *-->
                <li class="menu-title"><span>Authentication</span></li>
                <li
                    class="{{ set_active(['all/employee/list', 'search/user/list', 'userManagement', 'activity/log', 'activity/login/logout']) }} submenu">
                    <a href="#"
                        class="{{ set_active(['all/employee/list', 'search/user/list', 'userManagement', 'activity/log', 'activity/login/logout']) ? 'noti-dot' : '' }}">
                        <i class="la la-user-secret"></i> <span> User Controller</span> <span
                            class="menu-arrow"></span>
                    </a>
                    <ul style="{{ request()->is('/*') ? 'display: block;' : 'display: none;' }}">
                        <li>{!! privilege_link('userManagement', ['Admin'], 'All User', set_active(['search/user/list', 'userManagement'])) !!}</li>
                        <li>{!! privilege_link(
                            'employees.request',
                            ['Admin', 'DPO'],
                            'For Approval Request',
                            set_active(['all/employee/list']),
                        ) !!}</li>

                    </ul>
                </li>
                @endif
        </div>
    </div>
</div>
