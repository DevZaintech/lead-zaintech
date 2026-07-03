@extends('layouts.frontend')
@section('css')
<style>
    @media (max-width:768px){
        .detail-table td{

            padding:10px 12px !important;

            font-size:14px;

        }
    }
    @media (max-width: 768px) {

        /* Container */

        #followupTab{
            padding:16px;
        }

        #followupTab h2{
            font-size:24px;
            margin-bottom:20px;
        }

        /* Hilangkan header */

        #followupTab thead{
            display:none;
        }

        /* Table tetap table di desktop */

        #followupTab table,
        #followupTab tbody,
        #followupTab tr,
        #followupTab td{
            display:block;
            width:100%;
        }

        /* Card */

        #followupTab tr{

            margin-bottom:20px;

            border:1px solid #d1d5db;

            border-radius:10px;

            padding:15px;

            background:white;

        }

        /* td */

        #followupTab td{

            border:none !important;

            padding:0;

            margin-bottom:15px;

        }

        #followupTab td:last-child{

            margin-bottom:0;

        }

        /* Label */

        #followupTab td:nth-child(1)::before{

            content:"Tanggal Follow Up";

            display:block;

            font-weight:600;

            margin-bottom:6px;

        }

        #followupTab td:nth-child(2)::before{

            content:"Respon";

            display:block;

            font-weight:600;

            margin-bottom:6px;

        }

        #followupTab td:nth-child(3)::before{

            content:"Keterangan";

            display:block;

            font-weight:600;

            margin-bottom:6px;

        }

        /* Input */

        #followupTab input[type=date]{

            width:100%;

            padding:10px;

        }

        #followupTab textarea{

            width:100%;

            min-height:90px;

            padding:10px;

            resize:vertical;

        }

        /* Tombol */

        #add-row-fu{

            width:100%;

            margin-top:10px;

            padding:12px;

        }

        #followupTab .flex{

            display:block;

        }

        #followupTab button[type=submit]{

            width:100%;

            margin-top:15px;

            padding:12px;

        }

    }

    @media (max-width:768px){

        /* =========================
        EDIT QUOTATION MOBILE
        ========================= */

        #produk-table-edit-quo{
            width:100%;
            border:none;
            border-collapse:collapse;
        }

        #produk-table-edit-quo thead{
            display:none;
        }

        #produk-table-edit-quo,
        #produk-table-edit-quo tbody,
        #produk-table-edit-quo tr,
        #produk-table-edit-quo td{
            display:block;
            width:100%;
            max-width:100%;
            box-sizing:border-box;
        }

        #produk-table-edit-quo tr{
            border:1px solid #d1d5db;
            border-radius:10px;
            padding:15px;
            margin-bottom:20px;
            background:#fff;
        }

        #produk-table-edit-quo td{
            border:none !important;
            padding:0;
            margin-bottom:15px;
        }

        #produk-table-edit-quo td:last-child{
            margin-bottom:0;
        }

        #produk-table-edit-quo td:nth-child(1)::before{
            content:"Nama Produk";
            display:block;
            font-weight:600;
            margin-bottom:6px;
        }

        #produk-table-edit-quo td:nth-child(2)::before{
            content:"SKU";
            display:block;
            font-weight:600;
            margin-bottom:6px;
        }

        #produk-table-edit-quo td:nth-child(3)::before{
            content:"Qty";
            display:block;
            font-weight:600;
            margin-bottom:6px;
        }

        #produk-table-edit-quo td:nth-child(4)::before{
            content:"Price";
            display:block;
            font-weight:600;
            margin-bottom:6px;
        }

        #produk-table-edit-quo td:nth-child(5)::before{
            content:"Total";
            display:block;
            font-weight:600;
            margin-bottom:6px;
        }

        #produk-table-edit-quo input,
        #produk-table-edit-quo select,
        #produk-table-edit-quo textarea{
            width:100%;
            min-height:42px;
            box-sizing:border-box;
        }

        #add-row-quo{
            width:100%;
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
<div class="space-y-6">
    <div class="w-full lg:w-[98%] mx-auto bg-white p-4 md:p-8 rounded shadow space-y-6">

        <!-- Judul -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

            <h2 class="text-xl md:text-2xl font-semibold">
                Detail Lead
            </h2>

            @if($quo->opportunity->lead->CREATOR_ID == Auth::id())
            <a href="{{ route('edit.lead.sales', $quo->opportunity->lead->LEAD_ID) }}"
                class="inline-flex items-center justify-center px-3 md:px-5 py-2 bg-green-600 text-white text-sm md:text-base font-medium rounded-lg shadow-md hover:bg-green-700 transition-all duration-200">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5 mr-2"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M16.862 4.487l1.651-1.651a2.121 2.121 0 113 3l-1.651 1.651m-3-3l-9.193 9.193a4 4 0 00-1.037 1.74l-.397 1.59a.75.75 0 00.91.91l1.59-.397a4 4 0 001.74-1.037l9.193-9.193m-3-3l3 3"/>
                </svg>

                Edit
            </a>
            @endif

        </div>

        <!-- Grid Detail -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">

            <!-- KIRI -->
            <table class="detail-table w-full border border-gray-300 border-collapse">
                <tbody>

                    <tr>
                        <td class="bg-gray-100 px-3 py-2 w-[38%] md:w-1/3 border border-gray-300"><b>QUOTATION ID</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ $quo->QUO_ID }}</td>
                    </tr>

                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>NAMA CUSTOMER</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ $quo->opportunity->lead->NAMA }}</td>
                    </tr>

                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>KATEGORI CUSTOMER</b></td>
                        <td class="px-3 py-2 border border-gray-300">
                            @if($quo->opportunity->lead->KATEGORI_CUST != NULL)
                                {{ $quo->opportunity->lead->KATEGORI_CUST }}
                            @else
                                NULL
                            @endif

                            <!-- @if($quo->opportunity->lead->KATEGORI === 'INDIVIDU')
                                {{ $quo->opportunity->lead->KATEGORI }}
                            @elseif($quo->opportunity->lead->KATEGORI === 'COMPANY')
                                {{ $quo->opportunity->lead->KATEGORI }} - {{ $quo->opportunity->lead->PERUSAHAAN ?? '-' }}
                            @else
                                -
                            @endif -->
                        </td>
                    </tr>

                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>LEAD SOURCE</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ $quo->opportunity->lead->LEAD_SOURCE }}</td>
                    </tr>

                </tbody>
            </table>

            <!-- KANAN -->
            <table class="detail-table w-full border border-gray-300 border-collapse">
                <tbody>

                    <tr>
                        <td class="bg-gray-100 px-3 py-2 w-[38%] md:w-1/3 border border-gray-300"><b>TANGGAL QUOTATION</b></td>
                        <td class="px-3 py-2 border border-gray-300">
                            {{ \Carbon\Carbon::parse($quo->CREATED_AT)->translatedFormat('d F Y') }}
                        </td>
                    </tr>

                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>TELEPON</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ $quo->opportunity->lead->NO_TELP }}</td>
                    </tr>

                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>KOTA</b></td>
                        <td class="px-3 py-2 border border-gray-300">
                            {{ $quo->opportunity->lead->kota->name }}
                        </td>
                    </tr>

                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>STATUS</b></td>
                        <td class="px-3 py-2 border border-gray-300">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center px-2 py-1 rounded text-sm font-medium {{ $quo->opportunity->lead->stage_class }}">
                                    {{ $quo->opportunity->lead->stage_label }}
                                </span>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>

        </div>

        <!-- Catatan -->
        <div>

            <table class="detail-table w-full border border-gray-300 border-collapse">

                <tbody>

                    <tr>

                        <td class="bg-gray-100 px-3 py-2 w-[38%] md:w-1/6 border border-gray-300 align-top">
                            <b>CATATAN</b>
                        </td>

                        <td class="px-3 py-2 border border-gray-300">

                            <textarea
                                class="w-full border border-gray-300 rounded-md p-2 bg-gray-50 resize-none"
                                rows="3"
                                readonly>{{ $opp->NOTE }}</textarea>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>


        <div class="space-y-6 w-full lg:w-[98%] mx-auto">
            <!-- Tabs -->
            <div class="flex border-b mb-6 overflow-x-auto">

                <button
                    data-tab-button
                    onclick="openTab('followupTab', this)" 
                    class="flex-1 whitespace-nowrap px-2 py-2 md:px-4 text-xs md:text-base -mb-px border-b-2 font-medium text-blue-600 border-blue-600">
                    Follow Up
                </button>

                <button
                    data-tab-button
                    onclick="openTab('quotationTab', this)"
                    class="flex-1 whitespace-nowrap px-2 py-2 md:px-4 text-sm md:text-base -mb-px border-b-2 font-medium text-gray-600 border-transparent">
                    Edit Quotation
                </button>
                
            </div>

            <!-- Tab Contents -->

                <!-- Edit Quotation Tab -->
                <div id="quotationTab" class="tab-content hidden bg-white p-4 md:p-8 rounded shadow">
                    <h2 class="text-2xl font-semibold mb-6">Edit Quotation</h2>
                    <form action="{{ route('quotation.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="OPPORTUNITY_ID" value="{{ $opp->OPPORTUNITY_ID }}" readonly class="w-full border border-gray-400 rounded px-3 py-2 bg-gray-100 text-gray-600" required>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium mb-1">NILAI PROSPECT <span class="text-red-500">*</span></label>
                                <input type="text" id="NILAI_PROSPECT_QUO" name="NILAI_PROSPECT" value="{{ $opp->NILAI_PROSPECT }}" class="w-full border border-gray-400 rounded px-3 py-2 text-gray-600" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">PROSENTASE PROSPECT <span class="text-red-500">*</span></label>
                                <input type="text" id="PROSENTASE_QUO" name="PROSENTASE_PROSPECT" value="{{ $opp->PROSENTASE_PROSPECT }}%" class="w-full border border-gray-400 rounded px-3 py-2 text-gray-600" required>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-2">ITEM TABLE</label>
                            <table id="produk-table-edit-quo" class="w-full border border-gray-400 mt-4 text-sm">
                                <thead>
                                    <tr>
                                        <th class="border border-gray-400 p-2 w-[30%]">Nama Produk</th>
                                        <th class="border border-gray-400 p-2">SKU</th>
                                        <th class="border border-gray-400 p-2">Qty</th>
                                        <th class="border border-gray-400 p-2">Price</th>
                                        <th class="border border-gray-400 p-2">Total</th>
                                        <th class="border border-gray-400 p-2 w-[5%]"></th>
                                    </tr>
                                </thead>
                                <tbody id="produk-body-quo">
                                    @php $rowIdxQuo = 0; @endphp
                                    @foreach($item as $i)
                                        <tr>
                                            <input type="hidden" name="produk[{{ $rowIdxQuo }}][ID_ITEM]" value="{{ $i->ID_ITEM }}">
                                            <td class="border border-gray-400 p-2">
                                                <select name="produk[{{ $rowIdxQuo }}][ID_PRODUK]" class="produk-select w-full">
                                                    <option value="{{ $i->produk->ID_PRODUK }}" selected>{{ $i->produk->NAMA }}</option>
                                                </select>
                                            </td>
                                            <td class="border border-gray-400 p-2">
                                                <input type="text" name="produk[{{ $rowIdxQuo }}][SKU]" value="{{ $i->produk->SKU }}" class="sku-input w-full border border-gray-400 px-2 py-1" readonly>
                                            </td>
                                            <td class="border border-gray-400 p-2">
                                                <input type="number" name="produk[{{ $rowIdxQuo }}][QTY]" value="{{ $i->QTY }}" min="1" class="qty-input w-full border border-gray-400 px-2 py-1">
                                            </td>
                                            <td class="border border-gray-400 p-2">
                                                <input type="text" name="produk[{{ $rowIdxQuo }}][PRICE]" value="{{ number_format($i->PRICE,0,',','.') }}" data-raw="{{ $i->PRICE }}" class="price-input w-full border border-gray-400 px-2 py-1">
                                            </td>
                                            <td class="border border-gray-400 p-2">
                                                <input type="text" class="total-input w-full border border-gray-400 px-2 py-1" value="{{ number_format($i->TOTAL,0,',','.') }}" readonly>
                                                <input type="hidden" name="produk[{{ $rowIdxQuo }}][TOTAL]" value="{{ $i->TOTAL }}" class="total-hidden">
                                            </td>
                                            <td class="border border-gray-400 p-2 text-center">
                                                <button type="button" class="remove-row text-red-500">✖</button>
                                            </td>
                                        </tr>
                                        @php $rowIdxQuo++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                            <button type="button" id="add-row-quo" class="add-product-btn mt-2 px-3 py-2 bg-blue-500 text-white rounded">+ Tambah Produk</button>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-1">SYARAT & KETENTUAN</label>
                            <textarea name="SNK" rows="4" class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">{{ $quo->SNK }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium mb-1">
                                    STATUS <span class="text-red-500">*</span>
                                </label>
                                <select id="STATUS" name="STATUS" 
                                    class="w-full border border-gray-400 rounded px-3 py-2 text-gray-600" required>
                                    
                                    <option value="quotation" {{ $quo->opportunity->lead->STATUS === 'quotation' ? 'selected' : '' }}>Hot</option>
                                    <option value="converted" {{ $quo->opportunity->lead->STATUS === 'converted' ? 'selected' : '' }}>Deal</option>
                                    <option value="lost" {{ $quo->opportunity->lead->STATUS === 'lost' ? 'selected' : '' }}>Lost</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">
                                    VALID DATE <span class="text-red-500">*</span>
                                </label>
                                <div class="flex w-full rounded overflow-hidden border border-gray-400 valid-date-box">
                                    {{-- Bagian status --}}
                                    @if($quo->VALID_DATE && \Carbon\Carbon::parse($quo->VALID_DATE)->isSameDay(\Carbon\Carbon::today()))
                                        <div class="w-40 flex items-center justify-center bg-green-500 text-white text-xs font-semibold px-3 valid-date-status">
                                            EXPIRED TODAY
                                        </div>
                                    @elseif($quo->STATUS === 'OPEN')
                                        <div class="w-40 flex items-center justify-center bg-green-500 text-white text-xs font-semibold px-3 valid-date-status">
                                            OPEN
                                        </div>
                                    @elseif($quo->STATUS === 'EXPIRED')
                                        <div class="w-40 flex items-center justify-center bg-green-500 text-white text-xs font-semibold px-3 valid-date-status">
                                            EXPIRED
                                        </div>
                                    @else
                                        <div class="w-40 flex items-center justify-center bg-green-500 text-white text-xs font-semibold px-3 valid-date-status">
                                            {{ $quo->STATUS }}
                                        </div>
                                    @endif

                                    {{-- Bagian input tanggal --}}
                                    <input type="text"
                                        name="VALID_DATE"
                                        value="{{ $quo->VALID_DATE ? \Carbon\Carbon::parse($quo->VALID_DATE)->translatedFormat('d F Y') : '-' }}"
                                        class="flex-1 px-3 py-2 text-gray-600 text-sm focus:outline-none" readonly>
                                </div>
                            </div>

                        </div>

                        <div id="reasonField" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 hidden">
                            <div>
                                <label class="block text-sm font-medium mb-1">REASON <span class="text-red-500">*</span></label>
                                <select id="REASON" name="REASON" class="w-full border border-gray-400 rounded px-3 py-2 text-gray-600">
                                    <option value="">-- Pilih Reason --</option>
                                    @foreach($reason as $r)
                                    <option value="{{$r->REASON}}">{{$r->REASON}}</option>
                                    @endforeach
                                    
                                </select>
                            </div>
                        </div>

                        <div class="action-buttons flex justify-end space-x-3">
                            <button
                                type="submit"
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tab Contents -->
                <div id="followupTab" class="tab-content bg-white p-8 rounded shadow">
                    <!-- Paste form Follow Up kamu di sini -->
                    <form action="{{ route('follow.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="OPPORTUNITY_ID" value="{{ $quo->OPPORTUNITY_ID }}" readonly class="w-full border border-gray-400 rounded px-3 py-2 bg-gray-100 text-gray-600">

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <label for="KATEGORI_CUST" class="block text-gray-700 font-medium mb-1">
                                    KATEGORI CUSTOMER <span class="text-red-500">*</span>
                                </label>

                                <select
                                    name="KATEGORI_CUST"
                                    id="KATEGORI_CUST"
                                    class="w-full border border-gray-300 rounded px-3 py-2
                                        focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    required>
                                    <option value="">-- Pilih Satu --</option>
                                    <option value="EXPAND" {{ $lead->KATEGORI_CUST == 'EXPAND' ? 'selected' : '' }}>EXPAND</option>
                                    <option value="PEMULA" {{ $lead->KATEGORI_CUST == 'PEMULA' ? 'selected' : '' }}>PEMULA</option>
                                </select>
                            </div>
                        </div>

                        <label class="block text-gray-700 font-medium mb-2">
                            TABEL FOLLOW UP
                        </label>

                        <table class="w-full table-fixed border-collapse border border-gray-500 text-sm mb-4">
                            <thead class="bg-gray-100 text-gray-700 uppercase">
                                <tr>
                                    <th class="border border-gray-500 px-3 py-2">TANGGAL FU</th>
                                    <th class="border border-gray-500 px-3 py-2">RESPON</th>
                                    <th class="border border-gray-500 px-3 py-2">KETERANGAN</th>
                                </tr>
                            </thead>
                            <tbody id="followup-body">
                                @forelse($followups as $fu)
                                    <tr class="odd:bg-white even:bg-gray-50" data-id="{{ $fu->ID_FOLLOW }}">
                                        <td class="border border-gray-400 px-3 py-2">{{ \Carbon\Carbon::parse($fu->TGL_FOLLOW)->translatedFormat('d F Y') }}</td>
                                        <td class="border border-gray-400 px-3 py-2 editable" data-field="RESPON" contenteditable="true">{{ $fu->RESPON }}</td>
                                        <td class="border border-gray-400 px-3 py-2 editable" data-field="KETERANGAN" contenteditable="true">{{ $fu->KETERANGAN }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="border border-gray-400 px-3 py-2 text-center text-gray-500">BELUM ADA FOLLOW UP</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <button type="button" id="add-row-fu" class="mt-2 px-3 py-1 bg-blue-500 text-white rounded">
                            + Tambah Follow Up
                        </button>
                        <div class="flex justify-end space-x-3 mt-4">
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">Simpan</button>
                        </div>
                    </form>
                </div>

        </div>

</div>

@endsection

@section('scripts')
<!-- jQuery & Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
function openTab(tabId, btn) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.flex > button').forEach(b => {
        b.classList.remove('text-blue-600', 'border-blue-600');
        b.classList.add('text-gray-600', 'border-transparent');
    });
    document.getElementById(tabId).classList.remove('hidden');
    btn.classList.add('text-blue-600', 'border-blue-600');
    btn.classList.remove('text-gray-600', 'border-transparent');
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('add-row-fu').addEventListener('click', function () {
        let tbody = document.getElementById('followup-body');
        let rowCount = tbody.querySelectorAll('tr').length;
        let row = document.createElement('tr');

        row.innerHTML = `
            <td class="border border-gray-400 p-2">
                <input type="date" name="followup[${rowCount}][TANGGAL_FOLLOW]" class="w-full border border-gray-500 px-2 py-1 rounded" required>
            </td>
            <td class="border border-gray-400 p-2">
                <textarea name="followup[${rowCount}][RESPON]" rows="2" class="w-full border border-gray-400 px-2 py-1" required></textarea>
            </td>
            <td class="border border-gray-400 p-2">
                <textarea name="followup[${rowCount}][PROGRESS]" rows="2" class="w-full border border-gray-400 px-2 py-1" required></textarea>
            </td>
        `;
        tbody.appendChild(row);
    });
});
</script>
<script>
document.querySelectorAll('.editable').forEach(td => {
    td.addEventListener('blur', function(){
        const value = this.innerText.trim();
        const field = this.dataset.field;
        const ID_FOLLOW = this.closest('tr').dataset.id;

        fetch(`/follow/update/${ID_FOLLOW}`, {
            method: 'POST',
            headers: {
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':'{{ csrf_token() }}'
            },
            body: JSON.stringify({field, value})
        }).then(res => res.json())
        .then(data => {
            if(data.status !== 'success') alert('Update gagal!');
        });
    });
});
</script>
<script>
function formatRupiah(angka) { 
    return angka.replace(/\B(?=(\d{3})+(?!\d))/g, "."); 
}

