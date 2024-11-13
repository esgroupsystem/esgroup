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
                        <h3 class="page-title">Supplier</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Supplier List</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_supplier"><i class="fa fa-plus"></i> Add Supplier</a>
                    </div>
                </div>
            </div>
			<!-- /Page Header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable" id="supplierList" style="width: 100%">
                            <thead>
                                <tr>
                                    <th hidden>ID</th> 
                                    <th>No</th>
                                    <th>Supplier Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($supplierview as $key => $items)
                                    <tr>
                                        <td hidden class="id">{{ $items->id }}</td>
                                        <td>{{ ++$key }}</td>
                                        <td class="g_name">{{ $items->supplier_name ?? 'Supplier not found' }}</td>
                                        <td class="g_status">
                                            <span class="badge badge-large
                                                @if($items->supplier_status == 'Active') 
                                                    bg-inverse-primary 
                                                @elseif($items->supplier_status == 'InActive') 
                                                    bg-inverse-danger
                                                @endif">
                                                {{$items->supplier_status}}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item supplierUpdate" data-toggle="modal" data-id="{{ $items->id }}" data-target="#edit_supplier"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                    <a class="dropdown-item supplierDelete" href="#" data-toggle="modal" data-target="#delete_supplier"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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
        <div class="modal custom-modal fade" id="add_supplier" role="dialog">
            <div class="modal-dialog modal-dialog-centered" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Supplier</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <form action="{{ route('supplier/save') }}" method="POST">
                        @csrf
                            <div class="form-group">
                                <label class="col-form-label">Supplier Name <span class="text-danger">*</span></label>
                                <input class="form-control" id="supplier" name="supplier_name" placeholder="" required>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn" id="submit-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Category Modal -->

        <!-- Edit Category Modal -->
        <div class="modal custom-modal fade" id="edit_supplier" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('form/supplier/update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="e_id" value="">
                            <div class="form-group">
                                <label>Status <span class="text-danger">*</span></label>
                                <div>
                                    <select class="form-control custom-select" id="product_edit" name="supplier_status" required>
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
        <div class="modal custom-modal fade" id="delete_supplier" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Category</h3>
                            <p>Are you sure you want to delete this category?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <form action="{{ route('form/supplier/delete') }}" method="POST">
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
<!-- {{-- Update Category JS --}} -->
<!-- {{-- Update Category JS --}} -->
<!-- {{-- Update Category JS --}} -->

    <script>
        $(document).on('click', '.supplierUpdate', function() {
            var _this = $(this).closest('tr');
            $('#e_id').val(_this.find('.id').text().trim());
            $('#supplier_edit').val(_this.find('.supplier_status span').text().trim());
        });
    </script>

<!-- {{-- Delete Category JS --}} -->
<!-- {{-- Delete Category JS --}} -->
<!-- {{-- Delete Category JS --}} -->

    <script>
        $(document).on('click', '.supplierDelete', function() {
            var _this = $(this).closest('tr');
            var supplierId = _this.find('.id').text().trim();
            $('.e_id').val(supplierId); 
        });
    </script>
    
<!-- {{---- Filter Pagination ----}} -->
<!-- {{---- Filter Pagination ----}} -->
<!-- {{---- Filter Pagination ----}} -->

    <script>
        $(document).ready(function() {
            $('#supplierList').DataTable();
        });
    </script>

    @endsection
@endsection
