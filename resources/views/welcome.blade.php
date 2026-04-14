@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<div class="relative min-h-screen bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 overflow-x-hidden">
    
    <div class="absolute top-0 left-0 w-full h-[550px] md:h-[650px] overflow-hidden pointer-events-none">
        <div class="absolute inset-0 bg-cover bg-center"
            style="background-image: url('{{ asset('images/bg-opendata.jpg') }}');">
        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/10 to-gray-50 dark:to-gray-950"></div>
    </div>

    <div class="relative z-10">
        {{-- NAVBAR --}}
        <header class="fixed top-0 left-0 right-0 z-50 px-4 pt-5 transition-all duration-300">
            <div class="max-w-6xl mx-auto bg-white/70 dark:bg-gray-900/70 backdrop-blur-md rounded-full shadow-xl px-6 py-3 flex items-center justify-between border border-white/30 dark:border-gray-700 text-left">
                <div class="flex items-center space-x-3 text-left">
                    <img src="https://adminweb.sidoarjokab.go.id/upload/link/1703218932.png" alt="Logo" class="w-9 h-9 object-contain">
                    <h1 class="text-sm md:text-lg font-bold text-gray-800 dark:text-white leading-tight">
                        <span class="block text-left">Satu Data</span>
                        <span class="block text-xs md:text-sm font-semibold opacity-80">Kabupaten Sidoarjo</span>
                    </h1>
                </div>
                <nav class="hidden md:flex space-x-6">
                    <a href="/" class="text-sm text-green-600 font-bold transition">Home</a>
                    <a href="/datasets" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 font-medium transition">Datasets</a>
                    <a href="/organizations" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 font-medium transition">Organizations</a>
                    <a href="/groups" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 font-medium transition">Groups</a>
                    <a href="/about" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 font-medium transition">About</a>
                </nav>
                <div class="flex items-center space-x-4">
                    <button onclick="window.location.href='{{ url('/login') }}'" class="bg-green-700 hover:bg-green-800 text-white px-5 py-2 rounded-full transition text-sm font-medium shadow-lg shadow-green-500/20">Login</button>
                    <button id="theme-toggle" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                        <svg id="theme-toggle-light-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"></path></svg>
                    </button>
                </div>
            </div>
        </header>

        <section class="pt-52 flex flex-col items-center justify-center px-6 pb-20">
            <div class="max-w-4xl w-full text-center transition-all duration-300">
                <h2 class="text-3xl md:text-5xl font-extrabold mb-4 leading-tight text-white drop-shadow-md">Selamat Datang di Portal Satu Data<br>Kabupaten Sidoarjo</h2>
                <p class="text-sm md:text-lg mb-10 max-w-2xl mx-auto text-gray-100 font-medium drop-shadow">Sumber data resmi untuk pembangunan daerah yang lebih berkualitas</p>
                <form x-data="{ target: '/datasets' }" :action="target" method="GET" class="relative max-w-2xl mx-auto flex items-center bg-white/90 dark:bg-gray-800/90 border border-white/50 dark:border-gray-600 rounded-full shadow-2xl backdrop-blur-sm focus-within:ring-2 focus-within:ring-green-500 transition-all">
                    <div class="relative flex items-center">
                        <select x-model="target" class="appearance-none bg-transparent pl-5 pr-7 py-4 text-[10px] md:text-[11px] font-black text-green-700 dark:text-green-500 border-none outline-none focus:ring-0 cursor-pointer rounded-l-full uppercase tracking-wider">
                            <option value="/datasets">Datasets</option>
                            <option value="/organizations">Organizations</option>
                            <option value="/groups">Groups</option>
                        </select>
                    </div>
                    <div class="h-6 w-[2px] bg-gray-200 dark:bg-gray-700"></div>
                    <input type="text" name="search" placeholder="Ketik kata kunci pencarian..." class="w-full pl-4 pr-12 py-4 text-sm font-bold bg-transparent border-none outline-none focus:ring-0 placeholder-gray-400 text-gray-800 dark:text-gray-100 rounded-r-full">
                    <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-xl opacity-60 hover:opacity-100 hover:scale-110 hover:text-green-600 transition-all focus:outline-none">🔍</button>
                </form>
            </div>
        </section>

        {{-- STATS SECTION (MODAL EXPANDING CARDS - UKURAN KECIL) --}}
        @php
            // 🔥 PERBAIKAN: Hanya mengambil dan menghitung data yang statusnya 'approved' 🔥
            $allDatasetsData = \App\Models\Dataset::where('status', 'approved')->get();
            $totalAll = $allDatasetsData->count();
            $totalPercentageAll = 0;
            
            if ($totalAll > 0) {
                foreach ($allDatasetsData as $data) {
                    $filled = 0;
                    if (!empty($data->dssd_code)) $filled++;
                    if (!empty($data->description)) $filled++;
                    if (!empty($data->tags)) $filled++;
                    if (!empty($data->unit)) $filled++;
                    if (!empty($data->frequency)) $filled++;
                    $totalPercentageAll += ($filled / 5) * 100;
                }
                $persentaseVerifikasi = round($totalPercentageAll / $totalAll);
            } else { 
                $persentaseVerifikasi = 0; 
            }
            
            $targetOPD = 50; 
            
            // 🚨 PERBAIKAN FINAL: Hanya menghitung akun yang perannya sebagai Staf/OPD.
            $opdTerdaftar = \App\Models\User::where('role', 'opd')->count();
            
            // 🔥 PERBAIKAN: Hanya menghitung unduhan dari data yang 'approved' 🔥
            $totalDownload = \App\Models\Dataset::where('status', 'approved')->sum('downloads');
        @endphp

        <div class="max-w-6xl mx-auto w-full px-6 md:px-0" x-data="{ activeCard: null }">
            <div class="flex flex-col lg:flex-row gap-3 h-auto lg:h-32 transition-all duration-500 ease-in-out">
                
                {{-- 1. TOTAL DATASET --}}
                <div @mouseenter="activeCard = 1" @mouseleave="activeCard = null"
                     :class="activeCard === 1 ? 'lg:flex-[2.5] bg-blue-600 dark:bg-blue-700' : (activeCard === null ? 'lg:flex-1 bg-white/70 dark:bg-gray-900/60' : 'lg:flex-[0.5] opacity-50 bg-white/40 dark:bg-gray-900/30')"
                     class="relative overflow-hidden rounded-[24px] shadow-xl border border-white/50 dark:border-gray-800 transition-all duration-500 ease-in-out cursor-pointer group p-4 flex flex-col justify-center">
                    
                    <div class="flex items-center space-x-3">
                        <div class="p-2.5 rounded-xl transition-colors duration-500" :class="activeCard === 1 ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-600'">📊</div>
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black uppercase tracking-widest transition-colors duration-500" :class="activeCard === 1 ? 'text-blue-100' : 'text-gray-400'">Datasets</span>
                            <h3 class="text-xl font-black transition-all duration-500" :class="activeCard === 1 ? 'text-white scale-105' : 'text-gray-800 dark:text-white'">
                                {{ $totalAll }}
                            </h3>
                        </div>
                    </div>
                    <div x-show="activeCard === 1" x-transition.opacity.duration.500ms class="mt-2 text-blue-50 text-[10px] font-medium leading-tight line-clamp-2">
                        Daftar dataset sektoral dari berbagai instansi pemerintah yang telah dipublikasikan.
                    </div>
                </div>

                {{-- 2. ORGANISASI OPD --}}
                <div @mouseenter="activeCard = 2" @mouseleave="activeCard = null"
                     :class="activeCard === 2 ? 'lg:flex-[2.5] bg-purple-600 dark:bg-purple-700' : (activeCard === null ? 'lg:flex-1 bg-white/70 dark:bg-gray-900/60' : 'lg:flex-[0.5] opacity-50 bg-white/40 dark:bg-gray-900/30')"
                     class="relative overflow-hidden rounded-[24px] shadow-xl border border-white/50 dark:border-gray-800 transition-all duration-500 ease-in-out cursor-pointer group p-4 flex flex-col justify-center">
                    
                    <div class="flex items-center space-x-3">
                        <div class="p-2.5 rounded-xl transition-colors duration-500" :class="activeCard === 2 ? 'bg-white/20 text-white' : 'bg-purple-100 text-purple-600'">🏛️</div>
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black uppercase tracking-widest transition-colors duration-500" :class="activeCard === 2 ? 'text-purple-100' : 'text-gray-400'">Organizations</span>
                            <h3 class="text-xl font-black transition-all duration-500" :class="activeCard === 2 ? 'text-white scale-105' : 'text-gray-800 dark:text-white'">
                                {{ $opdTerdaftar }}
                            </h3>
                        </div>
                    </div>
                    <div x-show="activeCard === 2" x-transition.opacity.duration.500ms class="mt-2 text-purple-50 text-[10px] font-medium leading-tight line-clamp-2">
                        Sebanyak {{ $opdTerdaftar }} Organisasi Perangkat Daerah telah aktif berkontribusi.
                    </div>
                </div>

                {{-- 3. TOTAL DOWNLOAD --}}
                <div @mouseenter="activeCard = 3" @mouseleave="activeCard = null"
                     :class="activeCard === 3 ? 'lg:flex-[2.5] bg-orange-600 dark:bg-orange-700' : (activeCard === null ? 'lg:flex-1 bg-white/70 dark:bg-gray-900/60' : 'lg:flex-[0.5] opacity-50 bg-white/40 dark:bg-gray-900/30')"
                     class="relative overflow-hidden rounded-[24px] shadow-xl border border-white/50 dark:border-gray-800 transition-all duration-500 ease-in-out cursor-pointer group p-4 flex flex-col justify-center">
                    
                    <div class="flex items-center space-x-3">
                        <div class="p-2.5 rounded-xl transition-colors duration-500" :class="activeCard === 3 ? 'bg-white/20 text-white' : 'bg-orange-100 text-orange-600'">📥</div>
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black uppercase tracking-widest transition-colors duration-500" :class="activeCard === 3 ? 'text-orange-100' : 'text-gray-400'">Unduhan</span>
                            <h3 class="text-xl font-black transition-all duration-500" :class="activeCard === 3 ? 'text-white scale-105' : 'text-gray-800 dark:text-white'">
                                {{ $totalDownload }}
                            </h3>
                        </div>
                    </div>
                    <div x-show="activeCard === 3" x-transition.opacity.duration.500ms class="mt-2 text-orange-50 text-[10px] font-medium leading-tight line-clamp-2">
                        Data telah diunduh sebanyak {{ $totalDownload }} kali oleh pengguna data.
                    </div>
                </div>

                {{-- 4. DATA TERVERIFIKASI --}}
                <div @mouseenter="activeCard = 4" @mouseleave="activeCard = null"
                     :class="activeCard === 4 ? 'lg:flex-[2.5] bg-green-600 dark:bg-green-700' : (activeCard === null ? 'lg:flex-1 bg-white/70 dark:bg-gray-900/60' : 'lg:flex-[0.5] opacity-50 bg-white/40 dark:bg-gray-900/30')"
                     class="relative overflow-hidden rounded-[24px] shadow-xl border border-white/50 dark:border-gray-800 transition-all duration-500 ease-in-out cursor-pointer group p-4 flex flex-col justify-center">
                    
                    <div class="flex items-center space-x-3">
                        <div class="p-2.5 rounded-xl transition-colors duration-500" :class="activeCard === 4 ? 'bg-white/20 text-white' : 'bg-green-100 text-green-600'">✅</div>
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black uppercase tracking-widest transition-colors duration-500" :class="activeCard === 4 ? 'text-green-100' : 'text-gray-400'">Metadata</span>
                            <h3 class="text-xl font-black transition-all duration-500" :class="activeCard === 4 ? 'text-white scale-105' : 'text-gray-800 dark:text-white'">
                                {{ $persentaseVerifikasi }}%
                            </h3>
                        </div>
                    </div>
                    <div x-show="activeCard === 4" x-transition.opacity.duration.500ms class="mt-2 text-green-50 text-[10px] font-medium leading-tight line-clamp-2">
                        Kualitas kelengkapan metadata dataset mencapai standar validasi sistem sebesar {{ $persentaseVerifikasi }}%.
                    </div>
                </div>

            </div>
        </div>

        {{-- AREA PETA SEBARAN (Halaman Ke-2) --}}
        <section class="py-24 bg-white dark:bg-gray-950 border-t border-gray-100 dark:border-gray-900">
            <div class="max-w-6xl mx-auto px-10 md:px-0">
                <div class="flex items-center space-x-2 mb-10 text-left">
                    <span class="text-2xl">🗺️</span>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white border-b-4 border-green-500 pb-1">Sebaran Data Per Kecamatan</h3>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-900/60 rounded-[32px] p-4 border border-gray-100 dark:border-gray-800 shadow-lg relative overflow-hidden">
                    <div id="map" class="w-full h-[500px] rounded-[24px] z-0"></div>
                </div>
            </div>
        </section>

        {{-- VISUALISASI DATA INTERAKTIF (Analitik) --}}
        <section class="py-20 bg-white dark:bg-gray-950">
            <div class="max-w-6xl mx-auto px-10 md:px-0">
                <div class="flex items-center space-x-2 mb-6 text-left">
                    <span class="text-2xl">📈</span>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white border-b-4 border-green-500 pb-1">Analitik Data Sektoral</h3>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-gray-50 dark:bg-gray-900 rounded-[28px] p-6 border border-gray-100 dark:border-gray-800 shadow-lg relative overflow-hidden group text-left text-left">
                        <h4 class="font-black text-[10px] uppercase tracking-wider text-gray-500 dark:text-white/35 mb-1 text-left">Produsen Data Teratas</h4>
                        <div class="flex items-center mb-6 text-left">
                            <span class="text-[9px] font-bold px-2 py-0.5 rounded border uppercase" style="background:rgba(52,211,153,0.12);color:#10b981;border-color:rgba(52,211,153,0.25)">Insight</span>
                            <p class="text-xs text-gray-600 dark:text-white/40 ml-2">OPD aktif: <span class="font-bold text-green-600 dark:text-white/75">{{ $insightOpd }}</span></p>
                        </div>
                        <div class="h-64 w-full"><canvas id="opdChart"></canvas></div>
                        <div id="opdLegend" class="flex flex-wrap gap-2.5 mt-3"></div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-[28px] p-6 border border-gray-100 dark:border-gray-800 shadow-lg relative overflow-hidden flex flex-col group text-left text-left">
                        <h4 class="font-black text-[10px] uppercase tracking-wider text-gray-500 dark:text-white/35 mb-1 text-left">Distribusi Satuan</h4>
                        <div class="flex items-center mb-4 text-left">
                            <span class="text-[9px] font-bold px-2 py-0.5 rounded border uppercase" style="background:rgba(99,179,237,0.15);color:#3b82f6;border-color:rgba(99,179,237,0.2)">Insight</span>
                            <p class="text-xs text-gray-600 dark:text-white/40 ml-2">Mayoritas: <span class="font-bold text-blue-600 dark:text-white/75">{{ $insightUnit }}</span></p>
                        </div>
                        <div class="flex-1 min-h-[200px] flex items-center justify-center"><canvas id="unitChart"></canvas></div>
                    </div>
                    <div class="lg:col-span-3 bg-gray-50 dark:bg-gray-900 rounded-[28px] p-6 border border-gray-100 dark:border-gray-800 shadow-lg relative overflow-hidden group text-left text-left">
                        <h4 class="font-black text-[10px] uppercase tracking-wider text-gray-500 dark:text-white/35 mb-1 text-left">Tren Pertumbuhan Dataset</h4>
                        <div class="h-56 w-full"><canvas id="trendChart"></canvas></div>
                    </div>
                </div>
            </div>
        </section>

        {{-- AREA DATASET TERBARU & TOP ORGANISASI --}}
        <section class="py-24 bg-gray-50 dark:bg-gray-900">
            <div class="max-w-6xl mx-auto px-4 md:px-0">
                <div class="flex flex-col lg:flex-row gap-6 items-stretch">
                    {{-- SLIDER DATASET TERBARU --}}
                    <div class="w-full lg:w-3/4 text-left" x-data="{ 
                        scrollContainer: null, autoScrollInterval: null,
                        init() { this.scrollContainer = this.$refs.slider; this.startAutoScroll(); },
                        slideLeft() { this.scrollContainer.scrollBy({ left: -320, behavior: 'smooth' }); this.resetAutoScroll(); },
                        slideRight() { 
                            if (this.scrollContainer.scrollLeft + this.scrollContainer.clientWidth >= this.scrollContainer.scrollWidth - 10) {
                                this.scrollContainer.scrollTo({ left: 0, behavior: 'smooth' });
                            } else { this.scrollContainer.scrollBy({ left: 320, behavior: 'smooth' }); }
                            this.resetAutoScroll(); 
                        },
                        startAutoScroll() { this.autoScrollInterval = setInterval(() => { this.slideRight(); }, 3500); },
                        resetAutoScroll() { clearInterval(this.autoScrollInterval); this.startAutoScroll(); }
                    }">
                        <div class="flex justify-between items-center mb-8">
                            <h3 class="text-xl font-bold flex items-center text-left"><span class="mr-2">📁</span> Dataset Terbaru</h3>
                            <div class="flex space-x-1.5">
                                <button @click="slideLeft()" class="p-2 rounded-md bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 hover:text-green-600 transition">←</button>
                                <button @click="slideRight()" class="p-2 rounded-md bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 hover:text-green-600 transition">→</button>
                            </div>
                        </div>
                        <div class="flex overflow-x-auto gap-5 pb-6 snap-x snap-mandatory hide-scrollbar" x-ref="slider" @mouseenter="clearInterval(autoScrollInterval)" @mouseleave="startAutoScroll()" style="scrollbar-width: none; -ms-overflow-style: none;">
                            @php 
                                $card_styles = [['bg' => 'from-green-50 to-emerald-50/50', 'text' => 'text-green-700'],['bg' => 'from-blue-50 to-indigo-50/50', 'text' => 'text-blue-700'],['bg' => 'from-orange-50 to-yellow-50/50', 'text' => 'text-orange-700'],['bg' => 'from-purple-50 to-fuchsia-50/50', 'text' => 'text-purple-700'],['bg' => 'from-teal-50 to-cyan-50/50', 'text' => 'text-teal-700'],['bg' => 'from-rose-50 to-pink-50/50', 'text' => 'text-rose-700'],['bg' => 'from-amber-50 to-orange-50/50', 'text' => 'text-amber-700']];
                            @endphp
                            @foreach($latest_datasets as $index => $dataset)
                            @php $style = $card_styles[$index % 7]; @endphp
                            <div class="group relative overflow-hidden rounded-[24px] p-6 transition-all hover:shadow-xl hover:-translate-y-1 border border-white dark:border-gray-800 bg-gradient-to-br {{ $style['bg'] }} dark:from-gray-800/90 dark:to-gray-900/90 text-left flex-none w-[300px] flex flex-col snap-center cursor-pointer text-left" onclick="window.location.href='/datasets/{{ $dataset->id }}'">
                                <div class="flex justify-between items-start mb-5 text-left text-left">
                                    <span class="px-3 py-1 bg-white/80 dark:bg-gray-700/80 text-[9px] font-bold uppercase tracking-widest {{ $style['text'] }} rounded-full shadow-sm">XLSX</span>
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest text-left">{{ $dataset->created_at->diffForHumans() }}</span>
                                </div>
                                <h4 class="text-[14px] font-extrabold text-gray-800 dark:text-white mb-3 leading-snug uppercase line-clamp-3 flex-1 text-left">{{ $dataset->name }}</h4>
                                <div class="pt-4 border-t border-gray-100 dark:border-gray-700/50 flex justify-between items-center text-left">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter text-left">{{ Str::limit($dataset->user->name ?? 'Dinas Terkait', 18) }}</p>
                                    <div class="w-7 h-7 rounded-full bg-white/80 dark:bg-green-900/30 flex items-center justify-center text-green-600 text-sm transition-transform group-hover:rotate-45">→</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- BAGIAN TOP ORGANISASI --}}
                    <div class="w-full lg:w-1/4">
                        <div class="bg-white/60 dark:bg-gray-900/40 backdrop-blur-xl rounded-[28px] p-5 border border-white/60 dark:border-gray-800 shadow-lg h-full text-left flex flex-col text-left">
                            <div class="flex items-center space-x-2.5 mb-5 text-left">
                                <div class="p-2 bg-orange-100 dark:bg-orange-900/30 text-orange-600 rounded-xl text-lg">🏆</div>
                                <div class="text-left">
                                    <h3 class="font-bold text-sm leading-none uppercase tracking-tighter text-left">Top Organisasi</h3>
                                    <p class="text-[8px] text-gray-500 mt-1 uppercase font-bold tracking-widest leading-none text-left">Kontribusi</p>
                                </div>
                            </div>
                            <div class="space-y-3 flex-1 text-left text-left">
                                @foreach($top_organisasi as $index => $org)
                                <div class="flex items-center p-3 rounded-[20px] transition-all hover:bg-white/80 dark:hover:bg-gray-800/80 group cursor-pointer text-left" onclick="window.location.href='/organizations/{{ $org['id'] }}'">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm {{ $index == 0 ? 'bg-yellow-400 text-white' : ($index == 1 ? 'bg-gray-300' : 'bg-orange-400 text-white') }}">{{ $index + 1 }}</div>
                                    <div class="ml-4 flex-1 text-left">
                                        <h4 class="text-xs font-bold text-gray-800 dark:text-gray-100 uppercase leading-tight line-clamp-2 text-left">{{ $org['name'] }}</h4>
                                        <p class="text-[9px] text-gray-500 font-bold uppercase mt-1 text-left">{{ $org['datasets'] }} Datasets</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <a href="/organizations" class="mt-4 block w-full py-3 bg-gray-50 dark:bg-gray-800 rounded-xl text-[9px] font-black text-center uppercase tracking-[0.2em] text-gray-400 hover:text-green-700 transition-all shadow-sm">Lihat Semua</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer class="bg-white dark:bg-gray-950 py-10 border-t border-gray-100 dark:border-gray-900 text-center text-left">
            <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.4em] text-center">© {{ date('Y') }} PEMKAB SIDOARJO • PORTAL SATU DATA</p>
        </footer>
    </div>
</div>

<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .leaflet-interactive { transition: all 0.3s; }

    /* 🔥 MENGHILANGKAN GARIS KOTAK SAAT PETA DIKLIK 🔥 */
    .leaflet-interactive:focus,
    .leaflet-container *:focus {
        outline: none !important;
    }
    .leaflet-overlay-pane svg path {
        -webkit-tap-highlight-color: transparent;
    }

    /* 🔥 TAMBAHAN UNTUK POP-UP PETA 🔥 */
    .custom-map-tooltip {
        background: rgba(255, 255, 255, 0.95) !important;
        border: 1px solid rgba(16, 185, 129, 0.2) !important;
        border-radius: 16px !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
        backdrop-filter: blur(8px) !important;
        padding: 8px !important;
    }
    
    /* Hilangkan segitiga panah bawaan leaflet yang jelek */
    .custom-map-tooltip::before,
    .custom-map-tooltip::after {
        display: none !important; 
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    
    // --- 1. PETA LEAFLET ---
    function getKecColor(d) {
        return d > 50 ? '#022c22' : // Hijau sangat gelap (Paling banyak)
               d > 30 ? '#064e3b' : // Hijau gelap
               d > 20 ? '#065f46' : // Hijau tua
               d > 10 ? '#047857' : // Hijau standar
               d > 5  ? '#10b981' : // Hijau cerah
               d > 0  ? '#6ee7b7' : // Hijau pudar (Paling sedikit)
                        '#e2e8f0';
    }

    const map = L.map('map', { scrollWheelZoom: false }).setView([-7.4478, 112.7183], 11);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(map);

    const dataMap = {!! json_encode($kecamatanDataMap) !!};

    fetch('{{ asset("storage/data/35.15_kecamatan.json") }}')
        .then(res => {
            if (!res.ok) {
                throw new Error("Gagal memuat file peta. Status: " + res.status);
            }
            return res.json();
        })
        .then(geojsonData => {
            L.geoJson(geojsonData, {
                style: function(feature) {
                    const kecName = feature.properties.nm_kecamatan.toUpperCase().trim();
                    const kecData = dataMap[kecName] || { count: 0, id: null };
                    const count = kecData.count;
                    
                    return { 
                        fillColor: getKecColor(count), 
                        weight: 1.5, 
                        opacity: 1, 
                        color: 'white', 
                        fillOpacity: count > 0 ? 0.8 : 0.4
                    };
                },
                onEachFeature: function(feature, layer) {
                    const kecName = feature.properties.nm_kecamatan.toUpperCase().trim();
                    const kecData = dataMap[kecName] || { count: 0, id: null };
                    
                    const count = kecData.count || 0;
                    const orgId = kecData.id;
                    
                    // --- 🔥 PERBAIKAN LOGIKA DATA 🔥 ---
                    // Coba beberapa kemungkinan nama key dari controller agar lebih aman
                    const layanan = kecData.layanan ?? kecData.total_layanan ?? 0; 
                    
                    // Tangani format pertumbuhan dan cek apakah minus/plus
                    let pertumbuhanRaw = kecData.pertumbuhan ?? kecData.pertumbuhan_data ?? 0;
                    let pertumbuhanStr = String(pertumbuhanRaw);
                    // Tambahkan % jika tidak ada
                    if (pertumbuhanStr !== '0' && !pertumbuhanStr.includes('%')) {
                        pertumbuhanStr += '%';
                    }
                    if (pertumbuhanStr === '0') pertumbuhanStr = '0%';
                    
                    // Tentukan warna: Merah jika ada minus (-), Hijau jika plus
                    const isMinus = pertumbuhanStr.includes('-');
                    const pertumbuhanColor = isMinus ? 'text-red-500' : 'text-green-600';

                    const satuan = kecData.satuan_terbanyak ?? kecData.satuan ?? '-';

                    const tooltipContent = `
                        <div class="p-1.5 min-w-[260px]">
                            <h4 class="text-green-700 font-extrabold text-sm uppercase tracking-wider mb-2 text-center">${feature.properties.nm_kecamatan}</h4>
                            <div class="h-px w-full bg-green-200/50 mb-2"></div>
                            
                            <div class="grid grid-cols-2 gap-2 mb-1">
                                <div class="bg-gray-50 p-2 rounded border border-gray-100 text-center overflow-hidden">
                                    <p class="text-[8px] text-gray-400 font-bold uppercase tracking-wider mb-0.5 truncate">Data Set</p>
                                    <p class="text-xs font-black text-gray-700">${count}</p>
                                </div>
                                <div class="bg-gray-50 p-2 rounded border border-gray-100 text-center overflow-hidden">
                                    <p class="text-[8px] text-gray-400 font-bold uppercase tracking-wider mb-0.5 truncate">Data Baru</p>
                                    <p class="text-xs font-black text-gray-700">${layanan}</p>
                                </div>
                                <div class="bg-gray-50 p-2 rounded border border-gray-100 text-center overflow-hidden">
                                    <p class="text-[8px] text-gray-400 font-bold uppercase tracking-wider mb-0.5 truncate">Pertumbuhan</p>
                                    <p class="text-xs font-black ${pertumbuhanColor}">${pertumbuhanStr}</p>
                                </div>
                                <div class="bg-gray-50 p-2 rounded border border-gray-100 text-center overflow-hidden">
                                    <p class="text-[8px] text-gray-400 font-bold uppercase tracking-wider mb-0.5 truncate" title="Satuan Terbanyak">Satuan Terbanyak</p>
                                    <p class="text-[10px] font-black text-gray-700 truncate w-full" title="${satuan}">${satuan}</p>
                                </div>
                            </div>

                            <p class="text-[8px] text-gray-400 mt-2 uppercase tracking-widest animate-pulse text-center">(Klik untuk detail)</p>
                        </div>
                    `;

                    layer.bindTooltip(tooltipContent, {
                        sticky: true,
                        className: 'custom-map-tooltip',
                        opacity: 1
                    });

                    layer.on({
                        mouseover: function(e) {
                            const l = e.target;
                            l.setStyle({ weight: 3, color: '#059669', fillOpacity: 0.9 });
                            l.bringToFront();
                        },
                        mouseout: function(e) {
                            const l = e.target;
                            l.setStyle({ weight: 1.5, color: 'white', fillOpacity: count > 0 ? 0.8 : 0.4 });
                        },
                        click: function() { 
                            if (orgId) {
                                window.location.href = '/organizations/' + orgId; 
                            } else {
                                window.location.href = `/organizations?search=${feature.properties.nm_kecamatan}`; 
                            }
                        }
                    });
                }
            }).addTo(map);
        })
        .catch(error => {
            console.error("Error Leaflet:", error);
        });

    // --- 2. CHART CONFIGS ---
    const textColor = '#6b7280'; 
    const gridColor = 'rgba(107, 114, 128, 0.15)'; 
    Chart.defaults.font.family = "'DM Sans', sans-serif";
    Chart.defaults.color = textColor;

    const opdNames  = {!! json_encode($opdNames) !!};
    const opdCounts = {!! json_encode($opdCounts) !!};
    const barColors = ['#34d399','#2dd4bf','#38bdf8','#818cf8','#a78bfa','#f472b6','#fb923c'];

    new Chart(document.getElementById('opdChart'), {
        type: 'bar',
        data: {
            labels: opdNames,
            datasets: [{ data: opdCounts, backgroundColor: barColors.map(c => c + '88'), hoverBackgroundColor: barColors, borderColor: barColors, borderWidth: 1, borderRadius: 6 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: gridColor, drawBorder: false }, ticks: { font: { weight: '600' } } },
                x: { grid: { display: false }, ticks: { maxRotation: 45, font: { weight: '600' } } }
            }
        }
    });

    new Chart(document.getElementById('unitChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($unitLabels) !!},
            datasets: [{ data: {!! json_encode($unitCounts) !!}, backgroundColor: ['#34d399','#38bdf8','#a78bfa','#fb923c','#f472b6'], borderWidth: 2, borderColor: 'white', hoverOffset: 12 }]
        },
        options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { position: 'bottom', labels: { font: { weight: '600' }, usePointStyle: true } } } }
    });

    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($trendYears) !!},
            datasets: [{ data: {!! json_encode($trendCounts) !!}, borderColor: '#10b981', borderWidth: 3, tension: 0.4, fill: true, backgroundColor: 'rgba(16, 185, 129, 0.1)', pointBackgroundColor: '#fff', pointBorderColor: '#10b981', pointRadius: 5 }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { grid: { color: gridColor }, ticks: { font: { weight: '600' } } }, x: { ticks: { font: { weight: '600' } } } } }
    });
});
</script>
@endsection