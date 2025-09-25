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
    <div class="w-full max-w-[90%] mx-auto bg-white p-8 rounded shadow">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-semibold mb-6">Detail Lead</h2>

            @if($opp->lead->USER_ID == Auth::id())
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
                            @if($opp->lead->KATEGORI === 'INDIVIDU')
                                {{ $opp->lead->KATEGORI }}
                            @elseif($opp->lead->KATEGORI === 'COMPANY')
                                {{ $opp->lead->KATEGORI }} - {{ $opp->lead->PERUSAHAAN ?? '-' }}
                            @else
                                -
                            @endif
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
                            <textarea class="w-full border-gray-300 rounded-md" rows="2" readonly>{{ $opp->lead->REASON }}</textarea>
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

@endsection

@section('scripts')

@endsection