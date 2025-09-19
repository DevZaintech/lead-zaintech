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
<div class="space-y-6">
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
            @else
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
                            @if($lead->KATEGORI === 'INDIVIDU')
                                {{ $lead->KATEGORI }}
                            @elseif($lead->KATEGORI === 'COMPANY')
                                {{ $lead->KATEGORI }} - {{ $lead->PERUSAHAAN ?? '-' }}
                            @else
                                -
                            @endif
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

@endsection

@section('scripts')

@endsection