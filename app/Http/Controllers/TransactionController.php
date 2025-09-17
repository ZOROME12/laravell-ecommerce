<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\ManualIncome;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string',
            'unit_price' => 'required|numeric',
            'quantity' => 'required|integer',
            'order_status' => 'required|string',
            'payment_status' => 'required|string',
        ]);

        // Store transaction
        $transaction = Transaction::create($validated);

        // If both statuses are 'Successful', add to ManualIncome
        if (
            strtolower($validated['order_status']) === 'successful' &&
            strtolower($validated['payment_status']) === 'successful'
        ) {
            // Normalize item name (convert to UPPERCASE)
            $itemName = strtoupper($validated['item_name']);

            // Calculate total
            $total = $validated['unit_price'] * $validated['quantity'];

            // Check if item already exists
            $income = ManualIncome::where('item_name', $itemName)->first();

            if ($income) {
                // Update existing record
                $income->quantity += $validated['quantity'];
                $income->total_amount += $total;
                $income->save();
            } else {
                // Create new record
                ManualIncome::create([
                    'item_name' => $itemName,
                    'quantity' => $validated['quantity'],
                    'total_amount' => $total,
                ]);
            }
        }

        return response()->json(['message' => 'Transaction saved successfully.'], 201);
    }

    public function index()
    {
        return Transaction::orderBy('created_at', 'desc')->get();
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $validated = $request->validate([
            'item_name' => 'required|string',
            'unit_price' => 'required|numeric',
            'quantity' => 'required|integer',
            'order_status' => 'required|string',
            'payment_status' => 'required|string',
        ]);

        $transaction->update($validated);

        return response()->json(['message' => 'Transaction updated.', 'data' => $transaction]);
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return response()->json(['message' => 'Transaction deleted.']);
    }

    public function topSales()
    {
        $topSales = DB::table('transactions')
            ->select('item_name', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('item_name')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        return response()->json($topSales);
    }
}
