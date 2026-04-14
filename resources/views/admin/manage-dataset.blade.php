@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<div class="flex h-screen bg-[#F8F9FA] dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-500 overflow-hidden" 
     x-data="{ 
        sidebarOpen: window.innerWidth >= 1024, 
        searchQuery: '{{ request('search') }}',
        yearFilter: '{{ request('year', 'all') }}',
        showPreview: false,
        previewUrl: '',
        previewName: '',
        previewExt: '',
        excelData: [],
        isLoading: false,
        
        // State untuk Modal Penolakan (Revisi) Pimpinan
        rejectModalOpen: false,
        
        // State Dropdown Notifikasi
        isNotifOpen: false,

        applyFilters() {
            let url = new URL(window.location.href);
            url.searchParams.set('search', this.searchQuery);
            url.searchParams.set('year', this.yearFilter);
            url.searchParams.set('page', 1); 
            window.location.href = url.toString();
        },

        // Fungsi Membuka Modal Penolakan & Mengisi Action Form
        openRejectModal(id) {
            this.rejectModalOpen = true;
            const form = document.getElementById('rejectForm');
            form.action = `/admin/manage-dataset/${id}/verify`;
        },

        async loadPreview(url, name, ext) {
            this.showPreview = true;
            this.previewUrl = url;
            this.previewName = name;
            this.previewExt = ext.toLowerCase();
            this.excelData = [];

            if (['xlsx', 'xls', 'csv'].includes(this.previewExt)) {
                this.isLoading = true;
                try {
                    const response = await fetch(url);
                    const arrayBuffer = await response.arrayBuffer();
                    const data = new Uint8Array(arrayBuffer);
                    const workbook = XLSX.read(data, { type: 'array' });
                    const firstSheetName = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[firstSheetName];
                    this.excelData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
                } catch (error) {
                    console.error('Gagal membaca file Excel:', error);
                } finally {
                    this.isLoading = false;
                }
            }
        }
     }">
    
    {{-- SIDEBAR --}}
    <aside :class="sidebarOpen ? 'w-72' : 'w-0 md:w-20'" class="relative h-full bg-gradient-to-b from-teal-700 via-teal-600 to-teal-500 dark:from-teal-900 dark:via-teal-800 dark:to-teal-700 backdrop-blur-2xl border-r border-teal-400/30 transition-all duration-500 ease-in-out flex flex-col sticky top-0 z-50 shadow-xl overflow-hidden shrink-0">
        <div class="p-6 h-24 border-b border-teal-400/30 flex items-center justify-between overflow-hidden">
            <div class="flex items-center space-x-3 min-w-[180px]" x-show="sidebarOpen" x-transition>
                <img src="https://adminweb.sidoarjokab.go.id/upload/link/1703218932.png" alt="Logo" class="w-10 h-10 object-contain rounded-xl shadow-lg bg-white/90 p-1">
                <h2 class="text-xs font-black text-white leading-tight uppercase tracking-tighter text-left">Satu Data<br>Kabupaten Sidoarjo</h2>
            </div>
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl hover:bg-teal-600/50 transition-colors shrink-0 text-white text-left">
                <svg :class="!sidebarOpen ? 'rotate-180' : ''" class="w-6 h-6 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 p-4 space-y-2 overflow-y-auto text-white">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs group-hover:scale-110">DB</span>
            </a>
            <a href="{{ route('admin.upload.dataset') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Unggah Dataset</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs group-hover:scale-110">UG</span>
            </a>
            <a href="{{ route('admin.metadata') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Kelola Metadata</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs group-hover:scale-110">MT</span>
            </a>
            <a href="{{ route('admin.manage-dataset') }}" class="flex items-center px-6 py-4 bg-white/20 rounded-2xl shadow-lg font-bold text-sm transition-all">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Kelola Dataset</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs">KL</span>
            </a>
            <a href="{{ route('admin.organizations') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Organisasi</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs group-hover:scale-110">OR</span>
            </a>
            @if(auth()->user()->role == 'admin')
            <a href="{{ route('admin.users.index') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Manajemen Akun</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs group-hover:scale-110">AK</span>
            </a>
            @endif
        </nav>
    </aside>

    {{-- MAIN AREA --}}
    <main class="flex-1 h-full overflow-y-auto relative p-6 lg:p-8 scroll-smooth text-left">
        <div class="fixed top-0 right-0 w-[400px] h-[400px] bg-teal-200/20 dark:bg-teal-900/10 blur-[100px] rounded-full -z-10 opacity-50"></div>
        
        {{-- HEADER (DENGAN LONCENG NOTIFIKASI) --}}
        <header class="flex flex-col md:flex-row justify-between md:items-end mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-black tracking-tight text-gray-900 dark:text-white uppercase leading-none text-left">Kelola <span class="text-teal-600">Dataset</span></h1>
                <p class="text-[9px] text-gray-400 font-black uppercase tracking-[0.2em] mt-2 text-left">Manajemen File & Konten Data Sektoral</p>
            </div>

            <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto text-left relative">
                
                {{-- LONCENG NOTIFIKASI --}}
                <div class="relative" @click.away="isNotifOpen = false">
                    @php
                        // 🔥 LOGIKA NOTIFIKASI GLOBAL SUPER RADAR 🔥
                        $user = auth()->user();
                        $instansi = $user->opd_name ?: $user->name;
                        $userIds = \App\Models\User::where('opd_name', $instansi)->orWhere('name', $instansi)->pluck('id');

                        if($user->role == 'pimpinan') {
                            $notifQuery = \App\Models\Dataset::whereIn('user_id', $userIds)->where('status', 'pending');
                            $notifCount = $notifQuery->count();
                            $notifItems = $notifQuery->latest()->take(5)->get();
                            $notifColor = 'bg-amber-500';
                            $notifTitle = 'Menunggu Persetujuan';
                        } elseif($user->role == 'opd') {
                            $notifQuery = \App\Models\Dataset::whereIn('user_id', $userIds)->where('status', 'rejected');
                            $notifCount = $notifQuery->count();
                            $notifItems = $notifQuery->latest()->take(5)->get();
                            $notifColor = 'bg-red-500';
                            $notifTitle = 'Revisi Dataset';
                        } else {
                            $notifCount = 0;
                            $notifItems = collect();
                            $notifColor = 'bg-gray-500';
                            $notifTitle = 'Notifikasi';
                        }
                    @endphp
                    
                    <button @click="isNotifOpen = !isNotifOpen" class="p-3 bg-white/50 dark:bg-gray-900/50 backdrop-blur-xl border border-white dark:border-gray-800 rounded-full text-gray-500 hover:text-teal-600 transition-all shadow-sm relative focus:outline-none focus:ring-4 focus:ring-teal-500/10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        
                        @if($notifCount > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-[10px] font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 {{ $notifColor }} rounded-full animate-bounce">{{ $notifCount }}</span>
                        @endif
                    </button>

                    {{-- DROPDOWN NOTIFIKASI --}}
                    <div x-show="isNotifOpen" x-transition.opacity.duration.200ms class="absolute right-0 mt-3 w-80 bg-white dark:bg-gray-800 rounded-[20px] shadow-2xl border border-gray-100 dark:border-gray-700 z-50 overflow-hidden" x-cloak>
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex justify-between items-center">
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-500">{{ $notifTitle }}</span>
                            @if($notifCount > 0) <span class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $notifCount }} Baru</span> @endif
                        </div>
                        <div class="max-h-64 overflow-y-auto custom-scrollbar">
                            @if($notifCount > 0)
                                @foreach($notifItems as $item)
                                    @php
                                        // 🔥 PERBAIKAN PENTING DI SINI 🔥
                                        // Ubah urlencode($item->dssd_code) menjadi urlencode($item->name)
                                        // Karena sistem pencarian (search bar) kamu mencari berdasarkan Nama Dataset.
                                        $targetUrl = auth()->user()->role == 'opd' 
                                            ? route('admin.upload.dataset.edit', $item->id) 
                                            : "/admin/manage-dataset?search=" . urlencode($item->name);
                                    @endphp
                                    <div class="px-4 py-3 border-b border-gray-50 dark:border-gray-700/50 hover:bg-teal-50/30 dark:hover:bg-gray-700/30 transition-colors cursor-pointer" onclick="window.location.href='{{ $targetUrl }}'">
                                        <p class="text-xs font-bold text-gray-800 dark:text-gray-200 line-clamp-1">{{ $item->name }}</p>
                                        <p class="text-[10px] text-gray-500 mt-1 flex justify-between items-center">
                                            <span>{{ $item->dssd_code }}</span>
                                            <span class="font-medium text-gray-400">{{ $item->updated_at->diffForHumans() }}</span>
                                        </p>
                                    </div>
                                @endforeach
                            @else
                                <div class="px-4 py-8 text-center">
                                    <span class="text-3xl block mb-2 opacity-50">📭</span>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Tidak ada pemberitahuan baru</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Dropdown Tahun & Search Bar --}}
                <select x-model="yearFilter" @change="applyFilters()"
                        class="appearance-none pl-4 pr-10 py-3 bg-white/50 dark:bg-gray-900/50 backdrop-blur-xl border border-white dark:border-gray-800 rounded-[16px] text-[9px] font-black uppercase tracking-widest outline-none focus:ring-4 focus:ring-teal-500/10 transition-all shadow-sm cursor-pointer text-left">
                    <option value="all">SEMUA TAHUN</option>
                    @for ($year = 2026; $year >= 2019; $year--)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                </select>

                <div class="relative w-full md:w-64 group text-left">
                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" x-model="searchQuery" @keydown.enter="applyFilters()" placeholder="CARI DATASET..." 
                            class="w-full pl-10 pr-4 py-3 bg-white/50 dark:bg-gray-900/50 backdrop-blur-xl border border-white dark:border-gray-800 rounded-[16px] text-[9px] font-black uppercase tracking-widest outline-none focus:ring-4 focus:ring-teal-500/10 transition-all shadow-sm text-left">
                </div>
            </div>
        </header>

        {{-- NOTIFIKASI SUCCESS --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-2xl mb-6 shadow-sm flex items-center">
                <svg class="w-5 h-5 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <p class="text-sm text-emerald-700 font-bold text-left">{{ session('success') }}</p>
            </div>
        @endif

        {{-- NOTIFIKASI KHUSUS REVISI UNTUK STAF --}}
        @if(auth()->user()->role == 'opd' && $notifCount > 0)
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-2xl mb-6 shadow-md flex items-start">
                <svg class="w-6 h-6 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <div>
                    <p class="text-sm text-red-800 font-black uppercase text-left">Perhatian: Ada {{ $notifCount }} Data Yang Harus Direvisi!</p>
                    <p class="text-xs text-red-600 font-bold mt-1 text-left">Pimpinan telah mengembalikan beberapa dataset. Silakan klik notifikasi lonceng untuk langsung memperbaiki datanya.</p>
                </div>
            </div>
        @endif

        {{-- TABEL DATA --}}
        <div class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-2xl rounded-[32px] shadow-lg border border-white dark:border-gray-800 overflow-hidden text-left">
            <div class="overflow-x-auto text-left">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-800/50">
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-100 dark:border-gray-800 text-left">Kode DSSD</th>
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-100 dark:border-gray-800 text-left">Nama Dataset</th>
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-100 dark:border-gray-800 text-left">Format</th>
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-100 dark:border-gray-800 text-left">Tahun</th>
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-100 dark:border-gray-800 text-left text-left">Status</th>
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400 text-center border-b border-gray-100 dark:border-gray-800 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-left">
                        @forelse($datasets as $dataset)
                        <tr class="hover:bg-teal-50/30 dark:hover:bg-teal-900/10 transition-colors text-left">
                            <td class="px-6 py-4 text-[9px] font-black text-teal-600 text-left">{{ $dataset->dssd_code }}</td>
                            <td class="px-6 py-4 font-bold text-xs text-gray-800 dark:text-gray-200 text-left">
                                {{ $dataset->name }}
                                <p class="text-[9px] text-gray-400 font-medium mt-1 text-left">Oleh: {{ $dataset->user->name ?? 'User' }}</p>
                            </td>
                            <td class="px-6 py-4 text-left">
                                @php
                                    $ext = pathinfo($dataset->file_path, PATHINFO_EXTENSION);
                                    $fileUrl = asset('storage/' . $dataset->file_path);
                                @endphp
                                <span class="px-2.5 py-1 {{ $ext == 'xlsx' ? 'bg-emerald-100 text-emerald-600' : 'bg-blue-100 text-blue-600' }} rounded-lg text-[9px] font-black uppercase tracking-widest text-left">
                                    {{ strtoupper($ext) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-[11px] text-gray-500 font-bold text-left">{{ $dataset->year_start }}</td>
                            
                            {{-- KOLOM STATUS --}}
                            <td class="px-6 py-4 align-top text-left">
                                @if($dataset->status == 'approved')
                                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-lg text-[9px] font-black uppercase tracking-widest flex w-fit items-center text-left"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 text-left"></span>Disetujui</span>
                                @elseif($dataset->status == 'rejected')
                                    <span class="px-2.5 py-1 bg-red-100 text-red-700 border border-red-300 rounded-lg text-[9px] font-black uppercase tracking-widest flex w-fit items-center mb-1.5 text-left animate-pulse"><span class="w-1.5 h-1.5 rounded-full bg-red-600 mr-1.5 text-left"></span>Perlu Revisi</span>
                                    <div class="bg-red-50 dark:bg-red-900/20 p-2 rounded-lg border border-red-100 dark:border-red-800 max-w-[180px] text-left">
                                        <p class="text-[9px] text-red-600 font-black uppercase mb-0.5 text-left">Alasan Penolakan:</p> 
                                        <p class="text-[10px] text-gray-700 dark:text-gray-300 font-bold italic leading-tight text-left">"{{ $dataset->feedback }}"</p>
                                    </div>
                                @else
                                    <span class="px-2.5 py-1 bg-amber-50 text-amber-600 border border-amber-200 rounded-lg text-[9px] font-black uppercase tracking-widest flex w-fit items-center text-left"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-pulse text-left"></span>Menunggu</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-left">
                                <div class="flex items-start justify-center space-x-2 text-left">
                                    <button @click="loadPreview('{{ $fileUrl }}', '{{ addslashes($dataset->name) }}', '{{ $ext }}')" 
                                            class="p-2 bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-orange-500 rounded-lg shadow-sm text-left">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-width="2"/></svg>
                                    </button>

                                    @if(auth()->user()->role === 'pimpinan')
                                        @if($dataset->status == 'pending')
                                            <form action="{{ route('admin.dataset.verify', $dataset->id) }}" method="POST" class="inline">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="p-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white rounded-lg shadow-sm text-left">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                            </form>
                                            <button @click="openRejectModal({{ $dataset->id }})" class="p-2 bg-red-50 text-red-600 hover:bg-red-500 hover:text-white rounded-lg shadow-sm text-left">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        @endif
                                    @else
                                        <a href="{{ route('admin.upload.dataset.edit', $dataset->id) }}" class="p-2 bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-blue-600 rounded-lg shadow-sm text-left">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2.5"/></svg>
                                        </a>
                                        <form action="{{ route('admin.upload.dataset.destroy', $dataset->id) }}" method="POST" onsubmit="return confirm('Hapus permanen?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-red-600 rounded-lg shadow-sm text-left">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2.5"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center text-gray-400 font-bold uppercase tracking-widest text-[9px] text-left">Data tidak ditemukan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-gray-50/30 dark:bg-gray-800/30 border-t border-gray-100 dark:border-gray-800 text-[10px] text-left text-left text-left">
                {{ $datasets->appends(['search' => request('search'), 'year' => request('year')])->onEachSide(0)->links() }}
            </div>
        </div>
    </main>

    {{-- MODAL TOLAK / REVISI --}}
    <div x-show="rejectModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
        <div @click.away="rejectModalOpen = false" class="bg-white dark:bg-gray-800 rounded-[24px] shadow-2xl w-full max-w-md border border-gray-100 dark:border-gray-700 text-left">
            <div class="p-6 border-b dark:border-gray-700 flex justify-between items-center bg-red-50/50 dark:bg-red-900/10 text-left">
                <h3 class="text-lg font-extrabold text-red-600 flex items-center text-left text-left">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Alasan Penolakan
                </h3>
            </div>
            <form id="rejectForm" method="POST" class="p-6 text-left text-left">
                @csrf @method('PUT')
                <input type="hidden" name="status" value="rejected">
                <div class="mb-5 text-left">
                    <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest text-left text-left">Apa yang harus diperbaiki? <span class="text-red-500">*</span></label>
                    <textarea name="feedback" required rows="4" class="w-full rounded-[16px] border border-gray-200 dark:bg-gray-700 dark:text-white text-sm p-4 transition-all outline-none focus:ring-4 focus:ring-red-500/10 focus:border-red-500 text-left" placeholder="Contoh: File Excel lampiran untuk tahun 2022 masih kosong, tolong diunggah ulang file yang benar."></textarea>
                </div>
                <div class="flex justify-end space-x-3 text-left">
                    <button type="button" @click="rejectModalOpen = false" class="px-5 py-2.5 text-xs font-black text-gray-600 bg-gray-100 rounded-xl uppercase tracking-widest text-left">Batal</button>
                    <button type="submit" class="px-5 py-2.5 text-xs font-black text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-md tracking-widest uppercase flex items-center text-left">
                        Kirim ke Staf
                        <svg class="w-3 h-3 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL PREVIEW --}}
    <div x-show="showPreview" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
        <div class="bg-white dark:bg-gray-900 w-full max-w-6xl max-h-[90vh] rounded-[32px] overflow-hidden shadow-2xl flex flex-col border border-white/20 text-left">
            <div class="p-6 border-b dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50 text-left text-left">
                <div><h3 class="font-black text-teal-600 uppercase tracking-tight text-left" x-text="previewName"></h3><p class="text-[10px] text-gray-400 font-bold uppercase text-left" x-text="'Format: ' + previewExt"></p></div>
                <button @click="showPreview = false; excelData = []" class="p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-full transition-colors text-gray-500 text-left"><svg class="w-6 h-6 text-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2"/></svg></button>
            </div>
            <div class="flex-1 overflow-auto bg-gray-50 dark:bg-gray-950 p-6 text-left">
                <div x-show="isLoading" class="flex flex-col items-center justify-center py-20 text-gray-400 text-left"><div class="w-8 h-8 border-4 border-teal-500 border-t-transparent rounded-full animate-spin mb-4 text-left"></div><p class="text-[10px] font-black uppercase tracking-[0.2em] text-left">Memuat Dokumen...</p></div>
                <template x-if="excelData.length > 0"><div class="overflow-hidden border border-gray-200 dark:border-gray-800 rounded-2xl bg-white dark:bg-gray-900 shadow-sm text-left text-left"><table class="w-full text-left text-xs border-collapse text-left"><thead class="text-left"><tr class="bg-gray-100 dark:bg-gray-800/80 text-left text-left"><template x-for="(cell, index) in excelData[0]" :key="index"><th class="px-4 py-3 font-black border-b border-gray-200 dark:border-gray-700 uppercase tracking-wider text-teal-600 text-left" x-text="cell"></th></template></tr></thead><tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-left text-left text-left"><template x-for="(row, rowIndex) in excelData.slice(1)" :key="rowIndex"><tr class="hover:bg-teal-50/30 transition-colors text-left text-left text-left"><template x-for="(cell, cellIndex) in row" :key="cellIndex"><td class="px-4 py-3 text-gray-600 dark:text-gray-300 border-r dark:border-gray-800 text-left text-left text-left" x-text="cell"></td></template></tr></template></tbody></table></div></template>
                <template x-if="['jpg', 'jpeg', 'png', 'gif', 'svg'].includes(previewExt)"><div class="flex justify-center text-left"><img :src="previewUrl" class="max-w-full h-auto rounded-lg shadow-xl border-4 border-white dark:border-gray-800 text-left"></div></template>
                <template x-if="previewExt === 'pdf'"><iframe :src="previewUrl" class="w-full h-[65vh] rounded-xl border-none shadow-inner bg-white text-left"></iframe></template>
            </div>
        </div>
    </div>
</div>

<style>
    /* Tambahan agar scrollbar dropdown notifikasi lebih cantik */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 10px;
    }
</style>
@endsection