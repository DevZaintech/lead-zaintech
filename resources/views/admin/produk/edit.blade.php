@extends('layouts.frontend')

@section('content')
<div class="lg:w-[90%] mx-auto">
    <h2 class="text-lg font-semibold mb-4">Edit Produk</h2>

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('produk.update', $produk->ID_PRODUK) }}" method="POST" class="space-y-4 bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium">Nama Produk</label>
            <input type="text" name="NAMA" value="{{ old('NAMA', $produk->NAMA) }}" class="w-full border rounded p-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium">SKU</label>
            <input type="text" name="SKU" value="{{ old('SKU', $produk->SKU) }}" class="w-full border rounded p-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium">Sub Kategori</label>
            <select name="ID_SUB" class="w-full border rounded p-2 select2" required>
                @foreach($subkategori as $sub)
                    <option value="{{ $sub->ID_SUB }}" {{ $produk->ID_SUB == $sub->ID_SUB ? 'selected' : '' }}>
                        {{ $sub->NAMA }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium">Harga</label>
            <input type="text" name="HARGA" 
                   value="{{ old('HARGA', $produk->HARGA) }}" 
                   class="w-full border rounded p-2" 
                   required>
            <p class="text-xs text-gray-500 mt-1">Masukkan angka tanpa Rp/titik. Contoh: <strong>1100000</strong></p>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('produk.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Update</button>
        </div>
    </form>
</div>
@endsection
