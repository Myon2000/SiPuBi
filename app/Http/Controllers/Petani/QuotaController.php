<?php

namespace App\Http\Controllers\Petani;

use App\Http\Controllers\Controller;
use App\Models\Fertilizer;
use App\Models\Quota;
use Illuminate\Http\Request;

class QuotaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Buat array untuk menyimpan kuota
        $quotas = [];
        
        // Dapatkan semua jenis pupuk aktif
        $fertilizers = Fertilizer::where('status', true)->get();
        
        foreach ($fertilizers as $fertilizer) {
            // Cari kuota yang sudah ada atau buat baru
            $quota = Quota::firstOrCreate([
                'user_id' => $user->id,
                'fertilizer_id' => $fertilizer->id,
            ], [
                'used_amount' => 0,
            ]);
            
            $quotas[] = $quota;
        }
        
        return view('petani.quotas.index', compact('quotas'));
    }
}