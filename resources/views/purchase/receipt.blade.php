@extends('layouts.master')

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="card p-4" id="print-area">
                            <div class="row">
                                <div class="col-md-6">
                                    <img width="400" src="{{ asset('assets/img/ESGroup-Logo.png') }}" alt="Company Logo">
                                    <h5 class="mt-2">ES TRANSPORT INC.</h5>
                                    <p>#14 Mirasol, San Roque Cubao<br> Quezon City, Metro Manila<br>
                                        <a href="www.estransport.ph">www.estransport.ph</a><br>
                                        <strong>Email:</strong> espurchasingdept@gmail.com<br>
                                        <strong>Tel:</strong> 8421-0728 | <strong>Fax:</strong> 8421-0725</p>
                                </div>
                                <div class="col-md-6 text-right">
                                    <h4 class="border p-2 d-inline-block">Purchase Order</h4>
                                    <p><strong>Order #:</strong> {{ $purchaseOrder->po_number }}</p>
                                    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($purchaseOrder->created_at)->format('m/d/Y') }}</p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <p><strong>Vendor Address:</strong> {{ $supplier->supplier_name ?? 'N/A' }}</p>
                                <p><strong>Contact:</strong> Ms. Camille</p>
                                <p><strong>Phone:</strong> 09176728634</p>
                            </div>

                            <div class="mt-4 border p-2">
                                <p><strong>Payment Terms:</strong> {{ $purchaseOrder->payment_terms ?? 'N/A' }}</p>
                            </div>

                            <table class="table table-bordered mt-3">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Sub-Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($receipt as $order)
                                        <tr>
                                            <td>{{ $order->product_name }}</td>
                                            <td>{{ $order->qty }}</td>
                                            <td>Php {{ number_format($order->amount / $order->qty, 2) }}</td>
                                            <td>Php {{ number_format($order->amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-right">Sub-total:</th>
                                        <th>Php {{ number_format($receipt->sum(fn($order) => $order->amount), 2) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-right">Total:</th>
                                        <th>Php {{ number_format($receipt->sum(fn($order) => $order->total_amount), 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="border p-2">
                                        <p><strong>Remarks:</strong></p>
                                        <p>R.O. #</p>
                                    </div>
                                </div>
                                <div class="col-md-6 text-right">
                                    <p>Prepared & Approved By:</p>
                                    <h5>Myca Lavella Maderse</h5>
                                    <p>( Purchaser )</p>
                                </div>
                            </div>
                        </div>

                        <!-- Print Button -->
                        <div class="text-center mt-3">
                            <button class="btn btn-primary" onclick="printPurchaseOrder()">
                                <i class="fa fa-print"></i> Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function printPurchaseOrder() {
            var printContents = document.getElementById('print-area').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
@endsection
