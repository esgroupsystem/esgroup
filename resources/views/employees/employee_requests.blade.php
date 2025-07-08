@extends('layouts.master')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <!-- Page Content -->
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Approval</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Approval</li>
                    </ul>
                </div>
            </div>
        </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0 {{ count($requests) > 0 ? 'datatable' : '' }}">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Requested By</th>
                                    <th>Requested At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($requests as $req)
                                <tr>
                                    <td>{{ $req->employee_name }}</td>
                                    <td>{{ $req->hr_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($req->created_at)->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <div class="d-flex flex-wrap align-items-center">
                                            <form method="POST" action="{{ url('admin/employee-requests/approve/' . $req->id) }}" class="mr-2 mb-1">
                                                @csrf
                                                <div class="input-group input-group-sm">
                                                    <input type="datetime-local" name="approved_until" class="form-control" required>
                                                    <div class="input-group-append">
                                                        <button type="submit" class="btn btn-success">
                                                            <i class="fa fa-check"></i> Approve
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                            <form method="POST" action="{{ url('admin/employee-requests/reject/' . $req->id) }}" class="mb-1">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-times"></i> Reject
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No pending requests found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('script')
<script>
$(document).ready(function () {
    if ($('.datatable').length) {
        $('.datatable').DataTable();
    }
});
</script>
@endsection
@endsection
