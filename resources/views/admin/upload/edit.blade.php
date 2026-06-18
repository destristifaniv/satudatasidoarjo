@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-[#F8F9FA] dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-500 overflow-x-hidden relative" 
     x-data="{ 
        sidebarOpen: window.innerWidth >= 1024,
        isDropping: false, 
        fileName: '' 
     }" @resize.window="sidebarOpen = window.innerWidth >= 1024">
    
    {{-- SIDEBAR (STAY FIXED & HIGHEST Z-INDEX) --}}
    <aside :class="sidebarOpen ? 'translate-x-0 shadow-xl' : '-translate-x-full shadow-none'" 
           class="fixed inset-y-0 left-0 z-[60] w-72 transform bg-gradient-to-b from-teal-700 via-teal-600 to-teal-500 dark:from-teal-900 dark:via-teal-800 dark:to-teal-700 backdrop-blur-2xl border-r border-teal-400/30 transition-transform duration-500 ease-in-out flex flex-col">
        
        <div class="p-6 h-24 border-b border-teal-400/30 flex items-center justify-between overflow-hidden shrink-0">
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

        <nav class="flex-1 p-4 space-y-2 overflow-y-auto text-white">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group">
                <span class="whitespace-nowrap">Dashboard</span>
            </a>
            <a href="{{ route('admin.manage-dataset') }}" class="flex items-center px-6 py-4 bg-white/20 rounded-2xl shadow-lg font-bold text-sm transition-all">
                <span class="whitespace-nowrap uppercase tracking-widest text-[10px]">Kembali ke Dataset</span>
            </a>
        </nav>
    </aside>

    {{-- MAIN AREA DENGAN FLEX CENTERING --}}
    <main :class="sidebarOpen ? 'lg:ml-72' : 'ml-0'" class="flex-1 min-h-screen relative transition-all duration-500 overflow-y-auto flex flex-col p-4 sm:p-6 lg:p-8 scroll-smooth text-left">
        
        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-50 bg-black/30 lg:hidden" @click="sidebarOpen = false"></div>
        <div class="fixed top-0 right-0 w-[300px] sm:w-[500px] h-[300px] sm:h-[500px] bg-teal-200/20 dark:bg-teal-900/10 blur-[80px] sm:blur-[120px] rounded-full -z-10 opacity-60"></div>
        
        <div class="w-full max-w-4xl mx-auto flex items-center justify-start z-20 shrink-0 mb-4 lg:mb-0">
            <button @click="sidebarOpen = true" :class="sidebarOpen ? 'lg:hidden' : 'block'" class="p-2.5 mt-1 sm:mt-0 rounded-xl bg-white dark:bg-gray-900 shadow-sm border border-gray-100 dark:border-gray-800 text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>
        </div>

        {{-- BUNGKUSAN TENGAH (FLEX CENTER VERTICAL & HORIZONTAL) --}}
        <div class="flex-1 flex flex-col justify-center items-center w-full relative z-10 py-4 sm:py-6">
            
            <div class="w-full max-w-4xl px-2 sm:px-0">
                
                {{-- HEADER JUDUL --}}
                <header class="mb-6 lg:mb-8 text-center sm:text-left">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight text-gray-900 dark:text-white uppercase leading-none">
                        Edit <span class="text-teal-600">Dataset</span>
                    </h1>
                    <p class="text-[9px] sm:text-[10px] text-gray-400 font-black uppercase tracking-[0.3em] mt-2 sm:mt-3">Dataset Kode: {{ $dataset->dssd_code }}</p>
                </header>

                {{-- CARD FORM --}}
                <div class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-2xl rounded-[24px] sm:rounded-[40px] shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-white dark:border-gray-800 p-5 sm:p-8 lg:p-12 relative overflow-hidden">
                    
                    {{-- Tampilan Error Validasi --}}
                    @if($errors->any())
                        <div class="mb-4 sm:mb-8 p-4 sm:p-5 bg-red-500/10 border border-red-500/20 text-red-600 rounded-[16px] sm:rounded-[24px] text-[9px] sm:text-[10px] font-black uppercase tracking-widest">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.upload.dataset.update', $dataset->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5 sm:space-y-8 relative z-10">
                        @csrf
                        @method('PUT')
                        
                        {{-- Hidden category agar tidak error NOT NULL di DB --}}
                        <input type="hidden" name="category" value="{{ $dataset->category ?? '-' }}">

                        {{-- UPLOAD FILE DRAG & DROP --}}
                        <div class="relative">
                            <label 
                                :class="isDropping ? 'border-teal-500 bg-teal-50/50' : 'border-teal-400/30 bg-gray-50/30 dark:bg-gray-800/30'"
                                class="border-2 border-dashed rounded-[20px] sm:rounded-[32px] p-6 sm:p-10 text-center transition-all group cursor-pointer flex flex-col items-center justify-center min-h-[140px] sm:min-h-[160px] relative overflow-hidden shadow-sm">
                                
                                <input type="file" name="dataset_file" class="absolute inset-0 opacity-0 cursor-pointer z-20" 
                                       @change="fileName = $event.target.files[0].name">

                                <div class="relative z-10 group-hover:scale-105 transition-transform duration-500">
                                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white dark:bg-gray-800 rounded-2xl flex items-center justify-center mb-3 sm:mb-4 shadow-xl mx-auto border border-gray-100 dark:border-gray-700">
                                        <span class="text-2xl sm:text-3xl" x-show="!fileName">🔄</span>
                                        <span class="text-2xl sm:text-3xl" x-show="fileName" x-cloak>✅</span>
                                    </div>
                                    <p class="text-[10px] sm:text-[11px] font-black text-gray-700 dark:text-gray-300 uppercase tracking-widest px-2" x-text="fileName ? fileName : 'Klik untuk ganti file (Kosongkan jika tetap)'"></p>
                                </div>
                            </label>
                            @if($dataset->file_path)
                                <p class="mt-2 sm:mt-3 ml-2 sm:ml-4 text-[8px] sm:text-[9px] font-bold text-teal-600 uppercase italic">File aktif saat ini: {{ basename($dataset->file_path) }}</p>
                            @endif
                        </div>

                        {{-- GRID INPUT 1 --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 pt-2">
                            <div class="space-y-1.5 group">
                                <label class="text-[8px] sm:text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] ml-3 transition-colors group-focus-within:text-teal-600">Kode DSSD *</label>
                                <input type="text" name="dssd_code" value="{{ old('dssd_code', $dataset->dssd_code) }}" required class="w-full px-4 sm:px-5 py-3 sm:py-3.5 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 outline-none text-[10px] sm:text-xs font-bold transition-all shadow-sm">
                            </div>

                            <div class="space-y-1.5 group">
                                <label class="text-[8px] sm:text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] ml-3 transition-colors group-focus-within:text-teal-600">Nama Dataset *</label>
                                <input type="text" name="name" value="{{ old('name', $dataset->name) }}" required class="w-full px-4 sm:px-5 py-3 sm:py-3.5 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 outline-none text-[10px] sm:text-xs font-bold transition-all shadow-sm">
                            </div>

                            <div class="space-y-1.5 group">
                                <label class="text-[8px] sm:text-[9px] font-black text-gray-400 uppercase tracking-widest ml-3 transition-colors group-focus-within:text-teal-600">Tags (Kata Kunci) *</label>
                                <input type="text" name="tags" value="{{ old('tags', $dataset->tags) }}" required placeholder="penduduk, ekonomi" class="w-full px-4 sm:px-5 py-3 sm:py-3.5 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 outline-none text-[10px] sm:text-xs font-bold transition-all shadow-sm">
                            </div>

                            <div class="space-y-1.5 group">
                                <label class="text-[8px] sm:text-[9px] font-black text-gray-400 uppercase tracking-widest ml-3 transition-colors group-focus-within:text-teal-600">Satuan Data</label>
                                <input type="text" name="unit" value="{{ old('unit', $dataset->unit) }}" placeholder="Dokumen / Bangunan" class="w-full px-4 sm:px-5 py-3 sm:py-3.5 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border-none text-[10px] sm:text-xs font-bold outline-none shadow-sm focus:ring-4 focus:ring-teal-500/10 transition-all">
                            </div>
                        </div>

                        {{-- GRID INPUT 2 --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 pt-4 border-t border-gray-100 dark:border-gray-800/60">
                            <div class="space-y-1.5 group mt-2">
                                <label class="text-[8px] sm:text-[9px] font-black text-gray-400 uppercase tracking-widest ml-3 transition-colors group-focus-within:text-teal-600">Frekuensi Update</label>
                                <div class="relative">
                                    <select name="frequency" class="w-full px-4 sm:px-5 py-3 sm:py-3.5 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border-none text-[10px] sm:text-xs font-bold outline-none shadow-sm appearance-none cursor-pointer focus:ring-4 focus:ring-teal-500/10 transition-all">
                                        <option value="">-- Pilih --</option>
                                        <option value="Tahunan" {{ old('frequency', $dataset->frequency) == 'Tahunan' ? 'selected' : '' }}>Tahunan</option>
                                        <option value="Bulanan" {{ old('frequency', $dataset->frequency) == 'Bulanan' ? 'selected' : '' }}>Bulanan</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-1.5 group mt-2">
                                <label class="text-[8px] sm:text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] ml-3 transition-colors group-focus-within:text-teal-600">Tahun Awal *</label>
                                <input type="number" name="year_start" value="{{ old('year_start', $dataset->year_start) }}" required class="w-full px-4 sm:px-5 py-3 sm:py-3.5 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 outline-none text-[10px] sm:text-xs font-bold shadow-sm focus:ring-4 focus:ring-teal-500/10 transition-all">
                            </div>

                            <div class="space-y-1.5 group mt-2">
                                <label class="text-[8px] sm:text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] ml-3 transition-colors group-focus-within:text-teal-600">Tahun Akhir *</label>
                                <input type="number" name="year_end" value="{{ old('year_end', $dataset->year_end) }}" required class="w-full px-4 sm:px-5 py-3 sm:py-3.5 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 outline-none text-[10px] sm:text-xs font-bold shadow-sm focus:ring-4 focus:ring-teal-500/10 transition-all">
                            </div>
                        </div>

                        <div class="space-y-1.5 group pt-2">
                            <label class="text-[8px] sm:text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] ml-3 block transition-colors group-focus-within:text-teal-600">Deskripsi Dataset</label>
                            <textarea name="description" rows="3" class="w-full px-4 sm:px-5 py-3 sm:py-4 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 outline-none text-[10px] sm:text-xs font-medium leading-relaxed transition-all shadow-sm focus:ring-4 focus:ring-teal-500/10">{{ old('description', $dataset->description) }}</textarea>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-end gap-3 sm:gap-4 pt-6 sm:pt-8 border-t border-gray-100 dark:border-gray-800/60 mt-4">
                            <button type="button" onclick="window.history.back()" class="w-full sm:w-auto px-8 sm:px-10 py-3.5 sm:py-4 bg-gray-100 dark:bg-gray-800 text-gray-500 rounded-2xl sm:rounded-full font-black text-[9px] sm:text-[10px] uppercase tracking-[0.2em] hover:bg-gray-200 dark:hover:bg-gray-700 transition-all shadow-sm">
                                Batal
                            </button>
                            <button type="submit" class="w-full sm:w-auto px-8 sm:px-12 py-3.5 sm:py-4 bg-teal-600 text-white rounded-2xl sm:rounded-full font-black text-[9px] sm:text-[10px] uppercase tracking-[0.2em] shadow-lg shadow-teal-500/30 hover:bg-teal-700 transition-all active:scale-95 text-center">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </main>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection