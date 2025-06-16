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