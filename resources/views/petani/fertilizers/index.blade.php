@extends('layouts.app')

@section('title', 'Stok Pupuk')

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold">Informasi Stok Pupuk</h3>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Pupuk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Saat Ini</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($fertilizers as $fertilizer)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $fertilizer->name }}</td>
                        <td class="px-6 py-4">{{ $fertilizer->description }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($fertilizer->current_stock, 0) }} kg</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($fertilizer->current_stock > $fertilizer->minimum_stock)
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                    Tersedia
                                </span>
                            @elseif($fertilizer->current_stock > 0)
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                    Stok Menipis
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                    Kosong
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                            Tidak ada data stok pupuk
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-8">
            <h4 class="text-md font-medium text-gray-700 mb-4">Grafik Ketersediaan Stok</h4>
            <div class="space-y-4">
                @foreach($fertilizers as $fertilizer)
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">{{ $fertilizer->name }}</span>
                        <span class="text-sm font-medium text-gray-700">{{ number_format($fertilizer->current_stock, 0) }} kg</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        @php
                            // Anggap maksimum stok adalah 2x minimum untuk visualisasi, atau stok saat ini jika lebih besar
                            $maxStock = max($fertilizer->minimum_stock * 2, $fertilizer->current_stock);
                            $stockPercentage = ($fertilizer->current_stock / $maxStock) * 100;
                            
                            // Tentukan warna berdasarkan ketersediaan
                            if ($fertilizer->current_stock > $fertilizer->minimum_stock) {
                                $barColor = 'bg-green-600';
                            } elseif ($fertilizer->current_stock > 0) {
                                $barColor = 'bg-yellow-600';
                            } else {
                                $barColor = 'bg-red-600';
                            }
                        @endphp
                        <div class="{{ $barColor }} h-4 rounded-full" style="width: {{ $stockPercentage }}%"></div>
                    </div>
                    <div class="mt-1 text-xs text-gray-500">
                        <span>Minimum: {{ number_format($fertilizer->minimum_stock, 0) }} kg</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection