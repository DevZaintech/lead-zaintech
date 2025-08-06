@extends('layouts.frontend')

@section('content')
<div class="lg:w-[90%] mx-auto">
    {{-- Alert --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form Upload Excel --}}
    <div class="bg-white p-6 rounded shadow mb-8">
        <h2 class="text-lg font-semibold mb-4">Import Produk</h2>
        <div class="flex flex-col sm:flex-row gap-4 sm:items-center">
            <form action="{{ route('produk.import') }}" method="POST" enctype="multipart/form-data"
                class="flex flex-col sm:flex-row gap-4 w-full">
                @csrf
                <input type="file" name="file" class="border p-2 rounded w-full sm:w-auto">
                <button class="flex items-center justify-center gap-2 bg-blue-600 text-white px-5 py-2 min-w-[110px] rounded hover:bg-blue-700">
                    {{-- Icon Upload --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M12 12V4m0 0l-4 4m4-4l4 4" />
                    </svg>
                    Import
                </button>
            </form>

            {{-- Tombol New Product --}}
            <a href="javascript:void(0)" id="openAddModal"
            class="flex items-center justify-center gap-2 bg-green-600 text-white px-5 py-2 min-w-[110px] rounded hover:bg-green-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add
            </a>

            {{-- Tombol Export --}}
            <a href="{{ route('produk.export') }}"
            class="flex items-center justify-center gap-2 bg-yellow-600 text-white px-5 py-2 min-w-[110px] rounded hover:bg-yellow-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16c0 1.104.896 2 2 2h12c1.104 0 2-.896 2-2V4M8 12h8m-4-4v8" />
                </svg>
                Eksport
            </a>

        </div>
        @error('file')
            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
        @enderror
    </div>

    {{-- Input Search --}}
    <input type="text" id="searchInput" placeholder="Cari SKU, Nama, Subkategori atau Kategori..."
        class="border p-2 rounded w-full mb-4">

    {{-- Table Container --}}
    <div class="bg-white p-6 rounded shadow overflow-x-auto" id="produk_table">
        @include('admin.produk._table')
    </div>

    {{-- Modal Add Product --}}
    <div id="addProductModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white w-full max-w-lg rounded shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Tambah Produk</h2>
                <button id="closeModal" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <form id="addProductForm" action="{{ route('produk.store') }}" method="POST">
                @csrf
                <div>
                    <label for="NAMA" class="block font-medium mb-1">Nama Produk</label>
                    <input type="text" name="NAMA" id="NAMA" class="border p-2 rounded w-full" required>
                </div>
                <div>
                    <label for="SKU" class="block font-medium mb-1">SKU</label>
                    <input type="text" name="SKU" id="SKU" class="border p-2 rounded w-full">
                </div>
                <div>
                    <label for="selectSubkategori" class="block font-medium mb-1">Sub Kategori</label>
                    <select id="selectSubkategori" name="ID_SUB" class="border p-2 rounded w-full" required></select>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" id="cancelModal" class="px-4 py-2 bg-gray-400 text-white rounded">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                </div>
            </form>

        </div>
    </div>

</div>

{{-- Script Live Search + Pagination --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function fetch_data(page = 1, search = '') {
        $.ajax({
            url: "{{ route('produk.index') }}?page=" + page + "&search=" + search,
            success: function (data) {
                $('#produk_table').html(data);
            }
        });
    }

    $('#searchInput').on('keyup', function() {
        let search = $(this).val();
        fetch_data(1, search);
    });

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        let search = $('#searchInput').val();
        fetch_data(page, search);
    });
</script>

<script>
    const modal = document.getElementById('addProductModal');
    const openModal = document.getElementById('openAddModal');
    const closeModal = document.getElementById('closeModal');
    const cancelModal = document.getElementById('cancelModal');

    openModal.addEventListener('click', () => modal.classList.remove('hidden'));
    closeModal.addEventListener('click', () => modal.classList.add('hidden'));
    cancelModal.addEventListener('click', () => modal.classList.add('hidden'));
</script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script>
$(document).ready(function() {
    $('#selectSubkategori').select2({
        placeholder: '-- Cari Subkategori --',
        width: '100%',
        ajax: {
            url: "{{ route('subkategori.search') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term // keyword yang diketik
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
});
</script>


@endsection
