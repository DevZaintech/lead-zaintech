@extends('layouts.frontend')
@section('css')
<style>
    /* semua option biar keliatan kayak badge */
    #filterStatus option {
        padding: 2px 6px;
        border-radius: 0.375rem; /* rounded-md */
        display: inline-block;
        width: fit-content;
        font-weight: 500;
    }

    option.status-lead        { background:#DBEAFE; color:#1D4ED8; } /* biru */
    option.status-opportunity { background:#FFEDD5; color:#C2410C; } /* oranye */
    option.status-quotation   { background:#FEE2E2; color:#B91C1C; } /* merah */
    option.status-converted   { background:#DCFCE7; color:#166534; } /* hijau */
    option.status-lost        { background:#E5E7EB; color:#374151; } /* abu */
    option.status-norespon    { background:#FEF9C3; color:#854D0E; } /* kuning */

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

        {{-- Search (paling panjang) --}}
        <input type="text" id="searchInput"
            placeholder="Cari NAMA, NO TELP..."
            class="w-full md:flex-1 md:min-w-[200px] border p-2 rounded">
 
        {{-- Tombol My Lead --}}
        <button id="myLeadBtn" 
            class="w-40 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
            My Lead: On
        </button>

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
        </select>

        {{-- Filter Status --}}
        <select id="filterStatus" class="w-40 border p-2 rounded">
            <option value="">Semua Status</option>
            <option value="teroper" class="status">Teroper</option>
            <option value="norespon" class="status">Tidak Teroper</option>
            <option value="lead" class="status">Cold</option>
            <option value="opportunity" class="status">Warm</option>
            <option value="quotation" class="status">Hot</option>
            <option value="converted" class="status">Deal</option>
            <option value="lost" class="status">Lost</option>
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

        @if(Auth::user()->ROLE == 'spv')
        <a href="#" id="btnExport"
            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
            Export Excel
        </a>
        @endif

    </div>


    {{-- Table Container --}}
    <div class="bg-white p-6 rounded shadow overflow-x-auto" id="lead_table">
        @include('gate.lead._table')
    </div>
</div>


{{-- Script Live Search + Pagination --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

    // =============================
    // Default
    // =============================
    let myLead = true;

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
            kategori: $('#filterKategori').val(),
            myLead: myLead
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

            if(filters[key] !== '' && filters[key] !== null){
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

        if(params.has('kategori'))
            $('#filterKategori').val(params.get('kategori'));

        if(params.has('myLead'))
            myLead = params.get('myLead') === 'true';

    }

    // =============================
    // Update Button
    // =============================
    function updateMyLeadBtn() {

        $('#myLeadBtn')
            .text('My Lead: ' + (myLead ? 'On' : 'Off'))
            .toggleClass('bg-blue-500 text-white', myLead)
            .toggleClass('bg-gray-300', !myLead);

    }

    // =============================
    // AJAX
    // =============================
    function fetch_data(url = "{{ route('datalead.gate') }}") {

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
    $('#searchInput, #filterSales, #filterSource, #filterStatus, #startDate, #endDate, #filterKategori')
    .on('change keyup', function(){

        fetch_data();

    });

    // =============================
    // Toggle My Lead
    // =============================
    $('#myLeadBtn').on('click', function(){

        myLead = !myLead;

        updateMyLeadBtn();

        fetch_data();

    });

    // =============================
    // Pagination
    // =============================
    $(document).on('click', '.pagination a', function(e){

        e.preventDefault();

        let url = $(this).attr('href');

        fetch_data(url);

    });

    // =============================
    // Load Pertama
    // =============================
    $(document).ready(function(){

        loadFilterFromUrl();

        updateMyLeadBtn();

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
