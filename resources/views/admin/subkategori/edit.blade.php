@extends('layouts.frontend')

@section('title', 'Edit SubKategori')

@section('content')

<div class="bg-white p-6 rounded-lg shadow w-full lg:w-[75%] mx-auto">
    <h2 class="text-lg font-semibold mb-4">Edit SubKategori</h2>

    <form action="{{ route('subkategori.update', $subkategori->ID_SUB) }}" 
          method="POST"
          class="flex gap-2 flex-wrap lg:flex-nowrap"
          x-data="{ selectedKategori: '{{ $subkategori->ID_KATEGORI }}' }">
        @csrf
        @method('PUT')

        <input type="text" name="NAMA" value="{{ old('NAMA', $subkategori->NAMA) }}"
            placeholder="Nama SubKategori"
            class="border rounded p-2 flex-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
            required>

        <select name="ID_KATEGORI" required
            class="border rounded p-2 flex-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
            x-model="selectedKategori">
            <option value="">Pilih Kategori</option>
            @foreach($kategori as $k)
                <option value="{{ $k->ID_KATEGORI }}"
                        @if($k->ID_KATEGORI == $subkategori->ID_KATEGORI) selected @endif>
                    {{ $k->NAMA }}
                </option>
            @endforeach
        </select>

        <button type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="!selectedKategori">
            Update
        </button>
    </form>
</div>
@endsection
