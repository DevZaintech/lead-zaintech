@extends('layouts.frontend')

@section('title', 'Edit Kategori')

@section('content')
<div class="bg-white p-6 rounded-lg shadow w-full lg:w-[75%] mx-auto">
    <h2 class="text-lg font-semibold mb-4">Edit Kategori</h2>

    <form action="{{ route('kategori.update', $kategori->ID_KATEGORI) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="NAMA" class="block text-gray-700 mb-1">Nama Kategori</label>
            <input type="text" name="NAMA" id="NAMA"
                   value="{{ old('NAMA', $kategori->NAMA) }}"
                   class="border rounded p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Simpan
            </button>
            <a href="{{ route('kategori.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
