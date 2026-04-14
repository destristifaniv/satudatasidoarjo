@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F8F9FA] dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-500 overflow-hidden" 
     x-data="{ 
        sidebarOpen: window.innerWidth >= 1024,
        isDropping: false, 
        fileName: '' 
     }">
    
    <aside :class="sidebarOpen ? 'w-72' : 'w-0 md:w-20'" class="relative h-full bg-gradient-to-b from-teal-700 via-teal-600 to-teal-500 dark:from-teal-900 dark:via-teal-800 dark:to-teal-700 backdrop-blur-2xl border-r border-teal-400/30 transition-all duration-500 ease-in-out flex flex-col sticky top-0 h-screen z-50 shadow-xl overflow-hidden shrink-0">
        <div class="p-6 h-24 border-b border-teal-400/30 flex items-center justify-between overflow-hidden shrink-0 text-left">
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

        <nav class="flex-1 p-4 space-y-2 overflow-y-auto text-white">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs group-hover:scale-110 text-center">DB</span>
            </a>
            <a href="{{ route('admin.upload.dataset') }}" class="flex items-center px-6 py-4 bg-white/20 rounded-2xl shadow-lg font-bold text-sm transition-all">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Unggah Dataset</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs text-center">UG</span>
            </a>
            <a href="{{ route('admin.metadata') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Kelola Metadata</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs group-hover:scale-110 text-center">MT</span>
            </a>
            <a href="{{ route('admin.manage-dataset') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Kelola Dataset</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs">KL</span>
            </a>
            <a href="{{ route('admin.organizations') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Organisasi</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs group-hover:scale-110">OR</span>
            </a>
        </nav>
    </aside>

    <main class="flex-1 h-full overflow-y-auto relative p-6 lg:p-8 scroll-smooth">
        <div class="fixed top-0 right-0 w-[400px] h-[400px] bg-teal-200/20 dark:bg-teal-900/10 blur-[100px] rounded-full -z-10 opacity-50 text-left"></div>
        
        <header class="mb-8 text-left">
            <h1 class="text-2xl font-black tracking-tight text-gray-900 dark:text-white uppercase leading-none">
                Unggah <span class="text-teal-600">Dataset Baru</span>
            </h1>
            <p class="text-[9px] text-gray-400 font-black uppercase tracking-[0.2em] mt-2">Portal Manajemen Data Sektoral Kabupaten Sidoarjo</p>
        </header>

        <div class="max-w-4xl mx-auto">
            <div class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-2xl rounded-[32px] shadow-lg border border-white dark:border-gray-800 p-6 lg:p-8 relative overflow-hidden">
                
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center animate-pulse">
                        <div class="flex items-center justify-center gap-2">
                            <span>✨</span>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-600 rounded-[20px] text-[9px] font-black uppercase tracking-widest">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- FORM DENGAN ALPINE.JS UNTUK CUSTOM AUTOCOMPLETE --}}
                <form action="{{ route('admin.upload.dataset.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 relative z-10 text-left"
                      x-data="{
                          dssdInput: '{{ old('dssd_code') }}',
                          nameInput: '{{ old('name') }}',
                          showDssd: false,
                          showName: false,
                          masterData: @js($masterDatasets ?? []),
                          get filteredDssd() {
                              if (!this.dssdInput) return this.masterData;
                              return this.masterData.filter(i => i.dssd_code.toLowerCase().includes(this.dssdInput.toLowerCase()));
                          },
                          get filteredName() {
                              if (!this.nameInput) return this.masterData;
                              return this.masterData.filter(i => i.name.toLowerCase().includes(this.nameInput.toLowerCase()));
                          },
                          selectItem(item) {
                              this.dssdInput = item.dssd_code;
                              this.nameInput = item.name;
                              this.showDssd = false;
                              this.showName = false;
                          }
                      }">
                    @csrf
                    
                    <input type="hidden" name="category" value="-">

                    <div class="relative">
                        <label 
                            :class="isDropping ? 'border-teal-500 bg-teal-50/50' : 'border-teal-400/30 bg-gray-50/30'"
                            class="border-2 border-dashed rounded-[24px] p-8 text-center transition-all group cursor-pointer flex flex-col items-center justify-center min-h-[160px] relative overflow-hidden shadow-sm">
                            
                            <input type="file" name="dataset_file" required class="absolute inset-0 opacity-0 cursor-pointer z-20" 
                                    @change="fileName = $event.target.files[0].name">

                            <div class="relative z-10 group-hover:scale-105 transition-transform duration-500">
                                <div class="w-12 h-12 bg-white dark:bg-gray-800 rounded-xl flex items-center justify-center mb-3 shadow-lg shadow-teal-500/10 mx-auto">
                                    <span class="text-2xl" x-show="!fileName">📁</span>
                                    <span class="text-2xl" x-show="fileName">✅</span>
                                </div>
                                <p class="text-[11px] font-black text-gray-700 dark:text-gray-300 uppercase tracking-widest" x-text="fileName ? fileName : 'Pilih atau Tarik File Dataset'"></p>
                                <p class="text-[8px] text-gray-400 mt-1.5 uppercase font-bold tracking-tighter" x-show="!fileName">CSV, XLSX, JSON (Max 50MB)</p>
                            </div>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                        
                        {{-- 1. INPUT KODE DSSD CUSTOM AUTOCOMPLETE --}}
                        <div class="space-y-1.5 group relative" @click.away="showDssd = false">
                            <label class="text-[8px] font-black text-gray-400 uppercase tracking-[0.2em] ml-3 transition-colors group-focus-within:text-teal-600">Kode DSSD *</label>
                            
                            {{-- Input --}}
                            <input type="text" name="dssd_code" x-model="dssdInput" @focus="showDssd = true" @input="showDssd = true" required autocomplete="off" 
                                   class="w-full px-5 py-3 rounded-[16px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-teal-500/10 outline-none text-[11px] font-bold transition-all shadow-sm" placeholder="">
                            
                            {{-- Custom Dropdown --}}
                            <div x-show="showDssd && filteredDssd.length > 0" x-transition class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-[16px] shadow-2xl max-h-48 overflow-y-auto custom-scrollbar" x-cloak>
                                <template x-for="item in filteredDssd" :key="item.dssd_code">
                                    <div @click="selectItem(item)" class="px-5 py-3 hover:bg-teal-50 dark:hover:bg-teal-900/30 cursor-pointer border-b border-gray-50 dark:border-gray-700/50 last:border-0 transition-colors">
                                        <p class="text-[11px] font-black text-teal-600" x-text="item.dssd_code"></p>
                                        <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 truncate mt-0.5" x-text="item.name"></p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- 2. INPUT NAMA DATASET CUSTOM AUTOCOMPLETE --}}
                        <div class="space-y-1.5 group relative" @click.away="showName = false">
                            <label class="text-[8px] font-black text-gray-400 uppercase tracking-[0.2em] ml-3 transition-colors group-focus-within:text-teal-600">Nama Dataset *</label>
                            
                            {{-- Input --}}
                            <input type="text" name="name" x-model="nameInput" @focus="showName = true" @input="showName = true" required autocomplete="off" 
                                   class="w-full px-5 py-3 rounded-[16px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-teal-500/10 outline-none text-[11px] font-bold transition-all shadow-sm" placeholder="">
                            
                            {{-- Custom Dropdown --}}
                            <div x-show="showName && filteredName.length > 0" x-transition class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-[16px] shadow-2xl max-h-48 overflow-y-auto custom-scrollbar" x-cloak>
                                <template x-for="item in filteredName" :key="item.dssd_code">
                                    <div @click="selectItem(item)" class="px-5 py-3 hover:bg-teal-50 dark:hover:bg-teal-900/30 cursor-pointer border-b border-gray-50 dark:border-gray-700/50 last:border-0 transition-colors">
                                        <p class="text-[11px] font-black text-gray-800 dark:text-gray-200 line-clamp-2" x-text="item.name"></p>
                                        <p class="text-[9px] font-bold text-teal-600 mt-1 uppercase tracking-widest" x-text="'KODE DSSD: ' + item.dssd_code"></p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="space-y-1.5 group">
                            <label class="text-[8px] font-black text-gray-400 uppercase tracking-[0.2em] ml-3 transition-colors group-focus-within:text-teal-600">Organisasi / OPD *</label>
                            <input type="text" name="organization" value="{{ Auth::user()->name }}" readonly class="w-full px-5 py-3 rounded-[16px] bg-gray-100 dark:bg-gray-800/80 border border-transparent text-[11px] font-bold outline-none shadow-sm cursor-not-allowed text-gray-500">
                        </div>

                        <div class="space-y-1.5 group">
                            <label class="text-[8px] font-black text-gray-400 uppercase tracking-[0.2em] ml-3 transition-colors group-focus-within:text-teal-600">Tags (Kata Kunci) *</label>
                            <input type="text" name="tags" required placeholder="penduduk, 2024" value="{{ old('tags') }}" class="w-full px-5 py-3 rounded-[16px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-teal-500/10 outline-none text-[11px] font-bold transition-all shadow-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pt-3 border-t border-gray-100 dark:border-gray-800">
                        <div class="space-y-1.5 group mt-3">
                            <label class="text-[8px] font-black text-gray-400 uppercase tracking-widest ml-3 transition-colors group-focus-within:text-teal-600">Satuan Data</label>
                            <input type="text" name="unit" placeholder="Dokumen / Bangunan" value="{{ old('unit') }}" class="w-full px-5 py-3 rounded-[16px] bg-gray-50/50 dark:bg-gray-800/50 border-none text-[10px] font-bold outline-none shadow-sm focus:ring-4 focus:ring-teal-500/10 transition-all">
                        </div>
                        <div class="space-y-1.5 group mt-3">
                            <label class="text-[8px] font-black text-gray-400 uppercase tracking-widest ml-3 transition-colors group-focus-within:text-teal-600">Frekuensi Update</label>
                            <select name="frequency" class="w-full px-5 py-3 rounded-[16px] bg-gray-50/50 dark:bg-gray-800/50 border-none text-[10px] font-bold outline-none shadow-sm appearance-none cursor-pointer focus:ring-4 focus:ring-teal-500/10 transition-all">
                                <option value="">-- Pilih --</option>
                                <option value="Tahunan">Tahunan</option>
                                <option value="Bulanan">Bulanan</option>
                            </select>
                        </div>
                        <div class="space-y-1.5 group mt-3">
                            <label class="text-[8px] font-black text-gray-400 uppercase tracking-widest ml-3 transition-colors group-focus-within:text-teal-600">Level Estimasi</label>
                            <input type="text" name="level" placeholder="Kecamatan / Desa" value="{{ old('level') }}" class="w-full px-5 py-3 rounded-[16px] bg-gray-50/50 dark:bg-gray-800/50 border-none text-[10px] font-bold outline-none shadow-sm focus:ring-4 focus:ring-teal-500/10 transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                        <div class="space-y-1.5 group">
                            <label class="text-[8px] font-black text-gray-400 uppercase tracking-[0.2em] ml-3 transition-colors group-focus-within:text-teal-600">Data Dari Tahun</label>
                            <input type="number" name="year_start" required min="2000" max="2100" value="{{ date('Y') }}" class="w-full px-5 py-3 rounded-[16px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 outline-none text-[11px] font-bold shadow-sm">
                        </div>
                        <div class="space-y-1.5 group">
                            <label class="text-[8px] font-black text-gray-400 uppercase tracking-[0.2em] ml-3 transition-colors group-focus-within:text-teal-600">Hingga Tahun</label>
                            <input type="number" name="year_end" required min="2000" max="2100" value="{{ date('Y') }}" class="w-full px-5 py-3 rounded-[16px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 outline-none text-[11px] font-bold shadow-sm">
                        </div>
                    </div>

                    <div class="space-y-1.5 group pt-2">
                        <label class="text-[8px] font-black text-gray-400 uppercase tracking-[0.2em] ml-3 block text-left transition-colors group-focus-within:text-teal-600">
                            Deskripsi Dataset (Opsional)
                        </label>
                        <textarea name="description" rows="2" 
                                class="w-full px-5 py-3 rounded-[16px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 outline-none text-[11px] font-medium leading-relaxed transition-all shadow-sm">{{ old('description') }}</textarea>
                    </div>

                    <div class="pt-6 border-t border-gray-100 dark:border-gray-800 flex justify-end">
                        <button type="submit" class="px-10 py-3.5 bg-teal-600 text-white rounded-full font-black text-[9px] uppercase tracking-[0.2em] shadow-lg shadow-teal-500/30 hover:bg-teal-700 hover:scale-[1.02] active:scale-95 transition-all">
                            Ajukan Dataset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<style>
    /* Styling untuk scrollbar custom di dalam dropdown dropdown Alpine */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    [x-cloak] { display: none !important; }
</style>
@endsection