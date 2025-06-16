@extends('layouts.app_admin')

@section('title', 'Daftar Permintaan Pembelian')

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold">Daftar Permintaan Pembelian Pupuk</h3>
    </div>
    
    <div class="p-6">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="mb-6 flex space-x-2">
            <a href="{{ route('admin.purchase-requests.index', ['status' => 'pending']) }}" 
               class="px-4 py-2 rounded-md {{ $status === 'pending' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Menunggu
            </a>
            <a href="{{ route('admin.purchase-requests.index', ['status' => 'approved']) }}" 
               class="px-4 py-2 rounded-md {{ $status === 'approved' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Disetujui
            </a>
            <a href="{{ route('admin.purchase-requests.index', ['status' => 'rejected']) }}" 
               class="px-4 py-2 rounded-md {{ $status === 'rejected' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Ditolak
            </a>
            <a href="{{ route('admin.purchase-requests.index', ['status' => 'all']) }}" 
               class="px-4 py-2 rounded-md {{ $status === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Semua
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Petani</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Pupuk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($requests as $request)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    #{{ $request->id }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $request->farmer->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $request->fertilizer->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ number_format($request->quantity, 0) }} kg
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    Rp {{ number_format($request->total_price, 0) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($request->isPending())
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                        Menunggu
                                    </span>
                                @elseif($request->isApproved())
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                        Disetujui
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">
                                    {{ $request->created_at->format('d M Y H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.purchase-requests.show', $request) }}" class="text-blue-600 hover:text-blue-900">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                Tidak ada permintaan pembelian pupuk
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $requests->links() }}
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
$(document).ready(function() {
    // Tambahkan class "status-filter" ke tombol filter
    $('.px-4.py-2.rounded-md').addClass('status-filter');
    
    $('.status-filter').click(function(e) {
        e.preventDefault();
        
        const url = $(this).attr('href');
        const status = url.split('status=')[1];
        
        // Tampilkan loading
        const tableBody = $('table tbody');
        tableBody.html('<tr><td colspan="8" class="text-center py-4"><div class="spinner-border text-blue-500" role="status"><span class="sr-only">Loading...</span></div></td></tr>');
        
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.html) {
                    tableBody.html(response.html);
                    
                    // Update URL
                    window.history.pushState({}, "", url);
                    
                    // Update active filter
                    $('.status-filter').removeClass('bg-blue-600 text-white').addClass('bg-gray-200 text-gray-700');
                    $(e.target).removeClass('bg-gray-200 text-gray-700').addClass('bg-blue-600 text-white');
                }
            },
            error: function(xhr, status, error) {
                tableBody.html('<tr><td colspan="8" class="text-center py-4 text-red-500">Terjadi kesalahan saat memuat data</td></tr>');
                console.error('AJAX Error:', error);
            }
        });
    });
});
</script>
@endsection