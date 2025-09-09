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
    <div class="w-full max-w-[90%] mx-auto bg-white p-8 rounded shadow space-y-8">
        <!-- Judul -->
        <h2 class="text-2xl font-semibold">DETAIL QUOTATION</h2>

        <!-- Grid Detail -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kiri -->
            <table class="w-full border border-gray-300 border-collapse">
                <tbody>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 w-1/3 border border-gray-300"><b>QUOTATION ID</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ $quo->QUO_ID }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>NAMA CUSTOMER</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ $quo->opportunity->lead->NAMA }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>SALES</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ $quo->opportunity->lead->User->NAMA }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>VALID UNTUL</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ \Carbon\Carbon::parse($quo->VALID_DATE)->translatedFormat('d F Y') }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Kanan -->
            <table class="w-full border border-gray-300 border-collapse">
                <tbody>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 w-1/3 border border-gray-300"><b>TANGGAL</b></td>
                        <td class="px-3 py-2 border border-gray-300">
                            {{ \Carbon\Carbon::parse($quo->CREATED_AT)->translatedFormat('d F Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>KOTA</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ $quo->opportunity->lead->kota->name }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>NILAI</b></td>
                        <td class="px-3 py-2 border border-gray-300">
                            Rp {{ number_format($quo->opportunity->NILAI_PROSPECT, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>STAUS</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ $quo->STATUS }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Tabel Produk -->
        <div>
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
                            <td class="px-3 py-2 border border-gray-300">{{ $i->produk->NAMA }}</td>
                            <td class="px-3 py-2 border border-gray-300">{{ $i->produk->SKU }}</td>
                            <td class="px-3 py-2 border border-gray-300">{{ $i->QTY }}</td>
                            <td class="px-3 py-2 border border-gray-300">{{ number_format($i->PRICE, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 border border-gray-300">{{ number_format($i->TOTAL, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div>
            <table class="w-full border border-gray-300 border-collapse">
                <tbody>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 w-1/6 border border-gray-300 align-top"><b>S&K</b></td>
                        <td class="px-3 py-2 border border-gray-300">
                            <textarea class="w-full border-gray-300 rounded-md" rows="4" readonly>{{ $quo->SNK }}</textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')

@endsection