@extends('layouts.app_admin')

@section('title', 'Edit Kuota')

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold">Edit Kuota Pupuk: {{ $quota->user->name }}</h3>
    </div>
    <div class="p-6">
        <form action="{{ route('admin.quotas.update', $quota) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Petani</label>
                    <div class="mt-1 p-3 bg-gray-50 rounded-md">
                        {{ $quota->user->name }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Pupuk</label>
                    <div class="mt-1 p-3 bg-gray-50 rounded-md">
                        {{ $quota->fertilizer->name }}
                    </div>
                </div>

                <div>
                    <label for="allocated_amount" class="block text-sm font-medium text-gray-700">
                        Jumlah Kuota (kg)
                        <span class="text-gray-500 text-xs">
                            (Minimal: {{ $quota->used_amount }} kg - sudah terpakai)
                        </span>
                    </label>
                    <input type="number" 
                           name="allocated_amount" 
                           id="allocated_amount" 
                           value="{{ old('allocated_amount', $quota->allocated_amount) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('allocated_amount') border-red-300 @enderror"
                           min="{{ $quota->used_amount }}"
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
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('notes') border-red-300 @enderror">{{ old('notes', $quota->notes) }}</textarea>
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
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection