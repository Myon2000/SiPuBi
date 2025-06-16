@extends('layouts.app_admin')

@section('title', 'Detail Permintaan Pembelian')

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold">Detail Permintaan Pembelian #{{ $purchaseRequest->id }}</h3>
            <a href="{{ route('admin.purchase-requests.index') }}" class="text-blue-600 hover:text-blue-900">
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
        
        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
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
                    {{ $purchaseRequest->processor->name ?? 'Unknown' }}
                </div>
                <div class="text-sm text-gray-600">
                    <span class="font-medium">Tanggal keputusan:</span> 
                    {{ $purchaseRequest->processed_date ? $purchaseRequest->processed_date->format('d M Y H:i') : '-' }}
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
                <h5 class="text-md font-medium text-gray-700 mb-3">Informasi Petani</h5>
                <dl class="grid grid-cols-1 gap-y-4">
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Nama</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if($purchaseRequest->farmer)
                                {{ $purchaseRequest->farmer->name }}
                            @else
                                <span class="text-red-500">Petani tidak ditemukan</span>
                            @endif
                        </dd>
                    </div>
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if($purchaseRequest->farmer)
                                {{ $purchaseRequest->farmer->email }}
                            @else
                                <span class="text-red-500">Email petani tidak ditemukan</span>
                            @endif
                        </dd>
                    </div>
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Luas Lahan</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $purchaseRequest->farmer->land_area ?? 0 }} hektar
                        </dd>
                    </div>
                </dl>
            </div>
            
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
        </div>
        
        @if($purchaseRequest->status === 'pending')
            <div class="mt-6 border-t pt-6">
                <div class="flex justify-between">
                    <div>
                        <button type="button" 
                                id="rejectBtn"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Tolak Permintaan
                        </button>
                    </div>
                    
                    <form action="{{ route('admin.purchase-requests.approve', $purchaseRequest) }}" method="POST">
                        @csrf
                        <button type="submit" id="approve-button"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                onclick="return confirm('Apakah Anda yakin ingin menyetujui permintaan ini?')">
                            Setujui Permintaan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Modal untuk alasan penolakan - hidden by default -->
            <div id="rejectModal" 
                class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50"
                style="display: none;">
                <div class="w-full max-w-md overflow-hidden rounded-lg bg-white p-6 shadow-xl">
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Tolak Permintaan</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Silakan berikan alasan penolakan permintaan ini.
                        </p>
                    </div>
                    
                    <form action="{{ route('admin.purchase-requests.reject', $purchaseRequest) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Alasan Penolakan</label>
                            <textarea id="rejection_reason" 
                                    name="rejection_reason" 
                                    rows="3" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required></textarea>
                        </div>
                        
                        <div class="mt-5 flex justify-end space-x-3">
                            <button type="button" 
                                    id="cancelBtn"
                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Batal
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Tolak
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- JavaScript untuk modal -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const rejectBtn = document.getElementById('rejectBtn');
                    const cancelBtn = document.getElementById('cancelBtn');
                    const rejectModal = document.getElementById('rejectModal');
                    
                    // Debug logs
                    console.log('DOM loaded');
                    console.log('rejectBtn:', rejectBtn);
                    console.log('cancelBtn:', cancelBtn);
                    console.log('rejectModal:', rejectModal);
                    
                    // Buka modal
                    rejectBtn.addEventListener('click', function() {
                        console.log('Reject button clicked');
                        rejectModal.style.display = 'flex';
                    });
                    
                    // Tutup modal dengan tombol Cancel
                    cancelBtn.addEventListener('click', function() {
                        rejectModal.style.display = 'none';
                    });
                    
                    // Tutup modal dengan klik di luar
                    window.addEventListener('click', function(event) {
                        if (event.target === rejectModal) {
                            rejectModal.style.display = 'none';
                        }
                    });
                });
            </script>
        @endif
    </div>
</div>
@endsection

@section('script')
<script>
$('#approve-button').click(function(e) {
    e.preventDefault();
    
    $.ajax({
        url: "{{ route('admin.purchase-requests.approve', $purchaseRequest) }}",
        type: 'POST',
        data: {
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            // Update UI tanpa refresh halaman
            $('#status-badge').removeClass('bg-yellow-100 text-yellow-800')
                .addClass('bg-green-100 text-green-800')
                .text('Disetujui');
            
            // Tampilkan notifikasi sukses
            toastr.success('Permintaan berhasil disetujui');
            
            // Update tombol-tombol aksi
            $('#action-buttons').hide();
        }
    });
});
</script>
@endsection