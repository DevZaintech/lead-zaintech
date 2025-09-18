@extends('layouts.frontend')
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

        {{-- Filter Sales --}}
        <select id="filterSales" class="w-40 border p-2 rounded">
            <option value="me" selected>Lead Saya</option>
            @foreach($user as $s)
                <option value="{{ $s->ID_USER }}">{{ $s->NAMA }}</option>
            @endforeach
        </select>

        {{-- Search --}}
        <input type="text" id="searchInput"
            placeholder="Cari NAMA, NO TELP..."
            class="flex-1 min-w-[200px] border p-2 rounded">

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

        {{-- Filter Tanggal --}}
        <input type="date" id="startDate" class="w-40 border p-2 rounded">
        <input type="date" id="endDate" class="w-40 border p-2 rounded">
    </div>

    {{-- Table --}}
    <div class="bg-white p-6 rounded shadow overflow-x-auto" id="lead_table">
        @include('sales.lead._table')
    </div>
</div>

{{-- Ajax --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // ✅ Fungsi ambil data
    function fetch_data(url = "{{ route('datalead.sales') }}") {
        let search    = $('#searchInput').val();
        let sales     = $('#filterSales').val();
        let source    = $('#filterSource').val();
        let status    = $('#filterStatus').val();
        let startDate = $('#startDate').val();
        let endDate   = $('#endDate').val();

        $.ajax({
            url: url, // pakai full URL Laravel (page & filter ikut)
            data: {
                search: search,
                sales: sales,
                source: source,
                status: status,
                startDate: startDate,
                endDate: endDate
            },
            success: function(data) {
                $('#lead_table').html(data);
            }
        });
    }

    // ✅ Trigger filter/search → selalu reset ke page 1
    $('#searchInput, #filterSales, #filterSource, #filterStatus, #startDate, #endDate')
        .on('change keyup', function() {
            fetch_data("{{ route('datalead.sales') }}"); // reset ke page 1 saat filter berubah
        });

    // ✅ Pagination AJAX (ambil href Laravel)
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href'); // URL Laravel sudah ada ?page=2&filter

        let search    = $('#searchInput').val();
        let sales     = $('#filterSales').val();
        let source    = $('#filterSource').val();
        let status    = $('#filterStatus').val();
        let startDate = $('#startDate').val();
        let endDate   = $('#endDate').val();

        $.ajax({
            url: url,
            data: {
                search: search,
                sales: sales,
                source: source,
                status: status,
                startDate: startDate,
                endDate: endDate
            },
            success: function(data) {
                $('#lead_table').html(data);
            }
        });
    });

    // ✅ Load pertama kali
    $(document).ready(function () {
        fetch_data();
    });
</script>

@endsection
