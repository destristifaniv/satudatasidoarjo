@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F8F9FA] dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-500 overflow-hidden" 
     x-data="{ sidebarOpen: window.innerWidth >= 1024 }">
    
    <aside :class="sidebarOpen ? 'w-72' : 'w-0 md:w-20'" class="relative h-full bg-gradient-to-b from-teal-700 via-teal-600 to-teal-500 dark:from-teal-900 dark:via-teal-800 dark:to-teal-700 backdrop-blur-2xl border-r border-teal-400/30 transition-all duration-500 ease-in-out flex flex-col sticky top-0 z-50 shadow-xl overflow-hidden shrink-0">
        <div class="p-6 h-24 border-b border-teal-400/30 flex items-center justify-between overflow-hidden shrink-0">
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
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs group-hover:scale-110">DB</span>
            </a>
            <a href="{{ route('admin.metadata') }}" class="flex items-center px-6 py-4 bg-white/20 rounded-2xl shadow-lg font-bold text-sm transition-all">
                <span x-show="sidebarOpen">Kembali Ke Metadata</span>
            </a>
        </nav>
    </aside>

    <main class="flex-1 h-full overflow-y-auto relative p-6 lg:p-10 scroll-smooth text-left">
        <div class="fixed top-0 right-0 w-[500px] h-[500px] bg-teal-200/20 dark:bg-teal-900/10 blur-[120px] rounded-full -z-10 opacity-60"></div>
        
        <header class="mb-10 flex flex-col md:flex-row justify-between md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white uppercase leading-none">
                    Lengkapi <span class="text-teal-600">Metadata</span>
                </h1>
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.3em] mt-3">Dataset ID: {{ $dataset->dssd_code }}</p>
            </div>
        </header>

        <div class="max-w-5xl mx-auto">
            <div class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-2xl rounded-[48px] shadow-2xl border border-white dark:border-gray-800 p-8 lg:p-14 relative overflow-hidden">
                
                <div class="text-center mb-12">
                    <h2 class="text-2xl font-black uppercase tracking-tighter text-gray-800 dark:text-white">Informasi Metadata Lengkap</h2>
                    <div class="h-1.5 w-20 bg-teal-500 mx-auto mt-4 rounded-full"></div>
                </div>
                
                <form action="{{ route('admin.metadata.update', $dataset->id) }}" method="POST" class="space-y-10 relative z-10">
                    @csrf
                    
                    <div class="space-y-8 border-b border-gray-100 dark:border-gray-800 pb-10">
                        <div class="group">
                            <label class="block text-[9px] font-black uppercase tracking-[0.2em] text-gray-400 mb-3 ml-4 transition-colors group-focus-within:text-teal-600">Judul Dataset *</label>
                            <input type="text" name="name" value="{{ old('name', $dataset->name) }}" class="w-full px-8 py-5 rounded-[24px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 outline-none text-sm font-bold shadow-sm" required />
                        </div>

                        <div class="group">
                            <label class="block text-[9px] font-black uppercase tracking-[0.2em] text-gray-400 mb-3 ml-4 transition-colors group-focus-within:text-teal-600">Abstrak / Deskripsi Lengkap *</label>
                            <textarea name="description" rows="4" class="w-full px-8 py-5 rounded-[24px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 outline-none text-sm font-medium leading-relaxed shadow-sm" required>{{ old('description', $dataset->description) }}</textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="group">
                            <label class="block text-[9px] font-black uppercase tracking-[0.2em] text-gray-400 mb-3 ml-4 transition-colors group-focus-within:text-teal-600">Satuan Data * (Contoh: Orang, Ton)</label>
                            <input type="text" name="unit" value="{{ old('unit', $dataset->unit) }}" required placeholder="Contoh: Orang" class="w-full px-8 py-5 rounded-[24px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 outline-none text-sm font-bold shadow-sm" />
                        </div>
                        
                        <div class="group">
                            <label class="block text-[9px] font-black uppercase tracking-[0.2em] text-gray-400 mb-3 ml-4 transition-colors group-focus-within:text-teal-600">Frekuensi Pembaruan *</label>
                            <div class="relative">
                                <select name="frequency" required class="w-full px-8 py-5 rounded-[24px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 outline-none text-sm font-bold shadow-sm appearance-none cursor-pointer">
                                    <option value="Tahunan" {{ (old('frequency', $dataset->frequency) == 'Tahunan') ? 'selected' : '' }}>Tahunan</option>
                                    <option value="Semester" {{ (old('frequency', $dataset->frequency) == 'Semester') ? 'selected' : '' }}>Semester</option>
                                    <option value="Bulanan" {{ (old('frequency', $dataset->frequency) == 'Bulanan') ? 'selected' : '' }}>Bulanan</option>
                                </select>
                                <div class="absolute right-8 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                            </div>
                        </div>

                        <div class="group">
                            <label class="block text-[9px] font-black uppercase tracking-[0.2em] text-gray-400 mb-3 ml-4 transition-colors group-focus-within:text-teal-600">Cakupan Wilayah / Level Estimasi *</label>
                            <input type="text" name="level" value="{{ old('level', $dataset->level ?? 'Kabupaten Sidoarjo') }}" required class="w-full px-8 py-5 rounded-[24px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 outline-none text-sm font-bold shadow-sm" />
                        </div>

                        <div class="group">
                            <label class="block text-[9px] font-black uppercase tracking-[0.2em] text-gray-400 mb-3 ml-4 transition-colors group-focus-within:text-teal-600">Variabel Utama / Tags *</label>
                            <input type="text" name="tags" value="{{ old('tags', $dataset->tags) }}" required placeholder="Contoh: Penduduk, Laki-laki, Perempuan" class="w-full px-8 py-5 rounded-[24px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 outline-none text-sm font-bold shadow-sm" />
                        </div>
                    </div>

                    <div class="flex items-center justify-center space-x-6 pt-10 border-t border-gray-100 dark:border-gray-800">
                        <button type="button" onclick="window.history.back()" class="px-12 py-5 bg-gray-100 dark:bg-gray-800 text-gray-500 rounded-full font-black text-[9px] uppercase tracking-widest hover:bg-red-50 hover:text-red-600 transition-all shadow-sm">Batal</button>
                        <button type="submit" class="px-14 py-5 bg-teal-600 text-white rounded-full font-black text-[9px] uppercase tracking-widest hover:bg-teal-700 shadow-xl shadow-teal-500/30 transition-all active:scale-95">Simpan Metadata Lengkap</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection