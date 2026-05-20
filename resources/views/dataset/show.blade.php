@extends('layouts.app')

@section('content')
<div class="relative min-h-screen bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 overflow-x-hidden text-left">
    {{-- Library Pratinjau Excel --}}
    <script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- ── FLOATING NAVBAR ── --}}
    <header x-data="{ mobileMenuOpen: false }" class="fixed top-0 left-0 right-0 z-50 px-4 pt-4 md:pt-5 transition-all duration-300">
        {{-- KOTAK PUTIH UTAMA (Background & Blur) --}}
        <div class="max-w-6xl mx-auto bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-2xl md:rounded-full shadow-xl px-4 md:px-6 py-3 border border-white/30 dark:border-gray-700 text-left transition-all duration-300">
            
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <img src="https://adminweb.sidoarjokab.go.id/upload/link/1703218932.png" 
                         alt="Logo Kabupaten Sidoarjo" 
                         class="w-8 h-8 md:w-9 md:h-9 object-contain">
                    <h1 class="text-sm md:text-lg font-bold text-gray-800 dark:text-white leading-tight">
                        <span class="block text-left">Satu Data</span>
                        <span class="block text-[10px] md:text-sm font-semibold opacity-80">Kab. Sidoarjo</span>
                    </h1>
                </div>

                <nav class="hidden md:flex space-x-6">
                    <a href="/" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-medium transition">Home</a>
                    <a href="/datasets" class="text-sm text-green-600 font-bold transition">Datasets</a>
                    <a href="/organizations" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-medium transition">Organizations</a>
                    <a href="/groups" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-medium transition">Groups</a>
                    <a href="/about" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-medium transition">About</a>
                </nav>

                <div class="flex items-center space-x-2 md:space-x-4">
                    <button onclick="window.location.href='{{ url('/login') }}'" class="hidden md:block bg-green-700 hover:bg-green-800 text-white px-5 py-2 rounded-full transition text-sm font-medium shadow-lg shadow-green-500/20">
                        Login
                    </button>
                    <button id="theme-toggle" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition focus:outline-none">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                        <svg id="theme-toggle-light-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"></path></svg>
                    </button>

                    {{-- Mobile Menu Button --}}
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                        <svg x-show="mobileMenuOpen" style="display: none;" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            {{-- Mobile Dropdown DIMASUKKAN KE DALAM CONTAINER PUTIH --}}
            <div x-show="mobileMenuOpen" x-transition class="md:hidden mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex flex-col space-y-3 pb-2">
                <a href="/" class="text-sm text-gray-700 dark:text-gray-300 font-medium">Home</a>
                <a href="/datasets" class="text-sm text-green-600 font-bold">Datasets</a>
                <a href="/organizations" class="text-sm text-gray-700 dark:text-gray-300 font-medium">Organizations</a>
                <a href="/groups" class="text-sm text-gray-700 dark:text-gray-300 font-medium">Groups</a>
                <a href="/about" class="text-sm text-gray-700 dark:text-gray-300 font-medium">About</a>
                <button onclick="window.location.href='{{ url('/login') }}'" class="w-full mt-2 bg-green-700 text-white px-5 py-3 rounded-xl font-medium">Login</button>
            </div>

        </div>
    </header>

    <main class="pt-24 md:pt-32 pb-10 px-4 md:px-6">
        <div class="max-w-6xl mx-auto">
            
            {{-- Navigasi Balik Ringkas --}}
            <div class="mb-4 text-left">
                <a href="/datasets" class="inline-flex items-center text-xs md:text-sm font-bold uppercase tracking-widest text-gray-500 hover:text-green-700 transition group">
                    <svg class="w-4 h-4 mr-1.5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
                    Kembali
                </a>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-2xl md:rounded-[32px] p-5 md:p-6 border border-gray-100 dark:border-gray-800 shadow-sm text-left">
                
                {{-- ── HEADER ── --}}
                <div class="flex flex-col md:flex-row gap-5 md:gap-6 items-start mb-6">
                    {{-- Box Organisasi Kiri --}}
                    <div class="w-full md:w-52 shrink-0 bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-4 border border-gray-100 dark:border-gray-700 shadow-inner">
                        <div class="w-10 h-10 bg-white dark:bg-gray-800 rounded-xl flex items-center justify-center border border-gray-100 dark:border-gray-700 mb-3">
                            <img src="https://adminweb.sidoarjokab.go.id/upload/link/1703218932.png" class="w-6 opacity-80">
                        </div>
                        <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Organisasi / OPD</p>
                        <p class="text-sm md:text-base font-bold text-gray-800 dark:text-white leading-tight">{{ $dataset->user->name ?? 'Dinas Terkait' }}</p>
                    </div>

                    {{-- Judul & Tombol Kanan --}}
                    <div class="flex-1 text-left w-full">
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-4 text-left">
                            <div class="text-left flex-1">
                                <nav class="flex text-xs font-bold text-gray-400 uppercase mb-2">
                                    <span class="text-green-700 bg-green-50 dark:bg-green-900/30 px-2.5 py-1 rounded-md">Detail Dataset</span>
                                </nav>
                                <h1 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white leading-snug tracking-tight text-left">
                                    {{ $dataset->name }}
                                </h1>
                            </div>
                            <a href="/datasets/download/{{ $dataset->id }}" class="shrink-0 w-full sm:w-auto flex justify-center items-center bg-green-700 hover:bg-green-800 text-white px-6 py-3 sm:py-2.5 rounded-xl sm:rounded-full font-bold text-xs md:text-sm uppercase tracking-widest shadow-lg transition active:scale-95 mt-2 sm:mt-0">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Download
                            </a>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400 px-2.5 py-1 rounded-lg text-xs font-bold uppercase border border-green-200/30">Tahun {{ $dataset->year_start }}</span>
                            <span class="text-gray-500 text-xs font-medium uppercase leading-none mt-1 sm:mt-0">Diterbitkan: {{ $dataset->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                {{-- ── PRATINJAU DATA (AUTO-SCROLL AREA) ── --}}
                <div class="text-left mt-8">
                    <div class="flex items-center justify-between mb-3 text-left">
                        <h3 class="text-xs md:text-sm font-bold text-gray-500 uppercase tracking-widest text-left">Pratinjau isi data</h3>
                    </div>
                    <div id="preview-container" class="w-full bg-gray-50/50 dark:bg-gray-800/30 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden text-left">
                        <div id="table-preview" class="w-full overflow-auto max-h-[400px] text-left">
                            <div id="loading-preview" class="flex flex-col items-center justify-center py-16 text-center">
                                <div class="w-8 h-8 border-4 border-green-700 border-t-transparent rounded-full animate-spin mb-4 text-center"></div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Memuat pratinjau...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 flex flex-col md:flex-row justify-between items-start md:items-center opacity-80 text-left gap-4">
                    <p class="text-xs md:text-sm text-gray-600 dark:text-gray-400 font-medium max-w-3xl leading-relaxed">
                        {{ $dataset->description ?? 'Tidak ada deskripsi tambahan.' }}
                    </p>
                    <p class="text-[10px] md:text-xs text-gray-400 font-bold uppercase tracking-widest shrink-0 bg-gray-100 dark:bg-gray-800 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700">Format: XLSX/CSV</p>
                </div>

            </div>
        </div>
    </main>

    <footer class="py-8 text-center text-left border-t border-gray-100 dark:border-gray-900 mt-10">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest md:tracking-[0.4em] text-center">© {{ date('Y') }} PEMKAB SIDOARJO</p>
    </footer>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileUrl = "{{ asset('storage/' . $dataset->file_path) }}";
        const tableContainer = document.getElementById('table-preview');

        fetch(fileUrl)
            .then(res => res.arrayBuffer())
            .then(data => {
                const workbook = XLSX.read(data, { type: 'array' });
                const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                const jsonData = XLSX.utils.sheet_to_json(firstSheet, { header: 1, range: 0 });
                const limitedData = jsonData.slice(0, 11);

                if (limitedData.length > 0) {
                    let html = '<table class="w-full text-left border-collapse min-w-full">';
                    limitedData.forEach((row, index) => {
                        const isHeader = index === 0;
                        html += `<tr class="${isHeader ? 'bg-gray-200/80 dark:bg-gray-800 sticky top-0 z-10 shadow-sm' : 'border-b border-gray-200 dark:border-gray-700/50 hover:bg-green-50/50 dark:hover:bg-gray-800/50'}">`;
                        row.forEach(cell => {
                            html += `<td class="px-4 py-3.5 ${isHeader ? 'font-bold text-xs uppercase tracking-wider text-gray-600 dark:text-gray-300' : 'text-sm text-gray-700 dark:text-gray-200'} whitespace-nowrap border-r border-gray-200 dark:border-gray-800 last:border-0">${cell || '-'}</td>`;
                        });
                        html += '</tr>';
                    });
                    html += '</table>';
                    tableContainer.innerHTML = html;
                } else {
                    throw new Error('Data kosong');
                }
            })
            .catch(err => {
                tableContainer.innerHTML = '<div class="py-12 text-center text-left flex flex-col items-center"><svg class="w-10 h-10 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg><p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Pratinjau tidak tersedia.</p></div>';
            });
    });
</script>

<style>
    #table-preview::-webkit-scrollbar { width: 6px; height: 6px; }
    #table-preview::-webkit-scrollbar-track { background: transparent; }
    #table-preview::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
    #table-preview::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    .dark #table-preview::-webkit-scrollbar-thumb { background: #4b5563; }
    .dark #table-preview::-webkit-scrollbar-thumb:hover { background: #6b7280; }
</style>
@endsection