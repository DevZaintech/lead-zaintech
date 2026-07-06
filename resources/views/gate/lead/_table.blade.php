<div class="desktop-table">
    <table class="min-w-full table-auto border-collapse">
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-2 text-center" style="width:3%">NO</th>
                <th class="border p-2 text-center">TANGGAL LEAD</th>
                <th class="border p-2 text-center">TANGGAL DEAL</th>
                <th class="border p-2 text-center">NAMA/KOTA</th>
                <!-- <th class="border p-2 text-center">Kota</th> -->
                <th class="border p-2 text-center">TELP</th>
                <th class="border p-2 text-center">KEBUTUHAN</th>
                <th class="border p-2 text-center">SALES</th>
                <th class="border p-2 text-center">SOURCE</th>
                <th class="border p-2 text-center">KATEGORI</th>
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
                        $deal = \Carbon\Carbon::parse($item->UPDATED_AT);
                    @endphp
                    <td class="border p-2 text-center">{{ $tanggal->format('d') }} {{ $bulanIndo[$tanggal->format('n')] }} {{ $tanggal->format('Y') }}</td>
                    <td class="border p-2 text-center">
                        @if($item->STATUS == 'converted')
                            {{ $deal->format('d') }} {{ $bulanIndo[$deal->format('n')] }} {{ $deal->format('Y') }}
                        @else
                            Belum
                        @endif
                    </td>
                    <td class="border p-2 text-center">{{ $item->NAMA ?? '-' }} - {{ $item->kota->name ?? '-' }}</td>
                    <!-- <td class="border p-2 text-center">{{ $item->kota->name ?? '-' }}</td> -->
                    <td class="border p-2 text-center">{{ $item->NO_TELP }}</td>
                    <td class="border p-2 text-center">{{ $item->sub_kategori->NAMA ?? '-' }}</td>
                    <td class="border p-2 text-center">{{ $item->user->NAMA ?? '-' }}</td>
                    <td class="border p-2 text-center">{{ $item->LEAD_SOURCE ?? '-' }}</td>
                    <td class="border p-2 text-center">{{ $item->KATEGORI_CUST ?? '-' }}</td>
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

</div>

<div class="mobile-card-list">

    @forelse ($lead as $item)

        @php
            $bulanIndo = [
                1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
            ];

            $tanggal = \Carbon\Carbon::parse($item->CREATED_AT);
        @endphp

        <div class="bg-white rounded-lg shadow p-4">

            <div class="flex justify-between items-start mb-2">
                <div class="font-semibold text-gray-900">
                    {{ $item->NAMA ?? '(TANPA NAMA)' }}
                </div>

                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $item->stage_class }}">
                    {{ $item->stage_label }}
                </span>
            </div>

            <div class="text-sm text-gray-600 mb-1">
                {{ $item->kota->name ?? '-' }}
            </div>

            <div class="text-sm mb-1">
                {{ $item->NO_TELP }}
            </div>

            <div class="text-sm mb-1">
                {{ $tanggal->format('d') }} {{ $bulanIndo[$tanggal->format('n')] }} {{ $tanggal->format('Y') }}
            </div>

            <div class="text-sm mb-1">
                <span class="inline-block w-24">Kebutuhan</span>: {{ $item->sub_kategori->NAMA ?? '-' }}
            </div>

            <div class="text-sm mb-1">
                <span class="inline-block w-24">Sales</span>: {{ $item->user->NAMA ?? '-' }}
            </div>

            <div class="text-sm mb-3">
                <span class="inline-block w-24">Source</span>: {{ $item->LEAD_SOURCE ?? '-' }}
            </div>
            <div class="text-sm mb-3">
                <span class="inline-block w-24">Kategori</span>: {{ $item->KATEGORI_CUST ?? '-' }}
            </div>

            <a href="{{ route('lead.gate.detail', ['lead_id' => $item->LEAD_ID]) }}"
               class="block w-full text-center bg-blue-600 text-white py-2 rounded">
                Detail
            </a>

        </div>

    @empty

        <div class="bg-white rounded-lg p-4 text-center text-gray-500">
            Belum ada data
        </div>

    @endforelse

</div>

@if ($lead->hasPages())
    <div class="mt-4 text-sm text-gray-600">

        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3">

            <div class="text-center md:text-left">
                Showing
                <span class="font-semibold">{{ $lead->firstItem() }}</span>
                to
                <span class="font-semibold">{{ $lead->lastItem() }}</span>
                of
                <span class="font-semibold">{{ $lead->total() }}</span>
                results
            </div>

            <nav class="flex flex-wrap justify-center md:justify-end items-center gap-1 pagination">

                {{-- Previous --}}
                @if ($lead->onFirstPage())
                    <span class="px-3 py-1 rounded bg-gray-200 text-gray-400 cursor-not-allowed">
                        &laquo;
                    </span>
                @else
                    <a href="{{ $lead->previousPageUrl() }}"
                       class="px-3 py-1 rounded bg-blue-500 text-white hover:bg-blue-600">
                        &laquo;
                    </a>
                @endif

                {{-- Numbered Pages --}}
                @foreach ($lead->getUrlRange(1, $lead->lastPage()) as $page => $url)
                    @if ($page == $lead->currentPage())
                        <span class="px-3 py-1 rounded bg-blue-600 text-white font-bold">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           class="px-3 py-1 rounded bg-gray-200 text-gray-700 hover:bg-blue-500 hover:text-white">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($lead->hasMorePages())
                    <a href="{{ $lead->nextPageUrl() }}"
                       class="px-3 py-1 rounded bg-blue-500 text-white hover:bg-blue-600">
                        &raquo;
                    </a>
                @else
                    <span class="px-3 py-1 rounded bg-gray-200 text-gray-400 cursor-not-allowed">
                        &raquo;
                    </span>
                @endif

            </nav>

        </div>

    </div>
@endif

