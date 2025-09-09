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
        </tr>
        @endforeach
    </tbody>
</table>
