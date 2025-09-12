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
        <h2 class="text-2xl font-semibold mb-6">DETAIL OPPORTUNITY</h2>
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
                            @php
                                $statusClasses = [
                                    'lead'        => 'bg-blue-400 text-white',      // Cold
                                    'opportunity' => 'bg-orange-400 text-black',    // Warm
                                    'quotation'   => 'bg-red-500 text-white',       // Hot
                                    'converted'   => 'bg-green-500 text-white',     // Deal
                                    'lost'        => 'bg-gray-500 text-white',      // Lost
                                    'norespon'    => 'bg-yellow-400 text-black',    // No Respon
                                ];

                                $statusLabels = [
                                    'lead'        => 'Cold',
                                    'opportunity' => 'Warm',
                                    'quotation'   => 'Hot',
                                    'converted'   => 'Deal',
                                    'lost'        => 'Lost',
                                    'norespon'    => 'No Respon',
                                ];

                                $status = strtolower(trim($opp->lead->STATUS));
                                $class  = $statusClasses[$status] ?? 'bg-gray-400 text-white';
                                $label  = $statusLabels[$status] ?? ucfirst($status);
                            @endphp

                            <div class="flex items-center space-x-2">
                                <!-- Status badge -->
                                <span class="inline-flex items-center px-2 py-1 rounded text-sm font-medium {{ $class }}">
                                    {{ ucfirst($label) }}
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