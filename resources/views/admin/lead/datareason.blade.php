@extends('layouts.frontend')

@section('title', 'Data Reason Lost')

@section('content')
<div class="bg-white p-6 rounded-lg shadow w-full lg:w-[75%] mx-auto">
    <h2 class="text-lg font-semibold mb-4">Reason Lost Dinamis</h2>

    <form action="{{ route('reason.store') }}" method="POST" class="flex gap-2 mb-4">
        @csrf
        <input type="text" name="NAMA" placeholder="Nama Kategori"
               class="border rounded p-2 flex-1 focus:outline-none focus:ring-2 focus:ring-blue-400">
        <button type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Tambah
        </button>
    </form>

    <table class="min-w-full border text-sm">
        <thead class="bg-gray-100">
        <tr>
            <th class="border px-4 py-2 text-left">No</th>
            <th class="border px-4 py-2 text-left">Reason</th>
            <th class="border px-4 py-2 text-left">Status</th>
            <th class="border px-4 py-2 text-left">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @foreach($reason as $row)
            <tr class="hover:bg-gray-50">
                <td class="border px-4 py-2">{{ $row->ID_REASON }}</td>
                <td class="border px-4 py-2">{{ $row->REASON }}</td>
                @if (is_null($row->DELETED_AT))
                    <td class="border px-4 py-2 text-center">
                        <span class="bg-green-400 text-black text-sm px-3 py-1 rounded-full">
                            Display
                        </span>
                    </td>
                @else
                    <td class="border px-4 py-2 text-center">
                        <span class="bg-red-400 text-white text-sm px-3 py-1 rounded-full">
                            Not Display
                        </span>
                    </td>
                @endif
                
                <td class="border px-4 py-2 space-x-2">
                    <a href="{{ route('reason.edit', $row->ID_REASON) }}"
                    onclick="openEditModal(event, {{ $row->ID_REASON }}, '{{ addslashes($row->REASON) }}')"
                    class="bg-blue-400 hover:bg-blue-500 text-white px-3 py-1 rounded">
                        Edit
                    </a>

                    @if(is_null($row->DELETED_AT))
                    <a href="{{ route('reason.delete', $row->ID_REASON) }}"
                    onclick="return confirm('Apakah Anda yakin akan menghapus reason ini dari viewport?')"
                    class="bg-gray-400 hover:bg-gray-500 text-white px-3 py-1 rounded">
                        Delete
                    </a>
                    @else
                    <a href="{{ route('reason.undo', $row->ID_REASON) }}"
                    onclick="return confirm('Apakah Anda yakin akan menampilkan lagi ke viewport?')"
                    class="bg-blue-400 hover:bg-blue-500 text-white px-3 py-1 rounded">
                        Display
                    </a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-md p-6">
            <h2 class="text-lg font-semibold mb-4">Edit Reason</h2>

            <form method="POST" action="{{ route('reason.edit', 0) }}" id="editForm">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Reason</label>
                    <input type="text"
                        name="REASON"
                        id="editReason"
                        class="w-full border rounded px-3 py-2"
                        required>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button"
                            onclick="closeEditModal()"
                            class="px-4 py-2 bg-gray-300 rounded">
                        Batal
                    </button>

                    <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function openEditModal(e, id, reason) {
        e.preventDefault();

        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        const input = document.getElementById('editReason');

        // ganti angka 0 dengan ID asli
        form.action = form.action.replace('/0', '/' + id);
        input.value = reason;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditModal() {
        const modal = document.getElementById('editModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>

@endsection
