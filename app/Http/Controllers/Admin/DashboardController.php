<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fertilizer;
use App\Models\PurchaseRequest;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $statistics = [
            'total_fertilizers' => Fertilizer::count(),
            'low_stock_count' => Fertilizer::whereRaw('current_stock <= minimum_stock')->count(),
            'total_farmers' => User::where('role', 'petani')->count(),
            'pending_requests' => PurchaseRequest::where('status', 'pending')->count(),
        ];

        $lowStockFertilizers = Fertilizer::whereRaw('current_stock <= minimum_stock')->get();

        return view('admin.dashboard', compact('statistics', 'lowStockFertilizers'));
    }
}