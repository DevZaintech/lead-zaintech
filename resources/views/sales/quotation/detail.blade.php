@extends('layouts.frontend')
@section('css')
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
<div class="space-y-6">
    <div class="w-full max-w-[90%] mx-auto bg-white p-8 rounded shadow space-y-8">
        <!-- Judul -->
        <h2 class="text-2xl font-semibold">DETAIL QUOTATION</h2>

        <!-- Grid Detail -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kiri -->
            <table class="w-full border border-gray-300 border-collapse">
                <tbody>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 w-1/3 border border-gray-300"><b>QUOTATION ID</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ $quo->QUO_ID }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>NAMA CUSTOMER</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ $quo->opportunity->lead->NAMA }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>KATEGORI CUSTOMER</b></td>
                        <td class="px-3 py-2 border border-gray-300">
                            @if($quo->opportunity->lead->KATEGORI === 'INDIVIDU')
                                {{ $quo->opportunity->lead->KATEGORI }}
                            @elseif($quo->opportunity->lead->KATEGORI === 'COMPANY')
                                {{ $quo->opportunity->lead->KATEGORI }} - {{ $quo->opportunity->lead->PERUSAHAAN ?? '-' }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>LEAD SOURCE</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ $quo->opportunity->lead->LEAD_SOURCE }}</td>
                    </tr>
                   
                </tbody>
            </table>

            <!-- Kanan -->
            <table class="w-full border border-gray-300 border-collapse">
                <tbody>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 w-1/3 border border-gray-300"><b>TANGGAL QUOTATION</b></td>
                        <td class="px-3 py-2 border border-gray-300">
                            {{ \Carbon\Carbon::parse($quo->CREATED_AT)->translatedFormat('d F Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>TELEPON</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ $quo->opportunity->lead->NO_TELP }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>KOTA</b></td>
                        <td class="px-3 py-2 border border-gray-300">{{ $quo->opportunity->lead->kota->name }}</td>
                    </tr>
                    
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-300"><b>STATUS</b></td>
                        <td class="px-3 py-2 border border-gray-300">
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2 py-1 rounded text-sm font-medium {{ $quo->opportunity->lead->stage_class }}">
                                {{ $quo->opportunity->lead->stage_label }}
                            </span>
                        </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Catatan -->
        <div class="mt-6">
            <table class="w-full border border-gray-300 border-collapse">
                <tbody>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 w-1/6 border border-gray-300 align-top"><b>CATATAN</b></td>
                        <td class="px-3 py-2 border border-gray-300">
                            <textarea class="w-full border-gray-300 rounded-md" rows="2" readonly>{{ $opp->NOTE }}</textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-6">
        <div class="w-full max-w-[90%] mx-auto bg-white p-8 rounded shadow space-y-8">
            <!-- Tabs -->
            <div class="flex border-b mb-6">
                <button class="px-4 py-2 -mb-px border-b-2 font-medium text-blue-600 border-blue-600" onclick="openTab('quotationTab', this)">Edit Quotation</button>
                <button class="px-4 py-2 -mb-px border-b-2 font-medium text-gray-600 border-transparent" onclick="openTab('followupTab', this)">Follow Up</button>
            </div>

            <!-- Tab Contents -->
            <div>
                <!-- Edit Quotation Tab -->
                <div id="quotationTab" class="tab-content block">
                    <h2 class="text-2xl font-semibold mb-6">Edit Quotation</h2>
                    <form action="{{ route('quotation.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="OPPORTUNITY_ID" value="{{ $opp->OPPORTUNITY_ID }}" readonly class="w-full border border-gray-400 rounded px-3 py-2 bg-gray-100 text-gray-600" required>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium mb-1">NILAI PROSPECT <span class="text-red-500">*</span></label>
                                <input type="text" id="NILAI_PROSPECT_QUO" name="NILAI_PROSPECT" value="{{ $opp->NILAI_PROSPECT }}" class="w-full border border-gray-400 rounded px-3 py-2 text-gray-600" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">PROSENTASE PROSPECT <span class="text-red-500">*</span></label>
                                <input type="text" id="PROSENTASE_QUO" name="PROSENTASE_PROSPECT" value="{{ $opp->PROSENTASE_PROSPECT }}%" class="w-full border border-gray-400 rounded px-3 py-2 text-gray-600" required>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-2">ITEM TABLE</label>
                            <table class="w-full border border-gray-400 mt-4 text-sm">
                                <thead>
                                    <tr>
                                        <th class="border border-gray-400 p-2 w-[30%]">Nama Produk</th>
                                        <th class="border border-gray-400 p-2">SKU</th>
                                        <th class="border border-gray-400 p-2">Qty</th>
                                        <th class="border border-gray-400 p-2">Price</th>
                                        <th class="border border-gray-400 p-2">Total</th>
                                        <th class="border border-gray-400 p-2 w-[5%]"></th>
                                    </tr>
                                </thead>
                                <tbody id="produk-body-quo">
                                    @php $rowIdxQuo = 0; @endphp
                                    @foreach($item as $i)
                                        <tr>
                                            <input type="hidden" name="produk[{{ $rowIdxQuo }}][ID_ITEM]" value="{{ $i->ID_ITEM }}">
                                            <td class="border border-gray-400 p-2">
                                                <select name="produk[{{ $rowIdxQuo }}][ID_PRODUK]" class="produk-select w-full">
                                                    <option value="{{ $i->produk->ID_PRODUK }}" selected>{{ $i->produk->NAMA }}</option>
                                                </select>
                                            </td>
                                            <td class="border border-gray-400 p-2">
                                                <input type="text" name="produk[{{ $rowIdxQuo }}][SKU]" value="{{ $i->produk->SKU }}" class="sku-input w-full border border-gray-400 px-2 py-1" readonly>
                                            </td>
                                            <td class="border border-gray-400 p-2">
                                                <input type="number" name="produk[{{ $rowIdxQuo }}][QTY]" value="{{ $i->QTY }}" min="1" class="qty-input w-full border border-gray-400 px-2 py-1">
                                            </td>
                                            <td class="border border-gray-400 p-2">
                                                <input type="text" name="produk[{{ $rowIdxQuo }}][PRICE]" value="{{ number_format($i->PRICE,0,',','.') }}" data-raw="{{ $i->PRICE }}" class="price-input w-full border border-gray-400 px-2 py-1">
                                            </td>
                                            <td class="border border-gray-400 p-2">
                                                <input type="text" class="total-input w-full border border-gray-400 px-2 py-1" value="{{ number_format($i->TOTAL,0,',','.') }}" readonly>
                                                <input type="hidden" name="produk[{{ $rowIdxQuo }}][TOTAL]" value="{{ $i->TOTAL }}" class="total-hidden">
                                            </td>
                                            <td class="border border-gray-400 p-2 text-center">
                                                <button type="button" class="remove-row text-red-500">✖</button>
                                            </td>
                                        </tr>
                                        @php $rowIdxQuo++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                            <button type="button" id="add-row-quo" class="mt-2 px-3 py-1 bg-blue-500 text-white rounded">+ Tambah Produk</button>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-1">SYARAT & KETENTUAN</label>
                            <textarea name="SNK" rows="4" class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">{{ $quo->SNK }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium mb-1">
                                    STATUS <span class="text-red-500">*</span>
                                </label>
                                <select id="STATUS" name="STATUS" 
                                    class="w-full border border-gray-400 rounded px-3 py-2 text-gray-600" required>
                                    
                                    <option value="quotation" {{ $quo->opportunity->lead->STATUS === 'quotation' ? 'selected' : '' }}>Hot</option>
                                    <option value="converted" {{ $quo->opportunity->lead->STATUS === 'converted' ? 'selected' : '' }}>Deal</option>
                                    <option value="lost" {{ $quo->opportunity->lead->STATUS === 'lost' ? 'selected' : '' }}>Lost</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">
                                    VALID DATE <span class="text-red-500">*</span>
                                </label>
                                <div class="flex w-full rounded overflow-hidden border border-gray-400">
                                    {{-- Bagian status --}}
                                    @if($quo->VALID_DATE && \Carbon\Carbon::parse($quo->VALID_DATE)->isSameDay(\Carbon\Carbon::today()))
                                        <div class="w-40 flex items-center justify-center bg-orange-500 text-white text-xs font-semibold px-3">
                                            EXPIRED TODAY
                                        </div>
                                    @elseif($quo->STATUS === 'OPEN')
                                        <div class="w-40 flex items-center justify-center bg-green-500 text-white text-xs font-semibold px-3">
                                            OPEN
                                        </div>
                                    @elseif($quo->STATUS === 'EXPIRED')
                                        <div class="w-40 flex items-center justify-center bg-red-500 text-white text-xs font-semibold px-3">
                                            EXPIRED
                                        </div>
                                    @else
                                        <div class="w-40 flex items-center justify-center bg-gray-400 text-white text-xs font-semibold px-3">
                                            {{ $quo->STATUS }}
                                        </div>
                                    @endif

                                    {{-- Bagian input tanggal --}}
                                    <input type="text"
                                        name="VALID_DATE"
                                        value="{{ $quo->VALID_DATE ? \Carbon\Carbon::parse($quo->VALID_DATE)->translatedFormat('d F Y') : '-' }}"
                                        class="flex-1 px-3 py-2 text-gray-600 text-sm focus:outline-none" readonly>
                                </div>
                            </div>

                        </div>

                        <div id="reasonField" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 hidden">
                            <div>
                                <label class="block text-sm font-medium mb-1">REASON <span class="text-red-500">*</span></label>
                                <select id="REASON" name="REASON" class="w-full border border-gray-400 rounded px-3 py-2 text-gray-600">
                                    <option value="">-- Pilih Reason --</option>
                                    <option value="INDEN">INDEN</option>
                                    <option value="SUDAH BELI DI VENDOR LAIN">SUDAH BELI DI VENDOR LAIN</option>
                                    <option value="HARGA TINGGI">HARGA TINGGI</option>
                                    <option value="LOKASI TERLALU JAUH">LOKASI TERLALU JAUH</option>
                                    <option value="PEMBAYARAN">PEMBAYARAN</option>
                                    <option value="STOCK KOSONG">STOCK KOSONG</option>
                                    <option value="NO RESPON">NO RESPON</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">Simpan</button>
                        </div>
                    </form>
                </div>

                <!-- Tab Contents -->
                <div id="followupTab" class="tab-content hidden">
                    <!-- Paste form Follow Up kamu di sini -->
                    <form action="{{ route('follow.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="OPPORTUNITY_ID" value="{{ $quo->OPPORTUNITY_ID }}" readonly class="w-full border border-gray-400 rounded px-3 py-2 bg-gray-100 text-gray-600">

                        <!-- Tab: Follow Up -->
                        <table class="w-full table-fixed border-collapse border border-gray-500 text-sm mb-4">
                            <thead class="bg-gray-100 text-gray-700 uppercase">
                                <tr>
                                    <th class="border border-gray-500 px-3 py-2 text-left w-1/6">TANGGAL FU</th>
                                    <th class="border border-gray-500 px-3 py-2 text-left w-2/6">RESPON</th>
                                    <th class="border border-gray-500 px-3 py-2 text-left w-3/6">KETERANGAN</th>
                                </tr>
                            </thead>
                            <tbody id="followup-body">
                                @forelse($followups as $fu)
                                    <tr class="odd:bg-white even:bg-gray-50">
                                        <td class="border border-gray-400 px-3 py-2 text-gray-700">
                                            {{ \Carbon\Carbon::parse($fu->TGL_FOLLOW)->translatedFormat('d F Y') }}
                                        </td>
                                        <td class="border border-gray-400 px-3 py-2 text-gray-700 whitespace-normal break-words">
                                            {{ $fu->RESPON }}
                                        </td>
                                        <td class="border border-gray-400 px-3 py-2 text-gray-700 whitespace-normal break-words">
                                            {{ $fu->KETERANGAN }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="border border-gray-400 px-3 py-2 text-center text-gray-500">
                                            BELUM ADA FOLLOW UP
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <button type="button" id="add-row-fu" class="mt-2 px-3 py-1 bg-blue-500 text-white rounded">
                            + Tambah Follow Up
                        </button>
                        <div class="flex justify-end space-x-3 mt-4">
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- jQuery & Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
function openTab(tabId, btn) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.flex > button').forEach(b => {
        b.classList.remove('text-blue-600', 'border-blue-600');
        b.classList.add('text-gray-600', 'border-transparent');
    });
    document.getElementById(tabId).classList.remove('hidden');
    btn.classList.add('text-blue-600', 'border-blue-600');
    btn.classList.remove('text-gray-600', 'border-transparent');
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('add-row-fu').addEventListener('click', function () {
        let tbody = document.getElementById('followup-body');
        let rowCount = tbody.querySelectorAll('tr').length;
        let row = document.createElement('tr');

        row.innerHTML = `
            <td class="border border-gray-400 p-2">
                <input type="date" name="followup[${rowCount}][TANGGAL_FOLLOW]" class="w-full border border-gray-500 px-2 py-1 rounded" required>
            </td>
            <td class="border border-gray-400 p-2">
                <textarea name="followup[${rowCount}][RESPON]" rows="2" class="w-full border border-gray-400 px-2 py-1" required></textarea>
            </td>
            <td class="border border-gray-400 p-2">
                <textarea name="followup[${rowCount}][PROGRESS]" rows="2" class="w-full border border-gray-400 px-2 py-1" required></textarea>
            </td>
        `;
        tbody.appendChild(row);
    });
});
</script>

