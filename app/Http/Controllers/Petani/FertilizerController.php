<?php

namespace App\Http\Controllers\Petani;

use App\Http\Controllers\Controller;
use App\Models\Fertilizer;
use Illuminate\Http\Request;

class FertilizerController extends Controller
{
    public function index()
    {
        $fertilizers = Fertilizer::where('status', true)->get();
        return view('petani.fertilizers.index', compact('fertilizers'));
    }
}