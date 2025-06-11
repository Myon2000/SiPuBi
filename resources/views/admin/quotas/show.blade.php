@extends('layouts.app_admin')

@section('title', 'Detail Kuota Petani')

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold">Detail Kuota: {{ $farmer->name }}</h3>
            <a href="{{ route('admin.quotas.index') }}" class="text-blue-600 hover:text-blue-900">
                &larr; Kembali
            </a>
        </div>
    </div>
    <div class="p-6">
        <div class="mb-6">
            <h4 class="text-md font-medium text-gray-700">Informasi Petani</h4>
            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Nama: <span class="font-semibold">{{ $farmer->name }}</span></p>
                    <p class="text-sm text-gray-600">Email: <span class="font-semibold">{{ $farmer->email }}</span></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Luas Lahan: <span class="font-semibold">{{ $farmer->land_area ?? 0 }} hektar</span></p>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Pupuk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Kuota</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terpakai</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($quotas as $quota)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $quota->fertilizer->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $quota->allocated_amount }} kg</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $quota->used_amount }} kg</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $quota->allocated_amount - $quota->used_amount }} kg
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $remainingPercentage = $quota->allocated_amount > 0 ? (($quota->allocated_amount - $quota->used_amount) / $quota->allocated_amount) * 100 : 0;
                            @endphp
                            <div class="flex items-center">
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="h-2.5 rounded-full {{ $remainingPercentage <= 20 ? 'bg-red-600' : 'bg-green-600' }}" 
                                         style="width: {{ $remainingPercentage }}%">
                                    </div>
                                </div>
                                <span class="ml-2 text-sm {{ $remainingPercentage <= 20 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ number_format($remainingPercentage, 0) }}%
                                </span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                            Belum ada kuota pupuk yang dialokasikan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection