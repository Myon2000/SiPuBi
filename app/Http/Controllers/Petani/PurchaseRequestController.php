<?php

namespace App\Http\Controllers\Petani;

use App\Http\Controllers\Controller;
use App\Models\PurchaseRequest;
use App\Models\Fertilizer;
use Illuminate\Http\Request;

class PurchaseRequestController extends Controller
{
    public function index()
    {
        $requests = PurchaseRequest::where('farmer_id', auth()->id())
            ->with('fertilizer')
            ->latest()
            ->get();
        return view('petani.purchase-requests.index', compact('requests'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fertilizer_id' => 'required|exists:fertilizers,id',
            'quantity' => 'required|numeric|min:1'
        ]);

        // Cek kuota
        $quota = auth()->user()->quotas()
            ->where('fertilizer_id', $validated['fertilizer_id'])
            ->first();

        if(!$quota || $quota->remainingQuota() < $validated['quantity']) {
            return back()->with('error', 'Kuota tidak mencukupi');
        }

        $fertilizer = Fertilizer::find($validated['fertilizer_id']);
        $purchaseRequest = new PurchaseRequest($validated);
        $purchaseRequest->farmer_id = auth()->id();
        $purchaseRequest->total_price = $fertilizer->price * $validated['quantity'];
        $purchaseRequest->save();

        return redirect()->route('petani.purchase-requests.index')
            ->with('success', 'Permintaan pembelian berhasil diajukan');
    }
}