<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VerificationRequest;
use App\Models\User;
use App\Models\Quota;
use App\Models\Fertilizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VerificationController extends Controller
{
    /**
     * Display a listing of verification requests.
     */
    public function index()
    {
        $pendingCount = VerificationRequest::where('status', 'pending')->count();
        
        $verifications = VerificationRequest::with('user')
                          ->latest()
                          ->paginate(10);
                          
        return view('admin.verifications.index', compact('verifications', 'pendingCount'));
    }
    
    /**
     * Show the details of a verification request.
     */
    public function show(VerificationRequest $verification)
    {
        $verification->load(['user', 'processor']);
        
        return view('admin.verifications.show', compact('verification'));
    }
    
    /**
     * Approve a verification request.
     */
    public function approve(Request $request, VerificationRequest $verification)
    {
        // Validate the request
        $validated = $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);
        
        // Prevent processing already processed requests
        if ($verification->status !== 'pending') {
            return redirect()->route('admin.verifications.show', $verification)
                ->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }
        
        DB::beginTransaction();
        
        try {
            // Update status verifikasi
            $verification->update([
                'status' => 'approved',
                'admin_notes' => $validated['notes'] ?? null,
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);
            
            // Update data petani
            $user = User::findOrFail($verification->user_id);
            $user->update([
                'land_area' => $verification->new_land_area,
                'land_area_verified' => true,
                'verification_requested_at' => null,
            ]);
            
            // Hitung dan update kuota berdasarkan luas lahan
            $fertilizers = Fertilizer::where('status', true)->get();
            
            foreach ($fertilizers as $fertilizer) {
                // Default allocation factor if not set
                $allocationFactor = $fertilizer->allocation_factor ?? 50; // 50kg per hectare is default
                
                // Rumus alokasi: luas lahan x faktor alokasi pupuk
                $allocatedAmount = $user->land_area * $allocationFactor;
                
                // Update atau buat kuota baru
                Quota::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'fertilizer_id' => $fertilizer->id,
                    ],
                    [
                        'allocated_amount' => $allocatedAmount,
                    ]
                );
            }
            
            DB::commit();
            
            return redirect()->route('admin.verifications.index')
                            ->with('success', 'Verifikasi berhasil disetujui dan kuota telah diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Verification approval failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyetujui verifikasi: ' . $e->getMessage());
        }
    }
    
    /**
     * Reject a verification request.
     */
    public function reject(Request $request, VerificationRequest $verification)
    {
        // Validate the request
        $validated = $request->validate([
            'notes' => 'required|string|max:500',
        ]);
        
        // Prevent processing already processed requests
        if ($verification->status !== 'pending') {
            return redirect()->route('admin.verifications.show', $verification)
                ->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }
        
        DB::beginTransaction();
        
        try {
            // Update status verifikasi
            $verification->update([
                'status' => 'rejected',
                'admin_notes' => $validated['notes'],
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);
            
            // Reset status verifikasi user
            $user = User::findOrFail($verification->user_id);
            $user->update([
                'verification_requested_at' => null,
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.verifications.index')
                            ->with('success', 'Verifikasi berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Verification rejection failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menolak verifikasi: ' . $e->getMessage());
        }
    }
    
    /**
     * Download KTP image.
     */
    public function downloadKtp(VerificationRequest $verification)
    {
        if (!$verification->ktp_image || !Storage::disk('public')->exists($verification->ktp_image)) {
            return redirect()->back()->with('error', 'File KTP tidak ditemukan.');
        }
        
        return Storage::disk('public')->download($verification->ktp_image, 'ktp-' . $verification->user->name . '.jpg');
    }
}