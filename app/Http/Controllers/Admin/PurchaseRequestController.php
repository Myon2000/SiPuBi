<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseRequest;
use App\Models\Quota;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseRequestController extends Controller
{
    /**
     * Display a listing of the purchase requests.
     */
    public function index(Request $request)
    {
        $status = $request->status ?? 'pending';
        $query = PurchaseRequest::with(['farmer', 'fertilizer']);
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $requests = $query->latest()->paginate(10);
        
        if ($request->ajax()) {
            $view = view('admin.purchase_requests._requests_table', compact('requests'))->render();
            return response()->json(['html' => $view]);
        }
        
        return view('admin.purchase_requests.index', compact('requests', 'status'));
    }

    /**
     * Display the specified purchase request.
     */
    public function show(PurchaseRequest $purchaseRequest)
    {
        $purchaseRequest->load([
            'fertilizer',
            'farmer' => function($query) {
                $query->withDefault([
                    'name' => 'Petani tidak ditemukan',
                    'email' => '-'
                ]);
            },
            'processor' => function($query) {
                $query->withDefault([
                    'name' => 'Admin tidak ditemukan',
                    'email' => '-'
                ]);
            }
        ]);
        
        return view('admin.purchase_requests.show', compact('purchaseRequest'));
    }

    /**
     * Update purchase request status
     */
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

                // Catat transaksi stok
                StockTransaction::create([
                    'fertilizer_id' => $purchaseRequest->fertilizer_id,
                    'quantity' => $purchaseRequest->quantity,
                    'transaction_type' => 'out',
                    'notes' => "Pembelian pupuk oleh " . $purchaseRequest->farmer->name,
                    'admin_id' => Auth::id(),
                ]);

                // Log transaksi
                Log::info("Approved purchase request #{$purchaseRequest->id} for {$purchaseRequest->quantity} items");
            }

            // Update status permintaan
            $purchaseRequest->update([
                'status' => $validated['status'],
                'notes' => $validated['notes'],
                'processed_by' => Auth::id(),
                'processed_date' => now()
            ]);

            DB::commit();
            return back()->with('success', 'Permintaan berhasil diproses');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error processing purchase request: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memproses permintaan: ' . $e->getMessage());
        }
    }

    /**
     * Approve a purchase request (shortcut)
     */
    public function approve(PurchaseRequest $purchaseRequest)
    {
        // Bungkus dalam transaksi database untuk memastikan konsistensi data
        DB::beginTransaction();
        
        try {
            // Update status permintaan
            $purchaseRequest->update([
                'status' => 'approved',
                'processed_by' => auth()->id(),
                'processed_date' => now()
            ]);
            
            // Kurangi stok pupuk
            $fertilizer = $purchaseRequest->fertilizer;
            $fertilizer->current_stock -= $purchaseRequest->quantity;
            $fertilizer->save();
            
            // Catat transaksi stok dengan SEMUA kolom wajib diisi
            StockTransaction::create([
                'fertilizer_id' => $purchaseRequest->fertilizer_id,
                'quantity' => -$purchaseRequest->quantity,
                'type' => 'out', // Pastikan type juga diisi
                'description' => "Permintaan #" . $purchaseRequest->id . " disetujui untuk " . ($purchaseRequest->farmer->name ?? 'petani'),
                'performed_by' => auth()->id()
            ]);
            
            // Update kuota petani
            $quota = $purchaseRequest->farmer->quotas()
                ->where('fertilizer_id', $purchaseRequest->fertilizer_id)
                ->first();
                
            if ($quota) {
                $quota->used_amount += $purchaseRequest->quantity;
                $quota->save();
            }
            
            DB::commit();
            
            return redirect()->route('admin.purchase-requests.index')
                ->with('success', 'Permintaan berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menyetujui permintaan: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, PurchaseRequest $purchaseRequest)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Update status permintaan
            $purchaseRequest->update([
                'status' => 'rejected',
                'notes' => $validated['rejection_reason'],
                'processed_by' => auth()->id(),
                'processed_date' => now()
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.purchase-requests.index')
                ->with('success', 'Permintaan berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menolak permintaan: ' . $e->getMessage());
        }
    }
}