<!doctype html>
<html>
<head><meta charset="utf-8"><title>Your Appointment is Approved</title></head>
<body>
  <p>Hi {{ $appointment->full_name }},</p>
  <p>Your fitting appointment for 
     <strong>{{ \Carbon\Carbon::parse($appointment->schedule_date)->format('F d, Y') }}</strong> 
     has been <strong>approved</strong>.</p>

  <p>We’ve attached your official appointment confirmation as a PDF.</p>

  <p>Please download and present it when you arrive at EasePrint.</p>

  <p>See you soon!<br>— EASEPrint Team</p>
</body>
</html>
