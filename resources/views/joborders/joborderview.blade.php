
@extends('layouts.master')
@section('content')
    {{-- message --}}
    
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Job Order View</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">View</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            
            <section class="review-section information">
                <div class="review-header text-center">
                    <h2 class="review-title">{{ $jobDetail->job_name }}</h2>
                    <p class="text-muted">Bus Name</p>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-nowrap review-table mb-0">
                                <tbody>
                                    <tr>
                                        <td>
                                            <form>
                                                <div class="form-group">
                                                    <label for="name">Bus Name</label>
                                                    <input type="text" class="form-control" id="name" value="{{ $jobDetail->job_name }}" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="depart3">Department</label>
                                                    <input type="text" class="form-control" id="depart3">
                                                </div>
                                                <div class="form-group">
                                                    <label for="departa">Designation</label>
                                                    <input type="text" class="form-control" id="departa">
                                                </div>
                                                <div class="form-group">
                                                    <label for="qualif">Qualification: </label>
                                                    <input type="text" class="form-control" id="qualif">
                                                </div>
                                            </form>
                                        </td>
                                        <td>
                                            <form>
                                                <div class="form-group">
                                                    <label for="doj">Emp ID</label>
                                                    <input type="text" class="form-control" value="DGT-009">
                                                </div>
                                                <div class="form-group">
                                                    <label for="doj">Date of Join</label>
                                                    <input type="text" class="form-control" id="doj">
                                                </div>
                                                <div class="form-group">
                                                    <label for="doc">Date of Confirmation</label>
                                                    <input type="text" class="form-control" id="doc">
                                                </div>
                                                <div class="form-group">
                                                    <label for="qualif1">Previous years of Exp</label>
                                                    <input type="text" class="form-control" id="qualif1">
                                                </div>
                                            </form>
                                        </td>
                                        <td>
                                            <form>
                                                <div class="form-group">
                                                    <label for="name1"> RO's Name</label>
                                                    <input type="text" class="form-control" id="name1">
                                                </div>
                                                <div class="form-group">
                                                    <label for="depart1"> RO Designation: </label>
                                                    <input type="text" class="form-control" id="depart1">
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>	 
            
            <section class="review-section professional-excellence">
                <div class="review-header text-center">
                    <h3 class="review-title">Professional Excellence</h3>
                    <p class="text-muted">Lorem ipsum dollar</p>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered review-table mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:40px;">#</th>
                                        <th>Key Result Area</th>
                                        <th>Key Performance Indicators</th>
                                        <th>Weightage</th>
                                        <th>Percentage achieved <br>( self Score )</th>
                                        <th>Points Scored <br>( self )</th>
                                        <th>Percentage achieved <br>( RO's Score )</th>
                                        <th>Points Scored <br>( RO )</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td rowspan="2">1</td>
                                        <td rowspan="2">Production</td>
                                        <td>Quality</td>
                                        <td><input type="text" class="form-control" readonly value="30"></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>TAT (turn around time)</td>
                                        <td><input type="text" class="form-control" readonly value="30"></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Process Improvement</td>
                                        <td>PMS,New Ideas</td>
                                        <td><input type="text" class="form-control" readonly value="10"></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Team Management</td>
                                        <td>Team Productivity,dynaics,attendance,attrition</td>
                                        <td><input type="text" class="form-control" readonly value="5"></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Knowledge Sharing</td>
                                        <td>Sharing the knowledge for team productivity </td>
                                        <td><input type="text" class="form-control" readonly value="5"></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>Reporting and Communication</td>
                                        <td>Emails/Calls/Reports and Other Communication</td>
                                        <td><input type="text" class="form-control" readonly value="5"></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-center">Total </td>
                                        <td><input type="text" class="form-control" readonly value="85"></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                        <td><input type="text" class="form-control" readonly value="0"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            <section class="review-section">
                <div class="review-header text-center">
                    <h3 class="review-title">Comments on the role</h3>
                    <p class="text-muted">alterations if any requirred like addition/deletion of responsibilities</p>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-review review-table mb-0" id="table_alterations">
                                <thead>
                                    <tr>
                                        <th style="width:40px;">#</th>
                                        <th>By Self</th>
                                        <th>RO's Comment</th>
                                        <th>HOD's Comment</th>
                                        <th style="width: 64px;"><button type="button" class="btn btn-primary btn-add-row"><i class="fa fa-plus"></i></button></th>
                                    </tr>
                                </thead>
                                <tbody id="table_alterations_tbody">
                                    <tr>
                                        <td>1</td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td><input type="text" class="form-control" ></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
@endsection
