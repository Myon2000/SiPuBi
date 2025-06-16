<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Fertilizer;
use App\Models\Quota;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'land_area' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);
        
        $user->fill($validated);
        
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        
        // Jika luas lahan berubah, hitung ulang kuota
        if ($user->isDirty('land_area') && $user->role === 'petani') {
            $this->recalculateQuotas($user);
        }
        
        $user->save();
        
        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }

    protected function recalculateQuotas($user)
    {
        // Ambil semua jenis pupuk yang aktif
        $fertilizers = Fertilizer::where('status', true)->get();
        
        foreach ($fertilizers as $fertilizer) {
            // Gunakan nama pupuk untuk mendapatkan kuota per hektar
            $name = strtolower($fertilizer->name);
            $quotaMap = [
                'urea' => 200,    // kg per hektar
                'npk' => 150,     // kg per hektar
                'organik' => 500  // kg per hektar
            ];
            
            $quotaPerHectare = $quotaMap[$name] ?? 0;
            $newAllocation = $user->land_area * $quotaPerHectare;
            
            // Cari kuota yang sudah ada atau buat baru
            $quota = Quota::firstOrNew([
                'user_id' => $user->id,
                'fertilizer_id' => $fertilizer->id,
            ]);
            
            // Pastikan alokasi baru tidak kurang dari yang sudah terpakai
            $quota->allocated_amount = max($newAllocation, $quota->used_amount ?? 0);
            $quota->save();
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
