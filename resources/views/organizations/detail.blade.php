@extends('layouts.app')

@section('content')
<div x-data="{ mobileMenuOpen: false }" class="relative min-h-screen bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 overflow-x-hidden text-left">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- ── FLOATING NAVBAR ── --}}
    <header class="fixed top-0 left-0 right-0 z-50 px-4 pt-4 md:pt-5 transition-all duration-300">
        <div class="max-w-6xl mx-auto bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-2xl md:rounded-full shadow-xl px-4 md:px-6 py-3 border border-white/30 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <img src="https://adminweb.sidoarjokab.go.id/upload/link/1703218932.png" alt="Logo" class="w-8 h-8 md:w-9 md:h-9 object-contain">
                    <h1 class="text-sm md:text-lg font-bold text-gray-800 dark:text-white leading-tight">
                        <span class="block">Satu Data</span>
                        <span class="block text-[10px] md:text-sm font-semibold opacity-80">Kab. Sidoarjo</span>
                    </h1>
                </div>

                <nav class="hidden md:flex space-x-6">
                    <a href="/" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 font-medium transition">Home</a>
                    <a href="/datasets" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 font-medium transition">Datasets</a>
                    <a href="/organizations" class="text-sm text-green-600 font-bold transition">Organizations</a>
                    <a href="/groups" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 font-medium transition">Groups</a>
                    <a href="/about" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 font-medium transition">About</a>
                </nav>

                <div class="flex items-center space-x-2 md:space-x-4">
                    <button id="theme-toggle" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition focus:outline-none">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                        <svg id="theme-toggle-light-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707-.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"></path></svg>
                    </button>
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                        <svg x-show="mobileMenuOpen" style="display: none;" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
            {{-- Mobile Menu --}}
            <div x-show="mobileMenuOpen" x-transition class="md:hidden mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex flex-col space-y-3 pb-2">
                <a href="/" class="text-sm text-gray-700 dark:text-gray-300 font-medium">Home</a>
                <a href="/datasets" class="text-sm text-gray-700 dark:text-gray-300 font-medium">Datasets</a>
                <a href="/organizations" class="text-sm text-green-600 font-bold">Organizations</a>
                <a href="/groups" class="text-sm text-gray-700 dark:text-gray-300 font-medium">Groups</a>
                <a href="/about" class="text-sm text-gray-700 dark:text-gray-300 font-medium">About</a>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto pt-32 pb-20 px-4 md:px-6">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-2xl md:text-4xl font-black text-gray-900 dark:text-white leading-none">{{ $organization->name }}</h1>
                <p class="text-green-600 font-bold tracking-widest text-[10px] uppercase mt-2">Produsen Data Sektoral Aktif Sidoarjo</p>
            </div>
            <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 px-6 py-3 rounded-2xl shadow-sm text-center">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Total Dataset</p>
                <p class="text-3xl font-black text-green-700">{{ $organization->datasets_count ?? 0 }}</p>
            </div>
        </div>

        {{-- QUICK CARDS - Diubah menjadi sejajar (3 kolom) di semua device --}}
        <div class="grid grid-cols-3 gap-2 md:gap-4 mb-8 px-2">
            @php 
                $cards = [
                    ['title' => 'Data Terbaru', 'value' => $vol2024 ?? 0, 'color' => 'text-gray-900 dark:text-white'],
                    ['title' => 'Pertumbuhan', 'value' => ($growth ?? 0 >= 0 ? '+' : '') . number_format($growth ?? 0, 0) . '%', 'color' => ($growth ?? 0) >= 0 ? 'text-green-600' : 'text-red-500'],
                    ['title' => 'Satuan', 'value' => $mostUsedUnit->unit ?? '-', 'color' => 'text-teal-600']
                ];
            @endphp
            @foreach($cards as $card)
            <div class="bg-white dark:bg-gray-900 p-3 md:p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm text-center">
                <p class="text-[8px] md:text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">{{ $card['title'] }}</p>
                <h2 class="text-sm md:text-2xl font-black {{ $card['color'] }} leading-tight truncate">{{ $card['value'] }}</h2>
            </div>
            @endforeach
        </div>

        {{-- SECTION GRAFIK --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm">
                <h3 class="font-black uppercase text-[10px] tracking-widest mb-6 text-gray-400">Volume Output Data</h3>
                <div class="h-48"><canvas id="barChart"></canvas></div>
            </div>
            <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-3xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm">
                <h3 class="font-black uppercase text-[10px] tracking-widest text-gray-400 mb-6">Distribusi Fokus Satuan Output</h3>
                <div class="grid grid-cols-3 gap-4">
                    @foreach(['2022', '2023', '2024'] as $yr)
                    <div class="flex flex-col items-center">
                        <div class="w-24 h-24 sm:w-28 sm:h-28"><canvas id="donut{{ $yr }}"></canvas></div>
                        <p class="mt-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $yr }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- MATRIKS KEGIATAN --}}
        <div x-data="{ openModal: false }" class="bg-white dark:bg-gray-900 rounded-3xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm mb-10 overflow-hidden mx-2 text-left">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-black uppercase text-[10px] tracking-widest text-gray-900 dark:text-white">Matriks Keberlanjutan Program</h3>
                <button @click="openModal = true" class="md:hidden text-[9px] font-bold text-green-700 uppercase bg-green-50 px-3 py-1 rounded-full">Detail</button>
            </div>

            {{-- Versi Laptop: Animasi Scroll (Sembunyi di HP dengan class hidden md:block) --}}
            <div class="hidden md:block overflow-hidden border rounded-2xl dark:border-gray-800 shadow-inner relative h-[450px]">
                <table class="w-full text-left absolute top-0 z-20 table-fixed">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr class="text-[8px] font-black uppercase text-gray-400"><th class="px-6 py-3">Uraian Kegiatan</th><th class="py-3 text-center w-20">2022</th><th class="py-3 text-center w-20">2023</th><th class="py-3 text-center w-20">2024</th><th class="py-3 text-center w-40">Status</th></tr>
                    </thead>
                </table>
                <div class="pt-[42px] h-full overflow-hidden">
                    <div class="animate-scroll">
                        <table class="w-full border-collapse table-fixed">
                            <tbody class="divide-y dark:divide-gray-800">
                                @foreach(array_merge($allActivityNames ?? [], $allActivityNames ?? []) as $name)
                                <tr class="hover:bg-teal-50/50 dark:hover:bg-gray-800 h-[50px]">
                                    <td class="px-6 py-3 text-[10px] font-black text-gray-700 dark:text-gray-300 truncate">{{ $name }}</td>
                                    <td class="text-center w-20 font-bold text-green-500">{!! isset($activities2022[$name]) ? '✔' : '<span class="opacity-10">—</span>' !!}</td>
                                    <td class="text-center w-20 font-bold text-green-500">{!! isset($activities2023[$name]) ? '✔' : '<span class="opacity-10">—</span>' !!}</td>
                                    <td class="text-center w-20 font-bold text-green-500">{!! isset($activities2024[$name]) ? '✔' : '<span class="opacity-10">—</span>' !!}</td>
                                    <td class="text-center w-40 text-xs">...</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Versi Mobile: Tampil 5 data saja (Sembunyi di Laptop dengan class md:hidden) --}}
    <div class="md:hidden space-y-3">
        @foreach(array_slice($allActivityNames ?? [], 0, 5) as $name)
            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-xl flex justify-between items-center">
                <span class="text-[10px] font-bold text-gray-700 dark:text-gray-300 truncate pr-2">{{ $name }}</span>
                @if(isset($activities2024[$name]))
                    <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[8px] font-black uppercase rounded-md">On</span>
                @else
                    <span class="px-2 py-0.5 bg-gray-200 text-gray-500 text-[8px] font-black uppercase rounded-md">Off</span>
                @endif
            </div>
        @endforeach
    </div>

    {{-- TOMBOL LIHAT SEMUA (Tampil di HP) --}}
    <button @click="openModal = true" class="md:hidden mt-4 w-full py-3 border border-gray-200 dark:border-gray-700 rounded-xl text-[10px] font-bold text-gray-500 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-800 transition">
        Lihat Semua Matriks
    </button>

    {{-- MODAL LENGKAP --}}
    <div x-show="openModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" style="display: none;">
        <div @click.away="openModal = false" class="bg-white dark:bg-gray-900 rounded-3xl p-6 w-full max-h-[80vh] overflow-y-auto border dark:border-gray-800 shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-black uppercase text-xs tracking-widest">Matriks Lengkap</h3>
                <button @click="openModal = false" class="text-xl font-bold bg-gray-100 dark:bg-gray-800 w-8 h-8 rounded-full">✕</button>
            </div>
            <table class="w-full text-left">
                <thead class="text-[9px] uppercase text-gray-400">
                    <tr><th class="pb-3">Kegiatan</th><th class="pb-3 text-center">Status</th></tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-800">
                    @foreach($allActivityNames ?? [] as $name)
                        <tr class="text-[11px]">
                            <td class="py-3 font-medium">{{ $name }}</td>
                            <td class="text-center">
                                @if(isset($activities2024[$name]))
                                    <span class="px-2 py-1 bg-green-100 text-green-700 font-black rounded-md text-[8px] uppercase">On</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 dark:bg-gray-800 text-gray-400 font-black rounded-md text-[8px] uppercase">Off</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
        </div>
    </main>
