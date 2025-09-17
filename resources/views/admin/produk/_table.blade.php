<table class="min-w-full table-auto border-collapse">
    <thead class="bg-gray-100">
        <tr>
            <th class="border p-2 text-left">SKU</th>
            <th class="border p-2 text-left">Nama</th>
            <th class="border p-2 text-left">Subkategori</th>
            <th class="border p-2 text-left">Kategori</th>
            <th class="border p-2 text-left">Harga</th>
            <th class="border p-2 text-left">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($produk as $item)
            <tr>
                <td class="border p-2">{{ $item->SKU }}</td>
                <td class="border p-2">{{ $item->NAMA }}</td>
                <td class="border p-2">{{ $item->subkategori->NAMA ?? '-' }}</td>
                <td class="border p-2">{{ $item->subkategori->kategori->NAMA ?? '-' }}</td>
                <td class="border p-2">
                    {{ $item->HARGA ? 'Rp ' . number_format($item->HARGA, 0, ',', '.') : '-' }}
                </td>
                <td class="border p-2 space-x-2">
                    <a href="{{ route('produk.edit', $item->ID_PRODUK) }}"
                       class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">
                        Edit
                    </a>
                    <form action="{{ route('produk.destroy', $item->ID_PRODUK) }}" 
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
                <td colspan="6" class="border p-2 text-center text-gray-500">Belum ada data</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-4">
    {{ $produk->links() }}
</div>
