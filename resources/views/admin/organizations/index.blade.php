@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-[#F8F9FA] dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-500 overflow-hidden relative" 
     x-data="{ sidebarOpen: window.innerWidth >= 1024 }" @resize.window="sidebarOpen = window.innerWidth >= 1024">
    
    <aside :class="sidebarOpen ? 'translate-x-0 shadow-xl' : '-translate-x-full shadow-none'" class="fixed inset-y-0 left-0 z-50 w-72 transform overflow-hidden bg-gradient-to-b from-teal-700 via-teal-600 to-teal-500 dark:from-teal-900 dark:via-teal-800 dark:to-teal-700 backdrop-blur-2xl border-r border-teal-400/30 transition-all duration-500 ease-in-out flex flex-col lg:relative lg:translate-x-0 lg:w-72 lg:shadow-xl lg:sticky lg:top-0 lg:h-screen lg:z-auto">
        <div class="p-6 h-24 border-b border-teal-400/30 dark:border-teal-600/30 flex items-center justify-between overflow-hidden shrink-0 text-left">
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

        <nav class="flex-1 p-4 space-y-2 overflow-y-auto text-white">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group">
                <span x-show="sidebarOpen" class="whitespace-nowrap text-left">Dashboard</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs group-hover:scale-110">DB</span>
            </a>
            <a href="{{ route('admin.upload.dataset') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group text-left">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Unggah Dataset</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs group-hover:scale-110 text-center">UG</span>
            </a>
            <a href="{{ route('admin.metadata') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group text-left">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Kelola Metadata</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs group-hover:scale-110 text-center">MT</span>
            </a>
            <a href="{{ route('admin.manage-dataset') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group text-left">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Kelola Dataset</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs">KL</span>
            </a>
            <a href="{{ route('admin.organizations') }}" class="flex items-center px-6 py-4 bg-white/20 rounded-2xl shadow-lg font-bold text-sm transition-all text-left">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Organisasi</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs">OR</span>
            </a>
        </nav>

        <div class="p-4 border-t border-teal-400/30">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center px-4 py-4 rounded-2xl bg-red-600/80 text-white font-bold text-sm transition-all hover:bg-red-700 group">
                    <span x-show="sidebarOpen" class="whitespace-nowrap uppercase tracking-widest text-[10px]">Logout</span>
                    <span x-show="!sidebarOpen" class="font-black text-[10px]">OUT</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN AREA --}}
    <main class="flex-1 min-h-screen overflow-y-auto relative p-4 sm:p-6 lg:p-8 scroll-smooth lg:ml-72">
        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 bg-black/30 lg:hidden" @click="sidebarOpen = false"></div>
        <div class="fixed top-0 right-0 w-[400px] h-[400px] bg-teal-200/20 dark:bg-teal-900/10 blur-[100px] rounded-full -z-10 opacity-50"></div>
        
        <header class="mb-6 sm:mb-8 text-center md:text-left">
            <div class="flex items-center justify-between gap-4 mb-4 md:mb-0">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2.5 rounded-xl bg-white dark:bg-gray-900 shadow-sm border border-gray-100 dark:border-gray-800 text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <div class="flex-1">
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-gray-900 dark:text-white uppercase leading-none">Profil <span class="text-teal-600">Organisasi</span></h1>
                    <p class="text-[8px] sm:text-[9px] text-gray-400 font-black uppercase tracking-[0.2em] mt-2">Detail Perangkat Daerah yang Sedang Masuk</p>
                </div>
            </div>
        </header>

        <div class="max-w-3xl mx-auto px-2 sm:px-0">
            {{-- Kartu Profil Utama --}}
            <div class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-2xl rounded-[24px] sm:rounded-[32px] shadow-lg border border-white dark:border-gray-800 p-4 sm:p-6 lg:p-8 relative overflow-hidden text-left">
                
                <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start gap-4 sm:gap-6 lg:gap-8">
                    {{-- Avatar Organisasi --}}
                    <div class="w-20 h-20 sm:w-28 sm:h-28 md:w-32 md:h-32 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-[20px] sm:rounded-[28px] flex items-center justify-center text-white text-3xl sm:text-5xl font-black shadow-lg shadow-teal-500/20 overflow-hidden shrink-0 border-4 border-white dark:border-gray-800">
                        @if(Auth::user()->avatar)
                            {{-- PERBAIKAN LOGIKA GAMBAR: Hapus kata 'avatars/' karena datanya sudah menyimpan path lengkap --}}
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Logo Instansi" class="w-full h-full object-cover">
                        @else
                            {{-- Jika kosong, tampilkan inisial nama --}}
                            {{ substr(Auth::user()->name, 0, 1) }}
                        @endif
                    </div>

                    <div class="flex-1 text-center md:text-left">
                        <span class="inline-block px-2.5 sm:px-3 py-1 bg-teal-100 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 rounded-full text-[8px] sm:text-[9px] font-black uppercase tracking-widest mb-2 sm:mb-3">
                            Verified Organization
                        </span>
                        
                        <h2 class="text-xl sm:text-2xl lg:text-3xl font-black text-gray-900 dark:text-white leading-tight mb-1 sm:mb-1.5 uppercase">
                            {{ Auth::user()->name }}
                        </h2>
                        <p class="text-[9px] sm:text-[11px] text-gray-500 dark:text-gray-400 font-medium mb-4 sm:mb-5">
                            Admin Perangkat Daerah Pemerintah Kabupaten Sidoarjo
                        </p>

                        {{-- Grid Data --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 border-y border-gray-100 dark:border-gray-800 py-4 sm:py-6 mb-4 sm:mb-6">
                            <div class="space-y-0.5 sm:space-y-1">
                                <h4 class="text-[8px] sm:text-[9px] font-black text-gray-400 uppercase tracking-widest">Email Instansi</h4>
                                <p class="text-[9px] sm:text-xs font-bold text-gray-700 dark:text-gray-200 leading-none">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="space-y-0.5 sm:space-y-1">
                                <h4 class="text-[8px] sm:text-[9px] font-black text-gray-400 uppercase tracking-widest">Total Dataset</h4>
                                <p class="text-[9px] sm:text-xs font-bold text-teal-600 leading-none">{{ $totalDataset }} Terpublikasi</p>
                            </div>
                            <div class="space-y-0.5 sm:space-y-1">
                                <h4 class="text-[8px] sm:text-[9px] font-black text-gray-400 uppercase tracking-widest">Terakhir Login</h4>
                                <p class="text-[9px] sm:text-xs font-bold text-gray-700 dark:text-gray-200 leading-none">{{ now()->format('d M Y') }}</p>
                            </div>
                            <div class="space-y-0.5 sm:space-y-1">
                                <h4 class="text-[8px] sm:text-[9px] font-black text-gray-400 uppercase tracking-widest">Status Akun</h4>
                                <p class="text-[9px] sm:text-xs font-bold text-emerald-500 flex items-center justify-center md:justify-start leading-none">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-2 animate-pulse"></span> Aktif
                                </p>
                            </div>
                        </div>

                        {{-- Deskripsi dan Tags --}}
                        <div class="mb-6 sm:mb-8">
                            <h4 class="text-[8px] sm:text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5 sm:mb-2">Tentang Instansi</h4>
                            <p class="text-[10px] sm:text-[11px] text-gray-600 dark:text-gray-400 leading-relaxed italic mb-2.5 sm:mb-3">
                                {{ Auth::user()->description ?? 'Deskripsi instansi belum diatur.' }}
                            </p>
                            
                            <div class="flex flex-wrap justify-center md:justify-start gap-1 sm:gap-1.5">
                                @if(Auth::user()->tags)
                                    @foreach(explode(',', Auth::user()->tags) as $tag)
                                        <span class="px-2 sm:px-2.5 py-0.5 sm:py-1 bg-teal-50 dark:bg-teal-900/20 text-teal-600 text-[7px] sm:text-[8px] font-black uppercase rounded-lg border border-teal-100 dark:border-teal-800">
                                            #{{ trim($tag) }}
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex flex-col sm:flex-row justify-center md:justify-start gap-2 sm:gap-3">
                            <a href="{{ route('admin.organizations.edit') }}" 
                               class="w-full sm:w-auto px-6 py-2 sm:py-2.5 bg-teal-600 text-white rounded-[12px] sm:rounded-xl font-black text-[8px] sm:text-[9px] uppercase tracking-widest shadow-md shadow-teal-500/20 hover:bg-teal-700 transition-all active:scale-95 inline-block text-center">
                                Edit Profil Instansi
                            </a>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </main>
</div>
@endsection