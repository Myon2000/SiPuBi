<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockTransaction;
use App\Models\Fertilizer;
use Illuminate\Http\Request;

class StockTransactionController extends Controller
{
    public function index()
    {
        $transactions = StockTransaction::with(['fertilizer', 'admin'])->latest()->get();
        return view('admin.stock-transactions.index', compact('transactions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fertilizer_id' => 'required|exists:fertilizers,id',
            'quantity' => 'required|numeric|min:1',
            'transaction_type' => 'required|in:in,out',
            'notes' => 'nullable|string'
        ]);

        $fertilizer = Fertilizer::findOrFail($validated['fertilizer_id']);
        
        if($validated['transaction_type'] == 'out' && $fertilizer->current_stock < $validated['quantity']) {
            return back()->with('error', 'Stok tidak mencukupi');
        }

        $transaction = new StockTransaction($validated);
        $transaction->admin_id = auth()->id();
        $transaction->save();

        // Update stok pupuk
        $fertilizer->current_stock += $validated['transaction_type'] == 'in' 
            ? $validated['quantity'] 
            : -$validated['quantity'];
        $fertilizer->save();

        return redirect()->route('admin.stock-transactions.index')
            ->with('success', 'Transaksi stok berhasil dicatat');
    }
}