<script>
function formatRupiah(angka) { 
    return angka.replace(/\B(?=(\d{3})+(?!\d))/g, "."); 
}

function initProdukTable($tbody, rowIdxStart, addBtnSelector){
    let rowIdx = rowIdxStart;

    function initSelect2ForRow($select){
        $select.select2({
            placeholder: 'Pilih produk',
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('get.produk.sales') }}',
                dataType: 'json',
                delay: 250,
                data: params => ({q: params.term || ''}),
                processResults: data => ({
                    results: data.map(i => ({id: i.ID_PRODUK, text: i.NAMA, SKU: i.SKU}))
                }),
                cache: true
            }
        }).on('select2:select', function(e){
            let selected = e.params.data;
            $(this).closest('tr').find('.sku-input').val(selected.SKU);
        });
    }

    // Init select2 untuk row existing
    $tbody.find('.produk-select').each(function(){ 
        initSelect2ForRow($(this)); 
    });

    // Format harga sebelum submit
    $tbody.closest('form').on('submit', function(){
        $tbody.find('.price-input').each(function(){ 
            $(this).val($(this).data('raw')); 
        });
    });

    // Hapus row
    $tbody.on('click', '.remove-row', function(){ 
        $(this).closest('tr').remove(); 
    });

    // Update harga
    $tbody.on('input', '.price-input', function(){
        let raw = $(this).val().replace(/\D/g,'')||'0';
        $(this).data('raw', raw);
        $(this).val(formatRupiah(raw));
        updateRowTotal($(this).closest('tr'));
    });

    // Update qty
    $tbody.on('input', '.qty-input', function(){ 
        updateRowTotal($(this).closest('tr')); 
    });

    function updateRowTotal($row){
        let qty = parseInt($row.find('.qty-input').val())||0;
        let price = parseInt($row.find('.price-input').data('raw'))||0;
        let total = qty*price;
        $row.find('.total-input').val(formatRupiah(String(total)));
        $row.find('.total-hidden').val(total);
    }

    // Tambah row baru
    $(addBtnSelector).on('click', function(){
        rowIdx++;
        let newRow = `
        <tr>
            <td class="border border-gray-400 p-2">
                <select name="produk[${rowIdx}][ID_PRODUK]" class="produk-select w-full"></select>
            </td>
            <td class="border border-gray-400 p-2">
                <input type="text" name="produk[${rowIdx}][SKU]" class="sku-input w-full border border-gray-400 px-2 py-1" readonly>
            </td>
            <td class="border border-gray-400 p-2">
                <input type="number" name="produk[${rowIdx}][QTY]" value="1" min="1" class="qty-input w-full border border-gray-400 px-2 py-1">
            </td>
            <td class="border border-gray-400 p-2">
                <input type="text" name="produk[${rowIdx}][PRICE]" class="price-input w-full border border-gray-400 px-2 py-1">
            </td>
            <td class="border border-gray-400 p-2">
                <input type="text" class="total-input w-full border border-gray-400 px-2 py-1" readonly>
                <input type="hidden" name="produk[${rowIdx}][TOTAL]" class="total-hidden">
            </td>
            <td class="border border-gray-400 p-2 text-center">
                <button type="button" class="remove-row text-red-500">✖</button>
            </td>
        </tr>`;
        $tbody.append(newRow);
        initSelect2ForRow($tbody.find('tr:last-child .produk-select'));
    });
}

