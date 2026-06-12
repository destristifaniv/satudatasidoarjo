@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-[#F8F9FA] dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-500 overflow-hidden relative" 
     x-data="{ sidebarOpen: false }">
    
    <div class="fixed top-0 right-0 w-[400px] h-[400px] bg-teal-200/20 dark:bg-teal-900/10 blur-[100px] rounded-full -z-10 opacity-50"></div>
    
    <main class="flex-1 h-screen overflow-hidden flex flex-col relative p-4 sm:p-6 lg:p-8">
        
        {{-- HEADER --}}
        <header class="mb-4 sm:mb-6 flex flex-col md:flex-row justify-between md:items-center gap-3 sm:gap-4 max-w-7xl mx-auto w-full shrink-0">
            <div>
                <h1 class="text-xl sm:text-2xl font-black tracking-tight text-gray-900 dark:text-white uppercase leading-none">
                    Manajemen <span class="text-teal-600">Akses & Akun</span>
                </h1>
                <p class="text-[8px] sm:text-[9px] text-gray-400 font-black uppercase tracking-[0.3em] mt-1.5">Kelola akun administrator, pimpinan, dan staf OPD</p>
            </div>
            
            <a href="{{ route('admin.dashboard') }}" class="w-full md:w-auto px-4 sm:px-5 py-2 sm:py-2.5 bg-white/50 dark:bg-gray-800/50 backdrop-blur-xl border border-white dark:border-gray-700 text-gray-500 dark:text-gray-400 rounded-xl font-black text-[8px] sm:text-[9px] uppercase tracking-widest hover:bg-gray-100 transition-all shadow-sm text-center">
                ← Kembali ke Dashboard
            </a>
        </header>

        {{-- NOTIFIKASI --}}
        <div class="max-w-7xl mx-auto w-full shrink-0">
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-2xl mb-6 shadow-sm flex items-center">
                    <svg class="w-5 h-5 text-emerald-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <p class="text-[11px] sm:text-sm text-emerald-700 font-bold">{{ session('success') }}</p>
                </div>
            @endif
            
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 p-4 rounded-2xl mb-6 shadow-sm">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 text-red-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        <p class="text-[11px] sm:text-sm text-red-700 font-bold">Terjadi Kesalahan!</p>
                    </div>
                    <ul class="list-disc pl-7 text-red-600 font-medium space-y-1">
                        @foreach($errors->all() as $error) <li class="text-[10px] sm:text-xs">{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- GRID UTAMA (KUNCI SCROLL) --}}
        <div class="flex-1 min-h-0 max-w-7xl mx-auto w-full grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 pb-4">
            
            {{-- KOLOM KIRI: FORM BUAT AKUN (TIDAK SCROLL) --}}
            <div class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-2xl rounded-[32px] shadow-lg border border-white dark:border-gray-800 p-6 sm:p-8 h-fit relative overflow-hidden z-10 hidden lg:block">
                <div class="absolute top-0 right-0 w-32 h-32 bg-teal-200/20 dark:bg-teal-900/10 rounded-bl-full -z-10 blur-xl"></div>
                
                <h3 class="text-base sm:text-lg font-extrabold mb-6 text-gray-800 dark:text-white flex items-center gap-3">
                    <span class="bg-teal-100 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 p-2 rounded-xl flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </span>
                    <span class="truncate">Buat Akun Baru</span>
                </h3>
                
                <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="space-y-1.5 group">
                        <label class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[1px] ml-3 block transition-colors group-focus-within:text-teal-600">Nama Lengkap / Jabatan <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="w-full px-5 py-3 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-teal-500/10 outline-none text-xs sm:text-sm transition-all shadow-sm">
                    </div>
                    
                    <div class="space-y-1.5 group">
                        <label class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[1px] ml-3 block transition-colors group-focus-within:text-teal-600">Email Login <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required class="w-full px-5 py-3 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-teal-500/10 outline-none text-xs sm:text-sm transition-all shadow-sm">
                    </div>
                    
                    <div class="space-y-1.5 group" x-data="{ showPassword: false }">
                        <label class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[1px] ml-3 block transition-colors group-focus-within:text-teal-600">Password Default <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" name="password" required class="w-full pl-5 pr-12 py-3 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-teal-500/10 outline-none text-xs sm:text-sm transition-all shadow-sm" placeholder="••••••••">
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-4 flex items-center text-gray-400 hover:text-teal-600 focus:outline-none transition-colors">
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-1.5 group">
                        <label class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[1px] ml-3 block transition-colors group-focus-within:text-teal-600">Hak Akses (Role) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="role" required class="w-full px-5 py-3 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-teal-500/10 outline-none text-xs sm:text-sm font-bold transition-all shadow-sm appearance-none cursor-pointer">
                                <option value="opd">OPD (Staf / Operator)</option>
                                <option value="pimpinan">Pimpinan (Camat / Kadis)</option>
                                <option value="admin">Super Admin (Kominfo)</option>
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-1.5 group pt-1">
                        <label class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[1px] ml-3 block transition-colors group-focus-within:text-teal-600">Nama Instansi (OPD)</label>
                        <input type="text" name="opd_name" class="w-full px-5 py-3 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-teal-500/10 outline-none text-xs sm:text-sm transition-all shadow-sm">
                        <p class="text-[9px] text-orange-500 font-bold mt-2 ml-3 leading-tight flex items-start gap-1">
                            <svg class="w-3 h-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg> 
                            <span>Wajib diisi identik untuk Staf dan Pimpinannya.</span>
                        </p>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-100 dark:border-gray-800/60 mt-4">
                        <button type="submit" class="w-full py-4 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-lg shadow-teal-500/30 active:scale-[0.98] transition-all flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Simpan Akun
                        </button>
                    </div>
                </form>
            </div>

            {{-- KOLOM KANAN: TABEL PENGGUNA (BISA DI SCROLL) --}}
            <div class="lg:col-span-2 bg-white/70 dark:bg-gray-900/70 backdrop-blur-2xl rounded-[32px] shadow-lg border border-white dark:border-gray-800 overflow-hidden flex flex-col z-10 h-full">
                
                {{-- Table Header Fixed --}}
                <div class="px-6 sm:px-8 py-5 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50 flex justify-between items-center shrink-0">
                    <h3 class="text-base sm:text-lg font-black text-gray-800 dark:text-white truncate">Daftar Pengguna Sistem</h3>
                    <span class="bg-white dark:bg-gray-800 text-[9px] sm:text-[10px] font-black tracking-widest uppercase px-3 sm:px-4 py-1.5 rounded-full border border-gray-100 dark:border-gray-700 shadow-sm flex-shrink-0">{{ $users->count() }} Akun</span>
                </div>
                
                {{-- Table Body Scrollable --}}
                <div class="flex-1 overflow-y-auto overflow-x-auto custom-scrollbar p-2 sm:p-4">
                    <table class="w-full text-left border-collapse min-w-[700px]">
                        <thead>
                            <tr class="text-gray-400 text-[9px] uppercase tracking-widest font-black border-b border-gray-100 dark:border-gray-800">
                                <th class="px-4 py-4 w-1/3">Profil Pengguna</th>
                                <th class="px-4 py-4">Role</th>
                                <th class="px-4 py-4">Instansi Terkait</th>
                                <th class="px-4 py-4 text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800/60">
                            @foreach($users as $u)
                            <tr class="hover:bg-teal-50/30 dark:hover:bg-teal-900/10 transition-colors group">
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-white text-sm shadow-inner {{ $u->role == 'admin' ? 'bg-red-500' : ($u->role == 'pimpinan' ? 'bg-blue-500' : 'bg-teal-500') }}">
                                            {{ substr($u->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-xs sm:text-sm font-bold text-gray-800 dark:text-gray-100">{{ $u->name }}</p>
                                            <p class="text-[9px] sm:text-[10px] text-gray-500 font-medium mt-0.5">{{ $u->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    @if($u->role == 'admin')
                                        <span class="px-3 py-1.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800/50 rounded-lg text-[9px] font-black uppercase tracking-widest flex w-fit items-center"><span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-2"></span>ADMIN</span>
                                    @elseif($u->role == 'pimpinan')
                                        <span class="px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800/50 rounded-lg text-[9px] font-black uppercase tracking-widest flex w-fit items-center"><span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2"></span>PIMPINAN</span>
                                    @else
                                        <span class="px-3 py-1.5 bg-teal-50 dark:bg-teal-900/20 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-800/50 rounded-lg text-[9px] font-black uppercase tracking-widest flex w-fit items-center"><span class="w-1.5 h-1.5 rounded-full bg-teal-500 mr-2"></span>OPD / STAF</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    @if(empty($u->opd_name) || $u->opd_name == 'null' || $u->opd_name == '')
                                        <span class="text-[9px] font-bold text-gray-400 italic">Pusat / Kosong</span>
                                    @else
                                        <span class="text-[11px] font-bold text-gray-700 dark:text-gray-300">{{ $u->opd_name }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="flex justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button type="button" onclick="openEditModal({{ json_encode($u) }})" class="p-2 bg-gray-100 dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-blue-900/30 text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 rounded-xl transition-colors shadow-sm" title="Edit Akun">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>

                                        @if($u->id !== auth()->user()->id)
                                        <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Yakin menghapus permanen akun {{ $u->name }}?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 bg-gray-100 dark:bg-gray-800 hover:bg-red-50 dark:hover:bg-red-900/30 text-gray-500 hover:text-red-600 dark:hover:text-red-400 rounded-xl transition-colors shadow-sm" title="Hapus Akun">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                        @else
                                        <span class="text-[9px] font-black text-emerald-500 bg-emerald-50 dark:bg-emerald-900/20 px-2.5 py-1 rounded-lg border border-emerald-200 dark:border-emerald-800 uppercase tracking-widest flex items-center h-fit">Online</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            {{-- FORM BUAT AKUN UNTUK MOBILE (DITAMPILKAN DI BAWAH JIKA DI HP) --}}
            <div class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-2xl rounded-[32px] shadow-lg border border-white dark:border-gray-800 p-6 sm:p-8 h-fit relative overflow-hidden z-10 lg:hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-teal-200/20 dark:bg-teal-900/10 rounded-bl-full -z-10 blur-xl"></div>
                
                <h3 class="text-base sm:text-lg font-extrabold mb-6 text-gray-800 dark:text-white flex items-center gap-3">
                    <span class="bg-teal-100 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 p-2 rounded-xl flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </span>
                    <span class="truncate">Buat Akun Baru</span>
                </h3>
                
                <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                    @csrf
                    {{-- Input Form Sama Seperti di Atas --}}
                    <div class="space-y-1.5 group">
                        <label class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[1px] ml-3 block transition-colors group-focus-within:text-teal-600">Nama Lengkap / Jabatan <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="w-full px-5 py-3 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-teal-500/10 outline-none text-xs sm:text-sm transition-all shadow-sm">
                    </div>
                    
                    <div class="space-y-1.5 group">
                        <label class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[1px] ml-3 block transition-colors group-focus-within:text-teal-600">Email Login <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required class="w-full px-5 py-3 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-teal-500/10 outline-none text-xs sm:text-sm transition-all shadow-sm">
                    </div>
                    
                    <div class="space-y-1.5 group" x-data="{ showPassword: false }">
                        <label class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[1px] ml-3 block transition-colors group-focus-within:text-teal-600">Password Default <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" name="password" required class="w-full pl-5 pr-12 py-3 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-teal-500/10 outline-none text-xs sm:text-sm transition-all shadow-sm" placeholder="••••••••">
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-4 flex items-center text-gray-400 hover:text-teal-600 focus:outline-none transition-colors">
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-1.5 group">
                        <label class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[1px] ml-3 block transition-colors group-focus-within:text-teal-600">Hak Akses (Role) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="role" required class="w-full px-5 py-3 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-teal-500/10 outline-none text-xs sm:text-sm font-bold transition-all shadow-sm appearance-none cursor-pointer">
                                <option value="opd">OPD (Staf / Operator)</option>
                                <option value="pimpinan">Pimpinan (Camat / Kadis)</option>
                                <option value="admin">Super Admin (Kominfo)</option>
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-1.5 group pt-1">
                        <label class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[1px] ml-3 block transition-colors group-focus-within:text-teal-600">Nama Instansi (OPD)</label>
                        <input type="text" name="opd_name" class="w-full px-5 py-3 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-teal-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-teal-500/10 outline-none text-xs sm:text-sm transition-all shadow-sm">
                        <p class="text-[9px] text-orange-500 font-bold mt-2 ml-3 leading-tight flex items-start gap-1">
                            <svg class="w-3 h-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg> 
                            <span>Wajib diisi identik untuk Staf dan Pimpinannya.</span>
                        </p>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-100 dark:border-gray-800/60 mt-4">
                        <button type="submit" class="w-full py-4 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-lg shadow-teal-500/30 active:scale-[0.98] transition-all flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Simpan Akun
                        </button>
                    </div>
                </form>
            </div>
            
        </div>
    </main>
</div>

{{-- MODAL EDIT USER --}}
<div id="editUserModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeEditModal()"></div>
    
    <div class="bg-white dark:bg-gray-900 rounded-[32px] shadow-2xl w-full max-w-sm relative z-10 transform transition-all border border-white/20 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50 flex justify-between items-center">
            <h3 class="text-lg font-black text-gray-800 dark:text-white flex items-center gap-3 uppercase tracking-tight">
                <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 p-2 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                </span>
                Edit Akun
            </h3>
            <button onclick="closeEditModal()" class="p-2 text-gray-400 hover:text-red-500 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form id="formEditUser" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            
            <div class="space-y-1.5">
                <label class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[1px] ml-3 block">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="edit_name" required class="w-full px-4 py-3 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-blue-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-blue-500/10 outline-none text-xs sm:text-sm font-bold shadow-sm transition-all">
            </div>
            
            <div class="space-y-1.5">
                <label class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[1px] ml-3 block">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="edit_email" required class="w-full px-4 py-3 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-blue-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-blue-500/10 outline-none text-xs sm:text-sm font-bold shadow-sm transition-all">
            </div>

            <div class="space-y-1.5" x-data="{ showPasswordEdit: false }">
                <label class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[1px] ml-3 block">Ganti Password</label>
                <div class="relative">
                    <input :type="showPasswordEdit ? 'text' : 'password'" name="password" class="w-full pl-4 pr-12 py-3 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-blue-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-blue-500/10 outline-none text-xs sm:text-sm font-bold shadow-sm transition-all" placeholder="Kosongkan jika tetap">
                    <button type="button" @click="showPasswordEdit = !showPasswordEdit" class="absolute inset-y-0 right-4 flex items-center text-gray-400 hover:text-blue-600 focus:outline-none transition-colors">
                        <svg x-show="!showPasswordEdit" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="showPasswordEdit" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[1px] ml-3 block">Role <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="role" id="edit_role" required class="w-full px-4 py-3 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-blue-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-blue-500/10 outline-none text-xs sm:text-sm font-bold shadow-sm transition-all appearance-none cursor-pointer">
                            <option value="opd">OPD</option>
                            <option value="pimpinan">Pimpinan</option>
                            <option value="admin">Admin</option>
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[1px] ml-3 block">Instansi</label>
                    <input type="text" name="opd_name" id="edit_opd_name" class="w-full px-4 py-3 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border border-transparent focus:border-blue-500/30 focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-blue-500/10 outline-none text-xs sm:text-sm font-bold shadow-sm transition-all">
                </div>
            </div>
            
            <div class="pt-6 border-t border-gray-100 dark:border-gray-800 mt-4 flex justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="px-6 py-3.5 text-[10px] sm:text-xs font-black text-gray-600 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 rounded-2xl transition-colors uppercase tracking-widest">Batal</button>
                <button type="submit" class="px-6 py-3.5 text-[10px] sm:text-xs font-black text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-500/30 rounded-2xl transition-all active:scale-[0.98] uppercase tracking-widest">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Styling scrollbar dropdown dan tabel agar konsisten & rapi */
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #475569; }
    [x-cloak] { display: none !important; }
</style>

<script>
    const modal = document.getElementById('editUserModal');
    const form = document.getElementById('formEditUser');

    function openEditModal(user) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        form.action = `/admin/users/${user.id}/update`;
        
        document.getElementById('edit_name').value = user.name;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_role').value = user.role;
        document.getElementById('edit_opd_name').value = user.opd_name || '';
    }

    function closeEditModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection