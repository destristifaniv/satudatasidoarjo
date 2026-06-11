@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-[#F8F9FA] dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-500 overflow-hidden relative" 
     x-data="{ 
        sidebarOpen: window.innerWidth >= 1024,
        imageUrl: '{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : '' }}',
        fileChosen(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = (e) => {
                this.imageUrl = e.target.result;
            };
        }
     }" @resize.window="sidebarOpen = window.innerWidth >= 1024">
    
    <aside :class="sidebarOpen ? 'translate-x-0 shadow-xl' : '-translate-x-full shadow-none'" class="fixed inset-y-0 left-0 z-50 w-72 transform overflow-hidden bg-gradient-to-b from-teal-700 via-teal-600 to-teal-500 dark:from-teal-900 dark:via-teal-800 dark:to-teal-700 backdrop-blur-2xl border-r border-teal-400/30 transition-all duration-500 ease-in-out flex flex-col lg:relative lg:translate-x-0 lg:w-72 lg:shadow-xl lg:sticky lg:top-0 lg:h-screen lg:z-auto">
        <div class="p-6 h-24 border-b border-teal-400/30 flex items-center justify-between overflow-hidden shrink-0 text-left">
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
                <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs group-hover:scale-110">DB</span>
            </a>
            <a href="{{ route('admin.organizations') }}" class="flex items-center px-6 py-4 bg-white/20 rounded-2xl shadow-lg font-bold text-sm transition-all">
                <span x-show="sidebarOpen" class="whitespace-nowrap">Organisasi</span>
                <span x-show="!sidebarOpen" class="mx-auto font-black text-xs">OR</span>
            </a>
        </nav>
    </aside>
    
    <main class="flex-1 min-h-screen overflow-y-auto relative p-4 sm:p-6 lg:p-8 scroll-smooth text-left lg:ml-72">
        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 bg-black/30 lg:hidden" @click="sidebarOpen = false"></div>
        <div class="fixed top-0 right-0 w-[500px] h-[500px] bg-teal-200/20 dark:bg-teal-900/10 blur-[120px] rounded-full -z-10 opacity-60"></div>
        
        <header class="mb-4 sm:mb-6 flex flex-col md:flex-row justify-between md:items-center gap-3 sm:gap-4 max-w-5xl mx-auto w-full">
            <div class="flex items-center justify-between gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2.5 rounded-xl bg-white dark:bg-gray-900 shadow-sm border border-gray-100 dark:border-gray-800 text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <div>
                    <h1 class="text-xl sm:text-2xl font-black tracking-tight text-gray-900 dark:text-white uppercase leading-none">
                        Pengaturan <span class="text-teal-600">Keamanan & Profil</span>
                    </h1>
                    <p class="text-[8px] sm:text-[9px] text-gray-400 font-black uppercase tracking-[0.3em] mt-1.5">Manajemen Identitas & Akses Instansi</p>
                </div>
            </div>
            <a href="{{ route('admin.organizations') }}" class="w-full md:w-auto px-4 sm:px-5 py-2 sm:py-2.5 bg-white/50 dark:bg-gray-800/50 backdrop-blur-xl border border-white dark:border-gray-700 text-gray-500 dark:text-gray-400 rounded-[12px] sm:rounded-xl font-black text-[8px] sm:text-[9px] uppercase tracking-widest hover:bg-gray-100 transition-all shadow-sm text-center">
                ← Kembali
            </a>
        </header>

        <div class="max-w-5xl mx-auto w-full space-y-3 sm:space-y-4 px-2 sm:px-0">
            
            {{-- Form Profil --}}
            <div class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-2xl rounded-[20px] sm:rounded-[24px] shadow-xl border border-white dark:border-gray-800 p-4 sm:p-6 relative overflow-hidden">
                <form action="{{ route('admin.organizations.update') }}" method="POST" enctype="multipart/form-data" class="relative z-10">
                    @csrf
                    <div class="flex flex-col md:flex-row gap-3 sm:gap-6 items-center md:items-start">
                        
                        {{-- Avatar --}}
                        <div class="w-full md:w-auto flex flex-col items-center shrink-0">
                            <div class="group relative">
                                <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-[16px] sm:rounded-[20px] flex items-center justify-center text-white text-2xl sm:text-3xl font-black shadow-lg overflow-hidden transition-transform duration-500 group-hover:scale-[1.02]">
                                    <template x-if="imageUrl">
                                        <img :src="imageUrl" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!imageUrl">
                                        <span>{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </template>
                                    <label class="absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center cursor-pointer">
                                            <span class="text-[7px] sm:text-[8px] font-black uppercase tracking-widest text-white">Ganti Profil</span>
                                        <input type="file" name="avatar" class="hidden" accept="image/*" @change="fileChosen">
                                    </label>
                                </div>
                            </div>
                            <p class="text-[7px] sm:text-[8px] text-gray-400 font-bold uppercase tracking-widest mt-1.5 sm:mt-2">Logo Instansi</p>
                        </div>

                        {{-- Input Profil --}}
                        <div class="flex-1 w-full space-y-2 sm:space-y-3">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 sm:gap-3">
                                <div class="space-y-0.5 sm:space-y-1 group">
                                    <label class="text-[7px] sm:text-[8px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2 sm:ml-3 transition-colors group-focus-within:text-teal-600 block">Nama Instansi *</label>
                                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 rounded-[10px] sm:rounded-[12px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white outline-none text-[9px] sm:text-xs font-bold transition-all shadow-sm">
                                </div>
                                <div class="space-y-0.5 sm:space-y-1 group">
                                    <label class="text-[7px] sm:text-[8px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2 sm:ml-3 transition-colors group-focus-within:text-teal-600 block">Email Publik *</label>
                                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 rounded-[10px] sm:rounded-[12px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white outline-none text-[9px] sm:text-xs font-bold transition-all shadow-sm">
                                </div>
                            </div>

                            <div class="space-y-0.5 sm:space-y-1 group">
                                <label class="text-[7px] sm:text-[8px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2 sm:ml-3 transition-colors group-focus-within:text-teal-600 block">Deskripsi Instansi</label>
                                <textarea name="description" rows="2" class="w-full px-3 sm:px-4 py-1.5 sm:py-2 rounded-[10px] sm:rounded-[12px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white outline-none text-[9px] sm:text-xs font-bold transition-all shadow-sm">{{ old('description', Auth::user()->description) }}</textarea>
                            </div>

                            <div class="pt-1.5 sm:pt-2 flex justify-end">
                                <button type="submit" class="w-full sm:w-auto px-5 sm:px-6 py-2 sm:py-2.5 bg-teal-600 text-white rounded-[10px] sm:rounded-xl font-black text-[8px] sm:text-[9px] uppercase tracking-[0.2em] hover:bg-teal-700 transition-all">
                                    Simpan Profil
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Form Keamanan --}}
            <div class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-2xl rounded-[20px] sm:rounded-[24px] shadow-xl border border-white dark:border-gray-800 p-4 sm:p-6 relative overflow-hidden">
                <div class="mb-3 sm:mb-4">
                    <h3 class="text-sm sm:text-base font-black text-gray-800 dark:text-white uppercase tracking-tight leading-none">Keamanan <span class="text-teal-600">Akun</span></h3>
                </div>

                <form action="#" method="POST" class="space-y-2.5 sm:space-y-3">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 sm:gap-3">
                        <div class="space-y-0.5 sm:space-y-1 group">
                            <label class="text-[7px] sm:text-[8px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2 sm:ml-3 transition-colors group-focus-within:text-teal-600 block">Password Baru</label>
                            <input type="password" name="password" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 rounded-[10px] sm:rounded-[12px] bg-gray-50/50 border border-transparent focus:border-teal-500/30 outline-none text-[9px] sm:text-xs font-bold shadow-sm" placeholder="••••••••">
                        </div>
                        <div class="space-y-0.5 sm:space-y-1 group">
                            <label class="text-[7px] sm:text-[8px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2 sm:ml-3 transition-colors group-focus-within:text-teal-600 block">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 rounded-[10px] sm:rounded-[12px] bg-gray-50/50 border border-transparent focus:border-teal-500/30 outline-none text-[9px] sm:text-xs font-bold shadow-sm" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="pt-1.5 sm:pt-2 flex justify-end">
                        <button type="submit" class="w-full sm:w-auto px-5 sm:px-6 py-2 sm:py-2.5 bg-gray-800 text-white rounded-[10px] sm:rounded-xl font-black text-[8px] sm:text-[9px] uppercase tracking-[0.2em] hover:bg-gray-900 transition-all">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </main>
</div>
@endsection