@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-[#F8F9FA] dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-500 overflow-x-hidden relative" 
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
    
    {{-- SIDEBAR (STAY FIXED) --}}
    <aside :class="sidebarOpen ? 'translate-x-0 shadow-xl' : '-translate-x-full shadow-none'" 
           class="fixed inset-y-0 left-0 z-50 w-72 transform bg-gradient-to-b from-teal-700 via-teal-600 to-teal-500 dark:from-teal-900 dark:via-teal-800 dark:to-teal-700 backdrop-blur-2xl border-r border-teal-400/30 transition-transform duration-500 ease-in-out flex flex-col">
        
        <div class="p-6 h-24 border-b border-teal-400/30 dark:border-teal-600/30 flex items-center justify-between overflow-hidden shrink-0">
            <div class="flex items-center space-x-3 min-w-[180px]" x-show="sidebarOpen" x-transition>
                <img src="https://adminweb.sidoarjokab.go.id/upload/link/1703218932.png" alt="Logo" class="w-10 h-10 object-contain rounded-xl shadow-lg bg-white/90 p-1">
                <h2 class="text-xs font-black text-white leading-tight uppercase tracking-tighter">Satu Data<br>Kabupaten Sidoarjo</h2>
            </div>
            <button @click="sidebarOpen = false" class="p-2 rounded-xl hover:bg-teal-600/50 transition-colors shrink-0 text-white">
                <svg class="w-6 h-6 transition-transform duration-500 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 p-4 space-y-2 overflow-y-auto text-white">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group">
                <span class="whitespace-nowrap">Dashboard</span>
            </a>
            <a href="{{ route('admin.upload.dataset') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group">
                <span class="whitespace-nowrap">Unggah Dataset</span>
            </a>
            <a href="{{ route('admin.metadata') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group">
                <span class="whitespace-nowrap">Kelola Metadata</span>
            </a>
            <a href="{{ route('admin.manage-dataset') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group">
                <span class="whitespace-nowrap">Kelola Dataset</span>
            </a>
            <a href="{{ route('admin.organizations') }}" class="flex items-center px-6 py-4 bg-white/20 rounded-2xl shadow-lg font-bold text-sm transition-all">
                <span class="whitespace-nowrap">Organisasi</span>
            </a>
            @if(auth()->user()->role == 'admin')
            <a href="{{ route('admin.users.index') }}" class="flex items-center px-6 py-4 hover:bg-white/10 rounded-2xl font-medium text-sm transition-all group">
                <span class="whitespace-nowrap">Manajemen Akun</span>
            </a>
            @endif
        </nav>

        <div class="p-4 border-t border-teal-400/30">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center px-4 py-4 rounded-2xl bg-red-600/80 hover:bg-red-700 text-white font-bold text-sm transition-all group">
                    <span class="whitespace-nowrap uppercase tracking-widest text-[10px]">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN AREA --}}
    <main :class="sidebarOpen ? 'lg:ml-72' : 'ml-0'" class="flex-1 min-h-screen relative transition-all duration-500 overflow-y-auto p-4 sm:p-6 lg:p-8 scroll-smooth text-left">
        
        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 bg-black/30 lg:hidden" @click="sidebarOpen = false"></div>
        <div class="fixed top-0 right-0 w-[300px] sm:w-[500px] h-[300px] sm:h-[500px] bg-teal-200/20 dark:bg-teal-900/10 blur-[100px] sm:blur-[120px] rounded-full -z-10 opacity-60"></div>
        
        <header class="mb-6 sm:mb-8 flex flex-col md:flex-row justify-between md:items-center gap-4 max-w-5xl mx-auto w-full">
            <div class="flex items-start sm:items-center gap-3 sm:gap-4">
                <button @click="sidebarOpen = true" :class="sidebarOpen ? 'lg:hidden' : 'block'" class="p-2.5 mt-1 sm:mt-0 rounded-xl bg-white dark:bg-gray-900 shadow-sm border border-gray-100 dark:border-gray-800 text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <div>
                    <h1 class="text-xl sm:text-2xl font-black tracking-tight text-gray-900 dark:text-white uppercase leading-none">
                        Pengaturan <span class="text-teal-600">Keamanan & Profil</span>
                    </h1>
                    <p class="text-[8px] sm:text-[9px] text-gray-400 font-black uppercase tracking-[0.2em] mt-1.5 sm:mt-2">Manajemen Identitas & Akses Instansi</p>
                </div>
            </div>
            
            <a href="{{ route('admin.organizations') }}" class="w-full md:w-auto px-5 py-3 sm:py-3.5 bg-white/50 dark:bg-gray-800/50 backdrop-blur-xl border border-white dark:border-gray-700 text-gray-500 dark:text-gray-400 rounded-2xl font-black text-[9px] uppercase tracking-widest hover:bg-gray-100 dark:hover:bg-gray-800 transition-all shadow-sm text-center">
                ← Kembali ke Profil
            </a>
        </header>

        <div class="max-w-5xl mx-auto w-full space-y-4 sm:space-y-6 px-2 sm:px-0">
            
            {{-- NOTIFIKASI SUCCESS & ERROR --}}
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-2xl shadow-sm flex items-center">
                    <svg class="w-5 h-5 text-emerald-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <p class="text-[11px] sm:text-sm text-emerald-700 font-bold">{{ session('success') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 p-4 rounded-2xl shadow-sm">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 text-red-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        <p class="text-[11px] sm:text-sm text-red-700 font-bold">Terjadi Kesalahan!</p>
                    </div>
                    <ul class="list-disc pl-7 text-red-600 font-medium space-y-1">
                        @foreach($errors->all() as $error) <li class="text-[10px] sm:text-xs">{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form Profil --}}
            <div class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-2xl rounded-[24px] sm:rounded-[32px] shadow-lg border border-white dark:border-gray-800 p-5 sm:p-8 relative overflow-hidden">
                <form action="{{ route('admin.organizations.update') }}" method="POST" enctype="multipart/form-data" class="relative z-10">
                    @csrf
                    <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 items-center lg:items-start">
                        
                        {{-- Avatar --}}
                        <div class="w-full lg:w-auto flex flex-col items-center shrink-0">
                            <div class="group relative">
                                <div class="w-24 h-24 sm:w-32 sm:h-32 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-[24px] flex items-center justify-center text-white text-3xl sm:text-4xl font-black shadow-lg shadow-teal-500/20 overflow-hidden transition-transform duration-500 group-hover:scale-[1.02] border-4 border-white dark:border-gray-800">
                                    <template x-if="imageUrl">
                                        <img :src="imageUrl" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!imageUrl">
                                        <span>{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </template>
                                    
                                    {{-- Overlay Hover untuk Ganti Foto --}}
                                    <label class="absolute inset-0 bg-black/50 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center cursor-pointer">
                                        <svg class="w-6 h-6 text-white mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        <span class="text-[8px] sm:text-[9px] font-black uppercase tracking-[0.2em] text-white">Ganti Profil</span>
                                        <input type="file" name="avatar" class="hidden" accept="image/*" @change="fileChosen">
                                    </label>
                                </div>
                            </div>
                            <p class="text-[8px] sm:text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-3">Logo Instansi</p>
                        </div>

                        {{-- Input Profil --}}
                        <div class="flex-1 w-full space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1.5 group">
                                    <label class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[1px] ml-3 block transition-colors group-focus-within:text-teal-600">Nama Instansi <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required class="w-full px-4 py-3 sm:py-3.5 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-teal-500/10 outline-none text-xs sm:text-sm font-bold transition-all shadow-sm">
                                </div>
                                <div class="space-y-1.5 group">
                                    <label class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[1px] ml-3 block transition-colors group-focus-within:text-teal-600">Email Publik <span class="text-red-500">*</span></label>
                                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required class="w-full px-4 py-3 sm:py-3.5 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-teal-500/10 outline-none text-xs sm:text-sm font-bold transition-all shadow-sm">
                                </div>
                            </div>

                            <div class="space-y-1.5 group">
                                <label class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[1px] ml-3 block transition-colors group-focus-within:text-teal-600">Deskripsi Instansi</label>
                                <textarea name="description" rows="3" class="w-full px-4 py-3 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-teal-500/10 outline-none text-xs sm:text-sm font-medium transition-all shadow-sm leading-relaxed">{{ old('description', Auth::user()->description) }}</textarea>
                            </div>

                            <div class="pt-2 flex justify-end">
                                <button type="submit" class="w-full sm:w-auto px-8 py-3.5 sm:py-4 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl font-black text-[10px] md:text-[11px] uppercase tracking-[0.2em] shadow-lg shadow-teal-500/30 transition-all active:scale-95 text-center">
                                    Simpan Profil
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Form Keamanan --}}
            <div class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-2xl rounded-[24px] sm:rounded-[32px] shadow-lg border border-white dark:border-gray-800 p-5 sm:p-8 relative overflow-hidden">
                <div class="mb-5 sm:mb-6">
                    <h3 class="text-base sm:text-lg font-black text-gray-800 dark:text-white uppercase tracking-tight flex items-center gap-2">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Keamanan <span class="text-teal-600">Akun</span>
                    </h3>
                </div>

                <form action="#" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5 group" x-data="{ showPass: false }">
                            <label class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[1px] ml-3 block transition-colors group-focus-within:text-teal-600">Password Baru</label>
                            <div class="relative">
                                <input :type="showPass ? 'text' : 'password'" name="password" class="w-full pl-4 pr-12 py-3 sm:py-3.5 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-teal-500/10 outline-none text-xs sm:text-sm font-bold transition-all shadow-sm" placeholder="••••••••">
                                <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-4 flex items-center text-gray-400 hover:text-teal-600 transition-colors">
                                    <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <svg x-show="showPass" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                            </div>
                        </div>
                        <div class="space-y-1.5 group" x-data="{ showConfirm: false }">
                            <label class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[1px] ml-3 block transition-colors group-focus-within:text-teal-600">Konfirmasi Password</label>
                            <div class="relative">
                                <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" class="w-full pl-4 pr-12 py-3 sm:py-3.5 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-teal-500/10 outline-none text-xs sm:text-sm font-bold transition-all shadow-sm" placeholder="••••••••">
                                <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-4 flex items-center text-gray-400 hover:text-teal-600 transition-colors">
                                    <svg x-show="!showConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <svg x-show="showConfirm" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="w-full sm:w-auto px-8 py-3.5 sm:py-4 bg-gray-800 hover:bg-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600 text-white rounded-2xl font-black text-[10px] md:text-[11px] uppercase tracking-[0.2em] shadow-lg shadow-gray-500/30 transition-all active:scale-95 text-center">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </main>
</div>
@endsection