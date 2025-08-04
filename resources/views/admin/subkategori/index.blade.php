@extends('layouts.frontend')

@section('title', 'Data SubKategori')

@section('content')

<div class="bg-white p-6 rounded-lg shadow w-full lg:w-[75%] mx-auto">
    <h2 class="text-lg font-semibold mb-4">Data SubKategori</h2>

    <!-- Form 1 Baris di Desktop -->
    <form action="{{ route('subkategori.store') }}" method="POST"
        class="flex gap-2 mb-4 flex-wrap lg:flex-nowrap"
        x-data="{ selectedKategori: '' }">
        @csrf
        <input type="text" name="NAMA" placeholder="Nama SubKategori"
            class="border rounded p-2 flex-1 focus:outline-none focus:ring-2 focus:ring-blue-400" required>

        <select name="ID_KATEGORI" required
                class="border rounded p-2 flex-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                x-model="selectedKategori">
            <option value="">Pilih Kategori</option>
            @foreach($kategori as $k)
                <option value="{{ $k->ID_KATEGORI }}">{{ $k->NAMA }}</option>
            @endforeach
        </select>

        <button type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="!selectedKategori">
            Tambah
        </button>
    </form>

    <!-- Tabel -->
    <table class="min-w-full border text-sm">
        <thead class="bg-gray-100">
        <tr>
            <th class="border px-4 py-2 text-left">ID</th>
            <th class="border px-4 py-2 text-left">Nama</th>
            <th class="border px-4 py-2 text-left">Kategori</th>
            <th class="border px-4 py-2 text-left">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @foreach($subkategori as $row)
            <tr class="hover:bg-gray-50">
                <td class="border px-4 py-2">{{ $row->ID_SUB }}</td>
                <td class="border px-4 py-2">{{ $row->NAMA }}</td>
                <td class="border px-4 py-2">{{ $row->kategori->NAMA ?? '-' }}</td>
                <td class="border px-4 py-2 space-x-2">
                    <a href="{{ route('subkategori.edit', $row->ID_SUB) }}"
                       class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">
                        Edit
                    </a>
                    <form action="{{ route('subkategori.destroy', $row->ID_SUB) }}" 
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
        @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $subkategori->links() }}
    </div>
</div>
@endsection
