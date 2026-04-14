@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-[#F8F9FA] dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-500 overflow-hidden" 
     x-data="{ sidebarOpen: window.innerWidth >= 1024 }">
    
    <aside :class="sidebarOpen ? 'w-72' : 'w-0 md:w-20'" class="relative bg-gradient-to-b from-teal-700 via-teal-600 to-teal-500 dark:from-teal-900 dark:via-teal-800 dark:to-teal-700 backdrop-blur-2xl border-r border-teal-400/30 transition-all duration-500 ease-in-out flex flex-col sticky top-0 h-screen z-50 shadow-xl overflow-hidden text-left">
        <div class="p-6 h-24 border-b border-teal-400/30 flex items-center justify-between overflow-hidden">
            <div class="flex items-center space-x-3 min-w-[180px]" x-show="sidebarOpen" x-transition>
                <img src="https://adminweb.sidoarjokab.go.id/upload/link/1703218932.png" alt="Logo" class="w-10 h-10 object-contain rounded-xl shadow-lg bg-white/90 p-1">
                <h2 class="text-xs font-black text-white leading-tight uppercase tracking-tighter text-left">Satu Data<br>Kabupaten Sidoarjo</h2>
            </div>
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl hover:bg-teal-600/50 transition-colors shrink-0">
                <svg :class="!sidebarOpen ? 'rotate-180' : ''" class="w-6 h-6 text-white transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 p-4 space-y-2 overflow-y-auto text-white text-left">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-4 bg-white/20 rounded-2xl shadow-lg font-bold text-sm transition-all text-left">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs">DB</span>
            </a>
            <a href="{{ route('admin.upload.dataset') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group text-left">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Unggah Dataset</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs">UG</span>
            </a>
            <a href="{{ route('admin.metadata') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group text-left">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Kelola Metadata</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs">MT</span>
            </a>
            <a href="{{ route('admin.manage-dataset') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group text-left">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Kelola Dataset</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs">KL</span>
            </a>
            <a href="{{ route('admin.organizations') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group text-left">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Organisasi</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs">OR</span>
            </a>
        </nav>
    </aside>

    {{-- Main Container: p-6 lg:p-8 untuk merampingkan jarak --}}
    <main class="flex-1 h-screen overflow-y-auto p-6 lg:p-8 relative text-left transition-colors duration-500">
        <header class="flex flex-col md:flex-row justify-between md:items-center mb-8 text-left">
            <div>
                <h1 class="text-2xl md:text-3xl font-black tracking-tight leading-none text-left">
                    Selamat Datang, <span class="text-teal-600">{{ Auth::user()->name }}</span> 👋
                </h1>
                <p class="text-[9px] md:text-[10px] text-gray-400 font-black uppercase tracking-[0.2em] mt-2 leading-none text-left">Admin Dashboard • Satu Data Sidoarjo</p>
            </div>
            <div class="mt-4 md:mt-0 flex items-center">
                <button id="theme-toggle" class="p-2.5 rounded-xl bg-white dark:bg-gray-900 shadow-sm border border-gray-100 dark:border-gray-800 text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all">
                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"></path></svg>
                </button>
            </div>
        </header>

        {{-- Statistik Cards: Diperkecil padding dan ukuran teksnya --}}
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8 text-left">
            <div class="bg-white dark:bg-gray-900 p-5 rounded-[24px] shadow-sm border border-gray-100 dark:border-gray-800 hover:scale-[1.02] transition-all">
                <p class="text-[9px] font-black uppercase tracking-widest text-teal-600 opacity-70 leading-none">Total Dataset</p>
                <p class="text-3xl font-black mt-2 leading-none">{{ $stats['total_dataset'] ?? 0 }}</p>
            </div>
            <div class="bg-white dark:bg-gray-900 p-5 rounded-[24px] shadow-sm border border-gray-100 dark:border-gray-800 hover:scale-[1.02] transition-all">
                <p class="text-[9px] font-black uppercase tracking-widest text-indigo-600 opacity-70 leading-none">Total Tags</p>
                <p class="text-3xl font-black mt-2 leading-none">{{ $stats['total_tags'] ?? 0 }}</p>
            </div>
            <div class="bg-white dark:bg-gray-900 p-5 rounded-[24px] shadow-sm border border-gray-100 dark:border-gray-800 hover:scale-[1.02] transition-all">
                <p class="text-[9px] font-black uppercase tracking-widest text-emerald-600 opacity-70 leading-none">Total Unduhan</p>
                <p class="text-3xl font-black mt-2 leading-none text-emerald-600">{{ $stats['total_unduhan'] ?? 0 }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 text-left">
            <div class="lg:col-span-2 space-y-4">
                {{-- Bar Chart Card --}}
                <div class="bg-white dark:bg-gray-900 p-6 md:p-8 rounded-[32px] shadow-sm border border-gray-100 dark:border-gray-800" 
                     x-data="{ filterOpen: false, activeFilter: '{{ request('filter') == 'minggu' ? 'Minggu Ini' : (request('filter') == 'bulan' ? 'Bulan Ini' : 'Tahun Ini') }}' }">
                    
                    <div class="flex justify-between items-center mb-6 relative">
                        <h3 class="font-black uppercase text-[10px] tracking-widest text-left">Frekuensi Unggahan Dataset</h3>
                        
                        <div class="relative">
                            <button @click="filterOpen = !filterOpen" @click.away="filterOpen = false" class="flex items-center space-x-2 px-3 py-1.5 rounded-lg bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 text-[8px] font-black uppercase tracking-widest hover:bg-teal-50 dark:hover:bg-teal-900/30 transition-all">
                                <span x-text="activeFilter"></span>
                                <svg class="w-3 h-3 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>

                            <div x-show="filterOpen" x-transition class="absolute right-0 mt-2 w-36 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-100 dark:border-gray-700 z-50 overflow-hidden" style="display: none;">
                                <button @click="window.location.href='?filter=minggu'" class="w-full text-left px-4 py-2.5 text-[8px] font-black uppercase hover:bg-teal-50 dark:hover:bg-teal-900/30 transition-colors">Minggu Ini</button>
                                <button @click="window.location.href='?filter=bulan'" class="w-full text-left px-4 py-2.5 text-[8px] font-black uppercase hover:bg-teal-50 dark:hover:bg-teal-900/30 transition-colors border-y border-gray-50 dark:border-gray-700">Bulan Ini</button>
                                <button @click="window.location.href='?filter=tahun'" class="w-full text-left px-4 py-2.5 text-[8px] font-black uppercase hover:bg-teal-50 dark:hover:bg-teal-900/30 transition-colors">Tahun Ini</button>
                            </div>
                        </div>
                    </div>

                    {{-- AREA GRAFIK DAN LABEL DENGAN GRID AGAR SEJAJAR --}}
                    <div class="relative bg-gray-50/50 dark:bg-gray-800/20 p-4 pt-8 rounded-[24px] border border-gray-100 dark:border-gray-800/50 mt-6">
                        @php 
                            $statsArray = is_array($uploadStats ?? null) ? $uploadStats : [];
                            $maxVal = count($statsArray) > 0 ? max($statsArray) : 0;
                            $maxLimit = $maxVal > 0 ? $maxVal : 10; 
                            
                            // Tentukan label berdasarkan filter
                            $labels = [];
                            if(request('filter') == 'minggu') {
                                $labels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
                            } elseif(request('filter') == 'bulan') {
                                $labels = ['M-1', 'M-2', 'M-3', 'M-4'];
                            } else {
                                $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
                            }
                        @endphp
                        
                        @if(empty($statsArray) || array_sum($statsArray) == 0)
                            <div class="h-48 flex items-center justify-center text-[9px] font-bold text-gray-400 uppercase tracking-widest">
                                Belum ada aktivitas unggahan
                            </div>
                        @else
                            {{-- Menggunakan Grid agar setiap Bar dan Label berada di jalur vertikal yang sama --}}
                            <div class="grid" style="grid-template-columns: repeat({{ count($labels) }}, minmax(0, 1fr));">
                                
                                @foreach($statsArray as $index => $count)
                                    <div class="flex flex-col items-center justify-end h-56"> 
                                        
                                        {{-- Area Batang Grafik --}}
                                        <div class="h-48 w-full flex items-end justify-center group relative z-10 pb-2">
                                            <div class="w-full max-w-[20px] bg-teal-600 dark:bg-gradient-to-t dark:from-teal-600 dark:to-teal-400 rounded-t-lg transition-all duration-700 group-hover:brightness-110 shadow-sm relative" 
                                                 style="height: {{ $maxVal > 0 ? ($count / $maxLimit) * 100 : 0 }}%; min-height: {{ $count > 0 ? '4px' : '0' }}">
                                                
                                                {{-- Tooltip Angka Data --}}
                                                @if($count > 0)
                                                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 dark:bg-teal-500 text-white text-[8px] px-2 py-1 rounded-lg opacity-0 group-hover:opacity-100 transition-all whitespace-nowrap z-30 font-black shadow-md">
                                                        {{ $count }} Data
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Label Teks --}}
                                        <span class="text-[7px] font-black text-gray-400 uppercase tracking-tighter mt-1">
                                            {{ $labels[$index] ?? '' }}
                                        </span>
                                    </div>
                                @endforeach

                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-teal-50/50 dark:bg-teal-900/10 border border-teal-100 dark:border-teal-800 p-5 rounded-[24px]">
                    <div class="flex items-start space-x-3">
                        <div class="text-lg">💡</div>
                        <div>
                            <h4 class="text-[9px] font-black uppercase tracking-widest text-teal-700 dark:text-teal-400">Insight Aktivitas</h4>
                            <p class="text-[10px] text-gray-600 dark:text-gray-400 mt-0.5 leading-relaxed">
                                Saat ini total unggahan mencapai <strong>{{ is_array($uploadStats ?? null) ? array_sum($uploadStats) : 0 }} dataset</strong>. 
                                Rata-rata kelengkapan metadata Anda berada pada angka <strong>{{ $avgMetadata ?? 0 }}%</strong>. 
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Donut Chart Card: Diperkecil --}}
            <div class="bg-white dark:bg-gray-900 p-6 md:p-8 rounded-[32px] shadow-sm border border-gray-100 dark:border-gray-800 flex flex-col items-center justify-between text-center">
                <div class="w-full">
                    <h3 class="font-black uppercase text-[10px] tracking-widest mb-1 text-center">Kualitas Metadata</h3>
                    <p class="text-[8px] text-gray-400 font-bold uppercase tracking-tight mb-6">Tingkat Kelengkapan Data</p>
                </div>

                {{-- SVG telah menggunakan viewBox absolut dan logika opacity-0 untuk memastikan warna hilang saat 0% --}}
                <div class="relative w-40 h-40 md:w-48 md:h-48 flex items-center justify-center mx-auto mb-6">
                    <svg viewBox="0 0 100 100" class="w-full h-full transform -rotate-90">
                        <circle cx="50" cy="50" r="40" stroke="currentColor" stroke-width="8" fill="transparent" class="text-gray-100 dark:text-gray-800" />
                        <circle cx="50" cy="50" r="40" 
                                stroke="url(#teal_gradient)" 
                                stroke-width="8" 
                                fill="transparent" 
                                stroke-dasharray="251.3" 
                                stroke-dashoffset="{{ 251.3 - (251.3 * ($avgMetadata ?? 0) / 100) }}" 
                                stroke-linecap="round" 
                                class="transition-all duration-1000 {{ ($avgMetadata ?? 0) == 0 ? 'opacity-0' : 'opacity-100' }}" />
                        <defs>
                            <linearGradient id="teal_gradient">
                                <stop offset="0%" stop-color="#0d9488" />
                                <stop offset="100%" stop-color="#2dd4bf" />
                            </linearGradient>
                        </defs>
                    </svg>
                    
                    <div class="absolute text-center leading-none">
                        <span class="text-4xl font-black block text-center">{{ $avgMetadata ?? 0 }}%</span>
                        <span class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mt-1.5 block text-center">Terselesaikan</span>
                    </div>
                </div>

                <div class="w-full space-y-2.5">
                    <div class="flex justify-between text-[9px] font-black uppercase">
                        <span class="text-gray-400">Data Lengkap</span>
                        <span class="text-teal-600">{{ $avgMetadata ?? 0 }}%</span>
                    </div>
                    <div class="w-full h-1.5 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                        <div class="h-full bg-teal-500 rounded-full" style="width: {{ $avgMetadata ?? 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection