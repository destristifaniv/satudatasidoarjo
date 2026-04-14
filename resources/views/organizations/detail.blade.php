@extends('layouts.app')

@section('content')
<div class="relative min-h-screen bg-[#F8F9FA] dark:bg-gray-950 text-gray-900 dark:text-gray-100 overflow-x-hidden text-left">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        @keyframes scrollTable {
            0% { transform: translateY(0); }
            100% { transform: translateY(-50%); }
        }
        .animate-scroll { animation: scrollTable 100s linear infinite; }
        .animate-scroll:hover { animation-play-state: paused; }
    </style>

    {{-- ── FLOATING NAVBAR ── --}}
    <header class="fixed top-0 left-0 right-0 z-50 px-4 pt-5 transition-all duration-300">
        <div class="max-w-6xl mx-auto bg-white/70 dark:bg-gray-900/70 backdrop-blur-md rounded-full shadow-xl px-6 py-3 flex items-center justify-between border border-white/30 dark:border-gray-700">
            <div class="flex items-center space-x-3 text-left">
                <img src="https://adminweb.sidoarjokab.go.id/upload/link/1703218932.png" alt="Logo" class="w-9 h-9 object-contain">
                <h1 class="text-sm md:text-lg font-bold text-gray-800 dark:text-white leading-tight">
                    <span class="block text-left">Satu Data</span>
                    <span class="block text-xs md:text-sm font-semibold opacity-80">Kabupaten Sidoarjo</span>
                </h1>
            </div>
            <nav class="hidden md:flex space-x-6">
                <a href="/" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 font-medium transition">Home</a>
                <a href="/datasets" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 font-medium transition">Datasets</a>
                <a href="/organizations" class="text-sm text-green-600 font-bold transition">Organizations</a>
                <a href="/groups" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 font-medium transition">About</a>
            </nav>
            <div class="flex items-center space-x-4">
                <button onclick="window.location.href='{{ url('/login') }}'" class="bg-green-700 hover:bg-green-800 text-white px-5 py-2 rounded-full transition text-sm font-medium shadow-lg">Login</button>
            </div>
        </div>
    </header>

    {{-- Main Container: pt-24 merapatkan jarak; max-w-6xl menyejajarkan dengan navbar --}}
    <main class="max-w-6xl mx-auto px-0 pt-32 pb-48 text-left">
        <div class="flex flex-col md:flex-row items-center gap-6 mb-8 px-2">
            {{-- <div class="w-16 h-16 bg-white dark:bg-gray-800 rounded-2xl flex items-center justify-center shrink-0 shadow-sm border border-gray-100 dark:border-gray-700">
                <img src="https://adminweb.sidoarjokab.go.id/upload/link/1703218932.png" class="w-10">
            </div> --}}
            <div class="flex-1 text-left">
                <h1 class="text-2xl md:text-3xl font-black tracking-tight leading-tight text-gray-900 dark:text-white">{{ $organization->name }}</h1>
                <p class="text-green-600 font-bold tracking-widest text-[9px] mt-1">Produsen Data Sektoral Aktif Sidoarjo</p>
            </div>
            <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 px-6 py-3 rounded-2xl text-center shadow-sm min-w-[140px]">
                <p class="text-[8px] font-black text-gray-400 uppercase opacity-60 mb-0.5 tracking-widest">Total Dataset</p>
                <p class="text-3xl font-black text-green-700 leading-none">{{ $organization->datasets_count }}</p>
            </div>
        </div>

        {{-- QUICK CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 px-2">
            @php 
                $cards = [
                    ['title' => 'Data Terbaru', 'value' => $vol2024, 'color' => 'text-gray-900 dark:text-white'],
                    ['title' => 'Pertumbuhan Data', 'value' => ($growth >= 0 ? '+' : '') . number_format($growth, 1) . '%', 'color' => $growth >= 0 ? 'text-green-600' : 'text-red-500'],
                    ['title' => 'Satuan Terbanyak', 'value' => $mostUsedUnit->unit ?? 'N/A', 'color' => 'text-teal-600']
                ];
            @endphp
            @foreach($cards as $card)
            <div class="bg-white dark:bg-gray-900 p-5 rounded-[24px] border border-gray-100 dark:border-gray-800 shadow-sm">
                <p class="text-[8px] font-black uppercase text-gray-400 tracking-widest mb-1 text-left">{{ $card['title'] }}</p>
                <h2 class="text-2xl font-black {{ $card['color'] }} leading-none truncate text-left">{{ $card['value'] }}</h2>
            </div>
            @endforeach
        </div>

        {{-- SECTION GRAFIK --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 px-2">
            <div class="bg-white dark:bg-gray-900 rounded-[32px] p-6 border border-gray-100 dark:border-gray-800 shadow-sm flex flex-col">
                <h3 class="font-black uppercase text-[8px] tracking-widest mb-6 text-gray-400 text-left">Volume Output Data</h3>
                <div class="h-48 text-left"><canvas id="barChart"></canvas></div>
            </div>
            <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-[32px] p-6 border border-gray-100 dark:border-gray-800 shadow-sm flex flex-col">
                <h3 class="font-black uppercase text-[8px] tracking-widest text-gray-400 mb-6 text-left">Distribusi Fokus Satuan Output</h3>
                <div class="grid grid-cols-3 gap-4 flex-1 items-center">
                    @foreach(['2022', '2023', '2024'] as $yr)
                    <div class="flex flex-col items-center">
                        <div class="w-24 h-24 sm:w-28 sm:h-28"><canvas id="donut{{ $yr }}"></canvas></div>
                        <p class="mt-2 text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $yr }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- MATRIKS KEGIATAN --}}
        <div class="bg-white dark:bg-gray-900 rounded-[32px] p-6 border border-gray-100 dark:border-gray-800 shadow-sm mb-10 overflow-hidden mx-2 text-left">
            <h3 class="font-black uppercase text-[10px] tracking-widest text-gray-900 dark:text-white mb-6 text-left">Matriks Keberlanjutan Program</h3>
            <div class="overflow-hidden border rounded-2xl dark:border-gray-800 shadow-inner relative h-[450px] text-left">
                <table class="w-full text-left absolute top-0 z-20 table-fixed">
                    <thead class="bg-gray-50/95 dark:bg-gray-800/95 backdrop-blur-md border-b dark:border-gray-700">
                        <tr class="text-[8px] font-black uppercase text-gray-400">
                            <th class="px-6 py-3 text-left">Uraian Kegiatan</th>
                            <th class="py-3 text-center w-20">2022</th>
                            <th class="py-3 text-center w-20">2023</th>
                            <th class="py-3 text-center w-20">2024</th>
                            <th class="py-3 text-center w-40">Status</th>
                        </tr>
                    </thead>
                </table>
                <div class="pt-[42px] h-full overflow-hidden text-left">
                    <div class="animate-scroll text-left">
                        <table class="w-full border-collapse table-fixed">
                            <tbody class="divide-y dark:divide-gray-800 text-left">
                                @foreach(array_merge($allActivityNames, $allActivityNames) as $name)
                                <tr class="group hover:bg-teal-50/50 dark:hover:bg-teal-900/20 transition-colors h-[50px] text-left">
                                    <td class="px-6 py-3 overflow-hidden text-left">
                                        @php $id = $activities2024[$name] ?? $activities2023[$name] ?? $activities2022[$name] ?? null; @endphp
                                        <a href="{{ $id ? route('public.datasets.show', $id) : '#' }}" class="text-[10px] font-black text-gray-700 dark:text-gray-300 group-hover:text-green-600 transition-colors uppercase leading-tight block truncate text-left">
                                            {{ $name }}
                                        </a>
                                    </td>
                                    <td class="text-center w-20 font-bold text-green-500">{!! isset($activities2022[$name]) ? '✔' : '<span class="opacity-10">—</span>' !!}</td>
                                    <td class="text-center w-20 font-bold text-green-500 border-x dark:border-gray-800/50">{!! isset($activities2023[$name]) ? '✔' : '<span class="opacity-10">—</span>' !!}</td>
                                    <td class="text-center w-20 font-bold text-green-500">{!! isset($activities2024[$name]) ? '✔' : '<span class="opacity-10">—</span>' !!}</td>
                                    <td class="px-4 py-3 text-center w-40 text-left">
                                        <div class="flex justify-center text-left">
                                            @if(!isset($activities2022[$name]) && isset($activities2024[$name]))
                                                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[7px] font-black uppercase rounded-full border border-blue-100 whitespace-nowrap">Baru</span>
                                            @elseif(isset($activities2022[$name]) && isset($activities2024[$name]))
                                                <span class="px-3 py-1 bg-green-50 text-green-600 text-[7px] font-black uppercase rounded-full border border-green-100 whitespace-nowrap">Lanjut</span>
                                            @else
                                                <span class="px-3 py-1 bg-gray-50 text-gray-400 text-[7px] font-black uppercase rounded-full whitespace-nowrap">Off</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-white dark:bg-gray-950 py-10 border-t text-center text-[8px] font-black text-gray-400 uppercase tracking-[0.4em]">© {{ date('Y') }} PEMKAB SIDOARJO</footer>

    <script>
        const baseOptions = { plugins: { legend: { display: false } }, responsive: true, maintainAspectRatio: false };
        new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: { labels: ['22', '23', '24'], datasets: [{ data: [{{ $vol2022 }}, {{ $vol2023 }}, {{ $vol2024 }}], backgroundColor: ['#f1f5f9', '#2dd4bf', '#16a34a'], borderRadius: 8 }] },
            options: baseOptions
        });
        const setupDonut = (id, labels, data) => {
            new Chart(document.getElementById(id), {
                type: 'doughnut',
                data: { labels: labels, datasets: [{ data: data, backgroundColor: ['#0d9488', '#14b8a6', '#5eead4', '#99f6e4', '#ccfbf1'], borderWidth: 0 }] },
                options: { ...baseOptions, cutout: '80%' }
            });
        };
        setupDonut('donut2022', {!! json_encode($units2022->pluck('unit')) !!}, {!! json_encode($units2022->pluck('total')) !!});
        setupDonut('donut2023', {!! json_encode($units2023->pluck('unit')) !!}, {!! json_encode($units2023->pluck('total')) !!});
        setupDonut('donut2024', {!! json_encode($units2024->pluck('unit')) !!}, {!! json_encode($units2024->pluck('total')) !!});
    </script>
</div>
@endsection