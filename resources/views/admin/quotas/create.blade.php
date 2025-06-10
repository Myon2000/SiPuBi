@extends('layouts.app_admin')

@section('title', 'Tambah Kuota')

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold">Tambah Kuota Pupuk</h3>
    </div>
    <div class="p-6">
        <form action="{{ route('admin.quotas.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700">Petani</label>
                    <select name="user_id" 
                            id="user_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('user_id') border-red-300 @enderror"
                            required>
                        <option value="">Pilih Petani</option>
                        @foreach($farmers as $farmer)
                            <option value="{{ $farmer->id }}" {{ old('user_id') == $farmer->id ? 'selected' : '' }}>
                                {{ $farmer->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="fertilizer_id" class="block text-sm font-medium text-gray-700">Jenis Pupuk</label>
                    <select name="fertilizer_id" 
                            id="fertilizer_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('fertilizer_id') border-red-300 @enderror"
                            required>
                        <option value="">Pilih Jenis Pupuk</option>
                        @foreach($fertilizers as $fertilizer)
                            <option value="{{ $fertilizer->id }}" {{ old('fertilizer_id') == $fertilizer->id ? 'selected' : '' }}>
                                {{ $fertilizer->name }} (Stok: {{ $fertilizer->current_stock }} kg)
                            </option>
                        @endforeach
                    </select>
                    @error('fertilizer_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="allocated_amount" class="block text-sm font-medium text-gray-700">Jumlah Kuota (kg)</label>
                    <input type="number" 
                           name="allocated_amount" 
                           id="allocated_amount" 
                           value="{{ old('allocated_amount') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('allocated_amount') border-red-300 @enderror"
                           min="1"
                           required>
                    @error('allocated_amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                    <textarea name="notes" 
                              id="notes" 
                              rows="3" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('notes') border-red-300 @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.quotas.index') }}" 
                       class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection