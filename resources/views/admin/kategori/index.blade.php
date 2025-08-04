@extends('layouts.frontend')

@section('title', 'Data Kategori')

@section('content')
<div class="bg-white p-6 rounded-lg shadow w-full lg:w-[75%] mx-auto">
    <h2 class="text-lg font-semibold mb-4">Data Kategori</h2>

    <form action="{{ route('kategori.store') }}" method="POST" class="flex gap-2 mb-4">
        @csrf
        <input type="text" name="NAMA" placeholder="Nama Kategori"
               class="border rounded p-2 flex-1 focus:outline-none focus:ring-2 focus:ring-blue-400">
        <button type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Tambah
        </button>
    </form>

    <table class="min-w-full border text-sm">
        <thead class="bg-gray-100">
        <tr>
            <th class="border px-4 py-2 text-left">ID</th>
            <th class="border px-4 py-2 text-left">Nama</th>
            <th class="border px-4 py-2 text-left">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @foreach($kategori as $row)
            <tr class="hover:bg-gray-50">
                <td class="border px-4 py-2">{{ $row->ID_KATEGORI }}</td>
                <td class="border px-4 py-2">{{ $row->NAMA }}</td>
                <td class="border px-4 py-2 space-x-2">
                    <a href="{{ route('kategori.edit', $row->ID_KATEGORI) }}"
                       class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">
                        Edit
                    </a>

                    <form action="{{ route('kategori.destroy', $row->ID_KATEGORI) }}" 
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
</div>
@endsection
