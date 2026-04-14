@extends('layouts.app')

@section('content')
<div class="relative min-h-screen bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 overflow-x-hidden">
    
    <div class="relative z-10">
        {{-- Floating Header --}}
        <header class="fixed top-0 left-0 right-0 z-50 px-4 pt-5 transition-all duration-300">
            <div class="max-w-6xl mx-auto bg-white/70 dark:bg-gray-900/70 backdrop-blur-md rounded-full shadow-xl px-6 py-3 flex items-center justify-between border border-white/30 dark:border-gray-700">
                <div class="flex items-center space-x-3 text-left">
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
                    <a href="/datasets" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-medium transition">Datasets</a>
                    <a href="/organizations" class="text-sm text-green-600 font-bold transition">Organizations</a>
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

        <main class="max-w-6xl mx-auto pt-32 pb-40 px-4 md:px-0 text-left">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 px-2">
                <div class="text-left">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight leading-none">
                        Produsen <span class="text-green-700">Data Sektoral</span>
                    </h2>
                    <p class="text-[15px] text-gray-500 mt-2 opacity-70">Daftar instansi pemerintah daerah penyedia data resmi Kabupaten Sidoarjo</p>
                </div>
                
                <div class="mt-8 md:mt-0 text-left">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white leading-none">
                        <span class="text-green-700">{{ count($organizations) }}</span> 
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-2">Instansi Terdaftar</span>
                    </h3>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 px-2">
                @foreach($organizations as $org)
                <a href="{{ route('public.organizations.detail', $org->id) }}" 
                   class="group relative bg-white dark:bg-gray-900 rounded-[32px] p-6 shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-500 cursor-pointer overflow-hidden text-center block">
                    
                    <div class="absolute -top-10 -right-10 w-24 h-24 bg-green-50 dark:bg-green-900/10 blur-3xl rounded-full group-hover:bg-green-100 transition-colors opacity-0 group-hover:opacity-100"></div>

                    <div class="relative z-10 flex flex-col items-center h-full text-center text-left">
                        <div class="w-20 h-20 bg-gray-50 dark:bg-gray-800 rounded-2xl p-4 flex items-center justify-center border border-gray-100 dark:border-gray-700 shadow-inner group-hover:scale-105 transition-transform duration-500 mb-4 shrink-0">
                            <img src="https://adminweb.sidoarjokab.go.id/upload/link/1703218932.png" alt="{{ $org->name }}" class="w-12 h-12 object-contain opacity-80 group-hover:opacity-100">
                        </div>

                        <h3 class="font-bold text-[11px] text-gray-800 dark:text-white group-hover:text-green-700 transition-colors leading-snug tracking-tighter mb-4 line-clamp-2 h-7 text-center">
                            {{ $org->name }}
                        </h3>

                        <div class="mt-auto pt-4 border-t border-gray-50 dark:border-gray-800 w-full flex justify-between items-center leading-none">
                            <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Datasets</span>
                            <span class="text-sm font-black text-green-700">{{ $org->datasets_count ?? 0 }}</span>
                        </div>

                        <div class="mt-5 w-full py-2.5 rounded-xl bg-gray-50 dark:bg-gray-800 text-green-700 font-bold text-[8px] uppercase tracking-[0.2em] group-hover:bg-green-700 group-hover:text-white transition-all">
                            Lihat Detail
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </main>

        <footer class="bg-white dark:bg-gray-950 py-10 border-t border-gray-100 dark:border-gray-900 text-center text-left">
            <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.4em] text-center">© {{ date('Y') }} PEMKAB SIDOARJO • PORTAL SATU DATA</p>
        </footer>
    </div>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;  
        overflow: hidden;
    }
</style>
@endsection