function initProdukTable($tbody, rowIdxStart, addBtnSelector){
    let rowIdx = rowIdxStart;

    function initSelect2ForRow($select){
        $select.select2({
            placeholder: 'Pilih produk',
            minimumInputLength: 0,
            width: '100%',
            templateSelection: function (data) {

                // Desktop tampil normal
                if (window.innerWidth > 768) {
                    return data.text;
                }

                // Mobile dipotong
                if (data.text && data.text.length > 26) {
                    return data.text.substring(0, 26) + "...";
                }

                return data.text;
            },
            ajax: {
                url: '{{ route('get.produk.sales') }}',
                dataType: 'json',
                delay: 250,
                data: params => ({q: params.term || ''}),
                processResults: data => ({
                    results: data.map(i => ({
                        id: i.ID_PRODUK,
                        text: i.NAMA,
                        SKU: i.SKU,
                        HARGA: i.HARGA
                    }))
                }),
                cache: true
            }
        }).on('select2:select', function(e){
            let selected = e.params.data;
            let $row = $(this).closest('tr');
            $row.find('.sku-input').val(selected.SKU || '');
            if(selected.HARGA){
                let raw = String(selected.HARGA);
                $row.find('.price-input').data('raw', raw);
                $row.find('.price-input').val(formatRupiah(raw));
                updateRowTotal($row);
            }
        });
    }

    // Init select2 untuk row existing
    $tbody.find('.produk-select').each(function(){ 
        initSelect2ForRow($(this)); 
    });

    // Format harga sebelum submit
    $tbody.closest('form').on('submit', function(){
        $tbody.find('.price-input').each(function(){ 
            $(this).val($(this).data('raw')); 
        });
    });

    // Hapus row
    $tbody.on('click', '.remove-row', function(){ 
        $(this).closest('tr').remove(); 
    });

    // Update harga manual
    $tbody.on('input', '.price-input', function(){
        let raw = $(this).val().replace(/\D/g,'')||'0';
        $(this).data('raw', raw);
        $(this).val(formatRupiah(raw));
        updateRowTotal($(this).closest('tr'));
    });

    // Update qty
    $tbody.on('input', '.qty-input', function(){ 
        updateRowTotal($(this).closest('tr')); 
    });

    function updateRowTotal($row){
        let qty = parseInt($row.find('.qty-input').val())||0;
        let price = parseInt($row.find('.price-input').data('raw'))||0;
        let total = qty*price;
        $row.find('.total-input').val(formatRupiah(String(total)));
        $row.find('.total-hidden').val(total);
    }

    // Tambah row baru
    $(addBtnSelector).on('click', function(){
        rowIdx++;
        let newRow = `
        <tr>
            <td class="border border-gray-400 p-2">
                <select name="produk[${rowIdx}][ID_PRODUK]" class="produk-select w-full"></select>
            </td>
            <td class="border border-gray-400 p-2">
                <input type="text" name="produk[${rowIdx}][SKU]" class="sku-input w-full border border-gray-400 px-2 py-1" readonly>
            </td>
            <td class="border border-gray-400 p-2">
                <input type="number" name="produk[${rowIdx}][QTY]" value="1" min="1" class="qty-input w-full border border-gray-400 px-2 py-1">
            </td>
            <td class="border border-gray-400 p-2">
                <input type="text" name="produk[${rowIdx}][PRICE]" class="price-input w-full border border-gray-400 px-2 py-1">
            </td>
            <td class="border border-gray-400 p-2">
                <input type="text" class="total-input w-full border border-gray-400 px-2 py-1" readonly>
                <input type="hidden" name="produk[${rowIdx}][TOTAL]" class="total-hidden">
            </td>
            <td class="border border-gray-400 p-2 text-center">
                <button type="button" class="remove-row text-red-500">✖</button>
            </td>
        </tr>`;
        $tbody.append(newRow);
        initSelect2ForRow($tbody.find('tr:last-child .produk-select'));
    });
}

