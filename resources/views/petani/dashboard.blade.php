@extends('layouts.app')

@section('title', 'Dashboard Petani')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Selamat datang, {{ Auth::user()->name }}!</h1>
                <p class="mt-1 text-blue-100">Kelola kuota pupuk dan permintaan Anda di sini.</p>
            </div>
            <div class="hidden md:block">
                <svg class="w-24 h-24 text-white/20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Kuota Section -->
        <div class="md:col-span-1">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h5 class="text-lg font-semibold">Kuota Pupuk</h5>
                    <a href="{{ route('quotas.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua</a>
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
                                <div class="h-2 rounded-full {{ $percentage <= 20 ? 'bg-red-500' : 'bg-green-500' }}" 
                                     style="width: {{ $percentage }}%">
                                </div>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Sisa: {{ $quota->allocated_amount - $quota->used_amount }} kg</span>
                                <span>Total: {{ $quota->allocated_amount }} kg</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="mt-2">Belum ada kuota pupuk yang dialokasikan</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="md:col-span-2">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h5 class="text-lg font-semibold">Aktivitas Terbaru</h5>
                </div>
                <div class="p-6">
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @foreach(auth()->user()->purchaseRequests()->latest()->take(5)->get() as $request)
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
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900">
                                                Permintaan Pupuk {{ $request->fertilizer->name }}
                                            </div>
                                            <div class="mt-1 text-sm text-gray-500">
                                                <p>Jumlah: {{ $request->quantity }} kg</p>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $request->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                                       ($request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ ucfirst($request->status) }}
                                                </span>
                                            </div>
                                            <div class="mt-1 text-xs text-gray-500">
                                                {{ $request->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection