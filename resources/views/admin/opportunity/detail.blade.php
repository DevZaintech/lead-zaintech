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
                        <td class="bg-gray-100 px-3 py-2 w-1/3 border border-gray-300"><b>OPPORTUNITY ID</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ $opp->OPPORTUNITY_ID }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>NAMA CUSTOMER</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ $opp->lead->NAMA }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>SALES HANDLE</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ $opp->lead->User->NAMA }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>PROSENTASE</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ $opp->PROSENTASE_PROSPECT }}%</td>
                    </tr>
                </tbody>
            </table>

            <!-- Kanan -->
            <table class="w-full border border-gray-300 border-collapse">
                <tbody>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 w-1/3 border border-gray-300"><b>TANGGAL</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ \Carbon\Carbon::parse($opp->CREATED_AT)->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>KOTA</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ $opp->lead->kota->name }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>SOURCE</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ $opp->lead->LEAD_SOURCE }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>NILAI PROSPECT</b></td>
                        <td class="px-3 py-2 border border-gray-300">Rp {{ number_format($opp->NILAI_PROSPECT, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Catatan full width -->
        <div class="mt-6">
            <table class="w-full border border-gray-300 border-collapse">
                <tbody>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 w-1/6 border border-gray-300 align-top"><b>CATATAN</b></td>
                        <td class="px-3 py-2 border border-gray-300">
                            <textarea class="w-full border-gray-300 rounded-md" rows="4" readonly>{{ $opp->NOTE }}</textarea>
                        </td>
                    </tr>
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