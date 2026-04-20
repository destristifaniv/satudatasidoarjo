@extends('layouts.app')

@section('content')
<div class="relative min-h-screen bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 overflow-x-hidden" x-data="orgModal()">
    
    {{-- <div class="absolute top-0 left-0 w-full h-[400px] overflow-hidden pointer-events-none">
        <div class="absolute inset-0 bg-cover bg-center opacity-20 dark:opacity-10"
            style="background-image: url('{{ asset('images/bg-opendata.jpg') }}');">
        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-gray-100/50 via-gray-50 to-gray-50 dark:from-gray-900/50 dark:via-gray-950 dark:to-gray-950"></div>
    </div> --}}

    <div class="relative z-10">
        
        {{-- HEADER / NAVBAR --}}
        <header class="fixed top-0 left-0 right-0 z-50 px-4 pt-5 transition-all duration-300">
            <div class="max-w-6xl mx-auto bg-white/70 dark:bg-gray-900/70 backdrop-blur-md rounded-full shadow-xl px-6 py-3 flex items-center justify-between border border-white/30 dark:border-gray-700 text-left">
                <div class="flex items-center space-x-3 text-left">
                    <img src="https://adminweb.sidoarjokab.go.id/upload/link/1703218932.png" 
                         alt="Logo Kabupaten Sidoarjo" 
                         class="w-9 h-9 object-contain">
                    <h1 class="text-sm md:text-lg font-bold text-gray-800 dark:text-white leading-tight">
                        <span class="block text-left">Satu Data</span>
                        <span class="block text-xs md:text-sm font-semibold opacity-80">Kabupaten Sidoarjo</span>
                    </h1>
                </div>

                <nav class="hidden md:flex space-x-6">
                    <a href="/" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-medium transition">Home</a>
                    <a href="/datasets" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-medium transition">Datasets</a>
                    <a href="/organizations" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-medium transition">Organizations</a>
                    <a href="/groups" class="text-sm text-green-600 font-bold transition">Groups</a>
                    <a href="/about" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-medium transition">About</a>
                </nav>

                <div class="flex items-center space-x-4">
                    <button onclick="window.location.href='{{ url('/login') }}'" class="bg-green-700 hover:bg-green-800 text-white px-5 py-2 rounded-full transition text-sm font-medium shadow-lg shadow-green-500/20">
                        Login
                    </button>
                    <button id="theme-toggle" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                        <svg id="theme-toggle-light-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"></path></svg>
                    </button>
                </div>
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="pt-32 pb-16 px-4 md:px-0 max-w-6xl mx-auto">
            @php
                // Data tiruan
                $dummyGroups = [
                    ['name' => '1.01 URUSAN PEMERINTAHAN BIDANG PENDIDIKAN', 'datasets' => 626, 'members' => 1],
                    ['name' => '1.02 URUSAN PEMERINTAHAN BIDANG KESEHATAN', 'datasets' => 253, 'members' => 1],
                    ['name' => '1.03 URUSAN PEMERINTAHAN BIDANG PEKERJAAN UMUM DAN PENATAAN RUANG', 'datasets' => 124, 'members' => 1],
                    ['name' => '1.04 URUSAN PEMERINTAHAN BIDANG PERUMAHAN DAN KAWASAN PERMUKIMAN', 'datasets' => 28, 'members' => 1],
                    ['name' => '1.05 URUSAN PEMERINTAHAN KETENTRAMAN DAN KETERTIBAN UMUM SERTA PERLINDUNGAN MASYARAKAT', 'datasets' => 15, 'members' => 1],
                    ['name' => '1.06 URUSAN PEMERINTAHAN BIDANG SOSIAL', 'datasets' => 45, 'members' => 1],
                ];
                $displayGroups = isset($groups) && count($groups) > 0 ? $groups : $dummyGroups;
            @endphp

            {{-- HEADER KONTEN --}}
            <div class="flex flex-col md:flex-row justify-between md:items-end mt-4 mb-5 gap-4 px-2">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight leading-none">
                        Klasifikasi <span class="text-green-700 dark:text-green-500">Grup Data</span>
                    </h2>
                    <p class="text-[15px] text-gray-500 mt-2 opacity-70">
                        Daftar pengelompokan data berdasarkan urusan pemerintahan Kabupaten Sidoarjo
                    </p>
                </div>
                <div class="text-left md:text-right flex items-baseline md:justify-end">
                    <span class="text-3xl font-black text-green-700 dark:text-green-500 leading-none">{{ count($displayGroups) }}</span>
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest ml-2 mb-1">GRUP TERDAFTAR</span>
                </div>
            </div>

            {{-- KONTEN TUNGGAL --}}
            <div class="w-full bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-[20px] shadow-lg p-5 md:p-6 border border-white dark:border-gray-800">
                
                {{-- SEARCH & SORTING BAR --}}
                <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">
                    <div class="relative w-full md:w-2/3 lg:w-1/2">
                        <input type="text" placeholder="Cari nama grup data..." 
                               class="w-full pl-4 pr-10 py-2.5 rounded-xl text-xs shadow-inner focus:outline-none focus:ring-2 focus:ring-green-500 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 font-medium placeholder-gray-400 transition-all">
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </div>

                    <div class="flex items-center bg-gray-50 dark:bg-gray-800 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 w-full md:w-auto">
                        <span class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mr-2">Urutkan:</span>
                        <div class="relative w-full md:w-auto">
                            <select class="appearance-none bg-transparent border-none text-gray-800 dark:text-gray-200 p-0 pr-5 w-full md:w-auto rounded-md text-[10px] font-bold cursor-pointer focus:outline-none focus:ring-0">
                                <option>Nama (A-Z)</option>
                                <option>Nama (Z-A)</option>
                                <option>Dataset Terbanyak</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center text-gray-500">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7-7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kumpulan Kartu Grup --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($displayGroups as $group)
                    
                    {{-- LINK LANGSUNG KE HALAMAN GROUP UTAMA --}}
                    <a href="https://opendata.sidoarjokab.go.id/group/" target="_blank" class="group flex flex-col bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        
                        <div class="h-20 bg-green-50 dark:bg-green-900/30 flex flex-col items-center justify-center border-b border-gray-100 dark:border-gray-700/50 group-hover:bg-green-100 dark:group-hover:bg-green-900/50 transition-colors relative overflow-hidden">
                            <div class="absolute -right-4 -top-4 w-12 h-12 bg-green-200/50 dark:bg-green-700/20 rounded-full blur-xl"></div>
                            <div class="w-10 h-10 bg-white dark:bg-gray-800 rounded-xl flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform duration-300 border border-green-100 dark:border-green-800 z-10">
                                <span class="text-green-600 opacity-80">📁</span>
                            </div>
                        </div>

                        <div class="p-3.5 flex-1 flex flex-col text-left">
                            <p class="text-[8px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Klasifikasi Bidang</p>
                            <h3 class="font-black text-[11px] text-gray-900 dark:text-gray-100 uppercase leading-snug mb-2 group-hover:text-green-600 transition-colors line-clamp-2 flex-1" title="{{ $group['name'] }}">
                                {{ $group['name'] }}
                            </h3>
                            
                            <p class="text-[9px] text-gray-500 dark:text-gray-400 mb-3 line-clamp-2 leading-relaxed font-medium">
                                Berdasarkan klasifikasi DSSD di SIPD E-Walidata
                            </p>
                            
                            <div class="mt-auto pt-2.5 border-t border-gray-100 dark:border-gray-700/50 flex justify-between items-end gap-2">
                                <div class="flex-1">
                                    <p class="text-[8px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-tighter">Total Dataset</p>
                                    <span class="text-sm font-black text-green-600">{{ $group['datasets'] }}</span>
                                </div>
                                <div class="text-right">
                                    <p class="text-[8px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-tighter">Anggota</p>
                                    <span class="text-[9px] font-bold text-gray-700 dark:text-gray-300">{{ $group['members'] }} Member</span>
                                </div>
                            </div>

                            <div class="mt-3 w-full py-1.5 rounded-lg bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-700 text-green-600 font-bold text-[9px] hover:bg-green-600 hover:text-white hover:border-green-600 transition-all duration-300 flex items-center justify-center space-x-1.5 uppercase tracking-wider">
                                <span>Buka Grup</span>
                                <span class="group-hover:translate-x-1 transition-transform">→</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6 flex justify-center border-t border-gray-100 dark:border-gray-800 pt-5">
                    <nav class="flex items-center space-x-1.5 bg-gray-50 dark:bg-gray-800 p-1.5 rounded-xl border border-gray-200 dark:border-gray-700">
                        <button class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-white dark:hover:bg-gray-700 text-gray-400 transition" disabled>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <button class="w-7 h-7 flex items-center justify-center rounded-lg bg-green-600 text-white font-bold shadow-md text-xs">1</button>
                        <button class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-white dark:hover:bg-gray-700 font-bold text-gray-700 dark:text-gray-300 transition text-xs">2</button>
                        <button class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-white dark:hover:bg-gray-700 font-bold text-gray-700 dark:text-gray-300 transition text-xs">3</button>
                        <button class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-white dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </nav>
                </div>

            </div>
        </main>

        <footer class="bg-white dark:bg-gray-950 py-10 border-t border-gray-100 dark:border-gray-900 text-center text-left">
            <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.4em] text-center">© {{ date('Y') }} PEMKAB SIDOARJO • PORTAL SATU DATA</p>
        </footer>
    </div>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection