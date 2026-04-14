@extends('layouts.app')

@section('content')
<div class="relative min-h-screen bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 overflow-x-hidden text-left">
    {{-- Library Pratinjau Excel --}}
    <script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- ── FLOATING NAVBAR ── --}}
    <header class="fixed top-0 left-0 right-0 z-50 px-4 pt-5 transition-all duration-300">
        <div class="max-w-6xl mx-auto bg-white/70 dark:bg-gray-900/70 backdrop-blur-md rounded-full shadow-xl px-6 py-3 flex items-center justify-between border border-white/30 dark:border-gray-700 text-left">
            <div class="flex items-center space-x-3">
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
                <a href="/datasets" class="text-sm text-green-600 font-bold transition">Datasets</a>
                <a href="/organizations" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-medium transition">Organizations</a>
                <a href="/groups" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-medium transition">Groups</a>
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

    <main class="pt-28 pb-10 px-4">
        <div class="max-w-6xl mx-auto">
            
            {{-- Navigasi Balik Ringkas --}}
            <div class="mb-4 text-left">
                <a href="/datasets" class="inline-flex items-center text-[9px] font-bold uppercase tracking-widest text-gray-400 hover:text-green-700 transition group">
                    <svg class="w-3 h-3 mr-1.5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
                    Kembali
                </a>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-[32px] p-6 border border-gray-100 dark:border-gray-800 shadow-sm text-left">
                
                {{-- ── HEADER (DESAIN LAMA TAPI LEBIH KECIL) ── --}}
                <div class="flex flex-col md:flex-row gap-6 items-start mb-6">
                    {{-- Box Organisasi Kiri --}}
                    <div class="w-full md:w-52 shrink-0 bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-4 border border-gray-100 dark:border-gray-700 shadow-inner">
                        <div class="w-10 h-10 bg-white dark:bg-gray-800 rounded-xl flex items-center justify-center border dark:border-gray-700 mb-3">
                            <img src="https://adminweb.sidoarjokab.go.id/upload/link/1703218932.png" class="w-6 opacity-80">
                        </div>
                        <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-1">Organisasi / opd</p>
                        <p class="text-[11px] font-bold text-gray-800 dark:text-white leading-tight">{{ $dataset->user->name ?? 'Dinas Terkait' }}</p>
                    </div>

                    {{-- Judul & Tombol Kanan --}}
                    <div class="flex-1 text-left">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-3 text-left">
                            <div class="text-left">
                                <nav class="flex text-[9px] font-bold text-gray-400 uppercase mb-1">
                                    <span class="text-green-700">Detail dataset</span>
                                </nav>
                                <h1 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white leading-tight tracking-tight text-left">
                                    {{ $dataset->name }}
                                </h1>
                            </div>
                            <a href="/datasets/download/{{ $dataset->id }}" class="shrink-0 inline-flex items-center bg-green-700 hover:bg-green-800 text-white px-6 py-2.5 rounded-full font-bold text-[9px] uppercase tracking-widest shadow-lg transition active:scale-95">
                                <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Download
                            </a>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400 px-2 py-0.5 rounded-lg text-[9px] font-bold uppercase border border-green-200/30">Tahun {{ $dataset->year_start }}</span>
                            <span class="text-gray-400 text-[9px] font-medium uppercase leading-none">Diterbitkan: {{ $dataset->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                {{-- ── PRATINJAU DATA (AUTO-SCROLL AREA) ── --}}
                <div class="text-left">
                    <div class="flex items-center justify-between mb-3 text-left">
                        <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest text-left">Pratinjau isi data</h3>
                    </div>
                    <div id="preview-container" class="w-full bg-gray-50/50 dark:bg-gray-800/30 rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden text-left">
                        {{-- max-h dibatasi agar pas satu layar --}}
                        <div id="table-preview" class="w-full overflow-auto max-h-[320px] text-left">
                            <div id="loading-preview" class="flex flex-col items-center justify-center py-16 text-center">
                                <div class="w-6 h-6 border-2 border-green-700 border-t-transparent rounded-full animate-spin mb-3 text-center"></div>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Memuat pratinjau...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex justify-between items-center opacity-70 text-left">
                    <p class="text-[10px] text-gray-500 font-medium max-w-2xl truncate leading-none">
                        {{ $dataset->description ?? 'Tidak ada deskripsi tambahan.' }}
                    </p>
                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest shrink-0 leading-none">Format: XLSX/CSV</p>
                </div>

            </div>
        </div>
    </main>

    <footer class="py-6 text-center text-left">
        <p class="text-[8px] font-bold text-gray-400 uppercase tracking-[0.4em] text-center">© {{ date('Y') }} PEMKAB SIDOARJO</p>
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
                    let html = '<table class="w-full text-left border-collapse">';
                    limitedData.forEach((row, index) => {
                        const isHeader = index === 0;
                        html += `<tr class="${isHeader ? 'bg-gray-100/80 dark:bg-gray-800 sticky top-0 z-10' : 'border-b dark:border-gray-700/50 hover:bg-green-50/20'}">`;
                        row.forEach(cell => {
                            html += `<td class="px-4 py-3 ${isHeader ? 'font-bold text-[9px] uppercase tracking-widest text-gray-500' : 'text-[11px] text-gray-600 dark:text-gray-300'} whitespace-nowrap border-r dark:border-gray-800 last:border-0">${cell || '-'}</td>`;
                        });
                        html += '</tr>';
                    });
                    html += '</table>';
                    tableContainer.innerHTML = html;
                }
            })
            .catch(err => {
                tableContainer.innerHTML = '<div class="py-10 text-center text-left"><p class="text-[10px] font-bold text-red-400 uppercase">Pratinjau tidak tersedia.</p></div>';
            });
    });
</script>

<style>
    #table-preview::-webkit-scrollbar { width: 4px; height: 4px; }
    #table-preview::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
    .dark #table-preview::-webkit-scrollbar-thumb { background: #374151; }
</style>
@endsection