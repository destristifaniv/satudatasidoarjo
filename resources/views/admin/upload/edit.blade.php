@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F8F9FA] dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-500 overflow-hidden" 
     x-data="{ 
        sidebarOpen: window.innerWidth >= 1024,
        isDropping: false, 
        fileName: '' 
     }">
    
    <aside :class="sidebarOpen ? 'w-72' : 'w-0 md:w-20'" class="relative h-full bg-gradient-to-b from-teal-700 via-teal-600 to-teal-500 dark:from-teal-900 dark:via-teal-800 dark:to-teal-700 backdrop-blur-2xl border-r border-teal-400/30 transition-all duration-500 ease-in-out flex flex-col sticky top-0 z-50 shadow-xl overflow-hidden shrink-0">
        <div class="p-6 h-24 border-b border-teal-400/30 flex items-center justify-between overflow-hidden shrink-0">
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

        <nav class="flex-1 p-4 space-y-2 overflow-y-auto text-white">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs group-hover:scale-110">DB</span>
            </a>
            <a href="{{ route('admin.manage-dataset') }}" class="flex items-center px-6 py-4 bg-white/20 rounded-2xl shadow-lg font-bold text-sm transition-all text-left">
                <span x-show="sidebarOpen" class="whitespace-nowrap uppercase tracking-widest text-[10px]">Kembali ke Dataset</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs">KL</span>
            </a>
        </nav>
    </aside>

    <main class="flex-1 h-full overflow-y-auto relative p-6 lg:p-10 scroll-smooth">
        <div class="fixed top-0 right-0 w-[500px] h-[500px] bg-teal-200/20 dark:bg-teal-900/10 blur-[120px] rounded-full -z-10 opacity-60 text-left"></div>
        
        <header class="mb-10 text-left">
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white uppercase leading-none">
                Edit <span class="text-teal-600">Dataset</span>
            </h1>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.3em] mt-3">Dataset Kode: {{ $dataset->dssd_code }}</p>
        </header>

        <div class="max-w-5xl mx-auto">
            <div class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-2xl rounded-[40px] shadow-2xl border border-white dark:border-gray-800 p-8 lg:p-12 relative overflow-hidden">
                
                {{-- Tampilan Error Validasi --}}
                @if($errors->any())
                    <div class="mb-8 p-5 bg-red-500/10 border border-red-500/20 text-red-600 rounded-[24px] text-[10px] font-black uppercase tracking-widest text-left">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.upload.dataset.update', $dataset->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8 relative z-10 text-left">
                    @csrf
                    @method('PUT')
                    
                    {{-- Hidden category agar tidak error NOT NULL di DB --}}
                    <input type="hidden" name="category" value="{{ $dataset->category ?? '-' }}">

                    <div class="relative">
                        <label 
                            :class="isDropping ? 'border-teal-500 bg-teal-50/50' : 'border-teal-400/30 bg-gray-50/30'"
                            class="border-2 border-dashed rounded-[32px] p-10 text-center transition-all group cursor-pointer flex flex-col items-center justify-center min-h-[150px] relative overflow-hidden">
                            
                            <input type="file" name="dataset_file" class="absolute inset-0 opacity-0 cursor-pointer z-20" 
                                   @change="fileName = $event.target.files[0].name">

                            <div class="relative z-10 group-hover:scale-105 transition-transform duration-500">
                                <div class="w-12 h-12 bg-white dark:bg-gray-800 rounded-2xl flex items-center justify-center mb-3 shadow-xl mx-auto">
                                    <span class="text-2xl" x-show="!fileName">🔄</span>
                                    <span class="text-2xl" x-show="fileName">✅</span>
                                </div>
                                <p class="text-[10px] font-black text-gray-700 dark:text-gray-300 uppercase tracking-widest" x-text="fileName ? fileName : 'Klik untuk ganti file (Kosongkan jika tidak ingin mengubah)'"></p>
                            </div>
                        </label>
                        @if($dataset->file_path)
                            <p class="mt-3 ml-4 text-[9px] font-bold text-teal-600 uppercase italic">File aktif: {{ basename($dataset->file_path) }}</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                        <div class="space-y-2 group">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] ml-4">Kode DSSD *</label>
                            <input type="text" name="dssd_code" value="{{ old('dssd_code', $dataset->dssd_code) }}" required class="w-full px-6 py-4 rounded-[20px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 outline-none text-xs font-bold transition-all shadow-sm">
                        </div>

                        <div class="space-y-2 group">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] ml-4">Nama Dataset *</label>
                            <input type="text" name="name" value="{{ old('name', $dataset->name) }}" required class="w-full px-6 py-4 rounded-[20px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 outline-none text-xs font-bold transition-all shadow-sm">
                        </div>

                        <div class="space-y-2 group">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-4">Tags (Kata Kunci) *</label>
                            <input type="text" name="tags" value="{{ old('tags', $dataset->tags) }}" required placeholder="penduduk, ekonomi" class="w-full px-6 py-4 rounded-[20px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 outline-none text-xs font-bold shadow-sm">
                        </div>

                        <div class="space-y-2 group">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-4">Satuan Data</label>
                            <input type="text" name="unit" value="{{ old('unit', $dataset->unit) }}" placeholder="Dokumen / Bangunan" class="w-full px-6 py-4 rounded-[20px] bg-gray-50/50 dark:bg-gray-800/50 border-none text-xs font-bold outline-none shadow-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-gray-100 dark:border-gray-800 text-left">
                        <div class="space-y-2 group">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-4">Frekuensi Update</label>
                            <select name="frequency" class="w-full px-6 py-4 rounded-[20px] bg-gray-50/50 dark:bg-gray-800/50 border-none text-xs font-bold outline-none shadow-sm appearance-none">
                                <option value="">-- Pilih --</option>
                                <option value="Tahunan" {{ old('frequency', $dataset->frequency) == 'Tahunan' ? 'selected' : '' }}>Tahunan</option>
                                <option value="Bulanan" {{ old('frequency', $dataset->frequency) == 'Bulanan' ? 'selected' : '' }}>Bulanan</option>
                            </select>
                        </div>

                        <div class="space-y-2 group">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] ml-4">Tahun Awal *</label>
                            <input type="number" name="year_start" value="{{ old('year_start', $dataset->year_start) }}" required class="w-full px-6 py-4 rounded-[20px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 outline-none text-xs font-bold shadow-sm">
                        </div>

                        <div class="space-y-2 group">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] ml-4">Tahun Akhir *</label>
                            <input type="number" name="year_end" value="{{ old('year_end', $dataset->year_end) }}" required class="w-full px-6 py-4 rounded-[20px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 outline-none text-xs font-bold shadow-sm">
                        </div>
                    </div>

                    <div class="space-y-2 group">
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] ml-4 block">Deskripsi Dataset</label>
                        <textarea name="description" rows="4" class="w-full px-6 py-4 rounded-[24px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white outline-none text-xs font-medium leading-relaxed transition-all shadow-sm">{{ old('description', $dataset->description) }}</textarea>
                    </div>

                    <div class="pt-10 border-t border-gray-100 dark:border-gray-800 flex justify-center space-x-6">
                        <button type="button" onclick="window.location.href='{{ route('admin.manage-dataset') }}'" class="px-12 py-5 bg-gray-100 dark:bg-gray-800 text-gray-500 rounded-full font-black text-[10px] uppercase tracking-widest hover:bg-red-50 hover:text-red-600 transition-all">
                            Batal
                        </button>
                        <button type="submit" class="px-14 py-5 bg-teal-600 text-white rounded-full font-black text-[10px] uppercase tracking-[0.2em] shadow-xl hover:bg-teal-700 transition-all">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection