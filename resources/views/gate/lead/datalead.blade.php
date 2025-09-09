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
        {{-- Tombol My Lead --}}
        <button id="myLeadBtn" 
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
            My Lead: On
        </button>

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
        </select>

        {{-- Filter Status --}}
        <select id="filterStatus" class="w-40 border p-2 rounded">
            <option value="">Semua Status</option>
            <option value="lead" class="status-lead">Cold</option>
            <option value="opportunity" class="status-opportunity">Warm</option>
            <option value="quotation" class="status-quotation">Hot</option>
            <option value="converted" class="status-converted">Deal</option>
            <option value="lost" class="status-lost">Lost</option>
            <option value="norespon" class="status-norespon">No Respon</option>
        </select>

        {{-- Filter Tanggal --}}
        <input type="date" id="startDate" class="w-40 border p-2 rounded">
        <input type="date" id="endDate" class="w-40 border p-2 rounded">
    </div>


    {{-- Table Container --}}
    <div class="bg-white p-6 rounded shadow overflow-x-auto" id="lead_table">
        @include('gate.lead._table')
    </div>
</div>


{{-- Script Live Search + Pagination --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // ✅ Default My Lead ON
    let myLead = true;

    function fetch_data(page = 1) {
        let search    = $('#searchInput').val();
        let sales     = $('#filterSales').val();
        let source    = $('#filterSource').val();
        let status    = $('#filterStatus').val(); // ✅ tambahan
        let startDate = $('#startDate').val();
        let endDate   = $('#endDate').val();

        $.ajax({
            url: "{{ route('datalead.gate') }}",
            data: {
                page: page,
                search: search,
                sales: sales,
                source: source,
                status: status, // ✅ tambahan
                startDate: startDate,
                endDate: endDate,
                myLead: myLead
            },
            success: function(data) {
                $('#lead_table').html(data);
            }
        });
    }

    $('#searchInput, #filterSales, #filterSource, #filterStatus, #startDate, #endDate')
        .on('change keyup', function() {
            fetch_data();
        });

    $('#myLeadBtn').on('click', function() {
        myLead = !myLead;
        updateMyLeadBtn();
        fetch_data();
    });

    function updateMyLeadBtn() {
        $('#myLeadBtn')
            .text('My Lead: ' + (myLead ? 'On' : 'Off'))
            .toggleClass('bg-blue-500 text-white', myLead)
            .toggleClass('bg-gray-300', !myLead);
    }

    // ✅ Set button & fetch data default pas page load
    $(document).ready(function () {
        updateMyLeadBtn();
        fetch_data();
    });

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        fetch_data(page);
    });
</script>

@endsection
