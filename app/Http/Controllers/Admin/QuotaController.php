<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quota;
use App\Models\User;
use Illuminate\Http\Request;

class QuotaController extends Controller
{
    public function index()
    {
        $farmers = User::where('role', 'petani')->get();
        return view('admin.quotas.index', compact('farmers'));
    }
    
    public function show($userId)
    {
        $farmer = User::findOrFail($userId);
        
        // Dapatkan semua kuota pupuk untuk petani ini
        $quotas = Quota::with('fertilizer')
                    ->where('user_id', $userId)
                    ->get();
        
        return view('admin.quotas.show', compact('farmer', 'quotas'));
    }
}