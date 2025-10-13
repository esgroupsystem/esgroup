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
                                    </p>
                                </div>
                                <div class="col-md-6 text-right">
                                    <h4 class="border p-2 d-inline-block">Purchase Order</h4>
                                    <p><strong>Order #:</strong> {{ $transaction->purchase_id }}</p>
                                    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($transaction->created_at)->format('m/d/Y') }}</p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <p><strong>Vendor:</strong> {{ $supplier->supplier_name ?? $transaction->supplier_name }}</p>
                                <p><strong>Contact:</strong> {{ $supplier->contact_person ?? 'N/A' }}</p>
                                <p><strong>Phone:</strong> {{ $supplier->contact_number ?? 'N/A' }}</p>
                            </div>

                            <div class="mt-4 border p-2">
                                <p><strong>Payment Terms:</strong> {{ $transaction->payment_terms ?? 'N/A' }}</p>
                                <p><strong>Status:</strong> {{ $transaction->status_receiving ?? 'N/A' }}</p>
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
                                    @foreach ($transactions as $item)
                                        <tr>
                                            <td>{{ $item->product_complete_name }}</td>
                                            <td>{{ $item->product_qty }}</td>
                                            <td>₱ {{ number_format($item->grand_total / max($item->product_qty, 1), 2) }}</td>
                                            <td>₱ {{ number_format($item->grand_total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-right">Sub-total:</th>
                                        <th>₱ {{ number_format($transactions->sum('grand_total'), 2) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-right">Total Amount:</th>
                                        <th>₱ {{ number_format($transactions->sum('total_amount'), 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="border p-2">
                                        <p><strong>Remarks:</strong></p>
                                        <p>{{ $transaction->remarks ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 text-right">
                                    <p>Prepared & Approved By:</p>
                                    <h5>Myca Lavella Maderse</h5>
                                    <p>(Purchaser)</p>
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
            const printContents = document.getElementById('print-area').innerHTML;
            const originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
@endsection
