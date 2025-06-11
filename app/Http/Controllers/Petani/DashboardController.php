<?php

namespace App\Http\Controllers\Petani;

use App\Http\Controllers\Controller;
use App\Models\Fertilizer;
use App\Models\Quota;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data kuota petani
        $quotas = Quota::with('fertilizer')
                    ->where('user_id', auth()->id())
                    ->get();
        
        // Ambil data stok pupuk
        $fertilizers = Fertilizer::where('status', true)->get();
        
        // Ambil permintaan pembelian terbaru
        $recentRequests = auth()->user()->purchaseRequests()
                              ->latest()
                              ->take(5)
                              ->get();
        
        return view('petani.dashboard', compact('quotas', 'fertilizers', 'recentRequests'));
    }
}