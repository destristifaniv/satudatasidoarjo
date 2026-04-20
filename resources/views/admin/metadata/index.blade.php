@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F8F9FA] dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-500 overflow-hidden" 
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
     }">
    
    <aside :class="sidebarOpen ? 'w-72' : 'w-0 md:w-20'" class="relative h-full bg-gradient-to-b from-teal-700 via-teal-600 to-teal-500 dark:from-teal-900 dark:via-teal-800 dark:to-teal-700 backdrop-blur-2xl border-r border-teal-400/30 transition-all duration-500 ease-in-out flex flex-col sticky top-0 z-50 shadow-xl overflow-hidden shrink-0">
        <div class="p-6 h-24 border-b border-teal-400/30 flex items-center justify-between overflow-hidden">
            <div class="flex items-center space-x-3 min-w-[180px]" x-show="sidebarOpen" x-transition>
                <img src="https://adminweb.sidoarjokab.go.id/upload/link/1703218932.png" alt="Logo" class="w-10 h-10 object-contain rounded-xl shadow-lg bg-white/90 p-1">
                <h2 class="text-xs font-black text-white leading-tight uppercase tracking-tighter text-left">Satu Data<br>Kabupaten Sidoarjo</h2>
            </div>
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl hover:bg-teal-600/50 transition-colors shrink-0 text-white">
                <svg :class="!sidebarOpen ? 'rotate-180' : ''" class="w-6 h-6 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 p-4 space-y-2 overflow-y-auto text-white text-left">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group text-left">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs group-hover:scale-110">DB</span>
            </a>
            <a href="{{ route('admin.upload.dataset') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group text-left">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Unggah Dataset</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs text-center">UG</span>
            </a>
            <a href="{{ route('admin.metadata') }}" class="flex items-center px-6 py-4 bg-white/20 rounded-2xl shadow-lg font-bold text-sm transition-all text-left">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Kelola Metadata</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs text-center">MT</span>
            </a>
            <a href="{{ route('admin.manage-dataset') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group text-left">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Kelola Dataset</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs">KL</span>
            </a>
            <a href="{{ route('admin.organizations') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group text-left">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Organisasi</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs group-hover:scale-110">OR</span>
            </a>
        </nav>
    </aside>

    <main class="flex-1 h-full overflow-y-auto relative p-6 lg:p-8 scroll-smooth text-left">
        <div class="fixed top-0 right-0 w-[400px] h-[400px] bg-teal-200/20 dark:bg-teal-900/10 blur-[100px] rounded-full -z-10 opacity-50"></div>
        
        {{-- HEADER --}}
        <header class="mb-8 flex flex-col md:flex-row justify-between md:items-end gap-4">
            <div class="text-left">
                <h1 class="text-2xl font-black tracking-tight text-gray-900 dark:text-white uppercase leading-none">
                    Kelola <span class="text-teal-600">Metadata</span>
                </h1>
                <p class="text-[9px] text-gray-400 font-black uppercase tracking-[0.2em] mt-2">Definisi Struktur Data & Kamus Informasi</p>
            </div>

            <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                {{-- Dropdown Tahun --}}
                <div class="relative group">
                    <select x-model="yearFilter" @change="updateFilter()"
                            class="appearance-none pl-4 pr-10 py-3 bg-white/50 dark:bg-gray-900/50 backdrop-blur-xl border border-white dark:border-gray-800 rounded-[16px] text-[9px] font-black uppercase tracking-widest outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500/30 transition-all shadow-sm cursor-pointer">
                        <option value="all">SEMUA TAHUN</option>
                        @for ($year = 2025; $year >= 2019; $year--)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endfor
                    </select>
                </div>

                {{-- Search Bar --}}
                <div class="relative w-full md:w-64 group">
                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-gray-400 group-focus-within:text-teal-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        x-model="searchQuery"
                        @keydown.enter="updateFilter()"
                        placeholder="CARI DSSD ATAU NAMA..." 
                        class="w-full pl-10 pr-4 py-3 bg-white/50 dark:bg-gray-900/50 backdrop-blur-xl border border-white dark:border-gray-800 rounded-[16px] text-[9px] font-black uppercase tracking-widest outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500/30 transition-all shadow-sm"
                    >
                </div>
            </div>
        </header>

        {{-- TABEL DATA --}}
        <div class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-2xl rounded-[32px] shadow-lg border border-white dark:border-gray-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400">Kode DSSD</th>
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400">Nama Dataset</th>
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400">Tahun</th>
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400">Kelengkapan</th>
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
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
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 bg-teal-100 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 rounded-lg text-[9px] font-black tracking-widest uppercase border border-teal-200 dark:border-teal-800">
                                    {{ $dataset->dssd_code }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-xs text-gray-800 dark:text-gray-200">
                                {{ $dataset->name }}
                            </td>
                            <td class="px-6 py-4 text-[11px] text-gray-500 font-bold">
                                {{ $dataset->year_start }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <div class="w-12 h-1 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-full transition-all duration-1000 {{ $score == 100 ? 'bg-emerald-500' : 'bg-teal-500' }}" 
                                             style="width: {{ $score }}%"></div>
                                    </div>
                                    <span class="text-[8px] font-black {{ $score == 100 ? 'text-emerald-500' : 'text-teal-500' }} uppercase">
                                        {{ $score }}/100
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center">
                                    <a href="{{ route('admin.metadata.edit', $dataset->id) }}" 
                                       class="px-4 py-2 {{ $score == 100 ? 'bg-emerald-600' : 'bg-teal-600' }} text-white rounded-xl font-black text-[8px] uppercase tracking-widest hover:opacity-80 transition-all shadow-md shadow-teal-500/20 whitespace-nowrap">
                                        {{ $score == 100 ? 'Lihat Metadata' : 'Lengkapi Metadata' }}
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-gray-400 font-bold uppercase tracking-widest text-[9px]">Data tidak ditemukan untuk kriteria ini</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 bg-gray-50/30 dark:bg-gray-800/30 border-t border-gray-100 dark:border-gray-800 text-[10px]">
                {{ $datasets->onEachSide(0)->links() }}
            </div>
        </div>
    </main>
</div>
@endsection