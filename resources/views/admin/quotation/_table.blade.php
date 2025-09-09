<table class="min-w-full table-auto border-collapse">
    <thead class="bg-gray-100">
        <tr>
            <th class="border p-2 text-left">NO</th>
            <th class="border p-2 text-left">TGL</th>
            <th class="border p-2 text-left">NAMA</th>
            <th class="border p-2 text-left">KOTA</th>
            <th class="border p-2 text-left">SALES</th>
            <th class="border p-2 text-left">VALID</th>
            <th class="border p-2 text-left">STATUS</th>
            <th class="border p-2 text-left">ACTION</th>
        </tr>
    </thead>
    <tbody>
        @forelse($quo as $item)
            <tr>
                @php
                    $bulanIndo = [
                        1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
                    ];
                    $created = \Carbon\Carbon::parse($item->CREATED_AT);
                    $valid = \Carbon\Carbon::parse($item->VALID_DATE);
                @endphp
                <td class="border p-2">{{ $quo->firstItem() + $loop->index }}</td>
                <td class="border p-2">{{ $created->format('d') }} {{ $bulanIndo[$created->format('n')] }} {{ $created->format('Y') }}</td>
                <td class="border p-2">{{ $item->opportunity->lead->NAMA ?? '-' }}</td>
                <td class="border p-2">{{ $item->opportunity->lead->kota->name ?? '-' }}</td>
                <td class="border p-2">{{ $item->opportunity->lead->user->NAMA ?? '-' }}</td>
                <td class="border p-2">{{ $valid->format('d') }} {{ $bulanIndo[$valid->format('n')] }} {{ $valid->format('Y') }}</td>
                <td class="border p-2">
                    @if($valid->format('Y-m-d') < now()->format('Y-m-d'))
                        <span class="px-3 py-1 text-sm font-bold text-red-600 bg-red-100 rounded">
                            EXPIRED
                        </span>
                    @elseif($valid->format('Y-m-d') == now()->format('Y-m-d'))
                        <span class="px-3 py-1 text-sm font-bold text-yellow-600 bg-yellow-100 rounded">
                            EXPIRED TODAY
                        </span>
                    @else
                        <span class="px-3 py-1 text-base font-bold text-green-600 bg-green-100 rounded">
                            OPEN
                        </span>
                    @endif
                </td>
                <td class="border p-2 space-x-2">
                    <a href="{{ route('dataquo.admin.detail', ['quo_id' => $item->QUO_ID]) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-3 py-1 rounded">
                        Detail
                    </a>                    
                </td>
                
            </tr>
        @empty
            <tr>
                <td colspan="5" class="border p-2 text-center text-gray-500">Belum ada data</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="mt-4">
    {{ $quo->links() }}
</div>