</div>

<script>
    const baseOptions = { plugins: { legend: { display: false } }, responsive: true, maintainAspectRatio: false };
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: { labels: ['22', '23', '24'], datasets: [{ data: [{{ $vol2022 ?? 0 }}, {{ $vol2023 ?? 0 }}, {{ $vol2024 ?? 0 }}], backgroundColor: ['#f1f5f9', '#2dd4bf', '#16a34a'], borderRadius: 6 }] },
        options: baseOptions
    });
    const setupDonut = (id, labels, data) => {
        new Chart(document.getElementById(id), {
            type: 'doughnut',
            data: { labels: labels, datasets: [{ data: data, backgroundColor: ['#0d9488', '#14b8a6', '#5eead4', '#99f6e4', '#ccfbf1'], borderWidth: 0 }] },
            options: { ...baseOptions, cutout: '75%' }
        });
    };
    // Perbaikan: Tambahkan pengecekan agar tidak error jika data kosong
    @if(isset($units2022)) setupDonut('donut2022', {!! json_encode($units2022->pluck('unit')) !!}, {!! json_encode($units2022->pluck('total')) !!}); @endif
    @if(isset($units2023)) setupDonut('donut2023', {!! json_encode($units2023->pluck('unit')) !!}, {!! json_encode($units2023->pluck('total')) !!}); @endif
    @if(isset($units2024)) setupDonut('donut2024', {!! json_encode($units2024->pluck('unit')) !!}, {!! json_encode($units2024->pluck('total')) !!}); @endif
</script>
@endsection