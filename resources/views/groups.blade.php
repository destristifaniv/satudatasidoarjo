@extends('layouts.app')

@section('content')
<div x-data="{ mobileMenuOpen: false }" class="relative min-h-screen bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 overflow-x-hidden">
    
    {{-- Floating Header --}}
    <header class="fixed top-0 left-0 right-0 z-50 px-4 pt-4 md:pt-5 transition-all duration-300">
        <div class="max-w-6xl mx-auto bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-2xl md:rounded-full shadow-xl px-4 md:px-6 py-3 flex items-center justify-between border border-white/30 dark:border-gray-700">
            <div class="flex items-center space-x-3 text-left">
                <img src="https://adminweb.sidoarjokab.go.id/upload/link/1703218932.png" 
                     alt="Logo Kabupaten Sidoarjo" 
                     class="w-8 h-8 md:w-9 md:h-9 object-contain">
                <h1 class="text-sm md:text-lg font-bold text-gray-800 dark:text-white leading-tight">
                    <span class="block">Satu Data</span>
                    <span class="block text-[10px] md:text-sm font-semibold opacity-80">Kab. Sidoarjo</span>
                </h1>
            </div>

            <nav class="hidden md:flex space-x-6">
                <a href="/" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 font-medium transition">Home</a>
                <a href="/datasets" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 font-medium transition">Datasets</a>
                <a href="/organizations" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 font-medium transition">Organizations</a>
                <a href="/groups" class="text-sm text-green-600 font-bold transition">Groups</a>
                <a href="/about" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 font-medium transition">About</a>
            </nav>

            <div class="flex items-center space-x-2 md:space-x-4">
                <button onclick="window.location.href='{{ url('/login') }}'" class="hidden md:block bg-green-700 hover:bg-green-800 text-white px-5 py-2 rounded-full transition text-sm font-medium shadow-lg shadow-green-500/20">Login</button>
                <button id="theme-toggle" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg id="theme-toggle-light-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707-.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"></path></svg>
                </button>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                    <svg x-show="mobileMenuOpen" style="display: none;" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
        {{-- Mobile Dropdown --}}
        <div x-show="mobileMenuOpen" x-transition class="md:hidden mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex flex-col space-y-3 pb-2 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-2xl p-4 shadow-xl">
            <a href="/" class="text-sm text-gray-700 dark:text-gray-300 font-medium">Home</a>
            <a href="/datasets" class="text-sm text-gray-700 dark:text-gray-300 font-medium">Datasets</a>
            <a href="/organizations" class="text-sm text-gray-700 dark:text-gray-300 font-medium">Organizations</a>
            <a href="/groups" class="text-sm text-green-600 font-bold">Groups</a>
            <a href="/about" class="text-sm text-gray-700 dark:text-gray-300 font-medium">About</a>
            <button onclick="window.location.href='{{ url('/login') }}'" class="w-full mt-2 bg-green-700 text-white px-5 py-2.5 rounded-xl font-medium">Login</button>
        </div>
    </header>

    <main class="max-w-6xl mx-auto pt-32 pb-20 px-4 md:px-6">
        @php
            $dummyGroups = [
                ['name' => '1.01 URUSAN PEMERINTAHAN BIDANG PENDIDIKAN', 'datasets' => 626, 'members' => 1],
                ['name' => '1.02 URUSAN PEMERINTAHAN BIDANG KESEHATAN', 'datasets' => 253, 'members' => 1],
                ['name' => '1.03 URUSAN PEMERINTAHAN BIDANG PEKERJAAN UMUM DAN PENATAAN RUANG', 'datasets' => 124, 'members' => 1],
                ['name' => '1.04 URUSAN PEMERINTAHAN BIDANG PERUMAHAN DAN KAWASAN PERMUKIMAN', 'datasets' => 28, 'members' => 1],
                ['name' => '1.05 URUSAN KETENTRAMAN DAN KETERTIBAN UMUM', 'datasets' => 15, 'members' => 1],
                ['name' => '1.06 URUSAN PEMERINTAHAN BIDANG SOSIAL', 'datasets' => 45, 'members' => 1],
            ];
            $displayGroups = isset($groups) && count($groups) > 0 ? $groups : $dummyGroups;
        @endphp

        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4 px-2">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight leading-none">
                    Klasifikasi <span class="text-green-700">Grup Data</span>
                </h2>
                <p class="text-sm md:text-[15px] text-gray-500 mt-2 opacity-70">Daftar pengelompokan data berdasarkan urusan pemerintahan</p>
            </div>
            <div class="text-left md:text-right">
                <span class="text-3xl font-black text-green-700 leading-none">{{ count($displayGroups) }}</span> 
                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-2">GRUP TERDAFTAR</span>
            </div>
        </div>

        {{-- SEARCH BAR --}}
        <div class="relative w-full mb-8 px-2">
            <input type="text" placeholder="Cari nama grup data..." class="w-full pl-12 pr-6 py-4 rounded-2xl shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 font-medium text-sm transition-all">
            <svg class="w-5 h-5 text-gray-400 absolute left-6 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>

        {{-- GRID RESPONSIVE: 2 kolom (HP), 4 kolom (Laptop) --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6 px-2">
            @foreach($displayGroups as $group)
            <a href="https://opendata.sidoarjokab.go.id/group/" target="_blank" class="group flex flex-col bg-white dark:bg-gray-900 rounded-2xl md:rounded-[32px] p-3 md:p-6 border border-gray-100 dark:border-gray-800 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="h-16 md:h-20 bg-green-50 dark:bg-green-900/20 rounded-xl md:rounded-2xl flex items-center justify-center mb-4 shrink-0">
                    <span class="text-xl md:text-2xl">📁</span>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-[10px] md:text-xs text-gray-800 dark:text-white uppercase leading-snug line-clamp-2 md:h-10 text-center">
                        {{ $group['name'] }}
                    </h3>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-50 dark:border-gray-800 flex justify-between items-center text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-wider">
                    <span>{{ $group['datasets'] }} DATA</span>
                    <span class="text-green-700">{{ $group['members'] }} MEMBER</span>
                </div>
            </a>
            @endforeach
        </div>
    </main>

    <footer class="py-10 border-t border-gray-100 dark:border-gray-900 text-center">
        <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.4em]">© {{ date('Y') }} PEMKAB SIDOARJO</p>
    </footer>
</div>
@endsection