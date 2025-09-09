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
                <td class="border p-2 text-center">{{ $lead->firstItem() + $index }}</td>
                @php
                    $bulanIndo = [
                        1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
                    ];
                    $tanggal = \Carbon\Carbon::parse($item->CREATED_AT);
                @endphp
                <td class="border p-2 text-center">{{ $tanggal->format('d') }} {{ $bulanIndo[$tanggal->format('n')] }} {{ $tanggal->format('Y') }}</td>
                <td class="border p-2 text-center">{{ $item->NAMA ?? '-' }} - {{ $item->kota->name ?? '-' }}</td>
                <!-- <td class="border p-2 text-center">{{ $item->kota->name ?? '-' }}</td> -->
                <td class="border p-2 text-center">{{ $item->NO_TELP }}</td>
                <td class="border p-2 text-center">{{ $item->sub_kategori->NAMA ?? '-' }}</td>
                <td class="border p-2 text-center">{{ $item->user->NAMA ?? '-' }}</td>
                <td class="border p-2 text-center">{{ $item->LEAD_SOURCE ?? '-' }}</td>
                <td class="border p-2 text-center">
                    @php
                        $statusClasses = [
                            'lead'        => 'bg-blue-400 text-white',      // Cold
                            'opportunity' => 'bg-orange-400 text-black',    // Warm
                            'quotation'   => 'bg-red-400 text-white',       // Hot
                            'converted'   => 'bg-green-400 text-white',     // Deal
                            'lost'        => 'bg-gray-400 text-white',      // Lost
                            'norespon'    => 'bg-yellow-400 text-black',    // No Respon
                        ];

                        $statusLabels = [
                            'lead'        => 'Cold',
                            'opportunity' => 'Warm',
                            'quotation'   => 'Hot',
                            'converted'   => 'Deal',
                            'lost'        => 'Lost',
                            'norespon'    => 'No Respon',
                        ];

                        $status = strtolower(trim($item->STATUS));
                        $class  = $statusClasses[$status] ?? 'bg-gray-400 text-white';
                        $label  = $statusLabels[$status] ?? ucfirst($status);
                    @endphp

                    <span class="px-2 py-1 rounded text-sm font-medium {{ $class }}">
                        {{ $label }}
                    </span>
                </td>

                <td class="border p-2 text-center">
                    <a href="{{ route('lead.gate.detail', ['lead_id' => $item->LEAD_ID]) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-3 py-1 rounded transition-colors duration-200">
                            Detail
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="border p-2 text-center text-gray-500">Belum ada data</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="mt-4">
    {{ $lead->links() }}
</div>
