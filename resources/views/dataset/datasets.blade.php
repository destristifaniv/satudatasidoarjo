@extends('layouts.app')

@section('content')
<div class="relative min-h-screen bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 overflow-x-hidden text-left">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .pagination nav > div:first-child { display: none !important; }
        .pagination nav span[aria-current="page"] span { background-color: #15803d !important; color: white !important; border: none !important; }
        .pagination a, .pagination span { border-radius: 12px !important; margin: 0 2px !important; font-weight: 500 !important; font-size: 10px !important; }
        .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        #dataset-wrapper { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>

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

    {{-- Perubahan: px-0 untuk menghilangkan padding luar sehingga sejajar dengan navbar max-w-6xl --}}
    <main class="pt-32 pb-40 px-0 max-w-6xl mx-auto text-left">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 px-2">
            <div class="text-left">
                {{-- <nav class="flex text-[9px] text-gray-400 mb-3 uppercase tracking-widest">
                    <a href="/" class="hover:text-green-700 transition">Home</a>
                    <span class="mx-2 text-gray-300">/</span>
                    <span class="text-green-700">Datasets</span>
                </nav> --}}
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight leading-none">
                    Eksplorasi <span class="text-green-700">Dataset</span>
                </h2>
                <p class="text-[15px] text-gray-500 mt-2 opacity-70">Manajemen file & konten data sektoral Sidoarjo</p>
            </div>
            
            <div class="flex items-center space-x-2 mt-6 md:mt-0">
                <div class="flex bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-1 shadow-lg">
                    <button id="btn-grid" class="p-2.5 rounded-xl transition-all hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-400">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    </button>
                    <button id="btn-list" class="p-2.5 rounded-xl transition-all bg-green-700 text-white shadow-md">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 text-left">
            <aside class="lg:col-span-3 text-left">
                <div class="bg-white dark:bg-gray-900 rounded-[30px] p-6 shadow-xl border border-white dark:border-gray-800 sticky top-24">
                    <form action="{{ route('public.datasets') }}" method="GET">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-left">Filter</h3>
                            <a href="{{ route('public.datasets') }}" class="text-[9px] text-green-700 hover:underline font-bold">Reset</a>
                        </div>
                        <div class="space-y-6">
                            <div>
                                <label class="text-[8px] text-gray-400 mb-3 block uppercase font-bold tracking-widest">Organisasi</label>
                                <select name="org" class="w-full bg-gray-50 dark:bg-gray-800 border-none rounded-xl py-3 px-4 text-[10px] outline-none shadow-sm cursor-pointer font-medium">
                                    <option value="">Semua</option>
                                    @foreach($organizations as $org)
                                        <option value="{{ $org->id }}" {{ request('org') == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[8px] text-gray-400 mb-3 block uppercase font-bold tracking-widest">Tahun</label>
                                <select name="year" class="w-full bg-gray-50 dark:bg-gray-800 border-none rounded-xl py-3 px-4 text-[10px] outline-none shadow-sm font-medium">
                                    <option value="">Semua</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="w-full py-3 bg-green-700 text-white rounded-xl text-[9px] font-bold shadow-lg hover:bg-green-800 active:scale-95 transition-all uppercase tracking-widest">Terapkan</button>
                        </div>
                    </form>
                </div>
            </aside>

            <div class="lg:col-span-9 text-left">
                <div class="text-[9px] text-gray-400 mb-6 px-2 flex justify-between w-full font-bold uppercase tracking-widest">
                    <span>Hasil: {{ $latest_datasets->total() }} dataset</span>
                    <span class="text-green-700 font-black">Halaman {{ $latest_datasets->currentPage() }}</span>
                </div>

                <div id="dataset-wrapper" class="space-y-4 text-left">
                    @foreach($latest_datasets as $dataset)
                    <a href="/datasets/{{ $dataset->id }}" class="dataset-card block group bg-white dark:bg-gray-900 rounded-[24px] p-4 border border-white dark:border-gray-800 shadow-sm hover:shadow-lg transition-all duration-300 relative overflow-hidden text-left">
                        <div class="flex-content flex flex-col md:flex-row items-center gap-5 relative z-10 text-left">
                            <div class="logo-box w-12 h-12 bg-gray-50 dark:bg-gray-800 rounded-2xl p-2.5 flex items-center justify-center shrink-0 border dark:border-gray-700 shadow-inner transition-transform duration-300">
                                <img src="https://adminweb.sidoarjokab.go.id/upload/link/1703218932.png" class="w-8 h-8 object-contain opacity-80" alt="Logo">
                            </div>
                            <div class="flex-1 w-full text-left">
                                <div class="flex flex-wrap justify-between items-center mb-1.5 gap-2 leading-none text-left font-medium">
                                    <div class="flex items-center space-x-2 text-left">
                                        @php $ext = pathinfo($dataset->file_path, PATHINFO_EXTENSION); @endphp
                                        <span class="bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400 px-2 py-1 rounded-lg text-[7px] border border-green-200/30 text-left font-bold uppercase">{{ strtoupper($ext) ?: 'XLSX' }}</span>
                                        <span class="text-[8px] text-gray-400 flex items-center text-left font-bold uppercase tracking-widest">
                                            <svg class="w-3 h-3 mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
                                            {{ $dataset->downloads ?? 0 }} download
                                        </span>
                                    </div>
                                    <span class="text-[8px] text-gray-400 text-left uppercase font-bold tracking-widest">{{ $dataset->created_at->diffForHumans() }}</span>
                                </div>
                                <h4 class="dataset-title text-base text-gray-900 dark:text-white group-hover:text-green-700 transition-colors text-left leading-tight font-bold tracking-tight">
                                    {{ $dataset->name }} <span class="text-green-700/30 font-medium">({{ $dataset->year_start }})</span>
                                </h4>
                                <p class="description-text text-[10px] text-gray-500 dark:text-gray-400 line-clamp-1 opacity-80 text-left mt-1 font-medium leading-relaxed">
                                    {{ $dataset->description }}
                                </p>
                            </div>
                        </div>
                    </a> 
                    @endforeach
                </div>

                <div class="pagination flex justify-center pt-10">
                    {{ $latest_datasets->onEachSide(1)->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-white dark:bg-gray-950 py-10 border-t border-gray-100 dark:border-gray-900 text-center text-left">
            <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.4em] text-center">© {{ date('Y') }} PEMKAB SIDOARJO • PORTAL SATU DATA</p>
    </footer>
</div>
<script>
    const btnGrid = document.getElementById('btn-grid');
    const btnList = document.getElementById('btn-list');
    const wrapper = document.getElementById('dataset-wrapper');
    const cards = document.querySelectorAll('.dataset-card');

    function setListMode() {
        btnList.classList.add('bg-green-700', 'text-white', 'shadow-md');
        btnGrid.classList.remove('bg-green-700', 'text-white', 'shadow-md');
        wrapper.classList.remove('grid', 'grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-4', 'gap-4');
        wrapper.classList.add('space-y-4');
        cards.forEach(card => {
            card.classList.remove('h-full');
            card.querySelector('.flex-content').classList.remove('md:flex-col', 'text-center');
            card.querySelector('.flex-content').classList.add('md:flex-row');
            card.querySelector('.description-text').classList.add('line-clamp-1');
            card.querySelector('.description-text').classList.remove('line-clamp-2');
            card.querySelector('.dataset-title').classList.remove('text-center', 'text-sm');
            card.querySelector('.dataset-title').classList.add('text-lg');
        });
    }

    function setGridMode() {
        btnGrid.classList.add('bg-green-700', 'text-white', 'shadow-md');
        btnList.classList.remove('bg-green-700', 'text-white', 'shadow-md');
        wrapper.classList.remove('space-y-4');
        wrapper.classList.add('grid', 'grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-4', 'gap-4');
        cards.forEach(card => {
            card.classList.add('h-full');
            card.querySelector('.flex-content').classList.remove('md:flex-row');
            card.querySelector('.flex-content').classList.add('md:flex-col', 'text-center');
            card.querySelector('.description-text').classList.add('line-clamp-2');
            card.querySelector('.description-text').classList.remove('line-clamp-1');
            card.querySelector('.dataset-title').classList.add('text-center', 'text-sm');
            card.querySelector('.dataset-title').classList.remove('text-lg');
        });
    }

    btnGrid.addEventListener('click', setGridMode);
    btnList.addEventListener('click', setListMode);
</script>
@endsection