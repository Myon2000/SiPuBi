@extends('layouts.app')

@section('title', 'Detail Permintaan')

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold">Detail Permintaan Pembelian #{{ $purchaseRequest->id }}</h3>
            <a href="{{ route('petani.purchase-requests.index') }}" class="text-blue-600 hover:text-blue-900">
                &larr; Kembali
            </a>
        </div>
    </div>
    
    <div class="p-6">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-medium text-gray-900">Status Permintaan</h4>
                @if($purchaseRequest->status === 'pending')
                    <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">
                        Menunggu Persetujuan
                    </span>
                @elseif($purchaseRequest->status === 'approved')
                    <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">
                        Disetujui
                    </span>
                @else
                    <span class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-800">
                        Ditolak
                    </span>
                @endif
            </div>
            
            @if($purchaseRequest->status !== 'pending')
                <div class="text-sm text-gray-600 mb-2">
                    <span class="font-medium">Diputuskan oleh:</span> 
                    {{ $purchaseRequest->approver->name ?? 'Unknown' }}
                </div>
                <div class="text-sm text-gray-600">
                    <span class="font-medium">Tanggal keputusan:</span> 
                    {{ $purchaseRequest->approved_at ? $purchaseRequest->approved_at->format('d M Y H:i') : '-' }}
                </div>
            @endif
            
            @if($purchaseRequest->status === 'rejected' && $purchaseRequest->notes)
                <div class="mt-4 p-3 bg-red-50 text-red-700 rounded border border-red-200">
                    <p class="text-sm font-medium">Alasan Penolakan:</p>
                    <p class="text-sm">{{ $purchaseRequest->notes }}</p>
                </div>
            @endif
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h5 class="text-md font-medium text-gray-700 mb-3">Informasi Pembelian</h5>
                <dl class="grid grid-cols-1 gap-y-4">
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Jenis Pupuk</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $purchaseRequest->fertilizer->name }}
                        </dd>
                    </div>
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Jumlah</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ number_format($purchaseRequest->quantity, 0) }} kg
                        </dd>
                    </div>
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Catatan</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $purchaseRequest->notes ?: '-' }}
                        </dd>
                    </div>
                </dl>
            </div>
            
            <div>
                <h5 class="text-md font-medium text-gray-700 mb-3">Informasi Permintaan</h5>
                <dl class="grid grid-cols-1 gap-y-4">
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">ID Permintaan</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            #{{ $purchaseRequest->id }}
                        </dd>
                    </div>
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $purchaseRequest->created_at->format('d M Y H:i') }}
                        </dd>
                    </div>
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Terakhir Diperbarui</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $purchaseRequest->updated_at->format('d M Y H:i') }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
        
        @if($purchaseRequest->status === 'pending')
            <div class="mt-6 border-t pt-6 flex justify-end">
                <form action="{{ route('petani.purchase-requests.cancel', $purchaseRequest) }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                            onclick="return confirm('Apakah Anda yakin ingin membatalkan permintaan ini?')">
                        Batalkan Permintaan
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection