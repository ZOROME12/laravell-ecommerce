<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
// ADD THIS LINE to catch the database error
use Illuminate\Database\QueryException;

class AppointmentWebController extends Controller
{
    // Show the Blade form
    public function create()
    {
        return view('appointments.create');
    }

    // Handle form POST (uses same validations)
    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name'     => ['required', 'string', 'max:255'],
            'phone'         => ['required', 'string', 'max:30'],
            'email'         => ['required', 'email', 'max:255'],
            'schedule_date' => ['required', 'date', 'after_or_equal:today'],
            'notes'         => ['nullable', 'string', 'max:2000'],
        ]);

        $data['token'] = (string) Str::uuid();

        // START OF UPDATE
        try {
            // This line stays the same
            Appointment::create($data);

        } catch (QueryException $e) {
            // This catches the database error
            // 1062 is the MySQL error code for a duplicate unique entry
            if ($e->errorInfo[1] == 1062) {
                
                // This sends the user back to the form with a special "session" message
                // to trigger the modal.
                return redirect()->back()
                    ->withInput() // This keeps the data they already typed
                    ->with('show_modal_error', 'This name is already registered for an appointment.');
            }
            
            // If it's a different database error, just show the error page
            throw $e;
        }
        // END OF UPDATE

        return redirect()->route('appointments.success')->with('ok', true);
    }

    public function success()
    {
        abort_if(!session('ok'), 404);
        return view('appointments.success');
    }
}