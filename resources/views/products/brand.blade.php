@extends('layouts.master')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Brand</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Brand List</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_brand"><i class="fa fa-plus"></i> Add Brand</a>
                    </div>
                </div>
            </div>
			<!-- /Page Header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable" id="brandList" style="width: 100%">
                            <thead>
                                <tr>
                                    <th hidden>ID</th> 
                                    <th>No</th>
                                    <th>Brand Name</th>
                                    <th>Status</th>
                                    <th>Created by</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($brandview as $key => $items)
                                    <tr>
                                        <td hidden class="id">{{ $items->id }}</td>
                                        <td>{{ ++$key }}</td>
                                        <td class="brand_name">{{ $items->brand_name }}</td>
                                        <td class="brand_status">
                                            <span class="badge badge-large
                                                @if($items->brand_status == 'Active') 
                                                    bg-inverse-primary 
                                                @elseif($items->brand_status == 'InActive') 
                                                    bg-inverse-danger
                                                @endif">
                                                {{$items->brand_status}}
                                            </span>
                                        </td>
                                        <td>{{ $items->brand_creator }}</td>
                                        <td>
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item brandUpdate" data-toggle="modal" data-id="{{ $items->id }}" data-target="#edit_brand"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                    <a class="dropdown-item brandDelete" href="#" data-toggle="modal" data-target="#delete_brand"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->

        <!-- Add Category Modal -->
        <div class="modal custom-modal fade" id="add_brand" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Brand</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('form/brand/saving') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Brand Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="nameBrand" name="brand_name">
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Category Modal -->

        <!-- Edit Category Modal -->
        <div class="modal custom-modal fade" id="edit_brand" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Brand</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('form/brand/update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="e_id" value="">
                            <div class="form-group">
                                <label>Brand Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="brandname_edit" name="brand_name">
                            </div>
                            <div class="form-group">
                                <label>Status <span class="text-danger">*</span></label>
                                <div>
                                    <select class="form-control custom-select" id="brandstatus_edit" name="brand_status" required>
                                        <option value="Active">Active</option>
                                        <option value="InActive">InActive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Category Modal -->

        <!-- Delete Category Modal -->
        <div class="modal custom-modal fade" id="delete_brand" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete brand</h3>
                            <p>Are you sure you want to delete this brand?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <form action="{{ route('form/brand/delete') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" class="e_id" value="">
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary continue-btn submit-btn">Delete</button>
                                    </div>
                                    <div class="col-6">
                                        <a href="#" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete Category Modal -->
       
    </div>
    <!-- /Page Wrapper -->


    @section('script')


    {{-- Update Category JS --}}
    <script>
        $(document).on('click', '.brandUpdate', function() {
            var _this = $(this).closest('tr');
            $('#e_id').val(_this.find('.id').text().trim());
            $('#brandname_edit').val(_this.find('.brand_name').text().trim());
            $('#brandstatus_edit').val(_this.find('.brand_status span').text().trim());
        });
    </script>

    {{-- Delete Category JS --}}
    <script>
        $(document).on('click', '.brandDelete', function() {
            var _this = $(this).closest('tr');
            var categoryId = _this.find('.id').text().trim();
            $('.e_id').val(categoryId); 
        });
    </script>
    
    {{---- Filter Pagination ----}}
    <script>
        $(document).ready(function() {
            $('#brandList').DataTable();
        });
    </script>



    @endsection
@endsection
