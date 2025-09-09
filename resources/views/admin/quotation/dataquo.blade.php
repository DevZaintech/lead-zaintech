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

        <!-- <a href="#" id="btnExport"
            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
            Export Excel
        </a> -->

        {{-- Search (paling panjang) --}}
        <input type="text" id="searchInput"
            placeholder="Cari NAMA, NO TELP..."
            class="flex-1 min-w-[200px] border p-2 rounded">

        {{-- Filter Sales --}}
        <select id="filterSales" class="w-40 border p-2 rounded">
            <option value="">Semua Sales</option>
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
    <div class="bg-white p-6 rounded shadow overflow-x-auto" id="quo_table">
        @include('admin.quotation._table')
    </div>
</div>


{{-- Script Live Search + Pagination --}}
{{-- jQuery sudah dipanggil --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    function fetch_data(page = 1) {
        let search    = $('#searchInput').val();
        let sales     = $('#filterSales').val();
        let source    = $('#filterSource').val();
        let startDate = $('#startDate').val();
        let endDate   = $('#endDate').val();

        $.ajax({
            url: "{{ route('dataquo.admin') }}",
            data: {
                page: page,
                search: search,
                sales: sales,
                source: source,
                startDate: startDate,
                endDate: endDate,
            },
            success: function(data) {
                $('#quo_table').html(data);
            }
        });
    }

    $('#searchInput, #filterSales, #filterSource, #startDate, #endDate')
        .on('change keyup', function() {
            fetch_data();
        });

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        fetch_data(page);
    });

    $('#btnExport').on('click', function(e) {
        e.preventDefault();
        let search    = $('#searchInput').val();
        let sales     = $('#filterSales').val();
        let source    = $('#filterSource').val();
        let startDate = $('#startDate').val();
        let endDate   = $('#endDate').val();

        window.location.href = "{{ route('exportlead.admin') }}" +
            "?search=" + search +
            "&sales=" + sales +
            "&source=" + source +
            "&startDate=" + startDate +
            "&endDate=" + endDate;
    });
</script>

@endsection