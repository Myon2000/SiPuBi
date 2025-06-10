<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseRequestController extends Controller
{
    public function index()
    {
        $requests = PurchaseRequest::with(['farmer', 'fertilizer'])
            ->latest()
            ->get();
        return view('admin.purchase-requests.index', compact('requests'));
    }

    public function update(Request $request, PurchaseRequest $purchaseRequest)
    {
        // Cek apakah request masih pending
        if ($purchaseRequest->status !== 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses sebelumnya');
        }

        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'status' => 'required|in:approved,rejected',
                'notes' => 'nullable|string'
            ]);

            if ($validated['status'] === 'approved') {
                // Cek stok
                if ($purchaseRequest->fertilizer->current_stock < $purchaseRequest->quantity) {
                    DB::rollBack();
                    return back()->with('error', 'Stok pupuk tidak mencukupi');
                }

                // Cek kuota
                $quota = $purchaseRequest->farmer->quotas()
                    ->where('fertilizer_id', $purchaseRequest->fertilizer_id)
                    ->first();

                if (!$quota || ($quota->allocated_amount - $quota->used_amount) < $purchaseRequest->quantity) {
                    DB::rollBack();
                    return back()->with('error', 'Kuota petani tidak mencukupi');
                }

                // Kurangi stok
                $purchaseRequest->fertilizer->decrement('current_stock', $purchaseRequest->quantity);
                
                // Update kuota
                $quota->increment('used_amount', $purchaseRequest->quantity);

                // Log transaksi
                Log::info("Approved purchase request #{$purchaseRequest->id} for {$purchaseRequest->quantity} items");
            }

            // Update status permintaan
            $purchaseRequest->update([
                'status' => $validated['status'],
                'notes' => $validated['notes'],
                'processed_by' => auth()->id(),
                'processed_date' => now()
            ]);

            DB::commit();
            return back()->with('success', 'Permintaan berhasil diproses');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error processing purchase request: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memproses permintaan');
        }
    }
}