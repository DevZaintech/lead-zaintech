<table class="min-w-full table-auto border-collapse">
    <thead class="bg-gray-100">
        <tr>
            <th class="border p-2 text-center" style="width:3%">NO</th>
            <th class="border p-2 text-center">TANGGAL</th>
            <th class="border p-2 text-center">NAMA/KOTA</th>
            <!-- <th class="border p-2 text-center">Kota</th> -->
            <th class="border p-2 text-center">TELP</th>
            <th class="border p-2 text-center">KEBUTUHAN</th>
            <th class="border p-2 text-center">SALES</th>
            <th class="border p-2 text-center">SOURCE</th>
            <th class="border p-2 text-center">STATUS</th>
            <th class="border p-2 text-center"> </th>
        </tr>
    </thead>
    <tbody>
        @forelse ($lead as $index => $item)
            <tr>
                <td class="border p-2 text-center">{{ $lead->firstItem() + $index }}</td>
                @php
                    $bulanIndo = [
                        1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
                    ];
                    $tanggal = \Carbon\Carbon::parse($item->CREATED_AT);
                @endphp
                <td class="border p-2 text-center">{{ $tanggal->format('d') }} {{ $bulanIndo[$tanggal->format('n')] }} {{ $tanggal->format('Y') }}</td>
                <td class="border p-2 text-center">{{ $item->NAMA ?? '-' }} - {{ $item->kota->name ?? '-' }}</td>
                <!-- <td class="border p-2 text-center">{{ $item->kota->name ?? '-' }}</td> -->
                <td class="border p-2 text-center">{{ $item->NO_TELP }}</td>
                <td class="border p-2 text-center">{{ $item->sub_kategori->NAMA ?? '-' }}</td>
                <td class="border p-2 text-center">{{ $item->user->NAMA ?? '-' }}</td>
                <td class="border p-2 text-center">{{ $item->LEAD_SOURCE ?? '-' }}</td>
                <td class="border p-2 text-center">
                <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2 py-1 rounded text-sm font-medium {{ $item->stage_class }}">
                            {{ $item->stage_label }}
                        </span>
                    </div>
                </td>

                <td class="border p-2 text-center">
                    <a href="{{ route('lead.gate.detail', ['lead_id' => $item->LEAD_ID]) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-3 py-1 rounded transition-colors duration-200">
                            Detail
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="border p-2 text-center text-gray-500">Belum ada data</td>
            </tr>
        @endforelse
    </tbody>
</table>
@if ($lead->hasPages())
    <div class="flex justify-between items-center mt-4 text-sm text-gray-600">
        <div>
            Showing 
            <span class="font-semibold">{{ $lead->firstItem() }}</span>
            to 
            <span class="font-semibold">{{ $lead->lastItem() }}</span>
            of 
            <span class="font-semibold">{{ $lead->total() }}</span> results
        </div>

        <nav class="flex items-center space-x-1">
            {{-- Tombol Previous --}}
            @if ($lead->onFirstPage())
                <span class="px-3 py-1 rounded bg-gray-200 text-gray-400 cursor-not-allowed">&laquo;</span>
            @else
                <a href="{{ $lead->previousPageUrl() }}" 
                   class="px-3 py-1 rounded bg-blue-500 text-white hover:bg-blue-600">&laquo;</a>
            @endif

            {{-- Number Page --}}
            @foreach ($lead->getUrlRange(1, $lead->lastPage()) as $page => $url)
                @if ($page == $lead->currentPage())
                    <span class="px-3 py-1 rounded bg-blue-600 text-white font-bold">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" 
                       class="px-3 py-1 rounded bg-gray-200 text-gray-700 hover:bg-blue-500 hover:text-white">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Tombol Next --}}
            @if ($lead->hasMorePages())
                <a href="{{ $lead->nextPageUrl() }}" 
                   class="px-3 py-1 rounded bg-blue-500 text-white hover:bg-blue-600">&raquo;</a>
            @else
                <span class="px-3 py-1 rounded bg-gray-200 text-gray-400 cursor-not-allowed">&raquo;</span>
            @endif
        </nav>
    </div>
@endif

