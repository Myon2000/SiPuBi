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
        
        // Selalu ambil data fertilizers
        $fertilizers = Fertilizer::where('status', true)->get();
        $quotas = [];
        
        // Jika pengguna memiliki lahan, buat quotas
        if ($user->land_area && $user->land_area > 0) {
            foreach ($fertilizers as $fertilizer) {
                // Pastikan kuota ada untuk setiap pupuk
                $quota = Quota::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'fertilizer_id' => $fertilizer->id,
                    ],
                    [
                        'used_amount' => 0,
                    ]
                );
                
                // Muat relasi fertilizer secara manual
                $quota->setRelation('fertilizer', $fertilizer);
                
                $quotas[] = $quota;
            }
        }
        
        return view('petani.quotas.index', compact('quotas', 'fertilizers'));
    }
}