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

<div class="w-full max-w-[80%] mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-semibold mb-6">DETAIL LEAD</h2>
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
                    <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>PERUSAHAAN</b></td>
                    <td class="px-3 py-2 border border-gray-300">{{ $lead->PERUSAHAAN }}</td>
                </tr>
                <tr>
                    <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>SALES HANDLE</b></td>
                    <td class="px-3 py-2 border border-gray-300">{{ $lead->user->NAMA }}</td>
                </tr>
                <tr>
                    <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>KEBUTUHAN</b></td>
                    <td class="px-3 py-2 border border-gray-300">{{ $lead->sub_kategori->NAMA }}</td>
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
                        @php
                            $statusClasses = [
                                'lead'        => 'bg-blue-500 text-white',
                                'opportunity' => 'bg-yellow-500 text-black',
                                'quotation'   => 'bg-purple-500 text-white',
                                'converted'   => 'bg-green-500 text-white',
                                'los'         => 'bg-red-500 text-white',
                            ];
                            $status = strtolower(trim($lead->STATUS));
                            $class = $statusClasses[$status] ?? 'bg-gray-400 text-white';
                        @endphp
                        <span class="px-2 py-1 rounded text-sm font-medium {{ $class }}">
                            {{ ucfirst($status) }}
                        </span>
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
                    <td class="bg-gray-100 px-3 py-2 w-1/6 border border-gray-300 align-top"><b>CATATAN</b></td>
                    <td class="px-3 py-2 border border-gray-300">
                        <textarea class="w-full border-gray-300 rounded-md" rows="4" readonly>{{ $lead->NOTE }}</textarea>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

@endsection

@section('scripts')

@endsection