<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Appointment Confirmation</title>
  <style>
    body {
      font-family: DejaVu Sans, Arial, sans-serif;
      font-size: 14px;
      background: #f9fafb;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 600px;
      margin: 40px auto;
      background: #fff;
      padding: 24px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .header {
      text-align: center;
      margin-bottom: 24px;
    }
    .header img {
      max-height: 60px;
      margin-bottom: 12px;
    }
    .header h2 {
      margin: 0;
      font-size: 22px;
      color: #333;
    }
    .card {
      border-top: 2px solid #ED4A69;
      padding-top: 16px;
    }
    .row {
      margin-bottom: 10px;
    }
    .label {
      font-weight: bold;
      width: 180px;
      display: inline-block;
      color: #444;
    }
    .qr {
      text-align: center;
      margin-top: 20px;
    }
    .qr img {
      border: 4px solid #eee;
      border-radius: 8px;
      padding: 8px;
      background: #fff;
    }
    .note {
      margin-top: 20px;
      font-size: 13px;
      color: #666;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Header with logo -->
    <div class="header">
      <img src="{{ asset('image/easeP.jpg') }}"  alt="EasePrint">
      <h2>Appointment Confirmation</h2>
    </div>

    <!-- Appointment Info -->
    <div class="card">
      <div class="row"><span class="label">Status:</span> {{ strtoupper($appointment->status) }}</div>
      <div class="row"><span class="label">Full Name:</span> {{ $appointment->full_name }}</div>
      <div class="row"><span class="label">Phone:</span> {{ $appointment->phone }}</div>
      <div class="row"><span class="label">Email:</span> {{ $appointment->email }}</div>
      <div class="row"><span class="label">Schedule Date:</span> {{ \Carbon\Carbon::parse($appointment->schedule_date)->format('F d, Y') }}</div>
      
      @if($appointment->notes)
        <div class="row"><span class="label">Notes:</span> {{ $appointment->notes }}</div>
      @endif

 
<!-- QR Code -->
<div class="qr" style="text-align: center; margin-top: 20px;">
  <div class="row" style="margin-bottom: 10px;">
    <span class="label" style="font-weight: bold;">QR Token:</span>
    <span>{{ $appointment->token }}</span>
  </div>

  @if(!empty($qrCodeBase64))
      <img 
        src="data:image/svg+xml;base64,{{ $qrCodeBase64 }}" 
        width="200" 
        height="200" 
        alt="QR Code"
      >
  @endif
</div>



      <p class="note">Please present this QR code at EasePrint to check in.</p>
    </div>
  </div>
</body>
</html>
