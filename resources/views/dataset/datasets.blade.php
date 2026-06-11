@extends('layouts.app')

@section('content')
<div class="relative min-h-screen bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 overflow-x-hidden text-left">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* PAGINATION BASE */
        .pagination nav { width: 100%; display: flex; flex-direction: column; align-items: center; }

        /* PAGINATION DESKTOP */
        @media (min-width: 640px) {
            .pagination nav > div.hidden.sm\:flex-1 { 
                display: flex !important; 
                justify-content: center !important; 
                width: 100%;
            }
            .pagination nav > div.hidden.sm\:flex-1 > div:first-child { display: none !important; }
        }

        .pagination nav span.relative.z-0.inline-flex { 
            box-shadow: none !important; 
            gap: 6px; 
            flex-wrap: wrap; 
            justify-content: center; 
        }
        
        /* FIX: Menghilangkan styling dari wrapper bawaan Laravel agar tidak bentrok "kotak di dalam kotak" */
        .pagination nav span.relative.z-0.inline-flex > span {
            display: contents;
        }

        /* Target langsung ke tombol angka/panah */
        .pagination nav span.relative.z-0.inline-flex > span > span, 
        .pagination nav span.relative.z-0.inline-flex > a { 
            border-radius: 10px !important; 
            margin: 0 !important; 
            font-weight: 600 !important; 
            font-size: 14px !important; 
            padding: 8px 14px !important; 
            border: 1px solid #e5e7eb !important; 
            background-color: white !important; 
            color: #4b5563 !important; 
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important; 
            transition: all 0.2s; 
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
        }
        
        .pagination nav span.relative.z-0.inline-flex > a:hover { 
            background-color: #f3f4f6 !important; 
            transform: translateY(-2px); 
        }
        
        /* Tombol Aktif */
        .pagination nav span[aria-current="page"] > span { 
            background-color: #15803d !important; 
            color: white !important; 
            border-color: #15803d !important; 
            box-shadow: 0 4px 6px -1px rgba(21, 128, 61, 0.3) !important; 
        }

        /* Tombol Disabled (Panah ujung jika tidak bisa diklik) */
        .pagination nav span[aria-disabled="true"] > span {
            opacity: 0.5;
            cursor: not-allowed;
            background-color: #f9fafb !important;
        }
        
        /* PAGINATION MOBILE */
        .pagination nav > div.sm\:hidden { 
            display: flex !important; 
            width: 100%; 
            justify-content: space-between;
            gap: 12px; 
        }
        
        @media (min-width: 640px) {
            .pagination nav > div.sm\:hidden { display: none !important; }
        }

        .pagination nav > div.sm\:hidden > a, 
        .pagination nav > div.sm\:hidden > span { 
            flex: 1; 
            display: flex !important; 
            flex-direction: row !important; 
            align-items: center !important; 
            justify-content: center !important; 
            gap: 8px !important;
            border-radius: 10px !important; 
            padding: 10px 16px !important; 
            font-weight: 600 !important; 
            font-size: 14px !important; 
            color: #15803d !important; 
            background-color: white !important; 
            border: 1px solid #e5e7eb !important; 
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05) !important; 
            transition: all 0.2s; 
            text-align: center;
        }

        .pagination nav > div.sm\:hidden > a:hover {
            background-color: #f0fdf4 !important;
            border-color: #15803d !important;
        }

        .pagination nav > div.sm\:hidden > span {
            color: #9ca3af !important;
            border-color: #e5e7eb !important;
            background-color: #f9fafb !important;
            box-shadow: none !important;
        }
        
        /* DARK MODE - DESKTOP & MOBILE */
        .dark .pagination nav span.relative.z-0.inline-flex > span > span, 
        .dark .pagination nav span.relative.z-0.inline-flex > a { 
            background-color: #1f2937 !important; 
            border-color: #374151 !important; 
            color: #d1d5db !important; 
        }
        .dark .pagination nav span[aria-current="page"] > span { 
            background-color: #15803d !important; 
            color: white !important; 
            border-color: #15803d !important; 
        }
        .dark .pagination nav span.relative.z-0.inline-flex > a:hover { 
            background-color: #374151 !important; 
        }
        .dark .pagination nav span[aria-disabled="true"] > span {
            background-color: #111827 !important;
            opacity: 0.4;
        }

        .dark .pagination nav > div.sm\:hidden > a { background-color: #1f2937 !important; border-color: #374151 !important; color: #4ade80 !important; }
        .dark .pagination nav > div.sm\:hidden > span { background-color: #111827 !important; border-color: #374151 !important; color: #6b7280 !important; }

        /* UTILITIES */
        .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        #dataset-wrapper { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        @media (max-width: 1024px) {
            .mobile-filter-container { display: flex; flex-direction: column; gap: 1rem; }
            .mobile-filter-item { width: 100%; }
            #dataset-wrapper { display: flex !important; flex-direction: column !important; }
            .dataset-card { width: 100% !important; margin-bottom: 1rem; }
        }
    </style>

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
                <a href="/about" class="text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 font-medium transition">About</a>
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
            <a href="/about" class="text-sm text-gray-700 dark:text-gray-300 font-medium">About</a>
            <button onclick="window.location.href='{{ url('/login') }}'" class="w-full mt-2 bg-green-700 text-white px-5 py-2.5 rounded-xl font-medium">Login</button>
        </div>
    </header>

    <main class="pt-28 md:pt-32 pb-20 md:pb-40 px-4 md:px-6 max-w-6xl mx-auto text-left">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-6 md:mb-8">
            <div class="text-left mt-4 md:mt-0">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight leading-none">
                    Eksplorasi <span class="text-green-700">Dataset</span>
                </h2>
                <p class="text-sm md:text-base text-gray-500 mt-2 md:mt-3 opacity-70">Manajemen file & konten data sektoral Sidoarjo</p>
            </div>
            
            <div class="hidden lg:flex items-center space-x-2 mt-6 md:mt-0">
                <div class="flex bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-1 shadow-lg">
                    <button id="btn-grid" class="p-2.5 rounded-xl transition-all hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-400">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    </button>
                    <button id="btn-list" class="p-2.5 rounded-xl transition-all bg-green-700 text-white shadow-md">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8 text-left">
            {{-- SIDEBAR FILTER --}}
            <aside class="lg:col-span-3 text-left">
                <div class="bg-white dark:bg-gray-900 rounded-2xl md:rounded-[30px] p-5 md:p-6 shadow-xl border border-white dark:border-gray-800 lg:sticky lg:top-24 w-full">
                    <form action="{{ route('public.datasets') }}" method="GET">
                        <div class="flex items-center justify-between mb-5 md:mb-6">
                            <h3 class="text-sm md:text-xs font-bold uppercase tracking-widest text-left">Filter</h3>
                            <a href="{{ route('public.datasets') }}" class="text-xs text-green-700 hover:underline font-bold bg-green-50 dark:bg-green-900/30 px-3 py-1.5 rounded-full">Reset</a>
                        </div>
                        
                        <div class="mobile-filter-container lg:block lg:space-y-5">
                            <div class="mobile-filter-item">
                                <label class="text-xs text-gray-500 mb-2 block uppercase font-bold tracking-widest">Pencarian</label>
                                <div class="relative">
                                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul, tags..." class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 md:border-none rounded-xl py-3.5 pl-10 pr-4 text-sm outline-none shadow-sm font-medium focus:ring-2 focus:ring-green-500 transition-all text-gray-700 dark:text-gray-200">
                                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                            </div>

                            <div class="mobile-filter-item">
                                <label class="text-xs text-gray-500 mb-2 block uppercase font-bold tracking-widest">Organisasi</label>
                                <select name="org" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 md:border-none rounded-xl py-3.5 px-4 text-sm outline-none shadow-sm cursor-pointer font-medium appearance-none">
                                    <option value="">Semua Organisasi</option>
                                    @foreach($organizations as $org)
                                        <option value="{{ $org->id }}" {{ request('org') == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mobile-filter-item">
                                <label class="text-xs text-gray-500 mb-2 block uppercase font-bold tracking-widest">Tahun</label>
                                <select name="year" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 md:border-none rounded-xl py-3.5 px-4 text-sm outline-none shadow-sm cursor-pointer font-medium appearance-none">
                                    <option value="">Semua Tahun</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mobile-filter-item flex items-end pt-2">
                                <button type="submit" class="w-full py-3.5 bg-green-700 text-white rounded-xl text-xs font-bold shadow-lg hover:bg-green-800 active:scale-95 transition-all uppercase tracking-widest">Terapkan Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </aside>

            {{-- LIST DATASET --}}
            <div class="lg:col-span-9 text-left">
                <div class="text-xs text-gray-500 mb-5 px-1 md:px-2 flex justify-between w-full font-bold uppercase tracking-widest bg-gray-100 dark:bg-gray-800 p-3 rounded-lg lg:bg-transparent lg:p-0">
                    <span>Hasil: <span class="text-gray-800 dark:text-gray-200">{{ $latest_datasets->total() }}</span> dataset</span>
                    <span class="text-green-700 font-black">Halaman {{ $latest_datasets->currentPage() }}</span>
                </div>

                <div id="dataset-wrapper" class="flex flex-col space-y-4 text-left w-full">
                    @foreach($latest_datasets as $dataset)
                    <a href="/datasets/{{ $dataset->id }}" class="dataset-card block group bg-white dark:bg-gray-900 rounded-2xl md:rounded-[24px] p-5 border border-gray-100 md:border-white dark:border-gray-800 shadow-md hover:shadow-xl transition-all duration-300 relative overflow-hidden text-left w-full">
                        <div class="flex-content flex flex-row items-center gap-4 md:gap-5 relative z-10 text-left">
                            
                            <div class="logo-box hidden sm:flex w-14 h-14 bg-gray-50 dark:bg-gray-800 rounded-2xl p-2.5 items-center justify-center shrink-0 border border-gray-100 dark:border-gray-700 shadow-inner transition-transform duration-300">
                                <img src="https://adminweb.sidoarjokab.go.id/upload/link/1703218932.png" class="w-9 h-9 object-contain opacity-80" alt="Logo">
                            </div>
                            
                            <div class="flex-1 min-w-0 text-left">
                                <div class="flex flex-wrap items-center gap-2.5 mb-2.5 leading-none text-left font-medium">
                                    @php $ext = pathinfo($dataset->file_path, PATHINFO_EXTENSION); @endphp
                                    <span class="bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400 px-2.5 py-1 rounded-md text-[10px] border border-green-200/30 text-left font-bold uppercase">{{ strtoupper($ext) ?: 'XLSX' }}</span>
                                    
                                    <span class="text-[10px] text-gray-500 flex items-center font-bold uppercase tracking-widest">
                                        <svg class="w-3.5 h-3.5 mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
                                        {{ $dataset->downloads ?? 0 }}
                                    </span>
                                    
                                    <span class="text-[10px] text-gray-400 ml-auto uppercase font-bold tracking-widest hidden sm:block">{{ $dataset->created_at->diffForHumans() }}</span>
                                </div>
                                
                                <h4 class="dataset-title text-base md:text-lg text-gray-900 dark:text-white group-hover:text-green-700 transition-colors text-left leading-snug font-bold tracking-tight truncate md:whitespace-normal md:line-clamp-2">
                                    {{ $dataset->name }} <span class="text-green-700/50 font-medium text-sm">({{ $dataset->year_start }})</span>
                                </h4>
                                
                                <p class="description-text text-xs text-gray-500 dark:text-gray-400 line-clamp-1 opacity-90 text-left mt-2 font-medium leading-relaxed">
                                    {{ $dataset->description }}
                                </p>
                            </div>
                        </div>
                    </a> 
                    @endforeach
                </div>

                {{-- PAGINATION --}}
                <div class="pagination w-full flex justify-center mt-8 md:mt-10 mb-4 bg-white dark:bg-gray-900 p-3 md:p-4 rounded-2xl shadow-sm overflow-x-auto">
                    {{ $latest_datasets->onEachSide(1)->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </main>

    <footer class="py-10 border-t border-gray-100 dark:border-gray-900 text-center">
        <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.4em]">© {{ date('Y') }} PEMKAB SIDOARJO</p>
    </footer>
</div>

<script>
    const btnGrid = document.getElementById('btn-grid');
    const btnList = document.getElementById('btn-list');
    const wrapper = document.getElementById('dataset-wrapper');
    const cards = document.querySelectorAll('.dataset-card');

    if(btnGrid && btnList) {
        function setListMode() {
            if (window.innerWidth < 1024) return;
            
            btnList.classList.add('bg-green-700', 'text-white', 'shadow-md');
            btnGrid.classList.remove('bg-green-700', 'text-white', 'shadow-md');
            wrapper.classList.remove('grid', 'grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-3', 'gap-6');
            wrapper.classList.add('flex', 'flex-col', 'space-y-4');
            
            cards.forEach(card => {
                card.classList.remove('h-full');
                const content = card.querySelector('.flex-content');
                content.classList.remove('flex-col', 'text-center', 'items-center');
                content.classList.add('flex-row', 'text-left');
                
                card.querySelector('.description-text').classList.add('line-clamp-1');
                card.querySelector('.description-text').classList.remove('line-clamp-3');
                
                const title = card.querySelector('.dataset-title');
                title.classList.remove('text-center', 'text-sm');
                title.classList.add('text-left', 'text-base');
                
                const logo = card.querySelector('.logo-box');
                if(logo) { logo.classList.add('sm:flex'); logo.classList.remove('hidden'); }
            });
        }

        function setGridMode() {
            if (window.innerWidth < 1024) return;
            
            btnGrid.classList.add('bg-green-700', 'text-white', 'shadow-md');
            btnList.classList.remove('bg-green-700', 'text-white', 'shadow-md');
            wrapper.classList.remove('flex', 'flex-col', 'space-y-4');
            wrapper.classList.add('grid', 'grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-3', 'gap-6');
            
            cards.forEach(card => {
                card.classList.add('h-full');
                const content = card.querySelector('.flex-content');
                content.classList.remove('flex-row', 'text-left');
                content.classList.add('flex-col', 'text-center', 'items-center');
                
                card.querySelector('.description-text').classList.add('line-clamp-3');
                card.querySelector('.description-text').classList.remove('line-clamp-1');
                
                const title = card.querySelector('.dataset-title');
                title.classList.add('text-center', 'text-sm');
                title.classList.remove('text-left', 'text-base');
                
                const logo = card.querySelector('.logo-box');
                if(logo) { logo.classList.remove('hidden'); logo.classList.add('flex'); }
            });
        }

        btnGrid.addEventListener('click', setGridMode);
        btnList.addEventListener('click', setListMode);
        
        if (window.innerWidth >= 1024) {
            setListMode();
        }
    }
</script>
@endsection