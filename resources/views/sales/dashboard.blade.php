@extends('layouts.frontend')
@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-4">Selamat datang, {{ Auth::user()->NAMA }}</h2>
    <p class="text-gray-600">Ini adalah halaman Dashboard Sales yang responsif dengan sidebar ala Filament.</p>
</div>
@endsection