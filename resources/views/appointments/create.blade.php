<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Book Fitting Appointment</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      font-family: system-ui, Arial, sans-serif;
      background-color: #FBF8FB;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px;
    }

    .card {
      background: #fff;
      border: 2px solid #FBB3C8;
      border-radius: 16px;
      padding: 32px 66px;   /* 🔹 wider padding on sides */
      max-width: 520px;
      width: 100%;
      box-shadow: 0 6px 18px rgba(63, 26, 43, 0.15);
    }

    h2 {
      text-align: center;
      color: #3F1A2B;
      margin-bottom: 28px;
      font-size: 24px;
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: 600;
      color: #3F1A2B;
      font-size: 15px;
    }

    input, textarea {
      width: 100%;
      padding: 12px 16px;   /* 🔹 add more horizontal padding inside inputs */
      border: 1px solid #ED4A69;
      border-radius: 10px;
      margin-bottom: 6px;
      font-size: 15px;
      background: #FBF8FB;
      transition: border 0.25s, box-shadow 0.25s;
    }

    input:focus, textarea:focus {
      outline: none;
      border-color: #B2183A;
      box-shadow: 0 0 0 3px rgba(178, 24, 58, 0.2);
    }

    .note-text {
      font-size: 13px;
      color: #B2183A;
      margin-bottom: 14px;
    }

    button {
      width: 100%;
      background: linear-gradient(135deg, #B2183A, #ED4A69);
      color: #fff;
      font-weight: 600;
      padding: 14px;
      border: none;
      border-radius: 12px;
      cursor: pointer;
      font-size: 16px;
      transition: transform 0.2s, background 0.25s;
    }

    button:hover {
      background: linear-gradient(135deg, #ED4A69, #B2183A);
      transform: translateY(-2px);
    }

    .err {
      background: #FBB3C8;
      color: #3F1A2B;
      padding: 12px 14px;
      border-radius: 10px;
      margin-bottom: 18px;
      font-size: 14px;
      line-height: 1.4;
    }
    .err ul {
      margin: 0;
      padding-left: 20px;
    }

    /* Mobile adjustments */
    @media (max-width: 480px) {
      .card {
        padding: 24px 18px;
      }
      h2 {
        font-size: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="card">
    <h2>Book Fitting Appointment</h2>

    @if ($errors->any())
      <div class="err">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('appointments.store') }}">
      @csrf
      <div>
        <label>Fullname of the Customer</label>
        <input type="text" name="full_name" value="{{ old('full_name') }}" required>
      </div>

      <div>
        <label>Cellphone Number</label>
        <input type="text" name="phone" value="{{ old('phone') }}" required>
      </div>

      <div>
        <label>Valid Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
        <p class="note-text">Please enter a valid email address</p>
      </div>

      <div>
        <label>Date of Schedule (Preferred Date)</label>
        <input type="date" name="schedule_date" value="{{ old('schedule_date') }}" min="{{ date('Y-m-d') }}" required>
        <p class="note-text">Choose your preferred appointment date</p>
      </div>

      <div>
        <label>Notes (Optional)</label>
        <textarea name="notes" rows="3">{{ old('notes') }}</textarea>
        <p class="note-text">Add any extra details or requests (optional)</p>
      </div>

      <button type="submit">Submit Appointment</button>
    </form>
  </div>
</body>
</html>