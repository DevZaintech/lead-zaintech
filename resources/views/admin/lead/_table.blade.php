<table class="min-w-full table-auto border-collapse">
    <thead class="bg-gray-100">
        <tr>
            <th class="border p-2 text-center" style="width:3%">NO</th>
            <th class="border p-2 text-center">TANGGAL</th>
            <th class="border p-2 text-center">NAMA/KOTA</th>
            <!-- <th class="border p-2 text-center">Kota</th> -->
            <th class="border p-2 text-center">TELP</th>
            <th class="border p-2 text-center">KEBUTUHAN</th>
            <th class="border p-2 text-center">SALES</th>
            <th class="border p-2 text-center">SOURCE</th>
            <th class="border p-2 text-center">STATUS</th>
            <th class="border p-2 text-center"> </th>
        </tr>
    </thead>
    <tbody>
        @forelse ($lead as $index => $item)
            <tr>
                <td class="border p-2">{{ $lead->firstItem() + $index }}</td>
                @php
                    $bulanIndo = [
                        1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
                    ];
                    $tanggal = \Carbon\Carbon::parse($item->CREATED_AT);
                @endphp
                <td class="border p-2">{{ $tanggal->format('d') }} {{ $bulanIndo[$tanggal->format('n')] }} {{ $tanggal->format('Y') }}</td>
                <td class="border p-2">{{ $item->NAMA ?? '-' }} - {{ $item->kota->name ?? '-' }}</td>
                <!-- <td class="border p-2">{{ $item->kota->name ?? '-' }}</td> -->
                <td class="border p-2">{{ $item->NO_TELP }}</td>
                <td class="border p-2">{{ $item->sub_kategori->NAMA ?? '-' }}</td>
                <td class="border p-2">{{ $item->user->NAMA ?? '-' }}</td>
                <td class="border p-2">{{ $item->LEAD_SOURCE ?? '-' }}</td>
                <td class="border p-2 space-x-2">
                <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2 py-1 rounded text-sm font-medium {{ $item->stage_class }}">
                            {{ $item->stage_label }}
                        </span>
                    </div>
                </td>
                <td class="border p-2 space-x-2">
                    <a href="{{ route('lead.admin.detail', ['lead_id' => $item->LEAD_ID]) }}"
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
    {{ $lead->links() }}
</div>
