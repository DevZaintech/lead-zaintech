@extends('layouts.frontend')
@section('css')
<style>
    .desktop-table{
        display:block;
    }

    .mobile-card-list{
        display:none;
    }
    .filter-kategori {
        width: 160px; /* desktop = w-40 */
    }

    @media (max-width: 768px){

        .desktop-table{
            display:none;
        }

        .mobile-card-list{
            display:block;
        }
        .filter-kategori {
            width: 100%;
        }
    }
</style>
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

        {{-- Search --}}
        <input type="text" id="searchInput"
            placeholder="Cari NAMA, NO TELP..."
            class="flex-1 min-w-[200px] border p-2 rounded">
    </div>

    <div class="flex flex-wrap gap-2 mb-4 items-center">

        {{-- Filter Sales --}}
        <select id="filterSales" class="w-40 border p-2 rounded">
            @if(Auth::user()->ROLE == 'direktur')
            <option value="0">SEMUA SALES</option>
            @endif
            <option value="me" selected>{{Auth::user()->NAMA}}</option>
            @foreach($user as $s)
                <option value="{{ $s->ID_USER }}">{{ $s->NAMA }}</option>
            @endforeach
        </select>

        {{-- Filter Source --}}
        <select id="filterSource" class="w-40 border p-2 rounded">
            <option value="">Semua Source</option>
            <option value="meta ads">Meta Ads</option>
            <option value="google ads">Google Ads</option>
            <option value="youtube">Youtube</option>
            <option value="tiktok">Tiktok</option>
            <option value="instagram">Instagram</option>
            <option value="facebook">Facebook</option>
            <option value="marketplace">Marketplace</option>
            <option value="web">Web</option>
            <option value="sosmed pribadi">Sosmed Sales</option>
            <option value="walk in">Walk In</option>
            <option value="direct">Direct</option>
            <option value="exhibition">Exhibition</option>
            <option value="relasi">Relasi</option>
        </select>

        {{-- Filter Status --}}
        <select id="filterStatus" class="w-40 border p-2 rounded">
            <option value="">Semua Status</option>
            <option value="lead">Cold</option>
            <option value="opportunity">Warm</option>
            <option value="quotation">Hot</option>
            <option value="converted">Deal</option>
            <option value="lost">Lost</option>
        </select>

        {{-- Filter Follow --}}
        <select id="filterFollow" class="w-40 border p-2 rounded">
            <option value="">Follow Ke</option>
            <option value="9">Belum Follow</option>
            <option value="1">Ke 1</option>
            <option value="2">Ke 2</option>
            <option value="3">Ke 3</option>
        </select>
        
        {{-- Filter Tanggal --}}
        <input type="date" id="startDate" class="w-40 border p-2 rounded">
        <input type="date" id="endDate" class="w-40 border p-2 rounded">

        {{-- Filter PEMAIN EXPAND --}}
        <select id="filterKategori" class="filter-kategori border p-2 rounded">
            <option value="">Semua Kategori</option>
            <option value="EXPAND">EXPAND</option>
            <option value="PEMULA">PEMULA</option>
        </select>

        @if(Auth::user()->ROLE == 'direktur')
        <a href="#" id="btnExport"
            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
            Export Excel
        </a>
        @endif

    </div>

    {{-- Table --}}
    <div class="bg-white p-6 rounded shadow overflow-x-auto" id="lead_table">
        @include('sales.lead._table')
    </div>
</div>

{{-- Ajax --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

    // =============================
    // Ambil semua filter
    // =============================
    function getFilters() {
        return {
            search: $('#searchInput').val(),
            sales: $('#filterSales').val(),
            source: $('#filterSource').val(),
            status: $('#filterStatus').val(),
            startDate: $('#startDate').val(),
            endDate: $('#endDate').val(),
            follow: $('#filterFollow').val(),
            kategori: $('#filterKategori').val()
        };
    }

    // =============================
    // Simpan filter ke URL
    // =============================
    function updateUrl(url = null) {

        let currentUrl = new URL(url || window.location.href);
        let params = currentUrl.searchParams;
        let filters = getFilters();

        Object.keys(filters).forEach(function(key){

            if(filters[key]){
                params.set(key, filters[key]);
            }else{
                params.delete(key);
            }

        });

        history.replaceState({}, '', currentUrl.pathname + '?' + params.toString());

    }

    // =============================
    // Ambil filter dari URL
    // =============================
    function loadFilterFromUrl(){

        let params = new URLSearchParams(window.location.search);

        if(params.has('search'))
            $('#searchInput').val(params.get('search'));

        if(params.has('sales'))
            $('#filterSales').val(params.get('sales'));

        if(params.has('source'))
            $('#filterSource').val(params.get('source'));

        if(params.has('status'))
            $('#filterStatus').val(params.get('status'));

        if(params.has('startDate'))
            $('#startDate').val(params.get('startDate'));

        if(params.has('endDate'))
            $('#endDate').val(params.get('endDate'));

        if(params.has('follow'))
            $('#filterFollow').val(params.get('follow'));

        if(params.has('kategori'))
            $('#filterKategori').val(params.get('kategori'));

    }

    // =============================
    // AJAX Load Data
    // =============================
    function fetch_data(url = "{{ route('datalead.sales') }}") {

        updateUrl(url);

        $.ajax({

            url: url,
            data: getFilters(),

            success:function(data){

                $('#lead_table').html(data);

            }

        });

    }

    // =============================
    // Filter berubah
    // =============================
    $('#searchInput, #filterSales, #filterSource, #filterStatus, #startDate, #endDate, #filterFollow, #filterKategori')
    .on('change keyup', function(){

        fetch_data("{{ route('datalead.sales') }}");

    });

    // =============================
    // Pagination AJAX
    // =============================
    $(document).on('click', '.pagination a', function(e){

        e.preventDefault();

        let url = $(this).attr('href');

        fetch_data(url);

    });

    // =============================
    // Pertama kali halaman dibuka
    // =============================
    $(document).ready(function(){

        loadFilterFromUrl();

        fetch_data();

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
        let kategori  = $('#filterKategori').val();

        window.location.href = "{{ route('exportlead.admin') }}" +
            "?search=" + search +
            "&sales=" + sales +
            "&source=" + source +
            "&startDate=" + startDate +
            "&endDate=" + endDate +
            "&status=" + status +
            "&kategori=" + kategori;
    });


</script>

@endsection
