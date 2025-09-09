@extends('layouts.frontend')

@section('content')
<div class="max-w-4xl mx-auto mt-10">
    <div class="bg-white rounded-2xl shadow-lg p-8">

        {{-- Header Profil --}}
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
            <!-- Avatar -->
            <div class="w-32 h-32 rounded-full overflow-hidden shadow-md border-4 border-blue-500">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->NAMA) }}&background=3B82F6&color=fff&size=128"
                     alt="Avatar" class="w-full h-full object-cover">
            </div>

            <!-- Info -->
            <div class="flex-1">
                <h2 class="text-3xl font-bold text-gray-800">{{ $user->NAMA }}</h2>
                <p class="text-gray-500 text-lg">{{ $user->EMAIL }}</p>
                
                <span class="inline-block mt-3 px-4 py-1 text-sm font-semibold text-white rounded-full
                    @if($user->ROLE === 'admin') bg-red-500 
                    @elseif($user->ROLE === 'sales') bg-blue-500 
                    @elseif($user->ROLE === 'gate') bg-green-500 
                    @else bg-gray-500 @endif">
                    {{ strtoupper($user->ROLE) }}
                </span>
            </div>
        </div>

        {{-- Detail --}}
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
                <label class="block text-gray-500 text-sm">Tanggal Dibuat</label>
                <p class="text-lg font-semibold text-gray-800">
                    {{ $user->CREATED_AT ? \Carbon\Carbon::parse($user->CREATED_AT)->format('d M Y H:i') : '-' }}
                </p>
            </div>

            <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
                <label class="block text-gray-500 text-sm">Terakhir Update</label>
                <p class="text-lg font-semibold text-gray-800">
                    {{ $user->UPDATED_AT ? \Carbon\Carbon::parse($user->UPDATED_AT)->format('d M Y H:i') : '-' }}
                </p>
            </div>
        </div>

        {{-- Update Form --}}
        <div class="mt-10 bg-gray-50 p-6 rounded-2xl shadow">
            <h3 class="text-xl font-semibold mb-4">Update Profil</h3>

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-gray-600 font-medium">Nama</label>
                    <input type="text" name="NAMA" value="{{ old('NAMA', $user->NAMA) }}"
                           class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200" required>
                </div>

                <div>
                    <label class="block text-gray-600 font-medium">Password</label>
                    <div class="relative">
                        <input type="password" name="PASSWORD" id="password"
                               placeholder="Kosongkan jika tidak ingin ganti"
                               class="w-full px-4 py-2 border rounded-lg pr-10 focus:ring focus:ring-blue-200">
                        <button type="button" onclick="togglePassword()" 
                                class="absolute right-2 top-2 text-gray-500 hover:text-gray-700">
                            👁
                        </button>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" 
                            class="px-5 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>

        {{-- Back --}}
        <div class="mt-8 flex justify-end">
            <a href="{{ url()->previous() }}" 
               class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
               ← Kembali
            </a>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
@endsection
