<table class="min-w-full table-auto border-collapse">
    <thead class="bg-gray-100">
        <tr>
            <th class="border p-2 text-left">ID</th>
            <th class="border p-2 text-left">Nama</th>
            <th class="border p-2 text-left">Telp</th>
            <th class="border p-2 text-left">Sales</th>
            <th class="border p-2 text-left">Kebutuhan</th>
            <th class="border p-2 text-left">Tanggal</th>
            <th class="border p-2 text-left">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($lead as $item)
            <tr>
                <td class="border p-2">{{ $item->LEAD_ID }}</td>
                <td class="border p-2">{{ $item->NAMA ?? '-' }}</td>
                <td class="border p-2">{{ $item->NO_TELP }}</td>
                <td class="border p-2">{{ $item->user->NAMA ?? '-' }}</td>
                <td class="border p-2">{{ $item->sub_kategori->NAMA ?? '-' }}</td>
                @php
                    $bulanIndo = [
                        1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
                    ];
                    $tanggal = \Carbon\Carbon::parse($item->CREATED_AT);
                @endphp
                <td class="border p-2">{{ $tanggal->format('d') }} {{ $bulanIndo[$tanggal->format('n')] }} {{ $tanggal->format('Y') }}</td>
                
                <td class="border p-2 space-x-2">
                    <a href="#"
                       class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">
                        Edit
                    </a>
                    <form action="#" 
                        method="POST" class="inline"
                        onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                            Hapus
                        </button>
                    </form>
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
