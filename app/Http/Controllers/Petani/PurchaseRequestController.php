<?php

namespace App\Http\Controllers\Petani;

use App\Http\Controllers\Controller;
use App\Models\PurchaseRequest;
use App\Models\Fertilizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseRequestController extends Controller
{
    /**
     * Display a listing of the purchase requests.
     */
    public function index()
    {
        $requests = PurchaseRequest::where('farmer_id', Auth::id())
            ->with('fertilizer')
            ->latest()
            ->paginate(10);
            
        return view('petani.purchase_requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new purchase request.
     */
    public function create()
    {
        // Ambil fertilizer dengan status aktif dan stok tersedia
        $fertilizers = Fertilizer::where('status', true)
            ->where('current_stock', '>', 0)
            ->get();
        
        // Ambil data kuota petani
        $quotas = Auth::user()->quotas()
            ->with('fertilizer')
            ->get();
        
        // Susun data kuota dengan fertilizer_id sebagai key untuk memudahkan akses di view
        $quotasMap = $quotas->keyBy('fertilizer_id');
        
        return view('petani.purchase_requests.create', [
            'fertilizers' => $fertilizers,
            'quotas' => $quotasMap
        ]);
    }

    /**
     * Store a newly created purchase request in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fertilizer_id' => ['required', 'exists:fertilizers,id'],
            'quantity' => ['required', 'numeric', 'min:1'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);
        
        // Cek apakah petani memiliki kuota yang cukup
        $quota = Auth::user()->quotas()
            ->where('fertilizer_id', $validated['fertilizer_id'])
            ->first();
            
        if (!$quota) {
            return back()->withErrors(['fertilizer_id' => 'Anda tidak memiliki kuota untuk pupuk ini.']);
        }
        
        $remainingQuota = $quota->allocated_amount - $quota->used_amount;
        
        if ($validated['quantity'] > $remainingQuota) {
            return back()->withErrors(['quantity' => 'Jumlah melebihi sisa kuota Anda.']);
        }
        
        // Cek apakah stok pupuk mencukupi
        $fertilizer = Fertilizer::find($validated['fertilizer_id']);
        
        if ($validated['quantity'] > $fertilizer->current_stock) {
            return back()->withErrors(['quantity' => 'Jumlah melebihi stok yang tersedia.']);
        }
        
        // Hitung total harga
        $total_price = $fertilizer->price * $validated['quantity'];
        
        // Buat permintaan pembelian
        $purchaseRequest = PurchaseRequest::create([
            'farmer_id' => Auth::id(),
            'fertilizer_id' => $validated['fertilizer_id'],
            'quantity' => $validated['quantity'],
            'total_price' => $total_price, // Pastikan field ini diisi
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);
        
        return redirect()->route('petani.purchase-requests.index')
            ->with('success', 'Permintaan pembelian berhasil dibuat.');
    }

    /**
     * Display the specified purchase request.
     */
    public function show(PurchaseRequest $purchaseRequest)
    {
        // Pastikan hanya pemilik permintaan yang bisa melihat
        if ($purchaseRequest->farmer_id !== Auth::id()) {
            abort(403);
        }

        return view('petani.purchase_requests.show', compact('purchaseRequest'));
    }

    /**
     * Cancel a pending purchase request.
     */
    public function cancel(PurchaseRequest $purchaseRequest)
    {
        // Pastikan hanya pemilik permintaan yang bisa membatalkan
        if ($purchaseRequest->farmer_id !== Auth::id()) {
            abort(403);
        }
        
        // Pastikan status masih pending
        if ($purchaseRequest->status !== 'pending') {
            return back()->withErrors(['error' => 'Hanya permintaan dengan status pending yang dapat dibatalkan.']);
        }
        
        // Update status menjadi rejected
        $purchaseRequest->update([
            'status' => 'rejected',
            'notes' => 'Dibatalkan oleh petani',
            'processed_by' => Auth::id(),
            'processed_date' => now()
        ]);
        
        return redirect()->route('petani.purchase-requests.index')
            ->with('success', 'Permintaan pembelian berhasil dibatalkan.');
    }
}