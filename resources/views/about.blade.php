@extends('layouts.app')

@section('content')
<div class="relative min-h-screen bg-[#FBFBFB] dark:bg-gray-950 text-gray-900 dark:text-gray-100 overflow-x-hidden">
    
    {{-- NAVBAR TIDAK DIUBAH SAMA SEKALI --}}
    <header class="fixed top-0 left-0 right-0 z-50 px-4 pt-5 transition-all duration-300">
            <div class="max-w-6xl mx-auto bg-white/70 dark:bg-gray-900/70 backdrop-blur-md rounded-full shadow-xl px-6 py-3 flex items-center justify-between border border-white/30 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <img src="https://adminweb.sidoarjokab.go.id/upload/link/1703218932.png" 
                         alt="Logo Kabupaten Sidoarjo" 
                         class="w-9 h-9 object-contain">
                         <h1 class="text-sm md:text-lg font-bold text-gray-800 dark:text-white leading-tight">
                        <span class="block">Satu Data</span>
                        <span class="block text-xs md:text-sm font-semibold opacity-80">Kabupaten Sidoarjo</span>
                </div>

                <nav class="hidden md:flex space-x-6">
                    <a href="/" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-medium transition">Home</a>
                    <a href="/datasets" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-medium transition">Datasets</a>
                    <a href="/organizations" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-medium transition">Organizations</a>
                    <a href="/groups" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-medium transition">Groups</a>
                    <a href="/about" class="text-sm text-green-600 font-bold transition">About</a>
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

    <main class="pt-32 pb-16 px-6 max-w-4xl mx-auto">
        
        {{-- Hero Text About --}}
        <section class="text-center mb-16">
            <span class="text-[9px] font-black text-green-700 uppercase tracking-[0.3em] mb-3 block">Tentang Kami</span>
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-4">
                Portal Data Terpadu <br> <span class="text-green-700">Kabupaten Sidoarjo</span>
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-xl mx-auto leading-relaxed">
                Menyediakan akses data sektoral yang akurat dan transparan untuk mendukung pembangunan Kabupaten Sidoarjo yang lebih baik.
            </p>
        </section>

        {{-- Visi Misi --}}
        <div class="grid md:grid-cols-2 gap-8 md:gap-12 mb-16 bg-white dark:bg-gray-900 p-8 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm">
            <div class="space-y-4">
                <h2 class="text-lg font-bold flex items-center text-gray-800 dark:text-white">
                    <span class="w-6 h-1 bg-green-700 mr-2.5 rounded-full"></span> Visi
                </h2>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-300 leading-relaxed italic">
                    "Mewujudkan tata kelola data pemerintah yang akurat, mutakhir, terpadu, dan dapat dipertanggungjawabkan."
                </p>
            </div>
            <div class="space-y-4">
                <h2 class="text-lg font-bold flex items-center text-gray-800 dark:text-white">
                    <span class="w-6 h-1 bg-green-700 mr-2.5 rounded-full"></span> Misi
                </h2>
                <ul class="space-y-2.5 text-xs text-gray-500 dark:text-gray-400 font-medium">
                    <li class="flex items-start"><span class="text-green-700 mr-2 font-bold">•</span> Standarisasi metadata sektoral seluruh OPD.</li>
                    <li class="flex items-start"><span class="text-green-700 mr-2 font-bold">•</span> Penyebarluasan data yang mudah diakses publik.</li>
                    <li class="flex items-start"><span class="text-green-700 mr-2 font-bold">•</span> Penguatan koordinasi antar produsen data.</li>
                </ul>
            </div>
        </div>

        {{-- Call to Action / Kontak --}}
        <div class="bg-green-700 rounded-3xl p-8 md:p-10 text-white relative overflow-hidden shadow-lg">
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="text-center md:text-left max-w-md">
                    <h2 class="text-xl font-black mb-2">Ingin berkontribusi?</h2>
                    <p class="text-green-100 text-xs font-medium opacity-90 leading-relaxed">
                        Kami terbuka untuk kolaborasi data. Jika instansi Anda memiliki data publik yang ingin dibagikan, silakan hubungi tim pengelola kami.
                    </p>
                </div>
                <div class="flex-shrink-0 mt-4 md:mt-0">
                    <a href="mailto:satudata@sidoarjokab.go.id" class="inline-block bg-white text-green-700 px-6 py-3 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-gray-100 transition shadow">
                        Hubungi Kami
                    </a>
                </div>
            </div>
            {{-- Hiasan aksen dekoratif --}}
            <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -left-4 top-0 w-24 h-24 bg-black opacity-10 rounded-full blur-xl pointer-events-none"></div>
        </div>

    </main>

    <footer class="bg-white dark:bg-gray-950 py-10 border-t border-gray-100 dark:border-gray-900 text-center text-left">
            <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.4em] text-center">© {{ date('Y') }} PEMKAB SIDOARJO • PORTAL SATU DATA</p>
    </footer>
</div>
@endsection