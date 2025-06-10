@extends('layouts.app_admin')

@section('title', 'Edit Pupuk')

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold">Edit Pupuk: {{ $fertilizer->name }}</h3>
    </div>
    <div class="p-6">
        <form action="{{ route('admin.fertilizers.update', $fertilizer) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Jenis Pupuk</label>
                    <select name="name" 
                            id="name" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-300 @enderror"
                            required>
                        <option value="">Pilih Jenis Pupuk</option>
                        @foreach(\App\Models\Fertilizer::TYPES as $value => $label)
                            <option value="{{ $label }}" {{ old('name', $fertilizer->name) == $label ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea name="description" 
                              id="description" 
                              rows="3" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-300 @enderror"
                              required>{{ old('description', $fertilizer->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                        <input type="number" 
                               name="price" 
                               id="price" 
                               value="{{ old('price', $fertilizer->price) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('price') border-red-300 @enderror"
                               min="0"
                               required>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="current_stock" class="block text-sm font-medium text-gray-700">Stok Saat Ini (Kg)</label>
                        <input type="number" 
                               value="{{ $fertilizer->current_stock }}"
                               class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 cursor-not-allowed"
                               disabled>
                        <p class="mt-1 text-sm text-gray-500">Stok hanya bisa diubah melalui transaksi</p>
                    </div>

                    <div>
                        <label for="minimum_stock" class="block text-sm font-medium text-gray-700">Stok Minimum (Kg)</label>
                        <input type="number" 
                               name="minimum_stock" 
                               id="minimum_stock" 
                               value="{{ old('minimum_stock', $fertilizer->minimum_stock) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('minimum_stock') border-red-300 @enderror"
                               min="0"
                               required>
                        @error('minimum_stock')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="inline-flex items-center">
                        <input type="checkbox" 
                               name="status" 
                               value="1" 
                               {{ old('status', $fertilizer->status) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600">Aktif</span>
                    </label>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.fertilizers.index') }}" 
                       class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection