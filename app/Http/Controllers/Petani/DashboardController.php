<?php

namespace App\Http\Controllers\Petani;

use App\Http\Controllers\Controller;
use App\Models\PurchaseRequest;
use App\Models\Quota;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'quotas' => Quota::where('user_id', auth()->id())
                ->with('fertilizer')
                ->get(),
            'pending_requests' => PurchaseRequest::where('farmer_id', auth()->id())
                ->where('status', 'pending')
                ->count(),
            'recent_purchases' => PurchaseRequest::where('farmer_id', auth()->id())
                ->where('status', 'approved')
                ->latest()
                ->take(5)
                ->get()
        ];

        return view('petani.dashboard', compact('data'));
    }
}