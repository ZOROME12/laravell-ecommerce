@extends('layouts.app')
<?php
    // Assuming you want to hide the default hero section on this page, like the reference
    $hideHero = true; 
?>

@section('contents')

{{-- ADDED: Container div to center the card, similar to your old body styles --}}
<div style="display: flex; justify-content: center; align-items: center; padding: 20px; min-height: 80vh;"> 

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
        <label>Full Name of the Customer</label>
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

</div> {{-- End of centering container --}}


<div id="errorModal" class="modal-overlay">
    <div class="modal-content">
        <h3>Appointment Error</h3>
        <p id="modalErrorMessage"></p>
        <button id="closeModalBtn" class="modal-btn">Close</button>
    </div>
</div>

<script>
    // This runs when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        
        // Check if Laravel sent the special error message
        @if(session('show_modal_error'))
            
            // Get the modal elements
            var modal = document.getElementById('errorModal');
            var messageP = document.getElementById('modalErrorMessage');
            var closeBtn = document.getElementById('closeModalBtn');

            // Set the error message from the controller
            messageP.textContent = '{{ session('show_modal_error') }}';
            
            // Show the modal
            modal.style.display = 'flex';

            // When the user clicks "Close", hide the modal
            closeBtn.onclick = function() {
                modal.style.display = 'none';
            }
            
            // Also hide modal if user clicks outside the box
            window.onclick = function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            }
        @endif
    });
</script>
{{-- MOVED STYLES INSIDE THE SECTION --}}
<style>
  /* === MAIN PAGE STYLES (now specific to this section) === */
  /* Styles for .card, h2, label, input, textarea, .note-text, button[type="submit"], .err remain the same */
  .card {
      background: #fff;
      border: 2px solid #FBB3C8;
      border-radius: 16px;
      padding: 32px 30px;   
      max-width: 520px;
      width: 100%;
      box-shadow: 0 6px 18px rgba(63, 26, 43, 0.15);
      box-sizing: border-box; 
    }

    h2 {
      text-align: center;
      color: #3F1A2B;
      margin-bottom: 24px;
      font-size: 24px;
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: 600;
      color: #3F1A2B;
      font-size: 14px;
    }

    input, textarea {
      width: 100%;
      padding: 10px 14px;
      border: 1px solid #ED4A69;
      border-radius: 8px;
      margin-bottom: 8px;
      font-size: 14px;
      background: #FBF8FB;
      transition: border 0.25s, box-shadow 0.25s;
      box-sizing: border-box;
    }

    input:focus, textarea:focus {
      outline: none;
      border-color: #B2183A;
      box-shadow: 0 0 0 3px rgba(178, 24, 58, 0.2);
    }

    .note-text {
      font-size: 12px;
      color: #B2183A;
      margin-bottom: 18px;
    }

    /* Styles the main form button */
    button[type="submit"] { 
      width: 100%;
      background: linear-gradient(135deg, #B2183A, #ED4A69);
      color: #fff;
      font-weight: 600;
      padding: 12px;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-size: 15px;
      transition: transform 0.2s, background 0.25s;
    }

    /* Styles the main form button hover */
    button[type="submit"]:hover { 
      background: linear-gradient(135deg, #ED4A69, #B2183A);
      transform: translateY(-1px);
    }

    .err {
      background: #FBB3C8;
      color: #3F1A2B;
      padding: 10px 12px;
      border-radius: 8px;
      margin-bottom: 16px;
      font-size: 13px;
      line-height: 1.4;
    }

    .err ul {
      margin: 0;
      padding-left: 20px;
    }

    /* === COMPACT MOBILE ADJUSTMENTS === */
    @media (max-width: 480px) {
      .card {
        padding: 16px; 
        border-radius: 10px;
      }

      h2 {
        font-size: 17px; 
        margin-bottom: 16px;
      }

      label {
        font-size: 12px; 
        margin-bottom: 3px;
      }

      input, textarea {
        padding: 7px 10px; 
        font-size: 12px; 
        border-radius: 6px; 
        margin-bottom: 3px;
      }
      
      .card > form > div {
        margin-bottom: 16px; 
      }

      .note-text {
        font-size: 10px; 
        margin-bottom: 12px;
      }

      /* Mobile style for main form button */
      button[type="submit"] { 
        padding: 9px; 
        font-size: 13px; 
        border-radius: 8px;
      }

      .err {
        font-size: 11px; 
        padding: 6px 8px;
        margin-bottom: 10px;
      }
    }

    /* === MODAL CSS === */
    .modal-overlay {
        display: none; 
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(63, 26, 43, 0.6);
        justify-content: center;
        align-items: center;
        padding: 20px;
        font-family: system-ui, Arial, sans-serif; /* Use same font */
    }
    .modal-content {
        background-color: #fff;
        border-radius: 16px;
        padding: 24px 30px;
        border: 2px solid #FBB3C8;
        max-width: 450px;
        width: 100%;
        text-align: center;
        box-shadow: 0 6px 18px rgba(63, 26, 43, 0.15);
        box-sizing: border-box; 
    }
    .modal-content h3 {
        font-size: 22px;
        color: #3F1A2B;
        margin-top: 0; 
        margin-bottom: 12px;
        font-weight: 600; 
    }
    .modal-content p {
        font-size: 16px;
        color: #4A2C3D;
        line-height: 1.6;
        margin-bottom: 24px;
    }
    /* Styles the modal button */
    .modal-btn { 
        width: 100%;
        background: linear-gradient(135deg, #B2183A, #ED4A69);
        color: #fff;
        font-weight: 600;
        padding: 12px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 15px;
        transition: transform 0.2s, background 0.25s;
        font-family: inherit; 
    }
    /* Styles the modal button hover */
    .modal-btn:hover { 
        background: linear-gradient(135deg, #ED4A69, #B2183A);
        transform: translateY(-1px); 
    }
</style>

@endsection
