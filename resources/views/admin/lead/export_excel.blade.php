<table>
    <thead>
        <tr>
            <th>LEAD ID</th>
            <th>TANGGAL</th>
            <th>NAMA</th>
            <th>KOTA</th>
            <th>TELP</th>
            <th>SALES</th>
            <th>SOURCE</th>
            <th>KEBUTUHAN</th>
            <th>STATUS</th> {{-- Tambah kolom status --}}
        </tr>
    </thead>
    <tbody>
        @foreach($lead as $item)
        <tr>
            <td>{{ $item->LEAD_ID }}</td>
            <td>{{ \Carbon\Carbon::parse($item->CREATED_AT)->format('d-m-Y') }}</td>
            <td>{{ $item->NAMA }}</td>
            <td>{{ $item->kota->name ?? '-' }}</td>
            <td>{{ $item->NO_TELP }}</td>
            <td>{{ $item->user->NAMA ?? '-' }}</td>
            <td>{{ $item->LEAD_SOURCE ?? '-' }}</td>
            <td>{{ $item->sub_kategori->NAMA ?? '-' }}</td>
            <td>
                @php
                    $statusColor = match($item->STATUS) {
                        'lead'        => 'bg-blue-400 text-white',    // Cold
                        'opportunity' => 'bg-orange-500 text-white',  // Warm
                        'quotation'   => 'bg-red-400 text-white',     // Hot
                        'lost'        => 'bg-gray-400 text-white',    // Lost
                        'converted'   => 'bg-green-400 text-white',   // Deal
                        'norespon'    => 'bg-yellow-400 text-black',  // No Respon
                        default       => 'bg-gray-200 text-black',
                    };
                    $statusLabel = match($item->STATUS) {
                        'lead'        => 'Cold',
                        'opportunity' => 'Warm',
                        'quotation'   => 'Hot',
                        'lost'        => 'Lost',
                        'converted'   => 'Deal',
                        'norespon'    => 'No Respon',
                        default       => $item->STATUS,
                    };
                @endphp
                <span class="px-2 py-1 rounded {{ $statusColor }}">{{ $statusLabel }}</span>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
