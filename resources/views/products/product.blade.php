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
                        <h3 class="page-title">Product</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Product List</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_product"><i class="fa fa-plus"></i> Add Product</a>
                    </div>
                </div>
            </div>
			<!-- /Page Header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable" id="productList" style="width: 100%">
                            <thead>
                                <tr>
                                    <th hidden>ID</th> 
                                    <th>No</th>
                                    <th>Category</th>
                                    <th>Product Code</th>
                                    <th>Product Name</th>
                                    <th>Serial</th>
                                    <th>Unit</th>
                                    <th>Details</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productview as $key => $items)
                                    <tr>
                                        <td hidden class="id">{{ $items->id }}</td>
                                        <td>{{ ++$key }}</td>
                                        <td class="product_category">{{ $items->category_name ?? 'Category not found' }}</td>
                                        <td class="product_code">{{ $items->product_code }}</td>
                                        <td class="product_name">{{ $items->product_name ?? 'Product Name not found' }}</td>
                                        <td class="product_brand">{{ $items->product_serial ?? 'Serial not found' }}</td>
                                        <td class="product_unit">{{ $items->unit_name ?? 'Unit not found' }}</td>
                                        <td class="product_details">{{ $items->product_parts_details ?? 'No Details' }}</td>
                                        <td class="product_status">
                                            <span class="badge badge-large
                                                @if($items->product_status == 'Active') 
                                                    bg-inverse-primary 
                                                @elseif($items->product_status == 'InActive') 
                                                    bg-inverse-danger
                                                @endif">
                                                {{$items->product_status}}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item productUpdate" data-toggle="modal" data-id="{{ $items->id }}" data-target="#edit_product"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                    <a class="dropdown-item productDelete" href="#" data-toggle="modal" data-target="#delete_product"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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
        <div class="modal custom-modal fade" id="add_product" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <form action="{{ route('form/product/saving') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">Category <span class="text-danger">*</span></label>
                                    <select class="select select2s-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="p_category" name="product_category" required>
                                        <option value="">-- Select --</option>
                                        @foreach ($category as $key=>$items)
                                            <option value="{{ $items->id }}">{{ $items->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">Product Code <span class="text-danger">*</span></label>
                                    <input class="form-control" id="p_code" name="product_code" placeholder="Auto Product Code" readonly required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Product Name <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="p_name" name="product_name" readonly required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Serial Number <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="p_serial" name="product_serial" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Brand <span class="text-danger">*</span></label>
                                    <select class="select form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="p_brand" name="product_brand" disabled>
                                        <option value="">-- Select --</option>
                                        @foreach ($brand as $key=>$items)
                                            <option value="{{ $items->id }}">{{ $items->brand_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">Unit <span class="text-danger">*</span></label>
                                    <select class="select form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="p_unit" name="product_unit" disabled required>
                                        <option value="">-- Select --</option>
                                        @foreach ($unit as $key=>$items)
                                            <option value="{{ $items->id }}">{{ $items->unit_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="col-form-label">Details <span class="text-danger">*</span></label>
                                    <textarea class="form-control" type="text" id="p_details" name="product_details" rows="3" readonly></textarea>
                                </div>
                                <div class="submit-section">
                                    <button type="submit" class="btn btn-primary submit-btn" id="submit-btn" disabled>Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
        <!-- /Add Category Modal -->

        <!-- Edit Category Modal -->
        <div class="modal custom-modal fade" id="edit_product" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('form/product/update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="e_id" value="">
                            <div class="form-group">
                                <label>Status <span class="text-danger">*</span></label>
                                <div>
                                    <select class="form-control custom-select" id="product_edit" name="product_status" required>
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
        <div class="modal custom-modal fade" id="delete_product" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Category</h3>
                            <p>Are you sure you want to delete this category?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <form action="{{ route('form/product/delete') }}" method="POST">
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
        $(document).on('click', '.productUpdate', function() {
            var _this = $(this).closest('tr');
            $('#e_id').val(_this.find('.id').text().trim());
            $('#product_edit').val(_this.find('.product_status span').text().trim());
        });
    </script>

<!-- {{-- Delete Category JS --}} -->
<!-- {{-- Delete Category JS --}} -->
<!-- {{-- Delete Category JS --}} -->

    <script>
        $(document).on('click', '.productDelete', function() {
            var _this = $(this).closest('tr');
            var categoryId = _this.find('.id').text().trim();
            $('.e_id').val(categoryId); 
        });
    </script>
    
<!-- {{---- Filter Pagination ----}} -->
<!-- {{---- Filter Pagination ----}} -->
<!-- {{---- Filter Pagination ----}} -->

    <script>
        $(document).ready(function() {
            $('#categoryList').DataTable();
        });
    </script>

<!-- --- Auto Generated Product Code ---- -->
<!-- --- Auto Generated Product Code ---- -->
<!-- --- Auto Generated Product Code ---- -->

    <script>
        $(document).ready(function() {
            $('#p_brand').prop('disabled', true);
            $('#p_unit').prop('disabled', true);
            $('#submit-btn').prop('disabled', true);

            $('#p_category').change(function() {
                var categoryId = $(this).val();
                
                if (categoryId) {
                    $('#p_name').removeAttr('readonly');
                    $('#p_serial').removeAttr('readonly');
                    $('#p_brand').prop('disabled', false);
                    $('#p_unit').prop('disabled', false);
                    $('#p_details').removeAttr('readonly');
                    $('#submit-btn').prop('disabled', false);

                    $.ajax({
                        url: "{{ route('get.product.code') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            category_id: categoryId
                        },
                        success: function(response) {
                            $('#p_code').val(response.product_code);
                        }
                    });
                } else {
                    $('#p_name').attr('readonly', true);
                    $('#p_serial').attr('readonly', true);
                    $('#p_brand').prop('disabled', true);
                    $('#p_unit').prop('disabled', true);
                    $('#p_details').attr('readonly', true);
                    $('#submit-btn').prop('disabled', true);
                    $('#p_code').val('');
                }
            });
        });
    </script>

    @endsection
@endsection
