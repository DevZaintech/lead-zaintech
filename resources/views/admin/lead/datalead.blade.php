@extends('layouts.frontend')
@section('css')
@endsection
@section('content')

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 text-red-800 p-2 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

<div class="lg:w-[98%] mx-auto">

    {{-- Filter --}}
    <div class="flex flex-wrap gap-2 mb-4 items-center">

        <a href="#" id="btnExport"
            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
            Export Excel
        </a>

        {{-- Search (paling panjang) --}}
        <input type="text" id="searchInput"
            placeholder="Cari NAMA, NO TELP..."
            class="flex-1 min-w-[200px] border p-2 rounded">

        {{-- Filter Status --}}
        <select id="filterStatus" class="w-40 border p-2 rounded">
            <option value="">Semua Status</option>
            <option value="lead">Cold</option>
            <option value="opportunity">Warm</option>
            <option value="quotation">Hot</option>
            <option value="lost">Lost</option>
            <option value="converted">Deal</option>
            <option value="norespon">No Respon</option>
        </select>


        {{-- Filter Sales --}}
        <select id="filterSales" class="w-40 border p-2 rounded">
            <option value="">Semua Sales</option>
            <option value="404">Tidak Diteruskan</option>
            <option value="200">Diteruskan</option>
            @foreach($user as $s)
                <option value="{{ $s->ID_USER }}">{{ $s->NAMA }}</option>
            @endforeach
        </select>

        {{-- Filter Source --}}
        <select id="filterSource" class="w-40 border p-2 rounded">
            <option value="">Semua Source</option>
            <option value="Meta Ads">Meta Ads</option>
            <option value="Google Ads">Google Ads</option>
            <option value="Youtube">Youtube</option>
            <option value="Tiktok">Tiktok</option>
            <option value="Instagram">Instagram</option>
            <option value="Facebook">Facebook</option>
            <option value="Marketplace">Marketplace</option>
            <option value="Web">Web</option>
            <option value="Sosmed Pribadi">Sosmed Sales</option>
            <option value="Walk In">Walk In</option>
            <option value="Direct">Direct</option>
            <option value="Exhibition">Exhibition</option>
        </select>

        {{-- Filter Tanggal --}}
        <input type="date" id="startDate" class="w-40 border p-2 rounded">
        <input type="date" id="endDate" class="w-40 border p-2 rounded">
    </div>


    {{-- Table Container --}}
    <div class="bg-white p-6 rounded shadow overflow-x-auto" id="lead_table">
        @include('admin.lead._table')
    </div>
</div>


{{-- Script Live Search + Pagination --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function fetch_data(page = 1) {
        let search    = $('#searchInput').val();
        let sales     = $('#filterSales').val();
        let source    = $('#filterSource').val();
        let startDate = $('#startDate').val();
        let endDate   = $('#endDate').val();
        let status    = $('#filterStatus').val();

        $.ajax({
            url: "{{ route('datalead.admin') }}",
            data: {
                page: page,
                search: search,
                sales: sales,
                source: source,
                startDate: startDate,
                endDate: endDate,
                status: status,
            },
            success: function(data) {
                $('#lead_table').html(data);
            }
        });
    }

    // Trigger fetch saat filter berubah / cari
    $('#searchInput, #filterSales, #filterSource, #startDate, #endDate, #filterStatus')
        .on('change keyup', function() {
            fetch_data();
        });

    // Pagination
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        fetch_data(page);
    });

    // Export dengan semua filter
    $('#btnExport').on('click', function(e) {
        e.preventDefault();
        let search    = $('#searchInput').val();
        let sales     = $('#filterSales').val();
        let source    = $('#filterSource').val();
        let startDate = $('#startDate').val();
        let endDate   = $('#endDate').val();
        let status    = $('#filterStatus').val();

        window.location.href = "{{ route('exportlead.admin') }}" +
            "?search=" + search +
            "&sales=" + sales +
            "&source=" + source +
            "&startDate=" + startDate +
            "&endDate=" + endDate +
            "&status=" + status;
    });
</script>

@endsection