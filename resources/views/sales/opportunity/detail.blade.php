@extends('layouts.frontend')
@section('css')
<style>
    .desktop-view{
        display:block;
    }

    .mobile-view{
        display:none;
    }

    @media (max-width:768px){

    .desktop-view{
        display:none;
    }

    .mobile-view{
        display:block;
        padding:5px;
    }

    /* CARD */

    .mobile-card{
        background:#fff;
        border-radius:18px;
        padding:20px;
        box-shadow:0 4px 18px rgba(0,0,0,.08);
        margin:0;
    }


    /* HEADER */

    .mobile-header{

        display:flex;

        justify-content:space-between;

        align-items:center;

        margin-bottom:22px;

    }

    .mobile-header h2{

        font-size:22px;

        font-weight:700;

        color:#111827;

    }

    .mobile-header a{

        padding:10px 18px;

        border-radius:10px;

        font-size:14px;

    }


    /* SECTION */

    .mobile-title{

        font-size:18px;

        font-weight:700;

        color:#2563eb;

        margin-bottom:18px;

    }


    /* ROW */

    .mobile-row{

        display:flex;

        justify-content:space-between;

        align-items:flex-start;

        gap:18px;

        padding:14px 0;

    }

    .mobile-row:not(:last-child){

        border-bottom:1px solid #edf2f7;

    }


    /* LABEL */

    .mobile-label{

        width:38%;

        font-size:14px;

        color:#6b7280;

        font-weight:600;

    }


    /* VALUE */

    .mobile-value{

        width:62%;

        text-align:right;

        font-size:15px;

        color:#111827;

        font-weight:600;

        word-break:break-word;

    }


    /* DIVIDER */

    .mobile-divider{

        margin:18px 0;

        border-top:2px dashed #e5e7eb;

    }


    /* STATUS */

    .mobile-value .inline-flex{

        padding:6px 12px;

        font-size:12px;

        border-radius:999px;

    }


    /* BUTTON */

    .mobile-header .bg-green-600{

        min-width:74px;

        text-align:center;

    }


    /* CATATAN */

    .mobile-note{

        margin-top:18px;

        background:#f9fafb;

        border-radius:12px;

        padding:14px;

        font-size:14px;

        line-height:1.7;

    }


    /* ITEM */

    .mobile-item{

        background:#fff;

        border-radius:14px;

        padding:16px;

        box-shadow:0 2px 12px rgba(0,0,0,.06);

    }

    .mobile-item-title{

        font-size:16px;

        font-weight:700;

        margin-bottom:12px;

        color:#111827;

    }

    .mobile-item-row{

        display:flex;

        justify-content:space-between;

        padding:8px 0;

        border-bottom:1px solid #f3f4f6;

    }

    .mobile-item-row:last-child{

        border:none;

    }

    .mobile-item-label{

        color:#6b7280;

        font-size:13px;

    }

    .mobile-item-value{

        font-weight:600;

        text-align:right;

    }

    }

    /* ============================
    SECTION TITLE
    ============================ */

    .mobile-section-title{

        font-size:17px;

        font-weight:700;

        color:#111827;

        margin-bottom:14px;

    }


    /* ============================
    NOTE
    ============================ */

    .mobile-note-box{

        background:#f8fafc;

        border:1px solid #e5e7eb;

        border-radius:12px;

        padding:12px;

    }

    .mobile-textarea{

        width:100%;

        border:none;

        background:transparent;

        resize:none;

        outline:none;

        font-size:14px;

        line-height:1.8;

        color:#374151;

        padding:0;

    }
    /* ============================
    MOBILE PRODUCT
    ============================ */

    .mobile-product{

    border:1px solid #e5e7eb;

    border-radius:14px;

    padding:16px;

    margin-top:16px;

    background:#fafafa;

    }

    .mobile-product:first-child{

    margin-top:0;

    }

    .mobile-product-name{

    font-size:16px;

    font-weight:700;

    color:#111827;

    margin-bottom:14px;

    line-height:1.5;

    }

    .mobile-product-row{

    display:flex;

    justify-content:space-between;

    align-items:center;

    padding:8px 0;

    border-bottom:1px dashed #e5e7eb;

    }

    .mobile-product-row:last-child{

    border-bottom:none;

    }

    .mobile-product-row span{

    color:#6b7280;

    font-size:13px;

    }

    .mobile-product-row strong{

    color:#111827;

    font-size:14px;

    font-weight:600;

    text-align:right;

    }

    .mobile-product-total{

    margin-top:6px;

    padding-top:12px;

    border-top:2px solid #dbeafe;

    border-bottom:none;

    }

    .mobile-product-total strong{

    color:#2563eb;

    font-size:15px;

    }
    .mobile-wrapper{
        padding:5px;
        display:flex;
        flex-direction:column;
        gap:28px;
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
    <div class="desktop-view">
        <div class="w-full max-w-[90%] mx-auto bg-white p-8 rounded shadow">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-semibold mb-6">Detail Lead</h2>

                @if($opp->lead->ID_USER == Auth::id() && $opp->lead->STATUS != 'converted')
                <a href="{{ route('edit.lead.sales', $opp->lead->LEAD_ID) }}"
                class="inline-flex items-center px-5 py-2.5 bg-green-600 text-white text-base font-medium rounded-lg shadow-md hover:bg-green-700 transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                        class="w-5 h-5 mr-2" fill="none" 
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                            d="M16.862 4.487l1.651-1.651a2.121 2.121 0 113 3l-1.651 1.651m-3-3l-9.193 9.193a4 4 0 00-1.037 1.74l-.397 1.59a.75.75 0 00.91.91l1.59-.397a4 4 0 001.74-1.037l9.193-9.193m-3-3l3 3"/>
                    </svg>
                    Edit
                </a>
                @endif
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Kiri -->
                <table class="w-full border border-gray-300 border-collapse">
                    <tbody>
                        <tr>
                            <td class="bg-gray-100 px-3 py-2 w-1/3 border border-gray-300"><b>LEAD ID</b></td>
                            <td class="px-3 py-2 border border-gray-300">{{ $opp->lead->LEAD_ID }}</td>
                        </tr>
                        <tr>
                            <td class="bg-gray-100 px-3 py-2 w-1/3 border border-gray-300"><b>OPPORTUNITY ID</b></td>
                            <td class="px-3 py-2 border border-gray-300">{{ $opp->OPPORTUNITY_ID }}</td>
                        </tr>
                        <tr>
                            <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>NAMA CUSTOMER</b></td>
                            <td class="px-3 py-2 border border-gray-300">{{ $opp->lead->NAMA }}</td>
                        </tr>
                        <tr>
                            <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>KATEGORI CUSTOMER</b></td>
                            <td class="px-3 py-2 border border-gray-300">

                                @if($opp->lead->KATEGORI_CUST != NULL)
                                    {{ $opp->lead->KATEGORI_CUST }}
                                @else
                                    NULL
                                @endif

                                <!-- @if($opp->lead->KATEGORI === 'INDIVIDU')
                                    {{ $opp->lead->KATEGORI }}
                                @elseif($opp->lead->KATEGORI === 'COMPANY')
                                    {{ $opp->lead->KATEGORI }} - {{ $opp->lead->PERUSAHAAN ?? '-' }}
                                @else
                                    -
                                @endif -->
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>SALES HANDLE</b></td>
                            <td class="px-3 py-2 border border-gray-300">
                                {{ optional($opp->lead->user)->NAMA ?? '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>LEAD SOURCE</b></td>
                            <td class="px-3 py-2 border border-gray-300">{{ $opp->lead->LEAD_SOURCE }}</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Kanan -->
                <table class="w-full border border-gray-300 border-collapse">
                    <tbody>
                        <tr>
                            <td class="bg-gray-100 px-3 py-2 w-1/3 border border-gray-300"><b>TANGGAL LEAD</b></td>
                            <td class="px-3 py-2 border border-gray-300">{{ \Carbon\Carbon::parse($opp->lead->CREATED_AT)->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td class="bg-gray-100 px-3 py-2 w-1/3 border border-gray-300"><b>
                                @if($opp->lead->STATUS == 'converted')
                                    TANGGAL DEAL
                                @else
                                    TANGGAL LOST
                                @endif
                            </b></td>
                            <td class="px-3 py-2 border border-gray-300">{{ \Carbon\Carbon::parse($opp->lead->UPDATED_AT)->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>TELEPON</b></td>
                            <td class="px-3 py-2 border border-gray-300">{{ $opp->lead->NO_TELP }}</td>
                        </tr>
                        <tr>
                            <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>KOTA</b></td>
                            <td class="px-3 py-2 border border-gray-300">{{ $opp->lead->kota->name ?? '-' }}</td>
                        </tr>
                        <!-- <tr>
                            <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>PROSENTASE</b></td>
                            <td class="px-3 py-2 border border-gray-300">{{ $opp->PROSENTASE_PROSPECT }}%</td>
                        </tr> -->
                        <tr>
                            <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>NILAI</b></td>
                            <td class="px-3 py-2 border border-gray-300">Rp {{ number_format($opp->NILAI_PROSPECT, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>STATUS</b></td>
                            <td class="px-3 py-2 border border-gray-300">
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2 py-1 rounded text-sm font-medium {{ $opp->lead->stage_class }}">
                                    {{ $opp->lead->stage_label }}
                                </span>
                            </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Catatan full width -->
            <div class="mt-6">
                <table class="w-full border border-gray-300 border-collapse">
                    <tbody>
                        @if($opp->lead->STATUS == 'lost')
                        <tr>
                            <td class="bg-gray-100 px-3 py-2 w-1/6 border border-gray-300 align-top"><b>REASON LOST</b></td>
                            <td class="px-3 py-2 border border-gray-300">
                                <textarea class="w-full border-gray-300 rounded-md" rows="2" readonly>{{ $opp->lead->REASON }}. {{ $opp->NOTE }}</textarea>
                            </td>
                        </tr>
                        @else
                        <tr>
                            <td class="bg-gray-100 px-3 py-2 w-1/6 border border-gray-300 align-top"><b>CATATAN</b></td>
                            <td class="px-3 py-2 border border-gray-300">
                                <textarea class="w-full border-gray-300 rounded-md" rows="2" readonly>{{ $opp->NOTE }}</textarea>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

        </div>
        
        <div class="w-full max-w-[90%] mx-auto bg-white p-8 rounded shadow">
                {{-- TABEL PRODUK --}}
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold mb-6">ITEM TABLE</h2>
                    <table class="w-full border border-gray-300 border-collapse">
                        <thead>
                            <tr>
                                <th class="bg-gray-100 px-3 py-2 w-[40%] border border-gray-300">NAMA PRODUK</th>
                                <th class="bg-gray-100 px-3 py-2 w-[15%] border border-gray-300">SKU</th>
                                <th class="bg-gray-100 px-3 py-2 w-[5%] border border-gray-300">QTY</th>
                                <th class="bg-gray-100 px-3 py-2 w-[15%] border border-gray-300">PRICE</th>
                                <th class="bg-gray-100 px-3 py-2 w-[15%] border border-gray-300">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody id="produk-body">
                            @foreach($item as $i)
                                <tr>
                                    <td class="px-3 py-2 border border-gray-300">
                                        {{ $i->produk->NAMA }}
                                    </td>
                                    <td class="px-3 py-2 border border-gray-300">
                                        {{ $i->produk->SKU }}
                                    </td>
                                    <td class="px-3 py-2 border border-gray-300">
                                        {{ $i->QTY }}
                                    </td>
                                    <td class="px-3 py-2 border border-gray-300">
                                        {{ number_format($i->PRICE, 0, ',', '.') }}
                                    </td>
                                    <td class="px-3 py-2 border border-gray-300">
                                        {{ number_format($i->TOTAL, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
        </div>
    </div>

    <div class="mobile-view">
        <div class="mobile-wrapper">

            <div class="mobile-card">

                <div class="flex items-center justify-between mb-5">

                    <h2 class="text-lg font-bold text-gray-800">
                        Detail Lead
                    </h2>

                    @if($opp->lead->ID_USER == Auth::id() && $opp->lead->STATUS != 'converted')
                        <a href="{{ route('edit.lead.sales', $opp->lead->LEAD_ID) }}"
                            class="px-4 py-2 rounded-lg bg-green-600 text-white text-sm font-medium">
                            Edit
                        </a>
                    @endif

                </div>

                <div class="mobile-title">
                    Lead Information
                </div>

                <div class="mobile-row">
                    <div class="mobile-label">Lead ID</div>
                    <div class="mobile-value">{{ $opp->lead->LEAD_ID }}</div>
                </div>

                <div class="mobile-row">
                    <div class="mobile-label">Opportunity ID</div>
                    <div class="mobile-value">{{ $opp->OPPORTUNITY_ID }}</div>
                </div>

                <div class="mobile-row">
                    <div class="mobile-label">Customer</div>
                    <div class="mobile-value">{{ $opp->lead->NAMA }}</div>
                </div>

                <div class="mobile-row">
                    <div class="mobile-label">Kategori</div>
                    <div class="mobile-value">


                        @if($opp->lead->KATEGORI_CUST != NULL)
                            {{ $opp->lead->KATEGORI_CUST }}
                        @else
                            NULL
                        @endif

                        <!-- @if($opp->lead->KATEGORI == 'COMPANY')
                            {{ $opp->lead->KATEGORI }} - {{ $opp->lead->PERUSAHAAN }}
                        @else
                            {{ $opp->lead->KATEGORI }}
                        @endif -->
                    </div>
                </div>

                <div class="mobile-row">
                    <div class="mobile-label">Sales</div>
                    <div class="mobile-value">
                        {{ optional($opp->lead->user)->NAMA ?? '-' }}
                    </div>
                </div>

                <div class="mobile-row">
                    <div class="mobile-label">Status</div>
                    <div class="mobile-value">
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $opp->lead->stage_class }}">
                            {{ $opp->lead->stage_label }}
                        </span>
                    </div>
                </div>

                <hr class="my-4">

                <div class="mobile-row">
                    <div class="mobile-label">Telepon</div>
                    <div class="mobile-value">
                        {{ $opp->lead->NO_TELP }}
                    </div>
                </div>

                <div class="mobile-row">
                    <div class="mobile-label">Kota</div>
                    <div class="mobile-value">
                        {{ $opp->lead->kota->name ?? '-' }}
                    </div>
                </div>

                <div class="mobile-row">
                    <div class="mobile-label">Source</div>
                    <div class="mobile-value">
                        {{ $opp->lead->LEAD_SOURCE }}
                    </div>
                </div>

                <hr class="my-4">

                <div class="mobile-row">
                    <div class="mobile-label">
                        Tanggal Lead
                    </div>

                    <div class="mobile-value">
                        {{ \Carbon\Carbon::parse($opp->lead->CREATED_AT)->translatedFormat('d F Y') }}
                    </div>
                </div>

                <div class="mobile-row">

                    <div class="mobile-label">

                        @if($opp->lead->STATUS == 'converted')

                            Tanggal Deal

                        @else

                            Tanggal Lost

                        @endif

                    </div>

                    <div class="mobile-value">
                        {{ \Carbon\Carbon::parse($opp->lead->UPDATED_AT)->translatedFormat('d F Y') }}
                    </div>

                </div>

                <div class="mobile-row">
                    <div class="mobile-label">
                        Nilai Prospect
                    </div>

                    <div class="mobile-value font-semibold text-green-600">
                        Rp {{ number_format($opp->NILAI_PROSPECT,0,',','.') }}
                    </div>
                </div>

            </div>

            <div class="mobile-card">
                <h3 class="mobile-section-title">
                    @if($opp->lead->STATUS == 'lost')
                        Reason Lost
                    @else
                        Catatan
                    @endif
                </h3>

                <div class="mobile-note-box">

                @if($opp->lead->STATUS == 'lost')

                <textarea
                    class="mobile-textarea"
                    rows="4"
                    readonly>{{ trim(($opp->lead->REASON ?? '') . (!empty($opp->NOTE) ? '. '.$opp->NOTE : '')) }}</textarea>

                @else

                <textarea
                    class="mobile-textarea"
                    rows="4"
                    readonly>{{ $opp->NOTE }}</textarea>

                @endif

                </div>

            </div>

            <div class="mobile-card">
                <h3 class="mobile-section-title">
                    Item Produk ({{ $item->count() }})
                </h3>

                @foreach($item as $i)

                    <div class="mobile-product">

                        <div class="mobile-product-name">
                            {{ $i->produk->NAMA }}
                        </div>

                        <div class="mobile-product-row">
                            <span>SKU</span>
                            <strong>{{ $i->produk->SKU }}</strong>
                        </div>

                        <div class="mobile-product-row">
                            <span>Qty</span>
                            <strong>{{ $i->QTY }}</strong>
                        </div>

                        <div class="mobile-product-row">
                            <span>Harga</span>
                            <strong>
                                Rp {{ number_format($i->PRICE,0,',','.') }}
                            </strong>
                        </div>

                        <div class="mobile-product-row mobile-product-total">

                            <span>Total</span>

                            <strong>
                                Rp {{ number_format($i->TOTAL,0,',','.') }}
                            </strong>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>
    </div>

</div>

@endsection

@section('scripts')

@endsection