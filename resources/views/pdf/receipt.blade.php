<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt #{{ $order->order_id }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #222;
            margin: 0;
            background: #fff;
        }

        /* HEADER */
        .header {
            background: #000;
            color: #fff;
            padding: 10px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-left img {
            width: 45px;
        }
        .header-right {
            text-align: right;
        }
        .header-right h2 {
            margin: 0;
            font-size: 14px;
            letter-spacing: 0.4px;
        }
        .header-right p {
            margin: 2px 0 0;
            font-size: 9px;
            color: #ff3b3b;
        }

        /* BODY CONTAINER */
        .content {
            padding: 16px 22px;
        }

        /* INFO SECTION */
        .info {
            margin-bottom: 10px;
            line-height: 1.5;
        }
        .info p {
            margin: 0;
        }
        .info span {
            font-weight: bold;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        th {
            background: #000;
            color: #fff;
            padding: 6px 5px;
            font-size: 10px;
            text-align: left;
        }
        td {
            padding: 6px 5px;
            border-bottom: 1px solid #ccc;
            font-size: 10px;
        }
        td:last-child, th:last-child {
            text-align: right;
        }

        /* TOTALS */
        .totals {
            margin-top: 8px;
            float: right;
            width: 190px;
            font-size: 10px;
        }
        .totals div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        .totals .total {
            border-top: 1px solid #000;
            padding-top: 3px;
            font-weight: bold;
            font-size: 11px;
        }

        /* RETURN POLICY */
        .policy {
            clear: both;
            margin-top: 18px;
            font-size: 9px;
            line-height: 1.4;
        }
        .policy strong {
            display: block;
            margin-bottom: 3px;
        }

        /* SIGNATURES */
        .signatures {
            margin-top: 18px;
            display: flex;
            justify-content: space-between;
            font-size: 9px;
        }
        .sign-line {
            width: 42%;
            text-align: center;
        }
        .sign-line span {
            display: block;
            margin-top: 4px;
            border-top: 1px solid #000;
            padding-top: 2px;
        }

        /* FOOTER */
        .footer {
            background: #000;
            color: #fff;
            text-align: center;
            font-size: 9px;
            padding: 6px 0;
            margin-top: 20px;
        }
        .accent {
            color: #ff3b3b;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <div class="header">
        <div class="header-left">
            <img src="{{ public_path('image/easeP.jpg') }}" alt="Ease Print Logo">
        </div>
        <div class="header-right">
            <h2>OFFICIAL RECEIPT</h2>
            <p>{{ strtoupper($order->created_at->format('d F Y')) }}</p>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="content">

        <!-- INFO -->
        <div class="info">
            <p><span>Name:</span> {{ strtoupper($order->delivery_name ?? 'N/A') }}</p>
            <p><span>Phone:</span> {{ $order->delivery_phone ?? 'N/A' }}</p>
            <p><span>Payment:</span> {{ ucfirst($order->payment_method) }}</p>
            <p><span>Receipt No:</span> #{{ $order->order_id }}</p>
            <p><span>Date:</span> {{ $order->created_at->format('M d, Y h:i A') }}</p>
        </div>

        <!-- ITEMS -->
        <table>
            <thead>
                <tr>
                    <th style="width:6%;">#</th>
                    <th>Product</th>
                    <th style="width:14%;">Qty</th>
                    <th style="width:14%;">Size</th>
                    <th style="width:20%;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity ?? 1 }}</td>
                        <td>{{ $item->size ?? 'N/A' }}</td>
                        <td>₱{{ number_format(($item->price ?? 0) * ($item->quantity ?? 1), 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- TOTALS -->
        <div class="totals">
            <div><span>Subtotal:</span><span>₱{{ number_format($order->total, 2) }}</span></div>
            <div class="total"><span>Total:</span><span>₱{{ number_format($order->total, 2) }}</span></div>
        </div>

        <div style="clear:both;"></div>

        <!-- POLICY -->
        <div class="policy">
            <strong>Return Policy</strong>
            1. Returns accepted within 7 days for defects or wrong items.<br>
            2. Receipt required for all returns or exchanges.<br>
            3. Custom prints are non-refundable once approved.
        </div>

        <!-- SIGNATURES -->
        <div class="signatures">
            <div class="sign-line"><span>Customer Signature</span></div>
            <div class="sign-line"><span>Ease Print Representative</span></div>
        </div>

    </div>

    <!-- FOOTER -->
    <div class="footer">
        <span class="accent">Ease Print</span> — Quality Custom Printing Services
    </div>

</body>
</html>
