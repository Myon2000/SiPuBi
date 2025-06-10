@extends('layouts.app')

@section('title', 'Kuota Pupuk')

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold">Kuota Pupuk Saya</h3>
    </div>
    <div class="p-6">
        @if(!auth()->user()->land_area)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Anda belum mengatur luas lahan. <a href="{{ route('profile.edit') }}" class="font-medium underline text-yellow-700 hover:text-yellow-600">Atur sekarang</a> untuk menghitung kuota.
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="mb-4 bg-blue-50 border-l-4 border-blue-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            Kuota dihitung berdasarkan luas lahan Anda: <strong>{{ auth()->user()->land_area }} hektar</strong>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($quotas as $quota)
                <div class="bg-white border rounded-lg overflow-hidden shadow-sm">
                    <div class="px-4 py-5 sm:px-6 border-b">
                        <h3 class="text-lg font-medium text-gray-900">{{ $quota->fertilizer->name }}</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        @php
                            $percentage = $quota->allocated_amount > 0 
                                ? ($quota->remaining_quota / $quota->allocated_amount) * 100 
                                : 0;
                        @endphp
                        <div class="flex justify-between items-start mb-1">
                            <span class="text-sm text-gray-500">Sisa Kuota</span>
                            <span class="text-sm font-medium {{ $percentage <= 20 ? 'text-red-600' : 'text-green-600' }}">
                                {{ number_format($percentage, 0) }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                            <div class="h-2.5 rounded-full {{ $percentage <= 20 ? 'bg-red-600' : 'bg-green-600' }}" 
                                 style="width: {{ $percentage }}%">
                            </div>
                        </div>
                        <div class="mt-4 space-y-2">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Total Kuota</span>
                                <span>{{ number_format($quota->allocated_amount, 0) }} kg</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Terpakai</span>
                                <span>{{ number_format($quota->used_amount, 0) }} kg</span>
                            </div>
                            <div class="flex justify-between text-sm font-medium">
                                <span>Sisa</span>
                                <span>{{ number_format($quota->remaining_quota, 0) }} kg</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection