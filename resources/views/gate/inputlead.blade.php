@extends('layouts.frontend')
@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow">
    <h2 class="text-2xl font-semibold mb-6">Tambah Lead Baru</h2>

    <form action="#" method="POST" class="space-y-6">
        @csrf

        {{-- Baris 1: Nama & Perusahaan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="NAMA" class="block text-gray-700 font-medium mb-1">Nama</label>
                <input type="text" name="NAMA" id="NAMA"
                    class="w-full border border-gray-300 rounded px-3 py-2 
                           focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200" required>
            </div>
            <div>
                <label for="PERUSAHAAN" class="block text-gray-700 font-medium mb-1">Perusahaan</label>
                <input type="text" name="PERUSAHAAN" id="PERUSAHAAN"
                    class="w-full border border-gray-300 rounded px-3 py-2 
                           focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
        </div>

        {{-- Baris 2: Kota & Telepon --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="KOTA" class="block text-gray-700 font-medium mb-1">Kota</label>
                <input type="text" name="KOTA" id="KOTA"
                    class="w-full border border-gray-300 rounded px-3 py-2 
                           focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
            <div>
                <label for="NO_TELP" class="block text-gray-700 font-medium mb-1">No. Telepon</label>
                <input type="text" name="NO_TELP" id="NO_TELP"
                    class="w-full border border-gray-300 rounded px-3 py-2 
                           focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
        </div>

        {{-- Email --}}
        <div>
            <label for="EMAIL" class="block text-gray-700 font-medium mb-1">Email</label>
            <input type="email" name="EMAIL" id="EMAIL"
                class="w-full border border-gray-300 rounded px-3 py-2 
                       focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200">
        </div>

        {{-- Status --}}
        <div>
            <label for="STATUS" class="block text-gray-700 font-medium mb-1">Status</label>
            <input type="text" name="STATUS" id="STATUS"
                class="w-full border border-gray-300 rounded px-3 py-2 
                       focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200">
        </div>

        {{-- Lead Source Dropdown --}}
        <div>
            <label for="LEAD_SOURCE" class="block text-gray-700 font-medium mb-1">Lead Source</label>
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

@endsection