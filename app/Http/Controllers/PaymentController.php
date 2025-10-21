<?php

namespace App\Http\Controllers; // Ensure this namespace matches your file location

use App\Models\Order; // Make sure your Order model namespace is correct
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Import Log facade
use Illuminate\Support\Facades\Validator; // Import Validator facade
use Illuminate\Validation\ValidationException; // Import ValidationException class


class PaymentController extends Controller
{
    /**
     * Show the payment page specifically for SINGLE item orders.
     * Route: payment.showSingle
     */
    public function showSinglePaymentPage(Order $order)
    {
        // Security Check: Ensure the logged-in user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Status Check: Ensure order is awaiting payment
        if ($order->status !== 'pending_payment') {
             return redirect()->route('orders.index')->with('info', 'Payment for Order #' . $order->order_id . ' has already been submitted or is not required.');
        }

        // Return the specific view for single orders
        // This view's form should post to route('payment.confirmSingle', $order)
        return view('payments.show', ['order' => $order]);
    }

    /**
     * Show the payment page specifically for CART orders.
     * Route: payment.showCart
     */
    public function showCartPaymentPage(Order $order)
    {
        // Security Check: Ensure the logged-in user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Status Check: Ensure order is awaiting payment
        if ($order->status !== 'pending_payment') {
             return redirect()->route('orders.index')->with('info', 'Payment for Order #' . $order->order_id . ' has already been submitted or is not required.');
        }

        // Return the specific view for cart orders
        // This view's form should post to route('payment.confirmCart', $order)
        return view('payments.show-cart', ['order' => $order]);
    }


    /**
     * Handle confirmation specifically for single item orders.
     * Route: payment.confirmSingle
     */
    public function confirmSinglePayment(Request $request, Order $order)
    {
        try {
            // Call the shared processing logic
            $result = $this->processPaymentConfirmation($request, $order);

            if ($result['success']) {
                // Redirect to the success page for SINGLE orders
                return redirect()->route('order.successSingle', ['order' => $order->id])
                                 ->with('status', $result['message']);
            } else {
                 // This case might not be reached if validation throws exception
                 return back()->with('error', $result['message']);
            }
        // Catch validation errors specifically to redirect back with errors
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        // Catch any other unexpected errors
        } catch (\Exception $e) {
             Log::error('ConfirmSinglePayment Error: '.$e->getMessage(), ['order_id' => $order->id]);
             return back()->with('error', 'An unexpected error occurred while confirming payment.');
        }
    }

    /**
     * Handle confirmation specifically for cart orders.
     * Route: payment.confirmCart
     */
public function confirmCartPayment(Request $request, Order $order)
    {
         try {
             // Call the shared processing logic
             $result = $this->processPaymentConfirmation($request, $order);

if ($result['success']) {
                 // FIX: Pass the Order ID using the parameter name 'orderId' 
                 // This now correctly matches the updated web.php route segment {orderId}.
                 return redirect()->route('order.successCart', ['orderId' => $order->id]) 
                                  ->with('status', $result['message']);
             } else {
                  return back()->with('error', $result['message']);
             }
         // Catch validation errors specifically
         } catch (\Illuminate\Validation\ValidationException $e) {
             return back()->withErrors($e->validator)->withInput();
         // Catch any other unexpected errors
         } catch (\Exception $e) {
             Log::error('ConfirmCartPayment Error: '.$e->getMessage(), ['order_id' => $order->id]);
             return back()->with('error', 'An unexpected error occurred while confirming payment.');
         }
    }


    /**
     * Shared private method to process the payment confirmation.
     * Contains validation, file upload, and DB update logic.
     * Returns ['success' => bool, 'message' => string] or throws ValidationException
     */
    private function processPaymentConfirmation(Request $request, Order $order): array
    {
        // Security Check
        if ($order->user_id !== Auth::id()) {
             Log::warning('Unauthorized payment confirmation attempt.', ['order_id' => $order->id, 'user_id' => Auth::id()]);
             abort(403, 'Unauthorized action.'); // Use abort for security issues
        }

        // Prevent re-submission Check
        if ($order->status !== 'pending_payment') {
             // Return array here as it's not a security issue, just state management
             return ['success' => false, 'message' => 'Payment confirmation has already been submitted.'];
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'payment_reference_no' => 'required|string|max:30',
            'payment_screenshot'   => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120' // 5MB Max
        ], [
            // Custom messages as defined before
            'payment_screenshot.required' => 'Please upload a screenshot of your payment.',
            'payment_screenshot.image' => 'The uploaded file must be an image.',
            'payment_screenshot.mimes' => 'Only JPG, PNG, GIF, and WEBP images are allowed.',
            'payment_screenshot.max' => 'The screenshot size cannot exceed 5MB.',
            'payment_reference_no.required' => 'Please enter the GCash reference number.',
        ]);

        // Throw ValidationException if validation fails (handled by calling methods)
        $validator->validate();


        // File Upload and DB Update (runs only if validation passes)
        if ($request->hasFile('payment_screenshot')) {
            $file = $request->file('payment_screenshot');
            $filename = 'payment_order_' . $order->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Store file and get path
            $path = $file->storeAs('payments', $filename, 'public'); // Store in storage/app/public/payments

            if (!$path) {
                // Handle potential storage failure explicitly
                Log::error('Failed to store payment screenshot.', ['order_id' => $order->id]);
                return ['success' => false, 'message' => 'Could not save the screenshot. Please try again.'];
            }

            // Update the order
            $order->update([
                'payment_reference_no'    => $request->input('payment_reference_no'),
                'payment_screenshot_path' => $path, // Save relative path from storage/app/public
                'status'                  => 'for_verification'
            ]);

            return ['success' => true, 'message' => 'Payment submitted successfully! We are now verifying your order.'];
        }

        // Fallback if no file (should ideally not be reached due to 'required' validation)
        return ['success' => false, 'message' => 'Screenshot file not found. Please try again.'];
    }

} // End of PaymentController Class