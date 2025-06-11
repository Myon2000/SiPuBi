@extends('layouts.app')

@section('title', 'Dashboard Petani')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Kuota Section -->
        <div class="md:col-span-1">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h5 class="text-lg font-semibold">Kuota Pupuk</h5>
                </div>
                <div class="p-6 space-y-4">
                    @forelse(auth()->user()->quotas as $quota)
                    <div class="border rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h6 class="font-medium">{{ $quota->fertilizer->name }}</h6>
                            @php
                                $percentage = $quota->allocated_amount > 0 
                                    ? (($quota->allocated_amount - $quota->used_amount) / $quota->allocated_amount) * 100 
                                    : 0;
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full {{ $percentage <= 20 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ number_format($percentage, 0) }}%
                            </span>
                        </div>
                        <div class="space-y-2">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-600">
                                <span>Terpakai: {{ number_format($quota->used_amount, 0) }} kg</span>
                                <span>Total: {{ number_format($quota->allocated_amount, 0) }} kg</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-gray-500">
                        <p>Belum ada kuota pupuk</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Stok Pupuk Section -->
        <div class="md:col-span-1">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h5 class="text-lg font-semibold">Stok Pupuk di Gudang</h5>
                </div>
                <div class="p-6 space-y-4">
                    @forelse($fertilizers as $fertilizer)
                    <div class="border rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h6 class="font-medium">{{ $fertilizer->name }}</h6>
                            @php
                                $stockStatus = $fertilizer->current_stock > $fertilizer->minimum_stock ? 'Tersedia' : 'Menipis';
                                $statusColor = $fertilizer->current_stock > $fertilizer->minimum_stock ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full {{ $statusColor }}">
                                {{ $stockStatus }}
                            </span>
                        </div>
                        <div class="space-y-2">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                @php
                                    // Anggap maksimum stok adalah 2x minimum untuk visualisasi
                                    $maxStock = max($fertilizer->minimum_stock * 2, $fertilizer->current_stock);
                                    $stockPercentage = ($fertilizer->current_stock / $maxStock) * 100;
                                @endphp
                                <div class="{{ $fertilizer->current_stock > $fertilizer->minimum_stock ? 'bg-green-600' : 'bg-yellow-600' }} h-2 rounded-full" 
                                     style="width: {{ $stockPercentage }}%">
                                </div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-600">
                                <span>Minimum: {{ number_format($fertilizer->minimum_stock, 0) }} kg</span>
                                <span>Stok: {{ number_format($fertilizer->current_stock, 0) }} kg</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-gray-500">
                        <p>Tidak ada data stok pupuk</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Aktivitas Terbaru Section -->
        <div class="md:col-span-1">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h5 class="text-lg font-semibold">Aktivitas Terbaru</h5>
                </div>
                <div class="p-6">
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            {{-- @foreach($recentRequests as $request)
                            <li>
                                <div class="relative pb-8">
                                    @unless($loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endunless
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white 
                                                {{ $request->status === 'approved' ? 'bg-green-500' : 
                                                   ($request->status === 'pending' ? 'bg-yellow-500' : 'bg-red-500') }}">
                                                <!-- Icon -->
                                                <svg class="h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    @if($request->status === 'approved')
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    @elseif($request->status === 'pending')
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                    @else
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                    @endif
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div>
                                                <div class="text-sm">
                                                    <span class="font-medium text-gray-900">
                                                        Permintaan Pupuk {{ $request->fertilizer->name }}
                                                    </span>
                                                </div>
                                                <p class="mt-0.5 text-sm text-gray-500">
                                                    {{ $request->quantity }} kg · {{ $request->created_at->diffForHumans() }}
                                                </p>
                                                <p class="mt-1 text-xs">
                                                    <span class="{{ 
                                                        $request->status === 'approved' ? 'text-green-700' : 
                                                        ($request->status === 'pending' ? 'text-yellow-700' : 'text-red-700') 
                                                    }}">
                                                        {{ 
                                                            $request->status === 'approved' ? 'Disetujui' : 
                                                            ($request->status === 'pending' ? 'Menunggu Persetujuan' : 'Ditolak') 
                                                        }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach --}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection