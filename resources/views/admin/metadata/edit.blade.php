@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-[#F8F9FA] dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-500 overflow-hidden relative" 
     x-data="{ sidebarOpen: window.innerWidth >= 1024 }" @resize.window="sidebarOpen = window.innerWidth >= 1024">
    
    <aside :class="sidebarOpen ? 'translate-x-0 shadow-xl' : '-translate-x-full shadow-none'" class="fixed inset-y-0 left-0 z-50 w-72 transform overflow-hidden bg-gradient-to-b from-teal-700 via-teal-600 to-teal-500 dark:from-teal-900 dark:via-teal-800 dark:to-teal-700 backdrop-blur-2xl border-r border-teal-400/30 transition-all duration-500 ease-in-out flex flex-col lg:relative lg:translate-x-0 lg:w-72 lg:shadow-xl lg:sticky lg:top-0 lg:h-screen lg:z-auto">
        <div class="p-6 h-24 border-b border-teal-400/30 flex items-center justify-between overflow-hidden shrink-0 text-left">
            <div class="flex items-center space-x-3 min-w-[180px]" x-show="sidebarOpen" x-transition>
                <img src="https://adminweb.sidoarjokab.go.id/upload/link/1703218932.png" alt="Logo" class="w-10 h-10 object-contain rounded-xl shadow-lg bg-white/90 p-1">
                <h2 class="text-xs font-black text-white leading-tight uppercase tracking-tighter">Satu Data<br>Kabupaten Sidoarjo</h2>
            </div>
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl hover:bg-teal-600/50 transition-colors shrink-0 text-white">
                <svg :class="!sidebarOpen ? 'rotate-180' : ''" class="w-6 h-6 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </button>
        </div>
        <nav class="flex-1 p-4 space-y-2 overflow-y-auto text-white text-left">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs group-hover:scale-110">DB</span>
            </a>
            <a href="{{ route('admin.metadata') }}" class="flex items-center px-6 py-4 bg-white/20 rounded-2xl shadow-lg font-bold text-sm transition-all">
                <span x-show="sidebarOpen">Kembali Ke Metadata</span>
            </a>
        </nav>
    </aside>

    <main class="flex-1 min-h-screen overflow-y-auto relative p-4 sm:p-6 lg:p-8 scroll-smooth text-left lg:ml-72">
        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 bg-black/30 lg:hidden" @click="sidebarOpen = false"></div>
        <div class="fixed top-0 right-0 w-[500px] h-[500px] bg-teal-200/20 dark:bg-teal-900/10 blur-[120px] rounded-full -z-10 opacity-60"></div>
        
        <header class="mb-4 sm:mb-6 flex flex-col md:flex-row justify-between md:items-center gap-3 sm:gap-4 max-w-5xl mx-auto w-full">
            <div class="flex items-center justify-between gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2.5 rounded-xl bg-white dark:bg-gray-900 shadow-sm border border-gray-100 dark:border-gray-800 text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <div>
                    <h1 class="text-xl sm:text-2xl font-black tracking-tight text-gray-900 dark:text-white uppercase leading-none">
                        Lengkapi <span class="text-teal-600">Metadata</span>
                    </h1>
                    <p class="text-[8px] sm:text-[10px] text-gray-400 font-black uppercase tracking-[0.3em] mt-1.5 sm:mt-2">Dataset ID: {{ $dataset->dssd_code }}</p>
                </div>
            </div>
        </header>

        <div class="max-w-5xl mx-auto w-full px-2 sm:px-0">
            <div class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-2xl rounded-[24px] sm:rounded-[32px] shadow-2xl border border-white dark:border-gray-800 p-4 sm:p-6 lg:p-8 relative overflow-hidden">
                
                <div class="text-center mb-4 sm:mb-6">
                    <h2 class="text-base sm:text-lg font-black uppercase tracking-tighter text-gray-800 dark:text-white">Informasi Metadata Lengkap</h2>
                    <div class="h-1 w-16 bg-teal-500 mx-auto mt-1.5 sm:mt-2 rounded-full"></div>
                </div>
                
                <form action="{{ route('admin.metadata.update', $dataset->id) }}" method="POST" class="space-y-3 sm:space-y-5 relative z-10">
                    @csrf
                    
                    <div class="space-y-2.5 sm:space-y-4 border-b border-gray-100 dark:border-gray-800 pb-3 sm:pb-5">
                        <div class="group">
                            <label class="block text-[8px] sm:text-[9px] font-black uppercase tracking-[0.2em] text-gray-400 mb-1 sm:mb-1.5 ml-2 sm:ml-4 transition-colors group-focus-within:text-teal-600">Judul Dataset *</label>
                            <input type="text" name="name" value="{{ old('name', $dataset->name) }}" class="w-full px-3 sm:px-6 py-2 sm:py-3 rounded-[12px] sm:rounded-[16px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 outline-none text-[9px] sm:text-sm font-bold shadow-sm" required />
                        </div>

                        <div class="group">
                            <label class="block text-[8px] sm:text-[9px] font-black uppercase tracking-[0.2em] text-gray-400 mb-1 sm:mb-1.5 ml-2 sm:ml-4 transition-colors group-focus-within:text-teal-600">Abstrak / Deskripsi Lengkap *</label>
                            <textarea name="description" rows="2" class="w-full px-3 sm:px-6 py-2 sm:py-3 rounded-[12px] sm:rounded-[16px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 outline-none text-[9px] sm:text-sm font-medium leading-relaxed shadow-sm" required>{{ old('description', $dataset->description) }}</textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 sm:gap-4 lg:gap-6">
                        <div class="group">
                            <label class="block text-[8px] sm:text-[9px] font-black uppercase tracking-[0.2em] text-gray-400 mb-1 sm:mb-1.5 ml-2 sm:ml-4 transition-colors group-focus-within:text-teal-600">Satuan Data *</label>
                            <input type="text" name="unit" value="{{ old('unit', $dataset->unit) }}" required placeholder="Contoh: Orang" class="w-full px-3 sm:px-6 py-2 sm:py-3 rounded-[12px] sm:rounded-[16px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 outline-none text-[9px] sm:text-sm font-bold shadow-sm" />
                        </div>
                        
                        <div class="group">
                            <label class="block text-[8px] sm:text-[9px] font-black uppercase tracking-[0.2em] text-gray-400 mb-1 sm:mb-1.5 ml-2 sm:ml-4 transition-colors group-focus-within:text-teal-600">Frekuensi Pembaruan *</label>
                            <div class="relative">
                                <select name="frequency" required class="w-full px-3 sm:px-6 py-2 sm:py-3 rounded-[12px] sm:rounded-[16px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 outline-none text-[9px] sm:text-sm font-bold shadow-sm appearance-none cursor-pointer">
                                    <option value="Tahunan" {{ (old('frequency', $dataset->frequency) == 'Tahunan') ? 'selected' : '' }}>Tahunan</option>
                                    <option value="Semester" {{ (old('frequency', $dataset->frequency) == 'Semester') ? 'selected' : '' }}>Semester</option>
                                    <option value="Bulanan" {{ (old('frequency', $dataset->frequency) == 'Bulanan') ? 'selected' : '' }}>Bulanan</option>
                                </select>
                            </div>
                        </div>

                        <div class="group">
                            <label class="block text-[8px] sm:text-[9px] font-black uppercase tracking-[0.2em] text-gray-400 mb-1 sm:mb-1.5 ml-2 sm:ml-4 transition-colors group-focus-within:text-teal-600">Cakupan Wilayah *</label>
                            <input type="text" name="level" value="{{ old('level', $dataset->level ?? 'Kabupaten Sidoarjo') }}" required class="w-full px-3 sm:px-6 py-2 sm:py-3 rounded-[12px] sm:rounded-[16px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 outline-none text-[9px] sm:text-sm font-bold shadow-sm" />
                        </div>

                        <div class="group">
                            <label class="block text-[8px] sm:text-[9px] font-black uppercase tracking-[0.2em] text-gray-400 mb-1 sm:mb-1.5 ml-2 sm:ml-4 transition-colors group-focus-within:text-teal-600">Tags (Kata Kunci) *</label>
                            <input type="text" name="tags" value="{{ old('tags', $dataset->tags) }}" required placeholder="Contoh: Penduduk" class="w-full px-3 sm:px-6 py-2 sm:py-3 rounded-[12px] sm:rounded-[16px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 outline-none text-[9px] sm:text-sm font-bold shadow-sm" />
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-4 pt-3 sm:pt-5 border-t border-gray-100 dark:border-gray-800">
                        <button type="button" onclick="window.history.back()" class="w-full sm:w-auto px-6 sm:px-8 py-2.5 sm:py-3.5 bg-gray-100 dark:bg-gray-800 text-gray-500 rounded-full font-black text-[8px] sm:text-[9px] uppercase tracking-widest hover:bg-red-50 hover:text-red-600 transition-all shadow-sm">Batal</button>
                        <button type="submit" class="w-full sm:w-auto px-6 sm:px-10 py-2.5 sm:py-3.5 bg-teal-600 text-white rounded-full font-black text-[8px] sm:text-[9px] uppercase tracking-widest hover:bg-teal-700 shadow-xl shadow-teal-500/30 transition-all active:scale-95">Simpan Metadata</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection