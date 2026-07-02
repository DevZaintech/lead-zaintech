@extends('layouts.frontend')
@section('css')
<style>
    @media (max-width: 767px){
        .desktop-view{
            display:none !important;
        }
    }

    @media (min-width: 768px){
        .mobile-view{
            display:none !important;
        }
    }

    .mobile-view{
        padding:12px;
    }

    .mobile-card{
        background:#fff;
        border-radius:14px;
        box-shadow:0 2px 8px rgba(0,0,0,.08);
        margin-bottom:16px;
        overflow:hidden;
    }

    .mobile-header{
        padding:18px;
        border-bottom:1px solid #ececec;
    }

    .mobile-title{
        font-size:20px;
        font-weight:700;
        color:#111827;
    }

    .mobile-subtitle{
        font-size:14px;
        color:#6b7280;
        margin-top:3px;
    }

    .mobile-section{
        padding:16px 18px;
    }

    .mobile-label{
        font-size:12px;
        color:#6b7280;
        margin-bottom:3px;
    }

    .mobile-value{
        font-size:15px;
        font-weight:500;
        color:#111827;
        line-height:1.5;
        word-break:break-word;
    }

    .mobile-divider{
        height:1px;
        background:#ececec;
        margin:14px 0;
    }

    /* ===========================
    BUTTON
    =========================== */

    .mobile-btn{
        display:block;
        width:100%;
        padding:12px;
        background:#16a34a;
        color:#fff;
        border-radius:10px;
        text-align:center;
        font-weight:600;
        margin-top:16px;
        text-decoration:none;
    }

    /* ===========================
    ACCORDION
    =========================== */

    .mobile-accordion{
        background:#fff;
        border-radius:14px;
        box-shadow:0 2px 8px rgba(0,0,0,.08);
        margin-bottom:16px;
        overflow:hidden;
    }

    .mobile-accordion-header{

        display:flex;
        justify-content:space-between;
        align-items:center;

        padding:16px 18px;

        cursor:pointer;

        font-weight:600;

        font-size:16px;

        border-bottom:1px solid #ececec;

    }

    .mobile-count{

        background:#3fa9f3;
        color:#fff;

        padding:2px 10px;

        border-radius:999px;

        font-size:12px;

        margin-left:8px;

    }

    .mobile-arrow{

        transition:.25s;

    }

    .mobile-arrow.rotate{

        transform:rotate(180deg);

    }

    .mobile-accordion-body{

        padding:16px;

    }

    /* ===========================
    ITEM CARD
    =========================== */

    .mobile-item{

        border:1px solid #e5e7eb;

        border-radius:12px;

        margin-bottom:12px;

        overflow:hidden;

    }

    .mobile-item-header{

        padding:14px 16px;

        background:#f9fafb;

        display:flex;

        justify-content:space-between;

        align-items:center;

        cursor:pointer;

    }

    .mobile-item-title{

        font-size:15px;

        font-weight:600;

        color:#111827;

    }

    .mobile-item-body{

        padding:16px;

    }

    .mobile-row{

        display:flex;

        justify-content:space-between;

        gap:15px;

        margin-bottom:10px;

    }

    .mobile-row:last-child{

        margin-bottom:0;

    }

    .mobile-row .left{

        color:#6b7280;

        font-size:13px;

    }

    .mobile-row .right{

        text-align:right;

        font-weight:600;

        color:#111827;

        word-break:break-word;

    }

    /* ===========================
    STATUS BADGE
    =========================== */

    .mobile-status{

        margin-top:10px;

    }

    /* ===========================
    TEXTAREA
    =========================== */

    .mobile-note{

        width:100%;

        border:1px solid #d1d5db;

        border-radius:10px;

        padding:10px;

        resize:none;

        background:#fafafa;

    }

    /* ===========================
    STICKY SECTION TITLE
    =========================== */

    .mobile-sticky-title{

        position:sticky;

        top:0;

        background:#fff;

        z-index:5;

    }
    /* ===========================
    MOBILE IMPROVEMENT
    =========================== */

    .mobile-card,
    .mobile-accordion{

        animation:fadeIn .25s ease;

    }

    @keyframes fadeIn{

        from{

            opacity:0;
            transform:translateY(10px);

        }

        to{

            opacity:1;
            transform:translateY(0);

        }

    }

    .mobile-item:hover{

        border-color:#3fa9f3;

    }

    .mobile-item-header:hover{

        background:#f3f9ff;

    }

    .mobile-btn{

        transition:.2s;

    }

    .mobile-btn:hover{

        opacity:.9;

    }

    .mobile-row{

        align-items:center;

    }

    .mobile-row+.mobile-row{

        padding-top:10px;

        border-top:1px solid #f1f5f9;

    }

    .mobile-item-body{

        background:white;

    }

    .mobile-item-title{

        line-height:1.5;

    }

    .mobile-value{

        line-height:1.6;

    }

    .mobile-note{

        background:white;

        border:1px solid #e5e7eb;

    }

    .mobile-count{

        min-width:28px;

        text-align:center;

    }

    .mobile-header{
        color:white;
    }

    .mobile-header .mobile-title{
        color:white;
    }

    .mobile-header .mobile-subtitle{
        color:rgba(255,255,255,.9);
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
<div class="desktop-view">
    <div class="w-full max-w-[80%] mx-auto bg-white p-8 rounded shadow">
        @php
            $status = strtolower(trim($lead->STATUS));
        @endphp

        <!-- Header judul + tombol -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-semibold">DETAIL LEAD</h2>

            @if($status === 'norespon')
                <a href="{{ route('edit.lead.gate', $lead->LEAD_ID) }}"
                class="inline-flex items-center px-5 py-2.5 bg-green-600 text-white text-base font-medium rounded-lg shadow-md hover:bg-green-700 transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                        class="w-5 h-5 mr-2" fill="none" 
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                            d="M16.862 4.487l1.651-1.651a2.121 2.121 0 113 3l-1.651 1.651m-3-3l-9.193 9.193a4 4 0 00-1.037 1.74l-.397 1.59a.75.75 0 00.91.91l1.59-.397a4 4 0 001.74-1.037l9.193-9.193m-3-3l3 3"/>
                    </svg>
                    Follow Up
                </a>
            @elseif($lead->STATUS != 'converted')
                <a href="{{ route('edit.lead.gate', $lead->LEAD_ID) }}"
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
                        <td class="px-3 py-2 border border-gray-300">{{ $lead->LEAD_ID }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>NAMA CUSTOMER</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ $lead->NAMA }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>KATEGORI CUSTOMER</b></td>
                        <td class="px-3 py-2 border border-gray-300">

                            @if($lead->KATEGORI_CUST != NULL)
                                {{ $lead->KATEGORI_CUST }}
                            @else
                                NULL
                            @endif

                            <!-- @if($lead->KATEGORI === 'INDIVIDU')
                                {{ $lead->KATEGORI }}
                            @elseif($lead->KATEGORI === 'COMPANY')
                                {{ $lead->KATEGORI }} - {{ $lead->PERUSAHAAN ?? '-' }}
                            @else
                                -
                            @endif -->
                        </td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>SALES HANDLE</b></td>
                        <td class="px-3 py-2 border border-gray-300">
                            {{ optional($lead->user)->NAMA ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>KEBUTUHAN</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ $lead->ID_SUB ? $lead->sub_kategori->NAMA : '-' }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Kanan -->
            <table class="w-full border border-gray-300 border-collapse">
                <tbody>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 w-1/3 border border-gray-300"><b>TANGGAL LEAD</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ \Carbon\Carbon::parse($lead->CREATED_AT)->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>TELEPON</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ $lead->NO_TELP }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>KOTA</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ $lead->kota->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>LEAD SOURCE</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ $lead->LEAD_SOURCE }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>STATUS</b></td>
                        <td class="px-3 py-2 border border-gray-300">
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2 py-1 rounded text-sm font-medium {{ $lead->stage_class }}">
                                    {{ $lead->stage_label }}
                                </span>
                            </div>
                        </td>
                    </tr>
                    @if($lead->STATUS == 'converted')
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>TANGGAL DEAL</b></td>
                        <td class="px-3 py-2 border border-gray-300">
                        <div class="flex items-center space-x-2">
                            {{ \Carbon\Carbon::parse($lead->UPDATED_AT)->translatedFormat('d F Y') }}
                        </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Catatan full width -->
        <div class="mt-6">
            <table class="w-full border border-gray-300 border-collapse">
                <tbody>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 w-1/6 border border-gray-300 align-top">
                            <b>{{ $lead->STATUS == 'lost' ? 'REASON LOST' : 'CATATAN' }}</b>
                        </td>
                        <td class="px-3 py-2 border border-gray-300 align-top">
                            <textarea 
                                class="w-full border-gray-300 rounded-md p-2 text-left align-top leading-normal" 
                                rows="3" 
                                readonly
                            >{{ $lead->STATUS == 'lost' ? $lead->REASON : $lead->NOTE }}</textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    @if(in_array($lead->STATUS, ['lost', 'converted','opportunity']))
    <div class="w-full max-w-[80%] mx-auto bg-white p-8 rounded shadow">
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
    @endif

    @if($follow->count() > 0)
        <div class="w-full max-w-[80%] mx-auto bg-white p-8 rounded shadow mt-8">
            {{-- TABEL FOLLOW UP --}}
            <div class="mb-6">
                <h2 class="text-2xl font-semibold mb-6">FOLLOW UP</h2>
                <table class="w-full border border-gray-300 border-collapse">
                    <thead>
                        <tr>
                            <th class="bg-gray-100 px-3 py-2 w-[5%] border border-gray-300">NO</th>
                            <th class="bg-gray-100 px-3 py-2 w-[20%] border border-gray-300">TGL FOLLOW</th>
                            <th class="bg-gray-100 px-3 py-2 w-[20%] border border-gray-300">RESPON</th>
                            <th class="bg-gray-100 px-3 py-2 w-[40%] border border-gray-300">KETERANGAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($follow as $index => $f)
                            <tr>
                                <td class="px-3 py-2 border border-gray-300 text-center">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-3 py-2 border border-gray-300">
                                    {{ \Carbon\Carbon::parse($f->TGL_FOLLOW)->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-3 py-2 border border-gray-300">
                                    {{ $f->RESPON }}
                                </td>
                                <td class="px-3 py-2 border border-gray-300">
                                    {{ $f->KETERANGAN }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>

<div class="mobile-view">

    @php
        $headerColor = match(strtolower($lead->STATUS)) {
            'lead' => 'linear-gradient(135deg,#4A90E2,#66A6FF)',
            'opportunity' => 'linear-gradient(135deg,#FF8C32,#FFA94D)',
            'quotation' => 'linear-gradient(135deg,#F7686A,#FF8080)',
            'converted' => 'linear-gradient(135deg,#45D07A,#5CE18D)',
            'lost' => 'linear-gradient(135deg,#9CA3AF,#B3BAC5)',
            'norespon' => 'linear-gradient(135deg,#FACC15,#FFD84D)',
            default => 'linear-gradient(135deg,#3fa9f3,#61bdfc)',
        };
    @endphp

    <div class="mobile-card">

        <div class="mobile-header" style="background: {{ $headerColor }};">

            <div class="mobile-title">
                Detail Lead
            </div>

            <div class="mobile-subtitle">
                Lead ID :
                {{ $lead->LEAD_ID }}
            </div>

            <div style="margin-top:12px;">
                <span class="inline-flex items-center px-2 py-1 rounded text-sm font-medium {{ $lead->stage_class }}">
                    {{ $lead->stage_label }}
                </span>
            </div>

        </div>

        <div class="mobile-section">

            <div class="mobile-label">
                Nama Customer
            </div>

            <div class="mobile-value">
                {{ $lead->NAMA }}
            </div>

            <div class="mobile-divider"></div>

            <div class="mobile-label">
                Kategori
            </div>

            <div class="mobile-value">

                @if($lead->KATEGORI_CUST != NULL)
                    {{ $lead->KATEGORI_CUST }}
                @else
                    NULL
                @endif

                <!-- @if($lead->KATEGORI=='INDIVIDU')

                    {{ $lead->KATEGORI }}

                @elseif($lead->KATEGORI=='COMPANY')

                    {{ $lead->KATEGORI }}

                    @if($lead->PERUSAHAAN)

                        - {{ $lead->PERUSAHAAN }}

                    @endif

                @else

                    -

                @endif -->

            </div>

            <div class="mobile-divider"></div>

            <div class="mobile-label">
                Sales Handle
            </div>

            <div class="mobile-value">
                {{ optional($lead->user)->NAMA ?? '-' }}
            </div>

            <div class="mobile-divider"></div>

            <div class="mobile-label">
                Kebutuhan
            </div>

            <div class="mobile-value">
                {{ $lead->ID_SUB ? $lead->sub_kategori->NAMA : '-' }}
            </div>

            <div class="mobile-divider"></div>

            <div class="mobile-label">
                Telepon
            </div>

            <div class="mobile-value">
                {{ $lead->NO_TELP }}
            </div>

            <div class="mobile-divider"></div>

            <div class="mobile-label">
                Kota
            </div>

            <div class="mobile-value">
                {{ $lead->kota->name ?? '-' }}
            </div>

            <div class="mobile-divider"></div>

            <div class="mobile-label">
                Lead Source
            </div>

            <div class="mobile-value">
                {{ $lead->LEAD_SOURCE }}
            </div>

            <div class="mobile-divider"></div>

            <div class="mobile-label">
                Tanggal Lead
            </div>

            <div class="mobile-value">
                {{ \Carbon\Carbon::parse($lead->CREATED_AT)->translatedFormat('d F Y') }}
            </div>

            @if($lead->STATUS=='converted')

                <div class="mobile-divider"></div>

                <div class="mobile-label">
                    Tanggal Deal
                </div>

                <div class="mobile-value">
                    {{ \Carbon\Carbon::parse($lead->UPDATED_AT)->translatedFormat('d F Y') }}
                </div>

            @endif

            @if($status=='norespon')

                <a
                    href="{{ route('edit.lead.gate',$lead->LEAD_ID) }}"
                    class="mobile-btn">

                    Follow Up

                </a>

            @elseif($lead->STATUS != 'converted')

                <a
                    href="{{ route('edit.lead.gate',$lead->LEAD_ID) }}"
                    class="mobile-btn">

                    Edit

                </a>

            @endif

        </div>

    </div>

    {{-- ==========================
        CATATAN
    ========================== --}}

    <div class="mobile-card">

        <div class="mobile-header">

            <div class="mobile-title">

                {{ $lead->STATUS == 'lost' ? 'Reason Lost' : 'Catatan' }}

            </div>

        </div>

        <div class="mobile-section">

            <textarea
                class="mobile-note"
                rows="5"
                readonly>{{ $lead->STATUS == 'lost' ? $lead->REASON : $lead->NOTE }}</textarea>

        </div>

    </div>


    {{-- ==========================
        ITEM TABLE
    ========================== --}}

    @if(in_array($lead->STATUS,['lost','converted','opportunity']))

    <div
        class="mobile-accordion"
        x-data="{open:true}">

        <div
            class="mobile-accordion-header"
            @click="open=!open">

            <div>

                📦 Item

                <span class="mobile-count">

                    {{ $item->count() }}

                </span>

            </div>

            <svg
                class="mobile-arrow"
                :class="open ? 'rotate' : ''"
                width="22"
                height="22"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"/>

            </svg>

        </div>

        <div
            class="mobile-accordion-body"
            x-show="open"
            x-transition>

            @foreach($item as $i)

            <div
                class="mobile-item"
                x-data="{detail:false}">

                <div
                    class="mobile-item-header"
                    @click="detail=!detail">

                    <div>

                        <div class="mobile-item-title">

                            {{ $i->produk->NAMA }}

                        </div>

                        <div
                            style="
                            font-size:13px;
                            color:#6b7280;
                            margin-top:3px;">

                            {{ $i->produk->SKU }}

                        </div>

                    </div>

                    <svg
                        class="mobile-arrow"
                        :class="detail ? 'rotate' : ''"
                        width="20"
                        height="20"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7"/>

                    </svg>

                </div>

                <div
                    class="mobile-item-body"
                    x-show="detail"
                    x-transition>

                    <div class="mobile-row">

                        <div class="left">

                            SKU

                        </div>

                        <div class="right">

                            {{ $i->produk->SKU }}

                        </div>

                    </div>

                    <div class="mobile-row">

                        <div class="left">

                            Qty

                        </div>

                        <div class="right">

                            {{ $i->QTY }}

                        </div>

                    </div>

                    <div class="mobile-row">

                        <div class="left">

                            Harga

                        </div>

                        <div class="right">

                            Rp {{ number_format($i->PRICE,0,',','.') }}

                        </div>

                    </div>

                    <div class="mobile-row">

                        <div class="left">

                            Total

                        </div>

                        <div class="right">

                            Rp {{ number_format($i->TOTAL,0,',','.') }}

                        </div>

                    </div>

                </div>

            </div>

            @endforeach

        </div>

    </div>

    @endif

    @if($follow->count() > 0)

    <div
        class="mobile-accordion"
        x-data="{open:true}">

        <div
            class="mobile-accordion-header"
            @click="open=!open">

            <div>

                📝 Follow Up

                <span class="mobile-count">

                    {{ $follow->count() }}

                </span>

            </div>

            <svg
                class="mobile-arrow"
                :class="open ? 'rotate' : ''"
                width="22"
                height="22"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"/>

            </svg>

        </div>

        <div
            class="mobile-accordion-body"
            x-show="open"
            x-transition>

            @foreach($follow as $index=>$f)

            <div
                class="mobile-item"
                x-data="{detail:false}">

                <div
                    class="mobile-item-header"
                    @click="detail=!detail">

                    <div>

                        <div class="mobile-item-title">

                            {{ \Carbon\Carbon::parse($f->TGL_FOLLOW)->translatedFormat('d F Y') }}

                        </div>

                        <div
                            style="
                            font-size:13px;
                            color:#6b7280;
                            margin-top:3px;">

                            {{ $f->RESPON }}

                        </div>

                    </div>

                    <svg
                        class="mobile-arrow"
                        :class="detail ? 'rotate' : ''"
                        width="20"
                        height="20"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7"/>

                    </svg>

                </div>

                <div
                    class="mobile-item-body"
                    x-show="detail"
                    x-transition>

                    <div class="mobile-row">

                        <div class="left">

                            Tanggal

                        </div>

                        <div class="right">

                            {{ \Carbon\Carbon::parse($f->TGL_FOLLOW)->translatedFormat('d F Y') }}

                        </div>

                    </div>

                    <div class="mobile-row">

                        <div class="left">

                            Respon

                        </div>

                        <div class="right">

                            {{ $f->RESPON }}

                        </div>

                    </div>

                    <div
                        style="
                        margin-top:15px;
                        padding:14px;
                        background:#f9fafb;
                        border-radius:10px;
                        line-height:1.6;
                        font-size:14px;">

                        {{ $f->KETERANGAN }}

                    </div>

                </div>

            </div>

            @endforeach

        </div>

    </div>

    @endif

    {{-- PART 4 dimulai dari sini --}}

</div>

@endsection

@section('scripts')

@endsection