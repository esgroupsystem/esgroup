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
                            <li class="breadcrumb-item"><a>Purchase Orders</a></li>
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
                                            <img width="500" src="{{ asset('assets/img/ESGroup-Logo.png') }}" alt="Invoice logo">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 text-right">
                                        <ul class="list-unstyled">
                                            <li><strong>ES Group</strong></li>
                                            <li>#14 Street Mirasol</li>
                                            <li>Brgy. San Roque, Murphy</li>
                                            <li>Cubao, Quezon City</li>
                                        </ul>
                                    </div>
                                </div>

                                @if ($purchaseOrder)
                                    <div class="invoice-details mt25">
                                        <div class="well">
                                            <ul class="list-unstyled">
                                                <li><strong>PO Number:</strong> {{ $purchaseOrder->po_number }}</li>
                                                <li><strong>Transaction Request ID:</strong> {{ $purchaseOrder->request_id }}</li>
                                                <li><strong>Request Date:</strong> {{ \Carbon\Carbon::parse($purchaseOrder->created_at)->format('l, F jS, Y') }}</li>
                                                <li><strong>Garage:</strong> {{ $purchaseOrder->garage_name }}</li>
                                                <li><strong>Status:</strong> <span class="label label-danger">UNPAID</span></li>
                                            </ul>
                                        </div>
                                    </div>
                                @endif

                                <div class="invoice-items">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Product Code</th>
                                                    <th>Description</th>
                                                    <th class="text-center">Qty</th>
                                                    <th class="text-center">Unit Price</th>
                                                    <th class="text-center">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($receipt as $order)
                                                    <tr>
                                                        <td>{{ $order->product_code }}</td>
                                                        <td>{{ $order->product_name }}</td>
                                                        <td class="text-center">{{ $order->qty }}</td>
                                                        <td class="text-center">
                                                            {{ $order->qty > 0 ? number_format($order->amount / $order->qty, 2) : '0.00' }} PHP
                                                        </td>
                                                        <td class="text-center">{{ number_format($order->amount, 2) }} PHP</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                @php
                                                    $subtotal = $receipt->sum(fn($order) => $order->amount);
                                                    $total = $subtotal * 1.2; // Assuming a 20% tax or fee
                                                @endphp
                                                <tr>
                                                    <th colspan="4" class="text-right">Sub Total:</th>
                                                    <th class="text-center">{{ number_format($subtotal, 2) }} PHP</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="4" class="text-right">Total (with tax):</th>
                                                    <th class="text-center">{{ number_format($total, 2) }} PHP</th>
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
