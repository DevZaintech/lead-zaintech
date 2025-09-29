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
    <h2 class="text-2xl font-semibold mb-6">Tambah Lead Baru</h2>

    <form action="{{ route('storelead.gate') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Baris 1: Nama & Telp --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="NAMA" class="block text-gray-700 font-medium mb-1">
                    NAMA <span class="text-red-500">*</span>
                </label>
                <input type="text" name="NAMA" id="NAMA"
                    class="uppercase-input w-full border border-gray-300 rounded px-3 py-2 
                           focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
            <div>
                <label for="NO_TELP" class="block text-gray-700 font-medium mb-1">
                    TELEPON <span class="text-red-500">*</span>
                </label>
                <input type="text" name="NO_TELP" id="NO_TELP"
                    class="w-full border border-gray-300 rounded px-3 py-2"
                    inputmode="numeric" pattern="[0-9]*" minlength="8">
            </div>
        </div>

        {{-- Baris 1: company & Perusahaan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="KATEGORI" class="block text-gray-700 font-medium mb-1">
                    KATEGORI
                </label>
                <select name="KATEGORI" id="KATEGORI"
                    class="w-full border border-gray-300 rounded px-3 py-2 
                        focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                    <option value="INDIVIDU">INDIVIDU</option>
                    <option value="COMPANY">COMPANY</option>
                </select>
            </div>
            <div>
                <label for="PERUSAHAAN" class="block text-gray-700 font-medium mb-1">PERUSAHAAN</label>
                <input type="text" name="PERUSAHAAN" id="PERUSAHAAN"
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
                    <option value="">-- Pilih Kota --</option>
                </select>
            </div>
            <div>
                <label for="USER" class="block text-gray-700 font-medium mb-1">
                    SALES <span class="text-red-500" id="salesAsterisk">*</span>
                </label>
                <select name="USER" id="USER"
                    class="w-full border border-gray-300 rounded px-3 py-2 
                        focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">-- Pilih Sales --</option>
                    @foreach($user as $s)
                        <option value="{{ $s->ID_USER }}">{{ $s->NAMA }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Baris 3: Kebutuhan & Lead Status --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="KEBUTUHAN" class="block text-gray-700 font-medium mb-1">
                    KEBUTUHAN <span class="text-red-500">*</span>
                </label>
                <select name="KEBUTUHAN" id="KEBUTUHAN" class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="">-- Pilih Kebutuhan --</option>
                </select>
            </div> 
            <div>
                <label for="STATUS" class="block text-gray-700 font-medium mb-1">STATUS</label>
                <select name="STATUS" id="STATUS"
                    class="w-full border border-gray-300 rounded px-3 py-2 
                        focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="lead" selected>LEAD</option>
                    <option value="norespon">NO RESPON</option>
                </select>
            </div>
        </div> 
        
        {{-- Lead Source & TGL --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="LEAD_SOURCE" class="block text-gray-700 font-medium mb-1">
                    SOURCE <span class="text-red-500">*</span>
                </label>
                <select name="LEAD_SOURCE" id="LEAD_SOURCE"
                    class="w-full border border-gray-300 rounded px-3 py-2 
                        focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">Pilih Sumber Lead</option>
                    <option value="Meta Ads">Meta Ads</option>
                    <option value="Google Ads">Google Ads</option>
                    <option value="Youtube">Youtube</option>
                    <option value="Tiktok">Tiktok</option>
                    <option value="Instagram">Instagram</option>
                    <option value="Facebook">Facebook</option>
                    <option value="Marketplace">Marketplace</option>
                    <option value="Web">Web</option>
                </select>
            </div> 
            <div>
                <label for="CREATED_AT" class="block text-gray-700 font-medium mb-1">
                    TANGGAL LEAD <span class="text-red-500">*</span>
                </label>
                <input 
                    type="date" 
                    name="CREATED_AT" 
                    id="CREATED_AT" 
                    value="{{ old('CREATED_AT', now()->toDateString()) }}" 
                    class="w-full border border-gray-300 rounded px-3 py-2 
                        focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
        </div>

        
        

        {{-- Email --}}
        <div>
            <label for="EMAIL" class="block text-gray-700 font-medium mb-1">EMAIL</label>
            <input type="email" name="EMAIL" id="EMAIL"
                class="w-full border border-gray-300 rounded px-3 py-2 
                       focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200">
        </div>

        {{-- Catatan --}}
        <div>
            <label for="NOTE" class="block text-gray-700 font-medium mb-1">CATATAN</label>
            <textarea name="NOTE" id="NOTE" rows="3"
                class="w-full border border-gray-300 rounded px-3 py-2 
                       focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200"></textarea>
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
// Uppercase input & numeric telepon
document.getElementById('NO_TELP').addEventListener('input', function () {
    // Hanya angka
    let val = this.value.replace(/\D/g,'');

    // Jika diawali 62 → ubah jadi 0
    if (val.startsWith("62")) {
        val = "0" + val.slice(2);
    }

    this.value = val;
});

document.querySelectorAll('.uppercase-input').forEach(el => {
    el.addEventListener('input', function() { this.value = this.value.toUpperCase(); });
});

// Required fields sesuai STATUS
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
        const status = statusField.value;
        // Reset semua required
        Object.values(fields).forEach(f => f.required = false);
        Object.values(asterisks).forEach(a => { if(a) a.style.display = 'none'; });

        if(status === 'lead') {
            ['NAMA','KOTA','NO_TELP','USER','KEBUTUHAN','LEAD_SOURCE'].forEach(id => { fields[id].required = true; });
            asterisks.USER.style.display = 'inline';
            if(asterisks.NAMA) asterisks.NAMA.style.display = 'inline';
            if(asterisks.KOTA) asterisks.KOTA.style.display = 'inline';
            if(asterisks.KEBUTUHAN) asterisks.KEBUTUHAN.style.display = 'inline';
        } else if(status === 'norespon') {
            ['NO_TELP','LEAD_SOURCE'].forEach(id => { fields[id].required = true; });
        }
        updatePerusahaanRequired(); // cek ulang perusahaan setiap kali status berubah
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

// Select2 AJAX
$(document).ready(function() {
    $('#KEBUTUHAN').select2({
        placeholder: '-- Pilih Kebutuhan --',
        minimumInputLength: 0,
        ajax: {
            url: '{{ route('get.subkategori.gate') }}',
            dataType: 'json',
            delay: 250,
            data: params => ({ q: params.term || '' }),
            processResults: data => ({ results: data })
        }
    }).on('select2:open', () => { $(".select2-search__field").trigger('input'); });

    $('#KOTA').select2({
        placeholder: '-- Pilih Kota --',
        minimumInputLength: 0,
        ajax: {
            url: '{{ route('get.kota.gate') }}',
            dataType: 'json',
            delay: 250,
            data: params => ({ q: params.term || '' }),
            processResults: data => ({ results: data })
        }
    }).on('select2:open', () => { $(".select2-search__field").trigger('input'); });
});
</script>

@endsection
