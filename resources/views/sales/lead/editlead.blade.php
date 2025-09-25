@extends('layouts.frontend')
@section('css')
<style>
/* Sesuaikan tinggi dan border agar mirip select Tailwind */
.select2-container .select2-selection--single {
    height: 2.5rem !important;           /* sama seperti py-2 */
    border: 1px solid #d1d5db !important;/* border-gray-300 */
    border-radius: 0.375rem !important;  /* rounded-md */
    padding: 0.25rem 0.75rem !important; /* padding kiri kanan */
    display: flex !important;
    align-items: center !important;
    background-color: #fff !important;
    box-shadow: none !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #111827 !important; /* text-gray-900 */
    line-height: 1.5rem !important;
    padding-left: 0 !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 100% !important;
    top: 0 !important;
    right: 0.75rem !important;
}

.select2-container--default .select2-selection--single:focus {
    outline: none !important;
    border-color: #3b82f6 !important; /* border-blue-500 */
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3) !important;
}

.uppercase-input {
    text-transform: uppercase; /* Tampilan langsung uppercase */
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

<div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow">
    <h2 class="text-2xl font-semibold mb-6">Follow Up Lead</h2>

    <form action="{{ route('updatelead.sales') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="ID_LEAD" value="{{ $lead->ID_LEAD }}">

        {{-- Baris 1: Nama & Telp --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="NAMA" class="block text-gray-700 font-medium mb-1">
                    NAMA <span class="text-red-500">*</span>
                </label>
                <input type="text" name="NAMA" id="NAMA"
                    value="{{ $lead->NAMA }}"
                    class="uppercase-input w-full border border-gray-300 rounded px-3 py-2 
                           focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200">
                <input type="hidden" name="LEAD_ID" id="LEAD_ID"
                    value="{{ $lead->LEAD_ID }}"
                    class="uppercase-input w-full border border-gray-300 rounded px-3 py-2 
                           focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200" required>
            </div>
            <div>
                <label for="NO_TELP" class="block text-gray-700 font-medium mb-1">
                    TELEPON <span class="text-red-500">*</span>
                </label>
                <input type="text" name="NO_TELP" id="NO_TELP"
                    value="{{ $lead->NO_TELP }}"
                    class="w-full border border-gray-300 rounded px-3 py-2"
                    inputmode="numeric" pattern="[0-9]*" minlength="8">
            </div>
        </div>

        {{-- Baris 1: Kategori & Perusahaan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="KATEGORI" class="block text-gray-700 font-medium mb-1">
                    KATEGORI
                </label>
                <select name="KATEGORI" id="KATEGORI"
                    class="w-full border border-gray-300 rounded px-3 py-2 
                        focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                    <option value="INDIVIDU" {{ $lead->KATEGORI == 'INDIVIDU' ? 'selected' : '' }}>INDIVIDU</option>
                    <option value="COMPANY" {{ $lead->KATEGORI == 'COMPANY' ? 'selected' : '' }}>COMPANY</option>
                </select>
            </div>
            <div>
                <label for="PERUSAHAAN" class="block text-gray-700 font-medium mb-1">PERUSAHAAN</label>
                <input type="text" name="PERUSAHAAN" id="PERUSAHAAN"
                    value="{{ $lead->PERUSAHAAN }}"
                    class="uppercase-input w-full border border-gray-300 rounded px-3 py-2 
                           focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
        </div>

        {{-- Baris 2: Kota & Sales --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="KOTA" class="block text-gray-700 font-medium mb-1">
                    KOTA <span class="text-red-500">*</span>
                </label>
                <select name="kode_kota" id="KOTA" class="w-full border border-gray-300 rounded px-3 py-2">
                    @if(!empty($lead->kode_kota))
                        <option value="{{ $lead->kode_kota }}" selected>
                            {{ $lead->kota->name ?? '-' }}
                        </option>
                    @endif
                </select>
            </div>
            <div>
                <label for="LEAD_SOURCE" class="block text-gray-700 font-medium mb-1">
                    SOURCE <span class="text-red-500">*</span>
                </label>
                <input type="text" name="LEAD_SOURCE" id="LEAD_SOURCE"
                    value="{{ $lead->LEAD_SOURCE }}"
                    class="uppercase-input w-full border border-gray-300 rounded px-3 py-2 
                           focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200" readonly>
            </div>
        </div>

        {{-- Baris 3: Kebutuhan & Lead Status --}}
        <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
            <div>
                <label for="KEBUTUHAN" class="block text-gray-700 font-medium mb-1">
                    KEBUTUHAN <span class="text-red-500">*</span>
                </label>
                <select name="KEBUTUHAN" id="KEBUTUHAN" class="w-full border border-gray-300 rounded px-3 py-2">
                    @if(!empty($lead->ID_SUB))
                        <option value="{{ $lead->ID_SUB }}" selected>
                            {{ $lead->sub_kategori->NAMA ?? '-' }}
                        </option>
                    @endif
                </select>
            </div> 
            
        </div>

        @php
            $reasons = [
                'INDEN',
                'SUDAH BELI DI VENDOR LAIN',
                'HARGA TINGGI',
                'LOKASI TERLALU JAUH',
                'PEMBAYARAN',
                'STOCK KOSONG',
                'NO RESPON',
                'TIDAK JUAL',
            ];
        @endphp

        @if($lead->STATUS == 'lost')
            <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                <div>
                    <label for="REASON" class="block text-gray-700 font-medium mb-1">
                        REASON <span class="text-red-500">*</span>
                    </label>
                    <select name="REASON" id="REASON"
                        class="w-full border border-gray-400 rounded px-3 py-2 text-gray-600"
                        required>
                        <option value="">-- Pilih Reason --</option>
                        @foreach($reasons as $reason)
                            <option value="{{ $reason }}"
                                {{ old('REASON', $lead->REASON ?? '') == $reason ? 'selected' : '' }}>
                                {{ $reason }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif

        {{-- Email --}}
        <div>
            <label for="EMAIL" class="block text-gray-700 font-medium mb-1">EMAIL</label>
            <input type="email" name="EMAIL" id="EMAIL"
                value="{{ $lead->EMAIL }}"
                class="w-full border border-gray-300 rounded px-3 py-2 
                       focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200">
        </div>

        {{-- Catatan --}}
        <div>
            <label for="NOTE" class="block text-gray-700 font-medium mb-1">CATATAN</label>
            <textarea name="NOTE" id="NOTE" rows="3"
                class="w-full border border-gray-300 rounded px-3 py-2 
                       focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200">{{ $lead->NOTE }}</textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Simpan
            </button>
        </div>
    </form>
</div>

<!-- jQuery & Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
// Uppercase & numeric
document.getElementById('NO_TELP').addEventListener('input', function () {
    this.value = this.value.replace(/\D/g,'');
});
document.querySelectorAll('.uppercase-input').forEach(el => {
    el.addEventListener('input', function() { this.value = this.value.toUpperCase(); });
});

// Required fields dinamis
document.addEventListener('DOMContentLoaded', function() {
    const statusField = document.getElementById('STATUS');
    const kategoriField = document.getElementById('KATEGORI');
    const perusahaanField = document.getElementById('PERUSAHAAN');
    const perusahaanLabel = document.querySelector('label[for="PERUSAHAAN"]');

    const fields = {
        NAMA: document.getElementById('NAMA'),
        KOTA: document.getElementById('KOTA'),
        NO_TELP: document.getElementById('NO_TELP'),
        USER: document.getElementById('USER'),
        KEBUTUHAN: document.getElementById('KEBUTUHAN'),
        LEAD_SOURCE: document.getElementById('LEAD_SOURCE')
    };
    const asterisks = {
        NAMA: document.querySelector('label[for="NAMA"] span.text-red-500'),
        USER: document.getElementById('salesAsterisk'),
        KOTA: document.querySelector('label[for="KOTA"] span.text-red-500'),
        KEBUTUHAN: document.querySelector('label[for="KEBUTUHAN"] span.text-red-500')
    };

    function updateRequired() {
        Object.values(fields).forEach(f => f.required = false);
        Object.values(asterisks).forEach(a => { if(a) a.style.display = 'none'; });

        if(statusField.value === 'lead') {
            ['NAMA','KOTA','NO_TELP','USER','KEBUTUHAN','LEAD_SOURCE'].forEach(id => { fields[id].required = true; });
            asterisks.USER.style.display = 'inline';
            if(asterisks.NAMA) asterisks.NAMA.style.display = 'inline';
            if(asterisks.KOTA) asterisks.KOTA.style.display = 'inline';
            if(asterisks.KEBUTUHAN) asterisks.KEBUTUHAN.style.display = 'inline';
        } else if(statusField.value === 'norespon') {
            ['NO_TELP','LEAD_SOURCE'].forEach(id => { fields[id].required = true; });
        }
        updatePerusahaanRequired();
    }

    function updatePerusahaanRequired() {
        if (kategoriField.value === 'COMPANY') {
            perusahaanField.required = true;
            if (!perusahaanLabel.querySelector('span')) {
                perusahaanLabel.innerHTML += ' <span class="text-red-500">*</span>';
            }
        } else {
            perusahaanField.required = false;
            const span = perusahaanLabel.querySelector('span');
            if (span) span.remove();
        }
    }

    statusField.addEventListener('change', updateRequired);
    kategoriField.addEventListener('change', updatePerusahaanRequired);

    updateRequired();
    updatePerusahaanRequired();
});

// Select2 AJAX + preselect
$(document).ready(function() {
    $('#KEBUTUHAN').select2({
        placeholder: '-- Pilih Kebutuhan --',
        ajax: {
            url: '{{ route('get.subkategori.sales') }}',
            dataType: 'json',
            delay: 250,
            data: params => ({ q: params.term || '' }),
            processResults: data => ({ results: data })
        }
    });

    $('#KOTA').select2({
        placeholder: '-- Pilih Kota --',
        ajax: {
            url: '{{ route('get.kota.sales') }}',
            dataType: 'json',
            delay: 250,
            data: params => ({ q: params.term || '' }),
            processResults: data => ({ results: data })
        }
    });

    // inject data lama kalau ada
    @if(!empty($lead->ID_SUB) && !empty($lead->sub_kategori->NAMA))
        let kebutuhanOption = new Option("{{ $lead->sub_kategori->NAMA }}", "{{ $lead->ID_SUB }}", true, true);
        $('#KEBUTUHAN').append(kebutuhanOption).trigger('change');
    @endif

    @if(!empty($lead->kode_kota) && !empty($lead->kota->NAMA_KOTA))
        let kotaOption = new Option("{{ $lead->kota->NAMA_KOTA }}", "{{ $lead->kode_kota }}", true, true);
        $('#KOTA').append(kotaOption).trigger('change');
    @endif
});
</script>
@endsection
