@extends('layouts.app_admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Pupuk -->
        <div class="bg-blue-600 rounded-lg shadow-lg p-6 text-white">
            <h5 class="text-lg font-semibold">Total Pupuk</h5>
            <h2 class="text-3xl font-bold">{{ $statistics['total_fertilizers'] }}</h2>
        </div>

        <!-- Stok Menipis -->
        <div class="bg-yellow-500 rounded-lg shadow-lg p-6 text-white">
            <h5 class="text-lg font-semibold">Stok Menipis</h5>
            <h2 class="text-3xl font-bold">{{ $statistics['low_stock_count'] }}</h2>
        </div>

        <!-- Total Petani -->
        <div class="bg-green-600 rounded-lg shadow-lg p-6 text-white">
            <h5 class="text-lg font-semibold">Total Petani</h5>
            <h2 class="text-3xl font-bold">{{ $statistics['total_farmers'] }}</h2>
        </div>

        <!-- Permintaan Pending -->
        <div class="bg-cyan-600 rounded-lg shadow-lg p-6 text-white">
            <h5 class="text-lg font-semibold">Permintaan Pending</h5>
            <h2 class="text-3xl font-bold">{{ $statistics['pending_requests'] }}</h2>
        </div>
    </div>

    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-lg">
            <div class="border-b px-6 py-4">
                <h5 class="text-xl font-semibold">Stok Pupuk Menipis</h5>
            </div>
            <div class="p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pupuk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Saat Ini</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Minimum</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($lowStockFertilizers as $fertilizer)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $fertilizer->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $fertilizer->current_stock }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $fertilizer->minimum_stock }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                Tidak ada pupuk dengan stok menipis
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection