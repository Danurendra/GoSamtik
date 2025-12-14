<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            color: #333;
            line-height: 1.6;
        }
        .container {
            padding: 40px;
        }
        . header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            border-bottom: 2px solid #22c55e;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 28px;
            font-weight:  bold;
            color: #22c55e;
        }
        .logo span {
            color: #333;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h1 {
            font-size: 32px;
            color: #333;
            margin-bottom: 5px;
        }
        . invoice-number {
            color: #666;
        }
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        . info-block h3 {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        . info-block p {
            margin-bottom: 5px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        . table th {
            background-color: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            color: #666;
            border-bottom: 2px solid #e5e7eb;
        }
        .table td {
            padding: 15px 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        .table . amount {
            text-align: right;
        }
        .totals {
            width: 300px;
            margin-left: auto;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding:  8px 0;
        }
        .totals-row. total {
            border-top: 2px solid #333;
            font-weight: bold;
            font-size: 18px;
            padding-top: 15px;
            margin-top: 10px;
        }
        . totals-row . label {
            color: #666;
        }
        .status-paid {
            display: inline-block;
            background-color: #dcfce7;
            color:  #166534;
            padding: 4px 12px;
            border-radius:  20px;
            font-size:  12px;
            font-weight: bold;
        }
        .footer {
            margin-top: 60px;
            text-align:  center;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <table style="width: 100%; margin-bottom: 40px; border-bottom: 2px solid #22c55e; padding-bottom: 20px;">
            <tr>
                <td style="width: 50%;">
                    <div class="logo"><span>Eco</span>Collect</div>
                    <p style="color: #666; margin-top: 5px;">Professional Waste Collection Services</p>
                </td>
                <td style="width:  50%; text-align: right;">
                    <h1 style="font-size: 32px; margin:  0;">INVOICE</h1>
                    <p style="color: #666;">{{ $invoice->invoice_number }}</p>
                </td>
            </tr>
        </table>

        <!-- Info Section -->
        <table style="width: 100%; margin-bottom: 40px;">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <h3 style="font-size:  12px; color: #666; text-transform: uppercase; margin-bottom: 10px;">Bill To</h3>
                    <p style="font-weight: bold;">{{ $invoice->user->name }}</p>
                    <p>{{ $invoice->user->email }}</p>
                    @if($invoice->user->address)
                        <p>{{ $invoice->user->address }}</p>
                    @endif
                </td>
                <td style="width: 50%; vertical-align: top; text-align: right;">
                    <table style="margin-left: auto;">
                        <tr>
                            <td style="padding: 5px 20px 5px 0; color: #666;">Invoice Date: </td>
                            <td style="padding: 5px 0;">{{ $invoice->issue_date->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 20px 5px 0; color:  #666;">Due Date:</td>
                            <td style="padding: 5px 0;">{{ $invoice->due_date->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 20px 5px 0; color: #666;">Status:</td>
                            <td style="padding: 5px 0;">
                                <span class="status-paid">PAID</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Items Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Qty</th>
                    <th style="text-align: right;">Unit Price</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @if($invoice->payment->collection)
                    <tr>
                        <td>
                            <strong>{{ $invoice->payment->collection->serviceType->name }}</strong><br>
                            <span style="color: #666; font-size: 12px;">
                                Scheduled:  {{ $invoice->payment->collection->scheduled_date->format('M d, Y') }}
                            </span>
                        </td>
                        <td>1</td>
                        <td style="text-align: right;">${{ number_format($invoice->payment->amount, 2) }}</td>
                        <td style="text-align:  right;">${{ number_format($invoice->payment->amount, 2) }}</td>
                    </tr>
                @elseif($invoice->payment->subscription)
                    <tr>
                        <td>
                            <strong>{{ $invoice->payment->subscription->subscriptionPlan->name }}</strong><br>
                            <span style="color: #666; font-size: 12px;">Monthly Subscription</span>
                        </td>
                        <td>1</td>
                        <td style="text-align: right;">${{ number_format($invoice->payment->amount, 2) }}</td>
                        <td style="text-align: right;">${{ number_format($invoice->payment->amount, 2) }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Totals -->
        <table style="width: 300px; margin-left: auto;">
            <tr>
                <td style="padding: 8px 0; color: #666;">Subtotal</td>
                <td style="padding: 8px 0; text-align: right;">${{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #666;">Tax (10%)</td>
                <td style="padding: 8px 0; text-align: right;">${{ number_format($invoice->tax_amount, 2) }}</td>
            </tr>
            <tr>
                <td colspan="2" style="border-top: 2px solid #333;"></td>
            </tr>
            <tr>
                <td style="padding:  15px 0 0 0; font-weight: bold; font-size: 18px;">Total</td>
                <td style="padding: 15px 0 0 0; text-align: right; font-weight: bold; font-size: 18px; color: #22c55e;">
                    ${{ number_format($invoice->total_amount, 2) }}
                </td>
            </tr>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for choosing GoSamtik! </p>
            <p style="margin-top: 5px;">Questions? Contact us at support@gosamtik.com</p>
        </div>
    </div>
</body>
</html>