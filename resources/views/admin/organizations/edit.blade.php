@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-[#F8F9FA] dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-500 overflow-hidden" 
     x-data="{ 
        sidebarOpen: window.innerWidth >= 1024,
        imageUrl: '{{ Auth::user()->avatar ? asset('storage/avatars/' . Auth::user()->avatar) : '' }}',
        fileChosen(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = (e) => {
                this.imageUrl = e.target.result;
            };
        }
     }">
    
    <aside :class="sidebarOpen ? 'w-72' : 'w-0 md:w-20'" class="relative h-full bg-gradient-to-b from-teal-700 via-teal-600 to-teal-500 dark:from-teal-900 dark:via-teal-800 dark:to-teal-700 backdrop-blur-2xl border-r border-teal-400/30 transition-all duration-500 ease-in-out flex flex-col sticky top-0 z-50 shadow-xl overflow-hidden shrink-0">
        <div class="p-6 h-24 border-b border-teal-400/30 flex items-center justify-between overflow-hidden shrink-0">
            <div class="flex items-center space-x-3 min-w-[180px]" x-show="sidebarOpen" x-transition>
                <img src="https://adminweb.sidoarjokab.go.id/upload/link/1703218932.png" alt="Logo" class="w-10 h-10 object-contain rounded-xl shadow-lg bg-white/90 p-1">
                <h2 class="text-xs font-black text-white leading-tight uppercase tracking-tighter text-left">Satu Data<br>Kabupaten Sidoarjo</h2>
            </div>
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl hover:bg-teal-600/50 transition-colors shrink-0">
                <svg :class="!sidebarOpen ? 'rotate-180' : ''" class="w-6 h-6 text-white transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

    <main class="flex-1 h-full overflow-y-auto relative p-6 lg:p-10 scroll-smooth">
        <div class="fixed top-0 right-0 w-[500px] h-[500px] bg-teal-200/20 dark:bg-teal-900/10 blur-[120px] rounded-full -z-10 opacity-60"></div>
        
        <header class="mb-10 flex flex-col md:flex-row justify-between md:items-center gap-4 text-left">
            <div>
                <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white uppercase leading-none">
                    Pengaturan <span class="text-teal-600">Keamanan & Profil</span>
                </h1>
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.3em] mt-3">Manajemen Identitas & Akses Instansi</p>
            </div>
            <a href="{{ route('admin.organizations') }}" class="px-6 py-3 bg-white/50 dark:bg-gray-800/50 backdrop-blur-xl border border-white dark:border-gray-700 text-gray-500 dark:text-gray-400 rounded-2xl font-black text-[9px] uppercase tracking-widest hover:bg-gray-100 transition-all shadow-sm">
                ← Kembali
            </a>
        </header>

        <div class="max-w-5xl mx-auto space-y-8">
            <div class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-2xl rounded-[40px] shadow-2xl border border-white dark:border-gray-800 p-8 lg:p-12 relative overflow-hidden text-left">
                <form action="{{ route('admin.organizations.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8 relative z-10">
                    @csrf
                    <div class="flex flex-col lg:flex-row gap-12 items-start">
                        <div class="w-full lg:w-1/4 flex flex-col items-center">
                            <div class="group relative">
                                <div class="w-36 h-36 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-[32px] flex items-center justify-center text-white text-5xl font-black shadow-xl shadow-teal-500/20 overflow-hidden transition-transform duration-500 group-hover:scale-[1.02]">
                                    <template x-if="imageUrl">
                                        <img :src="imageUrl" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!imageUrl">
                                        <span>{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </template>
                                    <label class="absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center cursor-pointer">
                                        <span class="text-[8px] font-black uppercase tracking-widest text-white">Ganti Profil</span>
                                        <input type="file" name="avatar" class="hidden" accept="image/*" @change="fileChosen">
                                    </label>
                                </div>
                            </div>
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-4">Logo Instansi</p>
                        </div>

                        <div class="flex-1 w-full space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2 group">
                                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] ml-4 transition-colors group-focus-within:text-teal-600 text-left block">Nama Instansi *</label>
                                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="w-full px-6 py-4 rounded-[20px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-teal-500/10 outline-none text-xs font-bold transition-all shadow-sm">
                                </div>
                                <div class="space-y-2 group">
                                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] ml-4 transition-colors group-focus-within:text-teal-600 text-left block">Email Publik *</label>
                                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="w-full px-6 py-4 rounded-[20px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-teal-500/10 outline-none text-xs font-bold transition-all shadow-sm">
                                </div>
                            </div>

                            <div class="space-y-2 group">
                                <label class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] ml-4 transition-colors group-focus-within:text-teal-600 text-left block">Deskripsi Instansi</label>
                                <textarea name="description" rows="3" class="w-full px-6 py-4 rounded-[20px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-teal-500/10 outline-none text-xs font-bold transition-all shadow-sm">{{ old('description', Auth::user()->description) }}</textarea>
                            </div>

                            {{-- <div class="space-y-2 group">
                                <label class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] ml-4 transition-colors group-focus-within:text-teal-600 text-left block">Tags (Pisahkan dengan koma)</label>
                                <input type="text" name="tags" value="{{ old('tags', Auth::user()->tags) }}" placeholder="Sidoarjo, OPD, Layanan Publik" class="w-full px-6 py-4 rounded-[20px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-teal-500/10 outline-none text-xs font-bold transition-all shadow-sm">
                            </div> --}}

                            <div class="pt-4 flex justify-end">
                                <button type="submit" class="px-8 py-4 bg-teal-600 text-white rounded-2xl font-black text-[9px] uppercase tracking-[0.2em] shadow-lg shadow-teal-500/30 hover:bg-teal-700 hover:scale-[1.02] active:scale-95 transition-all">
                                    Simpan Perubahan Profil
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-2xl rounded-[40px] shadow-2xl border border-white dark:border-gray-800 p-8 lg:p-12 relative overflow-hidden max-w-3xl text-left">
                <div class="mb-8">
                    <h3 class="text-xl font-black text-gray-800 dark:text-white uppercase tracking-tight leading-none">Keamanan <span class="text-teal-600">Akun</span></h3>
                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-2">Ganti password secara berkala untuk keamanan data</p>
                </div>

                <form action="#" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2 group">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] ml-4 transition-colors group-focus-within:text-teal-600 text-left block">Password Baru</label>
                            <input type="password" name="password" class="w-full px-6 py-4 rounded-[20px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-teal-500/10 outline-none text-xs font-bold transition-all shadow-sm" placeholder="••••••••">
                        </div>
                        <div class="space-y-2 group">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] ml-4 transition-colors group-focus-within:text-teal-600 text-left block">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="w-full px-6 py-4 rounded-[20px] bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-teal-500/10 outline-none text-xs font-bold transition-all shadow-sm" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="px-8 py-4 bg-gray-800 dark:bg-gray-700 text-white rounded-2xl font-black text-[9px] uppercase tracking-[0.2em] shadow-xl hover:bg-gray-900 transition-all active:scale-95">
                            Update Password Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection