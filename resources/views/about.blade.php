@extends('layouts.app')

@section('content')
<div x-data="{ mobileMenuOpen: false }" class="relative min-h-screen bg-[#FBFBFB] dark:bg-gray-950 text-gray-900 dark:text-gray-100 overflow-x-hidden">
    
    {{-- NAVBAR --}}
    <header class="fixed top-0 left-0 right-0 z-50 px-4 pt-4 md:pt-5 transition-all duration-300">
        <div class="max-w-6xl mx-auto bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-2xl md:rounded-full shadow-xl px-4 md:px-6 py-3 flex items-center justify-between border border-white/30 dark:border-gray-700">
            <a href="/" class="flex items-center space-x-3 text-left group transition hover:text-green-600">
                <img src="https://adminweb.sidoarjokab.go.id/upload/link/1703218932.png" alt="Logo" class="w-8 h-8 md:w-9 md:h-9 object-contain">
                <h1 class="text-sm md:text-lg font-bold text-gray-800 dark:text-white leading-tight">
                    <span class="block">Satu Data</span>
                    <span class="block text-[10px] md:text-sm font-semibold opacity-80">Kab. Sidoarjo</span>
                </h1>
            </a>

            <nav class="hidden md:flex space-x-6">
                <a href="/" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 font-medium transition">Home</a>
                <a href="/datasets" class="text-sm text-green-600 font-bold transition">Datasets</a>
                <a href="/organizations" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 font-medium transition">Organizations</a>
                <a href="/groups" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 font-medium transition">Groups</a>
                <a href="/about" class="text-sm text-green-600 font-bold transition">About</a>
            </nav>

            <div class="flex items-center space-x-2 md:space-x-4">
                <button onclick="window.location.href='{{ url('/login') }}'" class="hidden md:block bg-green-700 hover:bg-green-800 text-white px-5 py-2 rounded-full transition text-sm font-medium shadow-lg shadow-green-500/20">Login</button>
                <button id="theme-toggle" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition focus:outline-none">
                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg id="theme-toggle-light-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707-.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"></path></svg>
                </button>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                    <svg x-show="mobileMenuOpen" style="display: none;" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
        {{-- Mobile Menu --}}
        <div x-show="mobileMenuOpen" x-transition class="md:hidden mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex flex-col space-y-3 pb-2 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-2xl p-4 shadow-xl">
            <a href="/" class="text-sm text-green-600 font-bold">Home</a>
            <a href="/datasets" class="text-sm text-gray-700 dark:text-gray-300 font-medium">Datasets</a>
            <a href="/organizations" class="text-sm text-gray-700 dark:text-gray-300 font-medium">Organizations</a>
            <a href="/groups" class="text-sm text-gray-700 dark:text-gray-300 font-medium">Groups</a>
            <a href="/about" class="text-sm text-green-600 font-bold">About</a>
            <button onclick="window.location.href='{{ url('/login') }}'" class="w-full mt-2 bg-green-700 text-white px-5 py-2.5 rounded-xl font-medium">Login</button>
        </div>
    </header>

    <main class="pt-32 pb-20 px-6 max-w-4xl mx-auto">
        <section class="text-center mb-16 px-2">
            <span class="text-[10px] font-black text-green-700 uppercase tracking-[0.3em] mb-3 block">Tentang Kami</span>
            <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-6 leading-tight">
                Portal Data Terpadu<br>
                <span class="text-green-700">Kabupaten Sidoarjo</span>
            </h1>
            <p class="text-sm md:text-base text-gray-500 dark:text-gray-400 max-w-xl mx-auto leading-relaxed">
                Menyediakan akses data sektoral yang akurat dan transparan untuk mendukung pembangunan Kabupaten Sidoarjo yang lebih baik.
            </p>
        </section>

        <div class="grid md:grid-cols-2 gap-6 mb-16 bg-white dark:bg-gray-900 p-6 md:p-10 rounded-[32px] border border-gray-100 dark:border-gray-800 shadow-sm">
            <div class="space-y-4">
                <h2 class="text-md font-bold flex items-center text-gray-800 dark:text-white">
                    <span class="w-6 h-1 bg-green-700 mr-3 rounded-full"></span> Visi
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed italic">
                    "Mewujudkan tata kelola data pemerintah yang akurat, mutakhir, terpadu, dan dapat dipertanggungjawabkan."
                </p>
            </div>
            <div class="space-y-4">
                <h2 class="text-md font-bold flex items-center text-gray-800 dark:text-white">
                    <span class="w-6 h-1 bg-green-700 mr-3 rounded-full"></span> Misi
                </h2>
                <ul class="space-y-3 text-xs md:text-sm text-gray-600 dark:text-gray-400 font-medium">
                    <li class="flex items-start"><span class="text-green-700 mr-2">✓</span> Standarisasi metadata sektoral seluruh OPD.</li>
                    <li class="flex items-start"><span class="text-green-700 mr-2">✓</span> Penyebarluasan data yang mudah diakses publik.</li>
                    <li class="flex items-start"><span class="text-green-700 mr-2">✓</span> Penguatan koordinasi antar produsen data.</li>
                </ul>
            </div>
        </div>

        <div class="bg-green-700 rounded-[32px] p-8 md:p-12 text-white relative overflow-hidden shadow-lg">
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="text-center md:text-left max-w-sm">
                    <h2 class="text-xl font-black mb-3">Ingin berkontribusi?</h2>
                    <p class="text-green-100 text-xs md:text-sm opacity-90 leading-relaxed">
                        Kami terbuka untuk kolaborasi data. Hubungi tim pengelola jika instansi Anda memiliki data publik yang ingin dibagikan.
                    </p>
                </div>
                <a href="mailto:satudata@sidoarjokab.go.id" class="inline-block bg-white text-green-700 px-8 py-4 rounded-full font-bold text-[11px] uppercase tracking-widest hover:bg-gray-100 transition shadow">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </main>

    <footer class="py-10 border-t border-gray-100 dark:border-gray-900 text-center">
        <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.4em]">© {{ date('Y') }} PEMKAB SIDOARJO</p>
    </footer>
</div>
@endsection