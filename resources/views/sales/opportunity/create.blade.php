@extends('layouts.frontend')

@section('content')
<div class="space-y-6">
    <div class="w-full lg:w-[98%] mx-auto bg-white p-8 rounded shadow">
        <h2 class="text-2xl font-semibold mb-6">Detail Lead</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Kiri -->
            <table class="w-full border border-gray-400 border-collapse">
                <tbody>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 w-1/3 border border-gray-400"><b>LEAD ID</b></td>
                        <td class="px-3 py-2 border border-gray-400">{{ $lead->LEAD_ID }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-400"><b>NAMA CUSTOMER</b></td>
                        <td class="px-3 py-2 border border-gray-400">{{ $lead->NAMA }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-400"><b>KATEGORI CUSTOMER</b></td>
                        <td class="px-3 py-2 border border-gray-400">
                            @if($lead->KATEGORI === 'INDIVIDU')
                                {{ $lead->KATEGORI }}
                            @elseif($lead->KATEGORI === 'COMPANY')
                                {{ $lead->KATEGORI }} - {{ $lead->PERUSAHAAN ?? '-' }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-400"><b>SALES HANDLE</b></td>
                        <td class="px-3 py-2 border border-gray-400">
                            {{ $lead->user->NAMA }}
                        </td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-400"><b>KEBUTUHAN</b></td>
                        <td class="px-3 py-2 border border-gray-400">{{ $lead->sub_kategori->NAMA }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Kanan -->
            <table class="w-full border border-gray-400 border-collapse">
                <tbody>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 w-1/3 border border-gray-400"><b>TANGGAL LEAD</b></td>
                        <td class="px-3 py-2 border border-gray-400">{{ \Carbon\Carbon::parse($lead->CREATED_AT)->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-400"><b>TELEPON</b></td>
                        <td class="px-3 py-2 border border-gray-400">{{ $lead->NO_TELP }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-400"><b>KOTA</b></td>
                        <td class="px-3 py-2 border border-gray-400">{{ $lead->kota->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-400"><b>LEAD SOURCE</b></td>
                        <td class="px-3 py-2 border border-gray-400">{{ $lead->LEAD_SOURCE }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 border border-gray-400"><b>STATUS</b></td>
                        <td class="px-3 py-2 border border-gray-400">
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2 py-1 rounded text-sm font-medium {{ $lead->stage_class }}">
                                {{ $lead->stage_label }}
                            </span>
                        </div>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
        <!-- Catatan full width -->
        <div class="mt-6">
            <table class="w-full border border-gray-400 border-collapse">
                <tbody>
                    <tr>
                        <td class="bg-gray-100 px-3 py-2 w-1/6 border border-gray-400 align-top"><b>CATATAN</b></td>
                        <td class="px-3 py-2 border border-gray-400">
                            <textarea class="w-full border-gray-400 rounded-md" rows="2" readonly>{{ $lead->NOTE }}</textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-6 w-full lg:w-[98%] mx-auto">
        <!-- Tab Button -->
        <div class="flex border-b mb-6">
            <button data-tab-button onclick="openTab('opportunityTab', this)"
                class="px-4 py-2 -mb-px border-b-2 font-medium text-gray-600 border-transparent">
                Create Opportunity
            </button>
            <button data-tab-button onclick="openTab('followupTab', this)"
                class="px-4 py-2 -mb-px border-b-2 font-medium text-gray-600 border-transparent">
                Follow Up
            </button>
        </div>

        <!-- Tab: Opportunity -->
        <div id="opportunityTab" class="tab-content bg-white p-8 rounded shadow">
            <h2 class="text-2xl font-semibold mb-6">Create Opportunity</h2>

            <form action="{{ route('opportunity.store') }}" method="POST">
                @csrf
                <input type="hidden" name="LEAD_ID" value="{{ $lead->LEAD_ID }}">

                {{-- FORM FIELDS --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium mb-1">NILAI PROSPECT</label>
                        <input type="text" name="NILAI_PROSPECT"
                            class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">PROSENTASE PROSPECT</label>
                        <input type="text" 
                            name="PROSENTASE_PROSPECT"
                            class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 percent-input"
                            required>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium mb-1">CATATAN</label>
                    <textarea name="NOTE" rows="4"
                        class="w-full border border-gray-400 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                </div>

                {{-- PRODUK TABLE --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2">ITEM PRODUK</label>
                    <table class="w-full border border-gray-400 mt-4 text-sm">
                        <thead>
                            <tr>
                                <th class="border border-gray-400 p-2">NAMA PRODUK</th>
                                <th class="border border-gray-400 p-2">SKU</th>
                                <th class="border border-gray-400 p-2">QTY</th>
                                <th class="border border-gray-400 p-2">PRICE</th>
                                <th class="border border-gray-400 p-2">TOTAL</th>
                                <th class="border border-gray-400 p-2"></th>
                            </tr>
                        </thead>
                        <tbody id="produk-body">
                            <tr>
                                <td class="border border-gray-400 p-2">
                                    <select name="produk[0][ID_PRODUK]" class="produk-select w-full" required></select>
                                </td>
                                <td class="border border-gray-400 p-2">
                                    <input type="text" name="produk[0][SKU]" class="sku-input w-full border border-gray-400 px-2 py-1" readonly>
                                </td>
                                <td class="border border-gray-400 p-2">
                                    <input type="number" name="produk[0][QTY]" class="qty-input w-full border border-gray-400 px-2 py-1" value="1" min="1" required>
                                </td>
                                <td class="border border-gray-400 p-2">
                                    <input type="text" name="produk[0][PRICE]" class="price-input w-full border border-gray-400 px-2 py-1" value="0">
                                </td>
                                <td class="border border-gray-400 p-2">
                                    <input type="text" class="total-input w-full border border-gray-400 px-2 py-1" readonly>
                                    <input type="hidden" name="produk[0][TOTAL]" class="total-hidden" value="0">
                                </td>
                                <td class="border border-gray-400 p-2 text-center">
                                    <button type="button" class="remove-row text-red-500">✖</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" id="add-row" class="mt-2 px-3 py-1 bg-blue-500 text-white rounded">+ Tambah Produk</button>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ url()->previous() }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">Simpan</button>
                </div>
            </form>
        </div>

        <!-- Tab: Follow Up -->
        <div id="followupTab" class="tab-content hidden bg-white p-8 rounded shadow">
            <h2 class="text-2xl font-semibold mb-6">Follow Up</h2>

            <form action="{{ route('follow.store') }}" method="POST">
                @csrf
                <input type="hidden" name="LEAD_ID" value="{{ $lead->LEAD_ID }}">

                <table class="w-full table-fixed border-collapse border border-gray-500 text-sm mb-4">
                    <thead class="bg-gray-100 text-gray-700 uppercase">
                        <tr>
                            <th class="border border-gray-500 px-3 py-2">TANGGAL FU</th>
                            <th class="border border-gray-500 px-3 py-2">RESPON</th>
                            <th class="border border-gray-500 px-3 py-2">KETERANGAN</th>
                        </tr>
                    </thead>
                    <tbody id="followup-body">
                        @forelse($followups as $fu)
                            <tr class="odd:bg-white even:bg-gray-50" data-id="{{ $fu->ID_FOLLOW }}">
                                <td class="border border-gray-400 px-3 py-2">{{ \Carbon\Carbon::parse($fu->TGL_FOLLOW)->translatedFormat('d F Y') }}</td>
                                <td class="border border-gray-400 px-3 py-2 editable" data-field="RESPON" contenteditable="true">{{ $fu->RESPON }}</td>
                                <td class="border border-gray-400 px-3 py-2 editable" data-field="KETERANGAN" contenteditable="true">{{ $fu->KETERANGAN }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="border border-gray-400 px-3 py-2 text-center text-gray-500">BELUM ADA FOLLOW UP</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <button type="button" id="add-row-fu" class="mt-2 px-3 py-1 bg-blue-500 text-white rounded">+ Tambah Follow Up</button>

                <div class="flex justify-end space-x-3 mt-4">
                    <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded">Simpan Follow Up</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    function openTab(tabId, element) {
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
        document.querySelectorAll('[data-tab-button]').forEach(btn => {
            btn.classList.remove('border-blue-500', 'text-blue-600');
            btn.classList.add('text-gray-600', 'border-transparent');
        });
        document.getElementById(tabId).classList.remove('hidden');
        element.classList.remove('text-gray-600', 'border-transparent');
        element.classList.add('border-blue-500', 'text-blue-600');
    }
    window.openTab = openTab;

    // Default buka tab (Follow Up jika ada data, kalau tidak Opportunity)
    @if($followups->count() > 0)
        document.querySelectorAll('[data-tab-button]')[1].click();
    @else
        document.querySelectorAll('[data-tab-button]')[0].click();
    @endif
});
</script>
<script>
document.querySelectorAll('.editable').forEach(td => {
    td.addEventListener('blur', function(){
        const value = this.innerText.trim();
        const field = this.dataset.field;
        const ID_FOLLOW = this.closest('tr').dataset.id;

        fetch(`/follow/update/${ID_FOLLOW}`, {
            method: 'POST',
            headers: {
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':'{{ csrf_token() }}'
            },
            body: JSON.stringify({field, value})
        }).then(res => res.json())
        .then(data => {
            if(data.status !== 'success') alert('Update gagal!');
        });
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function openTab(tabId, element) {
        // Sembunyikan semua tab
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });

        // Reset style semua tombol
        document.querySelectorAll('[data-tab-button]').forEach(btn => {
            btn.classList.remove('border-blue-500', 'text-blue-600');
            btn.classList.add('text-gray-600', 'border-transparent');
        });

        // Tampilkan tab yang dipilih
        const activeTab = document.getElementById(tabId);
        if (activeTab) {
            activeTab.classList.remove('hidden');
        }

        // Aktifkan tombol yang dipilih
        element.classList.remove('text-gray-600', 'border-transparent');
        element.classList.add('border-blue-500', 'text-blue-600');
    }

    // Biar bisa dipakai di onclick
    window.openTab = openTab;

    // Default buka tab pertama
    const defaultBtn = document.querySelector('[data-tab-button]');
    if (defaultBtn) {
        defaultBtn.click();
    }
});
</script>



<script>
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
<style>
    .select2-container .select2-selection--single {
        height: 2.5rem !important;
        border: 1px solid #d1d5db !important;
        border-radius: 0.375rem !important;
        padding: 0.25rem 0.75rem !important;
        display: flex !important;
        align-items: center !important;
        background-color: #fff !important;
        box-shadow: none !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #111827 !important;
        line-height: 1.5rem !important;
        padding-left: 0 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100% !important;
        top: 0 !important;
        right: 0.75rem !important;
    }
</style>

<script>
$(document).on('input', '.percent-input', function () {
    // Ambil hanya angka
    let raw = this.value.replace(/\D/g, '');
    
    // Batasi 0-100
    if (parseInt(raw) > 100) raw = '100';

    // Tampilkan dengan simbol %
    this.value = raw ? raw + '%' : '';
    
    // Simpan angka murni di data attribute (buat nanti submit)
    $(this).data('raw', raw);
});

$('form').on('submit', function () {
    // Saat submit, ganti value jadi angka murni
    $('.percent-input').each(function () {
        this.value = $(this).data('raw') || '0';
    });
});
</script>

<script>
$(document).ready(function () {
    let rowIdx = 0;
    let manualEdit = false;

    function formatRupiah(angka) {
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Init Select2
    function initSelect2(selector) {
        selector.select2({
            placeholder: 'Pilih produk',
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('get.produk.sales') }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { q: params.term || '' };
                },
                processResults: function (data) {
                    return {
                        results: data.map(function (item) {
                            return {
                                id: item.ID_PRODUK,
                                text: item.NAMA,
                                SKU: item.SKU
                            };
                        })
                    };
                },
                cache: true
            }
        }).on('select2:open', function () {
            $(".select2-search__field").trigger('input');
        }).on('select2:select', function (e) {
            let selected = e.params.data;
            let $row = $(this).closest('tr');
            $row.find('.sku-input').val(selected.SKU);
        });
    }
    initSelect2($('.produk-select'));

    // Add Row
    $('#add-row').on('click', function () {
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
                    <input type="text" name="produk[${rowIdx}][PRICE]" value="0" class="price-input w-full border border-gray-400 px-2 py-1">
                </td>
                <td class="border border-gray-400 p-2">
                    <input type="text" class="total-input w-full border border-gray-400 px-2 py-1" readonly>
                    <input type="hidden" name="produk[${rowIdx}][TOTAL]" value="0" class="total-hidden">
                </td>
                <td class="border border-gray-400 p-2 text-center">
                    <button type="button" class="remove-row text-red-500">✖</button>
                </td>
            </tr>
        `;
        $('#produk-body').append(newRow);
        initSelect2($(`#produk-body tr:last-child .produk-select`));
    });

    // Remove Row
    $('#produk-body').on('click', '.remove-row', function () {
        $(this).closest('tr').remove();
        updateNilaiProspectFromTable();
    });

    // Input Harga → format & simpan raw
    $(document).on('input', '.price-input', function () {
        let raw = $(this).val().replace(/\D/g, '').replace(/^0+/, '') || '0';
        $(this).data('raw', raw);
        $(this).val(formatRupiah(raw));
        updateRowTotal($(this).closest('tr'));
    });

    // Input Qty
    $(document).on('input', '.qty-input', function () {
        this.value = this.value.replace(/\D/g, '');
        if (this.value === '' || parseInt(this.value) < 1) this.value = 1;
        updateRowTotal($(this).closest('tr'));
    });

    // Hitung Total per baris
    function updateRowTotal($row) {
        let qty = parseInt($row.find('.qty-input').val()) || 0;
        let rawPrice = $row.find('.price-input').data('raw') || '0';
        let total = qty * parseInt(rawPrice);
        $row.find('.total-input').val(formatRupiah(String(total)));
        $row.find('.total-hidden').val(total);
        updateNilaiProspectFromTable();
    }

    // Hitung Nilai Prospect dari semua total-hidden
    // function updateNilaiProspectFromTable() {
    //     if (manualEdit) return;
    //     let totalSemua = 0;
    //     $('.total-hidden').each(function () {
    //         totalSemua += parseInt($(this).val() || 0);
    //     });
    //     let $np = $('[name="NILAI_PROSPECT"]');
    //     $np.data('raw', String(totalSemua));
    //     $np.val(formatRupiah(String(totalSemua)));
    // }

    // Input manual Nilai Prospect
    $(document).on('input', '[name="NILAI_PROSPECT"]', function () {
        manualEdit = true;
        let raw = this.value.replace(/\D/g, '').replace(/^0+/, '') || '0';
        $(this).data('raw', raw);
        this.value = formatRupiah(raw);
    }).on('blur', '[name="NILAI_PROSPECT"]', function () {
        if (this.value.trim() === '') {
            $(this).data('raw', '0');
            this.value = '0';
        }
        manualEdit = false;
    });

    // Saat submit
    $('form').on('submit', function () {
        let $np = $('[name="NILAI_PROSPECT"]');
        $np.val($np.data('raw') || '0');
        $('.price-input').each(function () {
            $(this).val($(this).data('raw') || '0');
        });
        $('.total-input').each(function () {
            let text = $(this).val().replace(/\D/g, '') || '0';
            $(this).val(text);
        });
    });

    // Hitung awal
    updateNilaiProspectFromTable();
});
</script>
@endsection
