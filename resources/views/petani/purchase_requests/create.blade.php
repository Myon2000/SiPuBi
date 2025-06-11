@extends('layouts.app')

@section('title', 'Ajukan Pembelian Pupuk')

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold">Ajukan Pembelian Pupuk</h3>
            <a href="{{ route('petani.purchase-requests.index') }}" class="text-blue-600 hover:text-blue-900">
                &larr; Kembali
            </a>
        </div>
    </div>
    <div class="p-6">
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('petani.purchase-requests.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label for="fertilizer_id" class="block text-sm font-medium text-gray-700 mb-1">Jenis Pupuk</label>
                    <select name="fertilizer_id" 
                            id="fertilizer_id" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('fertilizer_id') border-red-300 @enderror"
                            required
                            onchange="updateFertilizerInfo(this.value)">
                        <option value="">Pilih Jenis Pupuk</option>
                        @foreach($fertilizers as $fertilizer)
                            <option value="{{ $fertilizer->id }}" {{ old('fertilizer_id') == $fertilizer->id ? 'selected' : '' }}>
                                {{ $fertilizer->name }} (Stok: {{ number_format($fertilizer->current_stock, 0) }} kg)
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Card Informasi Pupuk yang Selalu Ditampilkan -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <h4 class="text-md font-semibold text-gray-700 mb-3">Informasi Pupuk</h4>
                    
                    <!-- Saat belum ada pupuk dipilih -->
                    <div id="no-selection" class="text-center py-4 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-2">Silakan pilih jenis pupuk untuk melihat informasi</p>
                    </div>
                    
                    <!-- Saat sudah ada pupuk dipilih (awalnya disembunyikan) -->
                    <div id="fertilizer-info" class="hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Kuota -->
                            <div class="bg-white p-3 rounded shadow-sm">
                                <div class="flex items-center mb-2">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 text-blue-500 mr-2">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </span>
                                    <h5 class="text-sm font-semibold text-gray-700">Kuota Anda</h5>
                                </div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span>Sisa:</span>
                                    <span id="info-quota" class="font-medium text-blue-600">-</span>
                                </div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span>Digunakan:</span>
                                    <span id="info-used" class="font-medium">-</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span>Total:</span>
                                    <span id="info-total" class="font-medium">-</span>
                                </div>
                            </div>
                            
                            <!-- Stok -->
                            <div class="bg-white p-3 rounded shadow-sm">
                                <div class="flex items-center mb-2">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-green-100 text-green-500 mr-2">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                        </svg>
                                    </span>
                                    <h5 class="text-sm font-semibold text-gray-700">Stok Gudang</h5>
                                </div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span>Tersedia:</span>
                                    <span id="info-stock" class="font-medium text-green-600">-</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span>Min. Stok:</span>
                                    <span id="info-min-stock" class="font-medium">-</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Harga dan Maksimum Pembelian -->
                        <div class="mt-3 bg-blue-50 p-3 rounded flex flex-col md:flex-row justify-between">
                            <div class="flex items-center mb-2 md:mb-0">
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 text-blue-500 mr-2">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                                <div>
                                    <div class="text-sm font-medium text-gray-700">Harga per kg:</div>
                                    <div id="info-price" class="text-sm font-bold">Rp -</div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100 text-indigo-500 mr-2">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                </span>
                                <div>
                                    <div class="text-sm font-medium text-gray-700">Maksimum pembelian:</div>
                                    <div id="info-max" class="text-sm font-bold text-indigo-600">- kg</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Jumlah (kg)</label>
                    <input type="number" 
                           name="quantity" 
                           id="quantity" 
                           min="1" 
                           step="1"
                           value="{{ old('quantity') }}" 
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('quantity') border-red-300 @enderror"
                           oninput="updateTotal()"
                           required>
                    <p class="mt-1 text-sm text-gray-500">
                        Masukkan jumlah pupuk yang ingin dibeli (dalam kg)
                    </p>
                </div>
                
                <!-- Total Harga (Selalu Tampil) -->
                <div class="bg-green-50 p-4 rounded-md border border-green-100">
                    <div class="flex items-center mb-2">
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-green-100 text-green-500 mr-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <h5 class="text-sm font-semibold text-gray-700">Ringkasan Pembelian</h5>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium">Total Harga:</span>
                        <span id="total-price" class="text-lg font-bold text-green-700">-</span>
                    </div>
                </div>
                
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                    <textarea name="notes" 
                              id="notes" 
                              rows="3" 
                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">
                        Tambahkan catatan jika diperlukan
                    </p>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Ajukan Pembelian
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Menyimpan data pupuk dalam JavaScript
    const fertilizerData = {
        @foreach($fertilizers as $fertilizer)
            "{{ $fertilizer->id }}": {
                stock: {{ $fertilizer->current_stock }},
                price: {{ $fertilizer->price }},
                quota: {{ isset($quotas[$fertilizer->id]) ? $quotas[$fertilizer->id]->allocated_amount - $quotas[$fertilizer->id]->used_amount : 0 }},
                used: {{ isset($quotas[$fertilizer->id]) ? $quotas[$fertilizer->id]->used_amount : 0 }},
                total: {{ isset($quotas[$fertilizer->id]) ? $quotas[$fertilizer->id]->allocated_amount : 0 }}
            },
        @endforeach
    };
    
    // Format angka dengan pemisah ribuan
    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }
    
    // Update informasi pupuk saat pilihan berubah
    function updateFertilizerInfo(fertilizerId) {
        const noSelection = document.getElementById('no-selection');
        const fertilizerInfo = document.getElementById('fertilizer-info');
        
        // Jika tidak ada pupuk yang dipilih
        if (!fertilizerId) {
            noSelection.classList.remove('hidden');
            fertilizerInfo.classList.add('hidden');
            document.getElementById('total-price').textContent = '-';
            return;
        }
        
        // Ambil data pupuk yang dipilih
        const data = fertilizerData[fertilizerId];
        if (!data) return;
        
        // Hitung nilai-nilai yang diperlukan
        const minStockValue = Math.round(data.stock * 0.2);
        const maxBuy = Math.min(data.quota, data.stock);
        
        // Update tampilan
        document.getElementById('info-quota').textContent = formatNumber(data.quota) + ' kg';
        document.getElementById('info-used').textContent = formatNumber(data.used) + ' kg';
        document.getElementById('info-total').textContent = formatNumber(data.total) + ' kg';
        
        document.getElementById('info-stock').textContent = formatNumber(data.stock) + ' kg';
        document.getElementById('info-min-stock').textContent = formatNumber(minStockValue) + ' kg';
        
        document.getElementById('info-price').textContent = 'Rp ' + formatNumber(data.price);
        document.getElementById('info-max').textContent = formatNumber(maxBuy) + ' kg';
        
        // Set atribut max pada input jumlah
        document.getElementById('quantity').setAttribute('max', maxBuy);
        
        // Tampilkan panel informasi pupuk
        noSelection.classList.add('hidden');
        fertilizerInfo.classList.remove('hidden');
        
        // Update total harga
        updateTotal();
    }
    
    // Update total harga saat jumlah berubah
    function updateTotal() {
        const fertilizerId = document.getElementById('fertilizer_id').value;
        const quantity = parseFloat(document.getElementById('quantity').value);
        
        if (!fertilizerId || isNaN(quantity) || quantity <= 0) {
            document.getElementById('total-price').textContent = '-';
            return;
        }
        
        const data = fertilizerData[fertilizerId];
        if (!data) return;
        
        const total = data.price * quantity;
        document.getElementById('total-price').textContent = 'Rp ' + formatNumber(total);
    }
    
    // Inisialisasi form
    document.addEventListener('DOMContentLoaded', function() {
        const fertilizerId = document.getElementById('fertilizer_id').value;
        if (fertilizerId) {
            updateFertilizerInfo(fertilizerId);
        }
    });
</script>
@endsection