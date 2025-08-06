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
        {{-- Tombol My Lead --}}
        <button id="myLeadBtn" 
            class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 transition">
            My Lead: Off
        </button>

        {{-- Search (paling panjang) --}}
        <input type="text" id="searchInput"
            placeholder="Cari NAMA, NO TELP..."
            class="flex-1 min-w-[200px] border p-2 rounded">

        {{-- Filter Sales --}}
        <select id="filterSales" class="w-40 border p-2 rounded">
            <option value="">Semua Sales</option>
            @foreach($user as $s)
                <option value="{{ $s->USER_ID }}">{{ $s->NAMA }}</option>
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
{{-- jQuery sudah dipanggil --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let myLead = false;

    function fetch_data(page = 1) {
        let search    = $('#searchInput').val();
        let sales     = $('#filterSales').val();
        let source    = $('#filterSource').val();
        let startDate = $('#startDate').val();
        let endDate   = $('#endDate').val();

        $.ajax({
            url: "{{ route('datalead.gate') }}",
            data: {
                page: page,
                search: search,
                sales: sales,
                source: source,
                startDate: startDate,
                endDate: endDate,
                myLead: myLead
            },
            success: function(data) {
                $('#lead_table').html(data);
            }
        });
    }

    $('#searchInput, #filterSales, #filterSource, #startDate, #endDate')
        .on('change keyup', function() {
            fetch_data();
        });

    $('#myLeadBtn').on('click', function() {
        myLead = !myLead;
        $(this).text('My Lead: ' + (myLead ? 'On' : 'Off'))
               .toggleClass('bg-blue-500 text-white', myLead)
               .toggleClass('bg-gray-300', !myLead);
        fetch_data();
    });

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        fetch_data(page);
    });
</script>





@endsection