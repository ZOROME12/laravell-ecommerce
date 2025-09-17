<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        Appointment::create($data);

        return redirect()->route('appointments.success')->with('ok', true);
    }

    public function success()
    {
        abort_if(!session('ok'), 404);
        return view('appointments.success');
    }
}