$(document).ready(function(){
    // Init produk untuk Quotation
    initProdukTable($('#produk-body-quo'), {{ $rowIdxQuo-1 }}, '#add-row-quo');

    // Init produk untuk Opportunity (fallback kalau $rowIdxOpp belum ada)
    initProdukTable($('#produk-body-opp'), {{ ($rowIdxOpp ?? 1) - 1 }}, '#add-row-opp');

    // Format input Rupiah & Persen
    function initInputFormatting(inputId, persenId=null){
        let input = document.getElementById(inputId);
        if(!input) return;
        input.value = formatRupiah(input.value);
        input.addEventListener('input', function(){
            const angka = input.value.replace(/\D/g,'');
            input.value = formatRupiah(angka);
        });
        if(persenId){
            let persenInput = document.getElementById(persenId);
            persenInput.addEventListener('input', function(){
                let val = this.value.replace(/\D/g,'');
                this.value = val? val+'%' : '';
            });
            persenInput.addEventListener('blur', function(){
                if(this.value && !this.value.includes('%')) this.value += '%';
            });
        }
    }

    initInputFormatting('NILAI_PROSPECT_OPP','PROSENTASE_OPP');
    initInputFormatting('NILAI_PROSPECT_QUO','PROSENTASE_QUO');

    // Tampilkan Reason kalau status = lost
    $('#STATUS').on('change', function() {
        if ($(this).val() === 'lost') {
            $('#reasonField').removeClass('hidden');
            $('#REASON').attr('required', true);
        } else {
            $('#reasonField').addClass('hidden');
            $('#REASON').removeAttr('required').val('');
        }
    });

    // Trigger awal (kalau status sudah lost waktu load page)
    if ($('#STATUS').val() === 'lost') {
        $('#reasonField').removeClass('hidden');
        $('#REASON').attr('required', true);
    }
});
</script>

@endsection
