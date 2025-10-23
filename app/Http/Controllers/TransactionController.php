<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\ManualIncome;
use App\Models\Product; // ✅ added to fetch category_id
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        // ✅ Added category_id validation
        $validated = $request->validate([
            'item_name' => 'required|string',
            'unit_price' => 'required|numeric',
            'quantity' => 'required|integer',
            'order_status' => 'required|string',
            'payment_status' => 'required|string',
            'category_id' => 'nullable|exists:categories,id', // category support
        ]);

        // ✅ Auto-detect category_id if missing
        if (empty($validated['category_id'])) {
            $product = Product::where('name', $validated['item_name'])->first();
            if ($product && $product->category_id) {
                $validated['category_id'] = $product->category_id;
            }
        }

        // ✅ Store transaction with category_id included
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
        // ✅ Added with('category') to include category info in response
        return Transaction::with('category')->orderBy('created_at', 'desc')->get();
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        // ✅ Added category_id validation
        $validated = $request->validate([
            'item_name' => 'required|string',
            'unit_price' => 'required|numeric',
            'quantity' => 'required|integer',
            'order_status' => 'required|string',
            'payment_status' => 'required|string',
            'category_id' => 'nullable|exists:categories,id', // category support
        ]);

        // ✅ Auto-detect category_id if missing or changed item_name
        if (empty($validated['category_id'])) {
            $product = Product::where('name', $validated['item_name'])->first();
            if ($product && $product->category_id) {
                $validated['category_id'] = $product->category_id;
            }
        }

        // ✅ Update transaction including category_id
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

    public function salesByCategory()
    {
        $sales = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->select(
                'categories.name as category_name',
                DB::raw('SUM(transactions.unit_price * transactions.quantity) as total_sales'),
                DB::raw('COUNT(transactions.id) as total_transactions')
            )
            ->groupBy('categories.name')
            ->orderByDesc('total_sales')
            ->get();

        return response()->json($sales);
    }
}
