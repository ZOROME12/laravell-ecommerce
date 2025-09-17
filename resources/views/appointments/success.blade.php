<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Appointment Submitted - EasePrint</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      font-family: system-ui, Arial, sans-serif;
      background-color: #FBF8FB;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      padding: 20px;
    }

    .card {
      background: #fff;
      border: 2px solid #FBB3C8;
      border-radius: 16px;
      padding: 32px;
      max-width: 480px;
      width: 100%;
      text-align: center;
      box-shadow: 0 6px 18px rgba(63, 26, 43, 0.15);
    }

    .logo {
      width: 90px;
      margin-bottom: 16px;
    }

    h2 {
      color: #3F1A2B;
      margin-bottom: 12px;
      font-size: 24px;
    }

    p {
      color: #4A2C3D;
      font-size: 16px;
      line-height: 1.6;
    }

    strong {
      color: #B2183A;
    }

    .btn {
      display: inline-block;
      margin-top: 20px;
      background: linear-gradient(135deg, #B2183A, #ED4A69);
      color: #fff;
      font-weight: 600;
      padding: 12px 20px;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-size: 15px;
      text-decoration: none;
      transition: background 0.25s, transform 0.2s;
    }

    .btn:hover {
      background: linear-gradient(135deg, #ED4A69, #B2183A);
      transform: translateY(-2px);
    }
  </style>
</head>
<body>
  <div class="card">
    <!-- Replace logo.png with your EasePrint logo file -->
    <img src="{{ asset('image/easeP.jpg') }}" alt="EasePrint Logo" class="logo">

    <h2>Thank You!</h2>
    <p>Your appointment was submitted and is now <strong>pending</strong>.<br>
    We’ll email you once it’s approved.</p>

    <a href="{{ route('home') }}" class="btn">Back to Home</a>
  </div>
</body>
</html>
