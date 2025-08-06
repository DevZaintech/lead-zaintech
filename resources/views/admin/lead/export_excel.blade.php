<table>
    <thead>
        <tr>
            <th>LEAD ID</th>
            <th>TANGGAL</th>
            <th>NAMA</th>
            <th>SALES</th>
            <th>PERUSAHAAN</th>
            <th>KOTA</th>
            <th>TELP</th>
            <th>SOURCE</th>
        </tr>
    </thead>
    <tbody>
        @foreach($lead as $item)
        <tr>
            <td>{{ $item->LEAD_ID }}</td>
            <td>{{ \Carbon\Carbon::parse($item->CREATED_AT)->format('d-m-Y') }}</td>
            <td>{{ $item->NAMA }}</td>
            <td>{{ $item->user->NAMA ?? '-' }}</td>
            <td>{{ $item->PERUSAHAAN ?? '-' }}</td>
            <td>{{ $item->KOTA ?? '-' }}</td>
            <td>{{ $item->NO_TELP }}</td>
            <td>{{ $item->LEAD_SOURCE ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
