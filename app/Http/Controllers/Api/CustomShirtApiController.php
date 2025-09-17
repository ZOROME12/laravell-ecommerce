<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomShirt;

class CustomShirtApiController extends Controller
{
    public function index()
    {
        return CustomShirt::all();
    }

    public function approve(Request $request, $id)
    {
        $order = CustomShirt::findOrFail($id);
        $order->status = 'Approved';
        $order->admin_note = $request->input('admin_note');
        $order->save();

        return response()->json(['message' => 'Request approved']);
    }

    public function reject(Request $request, $id)
    {
        $order = CustomShirt::findOrFail($id);
        $order->status = 'Rejected';
        $order->admin_note = $request->input('admin_note');
        $order->save();

        return response()->json(['message' => 'Request rejected']);
    }
}
