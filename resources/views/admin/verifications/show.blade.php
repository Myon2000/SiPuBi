@extends('layouts.app_admin')

@section('title', 'Detail Verifikasi Lahan')

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold">Detail Verifikasi #{{ $verification->id }}</h3>
        <a href="{{ route('admin.verifications.index') }}" class="text-blue-600 hover:text-blue-900">
            &larr; Kembali
        </a>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-md font-medium text-gray-700 mb-3">Informasi Petani</h4>
                <dl class="grid grid-cols-1 gap-y-4">
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Nama</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $verification->user->name }}
                        </dd>
                    </div>
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $verification->user->email }}
                        </dd>
                    </div>
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">No. Telepon</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $verification->user->phone ?? '-' }}
                        </dd>
                    </div>
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $verification->user->address ?? '-' }}
                        </dd>
                    </div>
                </dl>

                <h4 class="text-md font-medium text-gray-700 mt-6 mb-3">Detail Perubahan</h4>
                <dl class="grid grid-cols-1 gap-y-4">
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Luas Lahan Lama</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $verification->old_land_area }} hektar
                        </dd>
                    </div>
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Luas Lahan Baru</dt>
                        <dd class="mt-1 text-sm font-gray-900 sm:mt-0 sm:col-span-2 font-bold">
                            {{ $verification->new_land_area }} hektar
                        </dd>
                    </div>
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Tanggal Pengajuan</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $verification->created_at->format('d M Y H:i') }}
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="border rounded-lg p-4">
                <h4 class="text-md font-medium text-gray-700 mb-2">Foto KTP</h4>
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $verification->ktp_image) }}" alt="KTP" class="w-full h-auto rounded border border-gray-200">
                </div>
                <div class="mt-3">
                    <a href="{{ route('admin.verifications.download-ktp', $verification) }}" 
                    class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download KTP
                    </a>
                </div>
            </div>
                
                @if($verification->user->latitude && $verification->user->longitude)
                <div class="mt-4 border rounded-lg p-4">
                    <h4 class="text-md font-medium text-gray-700 mb-2">Lokasi Petani</h4>
                    <div id="map" class="h-48 w-full rounded"></div>
                </div>
                @endif
            </div>
        </div>
        
        @if($verification->status === 'pending')
        <div class="mt-6 border-t pt-6">
            <div class="flex justify-between">
                <div x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Tolak Verifikasi
                    </button>
                    
                    <div x-show="open" 
                        class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center"
                        style="z-index: 1500 !important;">
                        <div class="bg-white rounded-lg p-6 w-full max-w-md">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Tolak Verifikasi</h3>
                            
                            <form action="{{ route('admin.verifications.reject', $verification) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Alasan Penolakan</label>
                                    <textarea id="notes" name="notes" rows="3" required
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                </div>
                                
                                <div class="flex justify-end space-x-3">
                                    <button type="button" @click="open = false"
                                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                        Batal
                                    </button>
                                    <button type="submit"
                                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                        Tolak
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div x-data="{ open: false }">
                    <button @click="open = !open"
                            class="approve-btn inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Setujui Verifikasi
                    </button>
                    
                    <div x-show="open" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
                        <div class="bg-white rounded-lg p-6 w-full max-w-md">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Setujui Verifikasi</h3>
                            
                            <form action="{{ route('admin.verifications.approve', $verification) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                                    <textarea id="notes" name="notes" rows="3"
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                </div>
                                
                                <div class="flex justify-end space-x-3">
                                    <button type="button" @click="open = false"
                                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                        Batal
                                    </button>
                                    <button type="submit"
                                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                        Setujui
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        @if($verification->status !== 'pending')
        <div class="mt-6 border-t pt-6">
            <div class="p-4 rounded-md {{ $verification->status === 'approved' ? 'bg-green-50' : 'bg-red-50' }}">
                <h4 class="text-md font-medium text-{{ $verification->status === 'approved' ? 'green' : 'red' }}-800">
                    Verifikasi {{ $verification->status === 'approved' ? 'Disetujui' : 'Ditolak' }}
                </h4>
                <p class="text-sm text-gray-600 mt-1">
                    Oleh: {{ $verification->processor->name ?? 'Unknown' }} pada {{ $verification->processed_at->format('d M Y H:i') }}
                </p>
                @if($verification->admin_notes)
                <div class="mt-2">
                    <p class="text-sm font-medium text-gray-700">Catatan:</p>
                    <p class="text-sm text-gray-600">{{ $verification->admin_notes }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

@if($verification->user->latitude && $verification->user->longitude)
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const lat = {{ $verification->user->latitude }};
        const lng = {{ $verification->user->longitude }};
        
        // Inisialisasi peta
        const map = L.map('map').setView([lat, lng], 15);
        
        // Tambahkan layer OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        // Tambahkan marker untuk lokasi petani
        L.marker([lat, lng]).addTo(map)
            .bindPopup("Lokasi Petani: {{ $verification->user->name }}")
            .openPopup();
    });

    const fixZIndexIssues = () => {
        // Fix untuk navbar
        document.querySelectorAll('nav').forEach(nav => {
            nav.style.zIndex = '1000';
            nav.style.position = 'relative';
        });
        
        // Fix untuk modal dialog
        document.querySelectorAll('[x-show="open"].fixed.inset-0').forEach(modal => {
            modal.style.zIndex = '1500';
        });
        
        // Kurangi z-index pada kontainer peta
        document.querySelectorAll('.leaflet-container').forEach(container => {
            container.style.zIndex = '100';
        });
    };

    // Jalankan saat peta dimuat
    map.on('load', fixZIndexIssues);
    // Jalankan juga sekarang
    fixZIndexIssues();

    $(document).ready(function() {
        $('.approve-btn').click(function(e) {
            e.preventDefault();
            
            const form = $(this).closest('form');
            const notes = form.find('[name="notes"]').val();
            const url = form.attr('action');
            
            // Tampilkan loading
            Swal.fire({
                title: 'Memproses...',
                didOpen: () => {
                    Swal.showLoading();
                },
                allowOutsideClick: false,
                allowEscapeKey: false
            });
            
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    notes: notes
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Verifikasi lahan berhasil disetujui',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Reload halaman dengan hasil terbaru
                        window.location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Silakan coba lagi nanti.'
                    });
                }
            });
        });
    });
</script>
@endsection
@endif
@endsection