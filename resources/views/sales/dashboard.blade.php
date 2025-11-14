@extends('layouts.frontend')

@section('content')
<div class="p-6 space-y-6">

    {{-- Filter Tanggal + Source --}}
    <div class="bg-white p-6 rounded-2xl shadow">
        <form method="GET" action="{{ route('dashboard.sales') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate->toDateString() }}" class="mt-1 w-full border rounded p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ $endDate->toDateString() }}" class="mt-1 w-full border rounded p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Lead Source</label>
                <select name="source" class="mt-1 w-full border rounded p-2">
                    <option value="" {{ empty($source) ? 'selected' : '' }}>Semua</option>
                    <option value="Meta Ads" {{ $source == 'Meta Ads' ? 'selected' : '' }}>Meta Ads</option>
                    <option value="Google Ads" {{ $source == 'Google Ads' ? 'selected' : '' }}>Google Ads</option>
                    <option value="Youtube" {{ $source == 'Youtube' ? 'selected' : '' }}>Youtube</option>
                    <option value="Tiktok" {{ $source == 'Tiktok' ? 'selected' : '' }}>Tiktok</option>
                    <option value="Instagram" {{ $source == 'Instagram' ? 'selected' : '' }}>Instagram</option>
                    <option value="Facebook" {{ $source == 'Facebook' ? 'selected' : '' }}>Facebook</option>
                    <option value="Marketplace" {{ $source == 'Marketplace' ? 'selected' : '' }}>Marketplace</option>
                    <option value="Web" {{ $source == 'Web' ? 'selected' : '' }}>Web</option>
                    <option value="Sosmed Pribadi" {{ $source == 'Sosmed Pribadi' ? 'selected' : '' }}>Sosmed Sales</option>
                    <option value="Walk In" {{ $source == 'Walk In' ? 'selected' : '' }}>Walk In</option>
                    <option value="Direct" {{ $source == 'Direct' ? 'selected' : '' }}>Direct</option>
                    <option value="Exhibition" {{ $source == 'Exhibition' ? 'selected' : '' }}>Exhibition</option>
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    Filter
                </button>
                <a href="{{ route('dashboard.sales') }}" class="bg-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <div class="bg-blue-400 text-white rounded-2xl p-6 shadow">
            <h3 class="text-lg font-semibold">Total Lead</h3>
            <p class="text-3xl font-bold mt-2">{{ $total }}</p>
        </div>
        <div class="bg-orange-400 text-white rounded-2xl p-6 shadow">
            <h3 class="text-lg font-semibold">Opportunity</h3>
            <p class="text-3xl font-bold mt-2">{{ $opportunity }}</p>
        </div>
        <div class="bg-red-400 text-white rounded-2xl p-6 shadow">
            <h3 class="text-lg font-semibold">Quotation</h3>
            <p class="text-3xl font-bold mt-2">{{ $quotation }}</p>
        </div>
        <div class="bg-green-400 text-white rounded-2xl p-6 shadow">
            <h3 class="text-lg font-semibold">Converted</h3>
            <p class="text-3xl font-bold mt-2">{{ $converted }}</p>
        </div>
        <div class="bg-gray-400 text-white rounded-2xl p-6 shadow">
            <h3 class="text-lg font-semibold">Los</h3>
            <p class="text-3xl font-bold mt-2">{{ $lost }}</p>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Donut Chart -->
        <div class="bg-white p-6 rounded-2xl shadow flex flex-col items-center">
            <h3 class="text-lg font-bold mb-4">Distribusi Status</h3>
            <div class="relative w-80 h-80">
                <canvas id="pieChart"></canvas>
                <!-- <div id="pieTotal"
                    class="absolute inset-0 flex items-center justify-center text-3xl font-bold text-gray-700">
                    {{ $total }}
                </div> -->
            </div>
            <p class="mt-2 text-sm text-gray-500">Total Lead</p>
        </div>

        <!-- Bar Chart -->
        <div class="bg-white p-6 rounded-2xl shadow">
            <h3 class="text-lg font-bold mb-4">Perbandingan Status</h3>
            <canvas id="barChart"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Donut Chart
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: ['Opportunity', 'Quotation', 'Converted', 'Los', 'Cold'],
            datasets: [{
                data: [{{ $opportunity }}, {{ $quotation }}, {{ $converted }}, {{ $lost }}, {{ $cold }}],
                backgroundColor: [
                    '#FB923C',
                    '#F87171',
                    '#4ADE80',
                    '#9CA3AF',
                    '#60A5FA',
                ],
                borderWidth: 1
            }]
        },
        options: {
            cutout: '70%',
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Bar Chart
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: ['Total', 'Opportunity', 'Quotation', 'Converted', 'Los'],
            datasets: [{
                label: 'Jumlah',
                data: [{{ $total }}, {{ $opportunity }}, {{ $quotation }}, {{ $converted }}, {{ $lost }}],
                backgroundColor: [
                    '#60A5FA',
                    '#FB923C',
                    '#F87171',
                    '#4ADE80',
                    '#9CA3AF'
                ],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
