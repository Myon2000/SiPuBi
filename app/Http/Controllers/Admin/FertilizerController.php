<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fertilizer;
use Illuminate\Http\Request;

class FertilizerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $fertilizers = Fertilizer::latest()->get();
        return view('admin.fertilizers.index', compact('fertilizers'));
    }

    public function create()
    {
        return view('admin.fertilizers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'current_stock' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
        ]);

        // Explicitly set status based on checkbox presence
        $validated['status'] = $request->has('status');

        Fertilizer::create($validated);
        return redirect()->route('admin.fertilizers.index')
            ->with('success', 'Pupuk berhasil ditambahkan');
    }

    public function update(Request $request, Fertilizer $fertilizer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
        ]);

        // Explicitly set status based on checkbox presence
        $validated['status'] = $request->has('status');

        $fertilizer->update($validated);
        return redirect()->route('admin.fertilizers.index')
            ->with('success', 'Pupuk berhasil diupdate');
    }

    public function edit(Fertilizer $fertilizer)
    {
        return view('admin.fertilizers.edit', compact('fertilizer'));
    }

    public function destroy(Fertilizer $fertilizer)
    {
        if($fertilizer->current_stock > 0) {
            return back()->with('error', 'Tidak dapat menghapus pupuk dengan stok tersisa');
        }
        
        $fertilizer->delete();
        return back()->with('success', 'Pupuk berhasil dihapus');
    }
}