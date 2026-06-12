@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-[#F8F9FA] dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-500 overflow-x-hidden relative" 
     x-data="{ 
        sidebarOpen: window.innerWidth >= 1024,
        searchQuery: '{{ request('search') }}',
        yearFilter: '{{ request('year', 'all') }}',

        // Fungsi vital untuk mengirim filter ke Controller
        updateFilter() {
            let url = new URL(window.location.href);
            url.searchParams.set('year', this.yearFilter);
            url.searchParams.set('search', this.searchQuery);
            url.searchParams.set('page', 1); 
            window.location.href = url.toString();
        }
     }" @resize.window="sidebarOpen = window.innerWidth >= 1024">
    
    <aside :class="sidebarOpen ? 'translate-x-0 shadow-xl' : '-translate-x-full shadow-none'" 
           class="fixed inset-y-0 left-0 z-50 w-72 transform bg-gradient-to-b from-teal-700 via-teal-600 to-teal-500 dark:from-teal-900 dark:via-teal-800 dark:to-teal-700 backdrop-blur-2xl border-r border-teal-400/30 transition-transform duration-500 ease-in-out flex flex-col">
        
        <div class="p-6 h-24 border-b border-teal-400/30 flex items-center justify-between overflow-hidden shrink-0 text-left">
            <div class="flex items-center space-x-3 min-w-[180px]" x-show="sidebarOpen" x-transition>
                <img src="https://adminweb.sidoarjokab.go.id/upload/link/1703218932.png" alt="Logo" class="w-10 h-10 object-contain rounded-xl shadow-lg bg-white/90 p-1">
                <h2 class="text-xs font-black text-white leading-tight uppercase tracking-tighter">Satu Data<br>Kabupaten Sidoarjo</h2>
            </div>
            <button @click="sidebarOpen = false" class="p-2 rounded-xl hover:bg-teal-600/50 transition-colors shrink-0 text-white">
                <svg class="w-6 h-6 transition-transform duration-500 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 p-4 space-y-2 overflow-y-auto text-white text-left">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group text-left">
                <span class="whitespace-nowrap">Dashboard</span>
            </a>
            <a href="{{ route('admin.upload.dataset') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group text-left">
                <span class="whitespace-nowrap">Unggah Dataset</span>
            </a>
            <a href="{{ route('admin.metadata') }}" class="flex items-center px-6 py-4 bg-white/20 rounded-2xl shadow-lg font-bold text-sm transition-all text-left">
                <span class="whitespace-nowrap">Kelola Metadata</span>
            </a>
            <a href="{{ route('admin.manage-dataset') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group text-left">
                <span class="whitespace-nowrap">Kelola Dataset</span>
            </a>
            <a href="{{ route('admin.organizations') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group text-left">
                <span class="whitespace-nowrap">Organisasi</span>
            </a>
        </nav>
    </aside>

    <main :class="sidebarOpen ? 'lg:ml-72' : 'ml-0'" class="flex-1 min-h-screen relative transition-all duration-500 overflow-y-auto p-4 sm:p-6 lg:p-8 scroll-smooth text-left">
        
        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 bg-black/30 lg:hidden" @click="sidebarOpen = false"></div>
        
        <div class="fixed top-0 right-0 w-[300px] sm:w-[400px] h-[300px] sm:h-[400px] bg-teal-200/20 dark:bg-teal-900/10 blur-[80px] sm:blur-[100px] rounded-full -z-10 opacity-50"></div>
        
        {{-- HEADER --}}
        <header class="mb-6 lg:mb-8 flex flex-col lg:flex-row justify-between lg:items-end gap-4 sm:gap-6">
            <div class="flex items-start sm:items-center gap-3 sm:gap-4">
                <button @click="sidebarOpen = true" :class="sidebarOpen ? 'lg:hidden' : 'block'" class="p-2.5 mt-1 sm:mt-0 rounded-xl bg-white dark:bg-gray-900 shadow-sm border border-gray-100 dark:border-gray-800 text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <div class="text-left">
                    <h1 class="text-xl sm:text-2xl font-black tracking-tight text-gray-900 dark:text-white uppercase leading-none">
                        Kelola <span class="text-teal-600">Metadata</span>
                    </h1>
                    <p class="text-[8px] sm:text-[9px] text-gray-400 font-black uppercase tracking-[0.2em] mt-1.5 sm:mt-2">Definisi Struktur Data & Kamus Informasi</p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 w-full lg:w-auto">
                {{-- Dropdown Tahun --}}
                <div class="relative group w-full sm:w-auto">
                    <select x-model="yearFilter" @change="updateFilter()"
                            class="appearance-none w-full sm:w-auto pl-4 pr-10 py-3 sm:py-3.5 bg-white/50 dark:bg-gray-900/50 backdrop-blur-xl border border-white dark:border-gray-800 rounded-2xl text-[10px] sm:text-[9px] font-black uppercase tracking-widest outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500/30 transition-all shadow-sm cursor-pointer">
                        <option value="all">SEMUA TAHUN</option>
                        @for ($year = 2025; $year >= 2019; $year--)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endfor
                    </select>
                </div>

                {{-- Search Bar --}}
                <div class="relative w-full sm:w-64 md:w-72 group">
                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400 group-focus-within:text-teal-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        x-model="searchQuery"
                        @keydown.enter="updateFilter()"
                        placeholder="CARI DSSD ATAU NAMA..." 
                        class="w-full pl-11 pr-4 py-3 sm:py-3.5 bg-white/50 dark:bg-gray-900/50 backdrop-blur-xl border border-white dark:border-gray-800 rounded-2xl text-[10px] sm:text-[9px] font-black uppercase tracking-widest outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500/30 transition-all shadow-sm"
                    >
                </div>
            </div>
        </header>

        {{-- TABEL DATA --}}
        <div class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-2xl rounded-[24px] sm:rounded-[32px] shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-white dark:border-gray-800 overflow-hidden relative z-10">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse whitespace-nowrap md:whitespace-normal min-w-[800px]">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                            <th class="px-5 sm:px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400">Kode DSSD</th>
                            <th class="px-5 sm:px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400">Nama Dataset</th>
                            <th class="px-5 sm:px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400">Tahun</th>
                            <th class="px-5 sm:px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400">Kelengkapan</th>
                            <th class="px-5 sm:px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800/60">
                        @forelse($datasets as $dataset)
                        @php
                            $score = 0;
                            if($dataset->dssd_code) $score += 20;
                            if($dataset->description) $score += 20;
                            if($dataset->tags) $score += 20;
                            if($dataset->unit) $score += 20;
                            if($dataset->frequency) $score += 20;
                        @endphp
                        <tr class="hover:bg-teal-50/30 dark:hover:bg-teal-900/10 transition-colors">
                            <td class="px-5 sm:px-6 py-4">
                                <span class="px-3 py-1.5 bg-teal-100 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 rounded-lg text-[9px] font-black tracking-widest uppercase border border-teal-200 dark:border-teal-800">
                                    {{ $dataset->dssd_code }}
                                </span>
                            </td>
                            <td class="px-5 sm:px-6 py-4 font-bold text-xs text-gray-800 dark:text-gray-200 max-w-xs md:max-w-md truncate">
                                {{ $dataset->name }}
                            </td>
                            <td class="px-5 sm:px-6 py-4 text-[10px] sm:text-[11px] text-gray-500 font-bold">
                                {{ $dataset->year_start }}
                            </td>
                            <td class="px-5 sm:px-6 py-4">
                                <div class="flex items-center space-x-2.5">
                                    <div class="w-16 sm:w-20 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-full transition-all duration-1000 {{ $score == 100 ? 'bg-emerald-500' : 'bg-teal-500' }}" 
                                             style="width: {{ $score }}%"></div>
                                    </div>
                                    <span class="text-[8px] sm:text-[9px] font-black {{ $score == 100 ? 'text-emerald-500' : 'text-teal-500' }} uppercase whitespace-nowrap">
                                        {{ $score }}/100
                                    </span>
                                </div>
                            </td>
                            <td class="px-5 sm:px-6 py-4">
                                <div class="flex items-center justify-center">
                                    <a href="{{ route('admin.metadata.edit', $dataset->id) }}" 
                                       class="px-4 sm:px-5 py-2 sm:py-2.5 {{ $score == 100 ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-teal-600 hover:bg-teal-700' }} text-white rounded-xl font-black text-[8px] sm:text-[9px] uppercase tracking-widest transition-all shadow-md shadow-teal-500/20 active:scale-95 whitespace-nowrap">
                                        {{ $score == 100 ? 'Lihat' : 'Lengkapi' }}
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 sm:px-6 py-12 sm:py-16 text-center text-gray-400 font-bold uppercase tracking-widest text-[9px]">Data tidak ditemukan untuk kriteria ini</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-5 sm:px-6 py-4 bg-gray-50/30 dark:bg-gray-800/30 border-t border-gray-100 dark:border-gray-800 text-[10px] overflow-x-auto">
                {{ $datasets->onEachSide(0)->links() }}
            </div>
        </div>
    </main>
</div>

<style>
    /* Styling scrollbar tabel agar lebih rapi */
    .custom-scrollbar::-webkit-scrollbar { height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #475569; }
    [x-cloak] { display: none !important; }
</style>
@endsection