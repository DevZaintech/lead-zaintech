@extends('layouts.frontend')
@section('css')
<style>
/* Sesuaikan tinggi dan border agar mirip select Tailwind */
.select2-container .select2-selection--single {
    height: 2.5rem !important;
    border: 1px solid #d1d5db !important; /* border-gray-300 */
    border-radius: 0.375rem !important;   /* rounded-md */
    padding: 0.25rem 0.75rem !important;
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
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3) !important;
}
.uppercase-input {
    text-transform: uppercase;
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

    <form action="{{ route('storelead.sales') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Baris 1: Nama & Telp --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="NAMA" class="block text-gray-700 font-medium mb-1">
                    NAMA <span class="text-red-500">*</span>
                </label>
                <input type="text" name="NAMA" id="NAMA"
                    class="uppercase-input w-full border border-gray-300 rounded px-3 py-2 
                           focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200"
                    required>
            </div>
            <div>
                <label for="NO_TELP" class="block text-gray-700 font-medium mb-1">
                    TELEPON <span class="text-red-500">*</span>
                </label>
                <input type="text" name="NO_TELP" id="NO_TELP"
                    class="w-full border border-gray-300 rounded px-3 py-2"
                    inputmode="numeric" placeholder="08xxx" pattern="[0-9]*" minlength="8" required>
            </div>
        </div>

        {{-- Baris 2: Kategori & Perusahaan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="KATEGORI" class="block text-gray-700 font-medium mb-1">KATEGORI <span class="text-red-500">*</span></label>
                <select name="KATEGORI" id="KATEGORI"
                    class="w-full border border-gray-300 rounded px-3 py-2 
                        focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200"
                    required>
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

        {{-- Baris 3: Kota & Source --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="KOTA" class="block text-gray-700 font-medium mb-1">
                    KOTA <span class="text-red-500">*</span>
                </label>
                <select name="kode_kota" id="KOTA" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="">-- Pilih Kota --</option>
                </select>
            </div>
            <div>
                <label for="LEAD_SOURCE" class="block text-gray-700 font-medium mb-1">
                    SOURCE <span class="text-red-500">*</span>
                </label>
                <select name="LEAD_SOURCE" id="LEAD_SOURCE"
                    class="w-full border border-gray-300 rounded px-3 py-2 
                        focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200"
                    required>
                    <option value="">Pilih Sumber Lead</option>
                    <option value="Sosmed Pribadi">Sosmed Pribadi</option>
                    <option value="Walk In">Walk In</option>
                    <option value="Direct">Direct</option>
                    <option value="Exhibition">Exhibition</option>
                    <option value="Relasi">Relasi</option>
                </select>
            </div>
        </div>

        {{-- Baris 4: Kebutuhan --}}
        <div>
            <label for="KEBUTUHAN" class="block text-gray-700 font-medium mb-1">
                KEBUTUHAN <span class="text-red-500">*</span>
            </label>
            <select name="KEBUTUHAN" id="KEBUTUHAN" class="w-full border border-gray-300 rounded px-3 py-2" required>
                <option value="">-- Pilih Kebutuhan --</option>
            </select>
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

// Wajibkan perusahaan hanya kalau kategori = COMPANY
document.addEventListener('DOMContentLoaded', function() {
    const kategoriField = document.getElementById('KATEGORI');
    const perusahaanField = document.getElementById('PERUSAHAAN');
    const perusahaanLabel = document.querySelector('label[for="PERUSAHAAN"]');

    function updatePerusahaanRequired() {
        if (kategoriField.value == 'COMPANY') {
            perusahaanField.required = true;
            if (!perusahaanLabel.querySelector('span.text-red-500')) {
                perusahaanLabel.innerHTML += ' <span class="text-red-500">*</span>';
            }
        } else {
            perusahaanField.required = false;
            const span = perusahaanLabel.querySelector('span.text-red-500');
            if (span) span.remove();
        }
    }

    kategoriField.addEventListener('change', updatePerusahaanRequired);
    updatePerusahaanRequired();
});

// Select2 AJAX
$(document).ready(function() {
    $('#KEBUTUHAN').select2({
        placeholder: '-- Pilih Kebutuhan --',
        minimumInputLength: 0,
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
        minimumInputLength: 0,
        ajax: {
            url: '{{ route('get.kota.sales') }}',
            dataType: 'json',
            delay: 250,
            data: params => ({ q: params.term || '' }),
            processResults: data => ({ results: data })
        }
    });
});
</script>
@endsection
