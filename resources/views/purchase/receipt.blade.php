{{-- resources/views/purchase/receipt.blade.php --}}
@extends('layouts.master')

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div id="purchase-order-header" class="page-header container-fluid">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Purchase Order Details</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('purchase.list') }}">Purchase Orders</a></li>
                            <li class="breadcrumb-item active">Receipt</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row invoice">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body p30">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="invoice-logo">
                                            <img width="120" src="{{ asset('assets/img/gg.png') }}" alt="Invoice logo">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 text-right">
                                        <ul class="list-unstyled">
                                            <li>ES Group</li>
                                            <li>#14 Street Mirasol</li>
                                            <li>Brgy. San Roque, Murphy</li>
                                            <li>Cubao, Quezon City</li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="invoice-details mt25">
                                    <div class="well">
                                        <ul class="list-unstyled">
                                            <li><strong>Transaction Request ID:</strong> {{ $receipt->first()->transaction_id ?? 'N/A' }}</li>
                                            <li><strong>Request Date:</strong> {{ \Carbon\Carbon::parse($receipt->first()->request_date ?? now())->format('l, F jS, Y') }}</li>
                                            <li><strong>Requestor:</strong> {{ $receipt->first()->requestor_id ?? 'N/A' }}</li>
                                            <li><strong>Garage:</strong> {{ $receipt->first()->garage_id ?? 'N/A' }}</li>
                                            <li><strong>Status:</strong> <span class="label label-danger">UNPAID</span></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="invoice-items">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Description</th>
                                                    <th class="text-center">Qty</th>
                                                    <th class="text-center">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($receipt as $order)
                                                    <tr>
                                                        <td>PO Number: {{ $order->po_no }}</td>
                                                        <td class="text-center">{{ $order->total_qty }}</td>
                                                        <td class="text-center">{{ $order->total_amount }} USD</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="2" class="text-right">Sub Total:</th>
                                                    <th class="text-center">${{ $receipt->sum('total_amount') }} USD</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="2" class="text-right">20% VAT:</th>
                                                    <th class="text-center">${{ $receipt->sum('total_amount') * 0.2 }} USD</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="2" class="text-right">Total:</th>
                                                    <th class="text-center">${{ $receipt->sum('total_amount') * 1.2 }} USD</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <div class="invoice-footer mt25 text-center">
                                    <p>Generated on {{ now()->format('l, F jS, Y') }}</p>
                                    <a href="#" class="btn btn-default">
                                        <i class="fa fa-print"></i> Print
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
