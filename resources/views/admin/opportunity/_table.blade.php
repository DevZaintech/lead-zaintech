<table class="min-w-full table-auto border-collapse">
    <thead class="bg-gray-100">
        <tr>
            <th class="border p-2 text-left">NO</th>
            <th class="border p-2 text-left">TGL</th>
            <th class="border p-2 text-left">NAMA</th>
            <th class="border p-2 text-left">KOTA</th>
            <th class="border p-2 text-left">SOURCE</th>
            <th class="border p-2 text-left">SALES</th>
            <th class="border p-2 text-left">NILAI PROSPECT</th>
            <th class="border p-2 text-left">PROSENTASE</th>
            <th class="border p-2 text-left">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($opp as $index => $item)
            <tr>
                <td class="border p-2">{{ $opp->firstItem() + $index }}</td>
                @php
                    $bulanIndo = [
                        1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
                    ];
                    $tanggal = \Carbon\Carbon::parse($item->CREATED_AT);
                @endphp
                <td class="border p-2">{{ $tanggal->format('d') }} {{ $bulanIndo[$tanggal->format('n')] }} {{ $tanggal->format('Y') }}</td>
                <td class="border p-2">{{ $item->lead->NAMA ?? '-' }}</td>
                <td class="border p-2">{{ $item->lead->kota->name }}</td>
                <td class="border p-2">{{ $item->lead->LEAD_SOURCE }}</td>
                <td class="border p-2">{{ $item->lead->user->NAMA ?? '-' }}</td>
                <td class="border p-2">Rp {{ number_format($item->NILAI_PROSPECT, 0, ',', '.') }}</td>
                <td class="border p-2">{{ $item->PROSENTASE_PROSPECT }}%</td>
                
                <td class="border p-2 space-x-2">
                    <a href="{{ route('opp.admin.detail', ['opp_id' => $item->OPPORTUNITY_ID]) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-3 py-1 rounded transition-colors duration-200">
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
    {{ $opp->links() }}
</div>
