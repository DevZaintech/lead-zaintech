@extends('layouts.frontend')

@section('content')
<div 
    x-data="{ sortRole: '', openAddModal: false, openEditModal: false, selectedUser: {} }" 
    class="max-w-7xl mx-auto p-6 bg-white rounded-lg shadow relative">

    <h1 class="text-2xl font-bold mb-6">User Management</h1>

    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter + Add --}}
    <div class="mb-4 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <label class="font-medium">Filter by Role:</label>
            <select x-model="sortRole" class="border rounded px-3 py-1">
                <option value="">All</option>
                <option value="admin">Admin</option>
                <option value="gate">Gate</option>
                <option value="sales">Sales</option>
            </select>
        </div>

        <button @click="openAddModal = true"
            class="bg-[#3fa9f3] text-white px-4 py-2 rounded-lg shadow hover:bg-blue-500 transition">
            + Add User
        </button>
    </div>

    {{-- Table --}}
    <table class="min-w-full table-auto border border-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2">No</th>
                <th class="border px-4 py-2">NAMA</th>
                <th class="border px-4 py-2">EMAIL</th>
                <th class="border px-4 py-2">ROLE</th>
                <th class="border px-4 py-2">CREATED_AT</th>
                <th class="border px-4 py-2">STATUS</th>
                <th class="border px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td class="border px-4 py-2 text-center">{{ $loop->iteration }}</td>
                    <td class="border px-4 py-2">{{ $user->NAMA }}</td>
                    <td class="border px-4 py-2">{{ $user->EMAIL }}</td>
                    <td class="border px-4 py-2">{{ $user->ROLE }}</td>
                    <td class="border px-4 py-2">
                        {{ \Carbon\Carbon::parse($user->CREATED_AT)->translatedFormat('d F Y') }}
                    </td>
                    <td class="border px-4 py-2 text-center">
                        @if($user->STATUS == 'nonaktif')
                            <span class="text-red-500 font-semibold">Nonaktif</span>
                        @else
                            <span class="text-green-600 font-semibold">Aktif</span>
                        @endif
                    </td>
                    <td class="border px-4 py-2 text-center space-x-2">
                        {{-- Tombol Edit --}}
                        <button 
                            @click="openEditModal = true; selectedUser = {{ $user->toJson() }}" 
                            class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500">
                            Edit
                        </button>

                        {{-- Tombol Nonaktif --}}
                        @if($user->STATUS == 'aktif')
                            <form action="{{ route('nonaktifuser.admin') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="ID_USER" value="{{ $user->ID_USER }}">
                                <button type="submit"
                                    onclick="return confirm('Yakin ingin menonaktifkan user ini?')"
                                    class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                    Nonaktif
                                </button>
                            </form>
                        @else
                            <span class="text-gray-400 italic">Sudah nonaktif</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>



    {{-- Modal Add User --}}
    <div x-show="openAddModal" class="fixed inset-0 flex items-center justify-center z-50">
        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" @click="openAddModal = false"></div>

        <div class="bg-white p-6 rounded-lg w-full max-w-lg shadow-lg relative z-10">
            <h2 class="text-xl font-semibold mb-4">Add User</h2>

            <form action="{{ route('storeuser.admin') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium">Nama</label>
                    <input type="text" name="NAMA" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Email</label>
                    <input type="email" name="EMAIL" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Password</label>
                    <input type="text" name="PASSWORD" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Role</label>
                    <select name="ROLE" class="w-full border rounded px-3 py-2" required>
                        <option value="admin">Admin</option>
                        <option value="gate">Gate</option>
                        <option value="sales">Sales</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="openAddModal = false"
                        class="px-4 py-2 border rounded">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-[#3fa9f3] text-white rounded">Save</button>
                </div>
            </form>

            <button @click="openAddModal = false"
                class="absolute top-2 right-2 text-gray-500 hover:text-black">✕</button>
        </div>
    </div>

    {{-- Modal Edit User --}}
    <div x-show="openEditModal" class="fixed inset-0 flex items-center justify-center z-50">
        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" @click="openEditModal = false"></div>

        <div class="bg-white p-6 rounded-lg w-full max-w-lg shadow-lg relative z-10">
            <h2 class="text-xl font-semibold mb-4">Edit User</h2>

            <form action="{{ route('updateuser.admin') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="ID_USER" :value="selectedUser.ID_USER">

                <div>
                    <label class="block text-sm font-medium">Nama</label>
                    <input type="text" name="NAMA" class="w-full border rounded px-3 py-2" 
                        :value="selectedUser.NAMA" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Email</label>
                    <input type="email" name="EMAIL" class="w-full border rounded px-3 py-2" 
                        :value="selectedUser.EMAIL" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Password</label>
                    <input type="text" name="PASSWORD" class="w-full border rounded px-3 py-2"
                        x-model="selectedUser.PASSWORD" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Role</label>
                    <select name="ROLE" class="w-full border rounded px-3 py-2" required>
                        <option value="admin" :selected="selectedUser.ROLE === 'admin'">Admin</option>
                        <option value="gate" :selected="selectedUser.ROLE === 'gate'">Gate</option>
                        <option value="sales" :selected="selectedUser.ROLE === 'sales'">Sales</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="openEditModal = false"
                        class="px-4 py-2 border rounded">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-500 text-white rounded">Update</button>
                </div>
            </form>

            <button @click="openEditModal = false"
                class="absolute top-2 right-2 text-gray-500 hover:text-black">✕</button>
        </div>
    </div>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
@endsection
