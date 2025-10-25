<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Uploads and updates the payment QR code.
     */
    public function uploadPaymentQr(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('qr_code')) {
            // Get the old QR code path
            $oldPath = Setting::where('key', 'payment_qr_code')->value('value');

            // Store the new QR code
            $path = $request->file('qr_code')->store('qr_codes', 'public');

            // Update the database with the new path
            Setting::updateOrCreate(
                ['key' => 'payment_qr_code'],
                ['value' => $path]
            );

            // Delete the old QR code file if it exists
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            return response()->json([
                'message' => 'QR code uploaded successfully.',
                'path' => $path
            ], 200);
        }

        return response()->json(['error' => 'No file uploaded.'], 400);
    }
}