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

        {{-- Baris 1: Nama & Perusahaan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="NAMA" class="block text-gray-700 font-medium mb-1">Nama <span class="text-red-500">*</span></label>
                <input type="text" name="NAMA" id="NAMA"
                    class="uppercase-input w-full border border-gray-300 rounded px-3 py-2 
                           focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200" required>
            </div>
            <div>
                <label for="PERUSAHAAN" class="block text-gray-700 font-medium mb-1">Perusahaan</label>
                <input type="text" name="PERUSAHAAN" id="PERUSAHAAN"
                    class="uppercase-input w-full border border-gray-300 rounded px-3 py-2 
                           focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
        </div>

        {{-- Baris 2: Kota & Telepon --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="KOTA" class="block text-gray-700 font-medium mb-1">Kota</label>
                <input type="text" name="KOTA" id="KOTA"
                    class="uppercase-input w-full border border-gray-300 rounded px-3 py-2 
                           focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
            <div>
                <label for="NO_TELP" class="block text-gray-700 font-medium mb-1">
                    No. Telepon <span class="text-red-500">*</span>
                </label>
                <input type="text" name="NO_TELP" id="NO_TELP"
                    class="w-full border border-gray-300 rounded px-3 py-2 
                        focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200"
                    inputmode="numeric" pattern="[0-9]*" minlength="8" required>
            </div>
        </div>

        {{-- Baris 3: Sales & Lead --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="USER" class="block text-gray-700 font-medium mb-1">
                    Sales <span class="text-red-500" id="salesAsterisk" style="display: none;">*</span>
                </label>
                <select name="USER" id="USER"
                    class="w-full border border-gray-300 rounded px-3 py-2 
                        focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">-- Pilih Sales --</option>
                    @foreach($user as $s)
                        <option value="{{ $s->ID_USER }}">{{ $s->NAMA }}</option>
                    @endforeach
                </select>
                @error('USER')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="STATUS" class="block text-gray-700 font-medium mb-1">Status</label>
                <select name="STATUS" id="STATUS"
                    class="w-full border border-gray-300 rounded px-3 py-2 
                        focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="lead" selected>New Lead</option>
                    <option value="lost">Lost Lead</option>
                </select>
                @error('STATUS')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>


        {{-- Kebutuhan --}}
        <div>
            <label for="KEBUTUHAN" class="block text-gray-700 font-medium mb-1">Kebutuhan</label>
            <select name="KEBUTUHAN" id="KEBUTUHAN"
                class="w-full border border-gray-300 rounded px-3 py-2">
                <option value="">-- Pilih Kebutuhan --</option>
            </select>
        </div>


        {{-- Lead Source Dropdown --}}
        <div>
            <label for="LEAD_SOURCE" class="block text-gray-700 font-medium mb-1">Lead Source <span class="text-red-500">*</span></label>
            <select name="LEAD_SOURCE" id="LEAD_SOURCE" required
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
            @error('LEAD_SOURCE')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="EMAIL" class="block text-gray-700 font-medium mb-1">Email</label>
            <input type="email" name="EMAIL" id="EMAIL"
                class="w-full border border-gray-300 rounded px-3 py-2 
                       focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200">
        </div>

        {{-- Catatan --}}
        <div>
            <label for="NOTE" class="block text-gray-700 font-medium mb-1">Catatan</label>
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

<!-- jQuery (wajib sebelum Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
document.getElementById('NO_TELP').addEventListener('input', function (e) {
    // Hapus semua karakter selain angka
    this.value = this.value.replace(/\D/g, '');
});

document.querySelectorAll('.uppercase-input').forEach(function(el) {
    el.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusField = document.getElementById('STATUS');
    const salesAsterisk = document.getElementById('salesAsterisk');

    function updateAsterisk() {
        salesAsterisk.style.display = statusField.value === 'lead' ? 'inline' : 'none';
    }

    statusField.addEventListener('change', updateAsterisk);

    // Jalankan saat pertama kali
    updateAsterisk();
});
</script>

<script>
$(document).ready(function() {
    $('#KEBUTUHAN').select2({
        placeholder: '-- Pilih Kebutuhan --',
        minimumInputLength: 0, // <- biar bisa buka tanpa ketik
        ajax: {
            url: '{{ route('get.subkategori') }}',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return { q: params.term || '' }; // kalau kosong kirim kosong
            },
            processResults: function(data) {
                return { results: data };
            }
        }
    }).on('select2:open', function() {
        // trigger pencarian kosong agar langsung load semua saat pertama buka
        $(".select2-search__field").trigger('input');
    });
});
</script>


@endsection