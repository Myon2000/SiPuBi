<?php

namespace App\Http\Controllers;

use App\Models\VerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class VerificationController extends Controller
{
    /**
     * Store a new verification request.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Check if user already has a pending verification
        if ($user->hasPendingVerification()) {
            return redirect()->route('profile.edit')
                ->with('error', 'Anda sudah memiliki permintaan verifikasi yang sedang diproses.');
        }
        
        // Validasi request
        $validated = $request->validate([
            'land_area' => 'required|numeric|min:0.01|max:100',
            'ktp_image' => 'required|image|max:2048', // max 2MB
        ]);
        
        DB::beginTransaction();
        
        try {
            // Simpan gambar KTP
            $path = $request->file('ktp_image')->store('ktp_images', 'public');
            
            // Buat permintaan verifikasi
            VerificationRequest::create([
                'user_id' => $user->id,
                'ktp_image' => $path,
                'old_land_area' => $user->land_area ?: 0,
                'new_land_area' => $validated['land_area'],
                'status' => 'pending',
            ]);
            
            // Update status user
            $user->update([
                'verification_requested_at' => now(),
            ]);
            
            DB::commit();
            
            return redirect()->route('profile.edit')
                ->with('status', 'verification-requested')
                ->with('success', 'Permintaan verifikasi lahan berhasil diajukan. Admin akan segera memproses permintaan Anda.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Hapus file yang sudah diupload jika ada error
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            
            Log::error('Verification request failed: ' . $e->getMessage());
            
            return redirect()->route('profile.edit')
                ->with('error', 'Terjadi kesalahan saat mengajukan verifikasi: ' . $e->getMessage());
        }
    }
}