$(document).ready(function(){
    // Init produk untuk Quotation
    initProdukTable($('#produk-body-quo'), {{ count($item) }}, '#add-row-quo');

    // Format input Rupiah & Persen
    function initInputFormatting(inputId, persenId=null){
        let input = document.getElementById(inputId);
        if(!input) return;
        input.value = formatRupiah(input.value);
        input.addEventListener('input', function(){
            const angka = input.value.replace(/\D/g,'');
            input.value = formatRupiah(angka);
        });
        if(persenId){
            let persenInput = document.getElementById(persenId);
            persenInput.addEventListener('input', function(){
                let val = this.value.replace(/\D/g,'');
                this.value = val? val+'%' : '';
            });
            persenInput.addEventListener('blur', function(){
                if(this.value && !this.value.includes('%')) this.value += '%';
            });
        }
    }

    initInputFormatting('NILAI_PROSPECT_OPP','PROSENTASE_OPP');
    initInputFormatting('NILAI_PROSPECT_QUO','PROSENTASE_QUO');

    // Tampilkan Reason kalau status = lost
    $('#STATUS').on('change', function() {
        if ($(this).val() === 'lost') {
            $('#reasonField').removeClass('hidden');
            $('#REASON').attr('required', true);
        } else {
            $('#reasonField').addClass('hidden');
            $('#REASON').removeAttr('required').val('');
        }
    });

    // Trigger awal (kalau status sudah lost waktu load page)
    if ($('#STATUS').val() === 'lost') {
        $('#reasonField').removeClass('hidden');
        $('#REASON').attr('required', true);
    }

});
</script>
@endsection
