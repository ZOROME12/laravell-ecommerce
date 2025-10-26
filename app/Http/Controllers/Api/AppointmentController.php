<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\AppointmentApprovedMail;
use Illuminate\Support\Facades\Mail;


class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::query();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        return $query->orderBy('id', 'desc')->get();
    }



 public function approve($id)
{
    $appointment = Appointment::findOrFail($id);
    $appointment->status = 'approved';

    $pdfFilePath = "appointments/appointment_{$appointment->id}.pdf";

    // ✅ Generate verify link
    $verifyLink = "https://easeprint.com/verify/{$appointment->token}";

    // ✅ Generate QR Code as Base64 SVG
    $qrCodeSvg = QrCode::format('svg')
        ->size(200)
        ->margin(2)
        ->generate($verifyLink);

    $qrCodeBase64 = base64_encode($qrCodeSvg);

    // ✅ Generate PDF
    $pdf = Pdf::loadView('pdf.appointment', [
        'appointment' => $appointment,
        'qrCodeBase64' => $qrCodeBase64,
        'verifyLink' => $verifyLink,
    ]);

    Storage::disk('public')->put($pdfFilePath, $pdf->output());
    $appointment->pdf_path = $pdfFilePath;
    $appointment->save();

    Mail::to($appointment->email)->send(new AppointmentApprovedMail($appointment));

    return response()->json([
        'message' => 'Appointment approved successfully, email sent',
        'pdf_url' => Storage::url($pdfFilePath),
    ]);
}




    public function reject($id)
    {
        $appointment = Appointment::findOrFail($id);

        if ($appointment->status !== 'pending') {
            return response()->json(['message' => 'This appointment has already been processed.'], 400);
        }

        $appointment->status = 'rejected';
        $appointment->save();

        return response()->json(['message' => 'Appointment rejected']);
    }

    public function verify($token)
    {
        $appointment = Appointment::where('token', $token)->firstOrFail();
        return view('appointments.verify', compact('appointment'));
    }
}

