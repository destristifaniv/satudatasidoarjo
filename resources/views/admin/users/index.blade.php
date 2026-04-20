@extends('layouts.app')

@section('content')
{{-- Container utama dikunci tingginya seukuran layar dan overflow-hidden --}}
<div class="container mx-auto px-4 max-w-7xl flex flex-col h-[calc(100vh-1rem)] py-4 overflow-hidden">
    
    {{-- HEADER SECTION --}}
    <div class="shrink-0 flex flex-col md:flex-row md:items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-800 dark:text-white flex items-center">
                <svg class="w-8 h-8 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Manajemen Akses & Akun
            </h2>
            <p class="text-sm text-gray-500 font-medium mt-1">Kelola akun administrator, pimpinan, dan staf OPD.</p>
        </div>
        
        {{-- TOMBOL LOGOUT --}}
        <div class="mt-4 md:mt-0">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center px-4 py-2 bg-red-50 hover:bg-red-500 text-red-600 hover:text-white rounded-xl font-bold text-sm transition-all border border-red-200 hover:border-red-500 shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout / Keluar
                </button>
            </form>
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="shrink-0 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg mb-6 shadow-sm flex items-center">
            <svg class="w-5 h-5 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <p class="text-sm text-emerald-700 font-bold">{{ session('success') }}</p>
        </div>
    @endif
    @if($errors->any())
        <div class="shrink-0 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg mb-6 shadow-sm">
            <div class="flex items-center mb-1">
                <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <p class="text-sm text-red-700 font-bold">Terjadi Kesalahan!</p>
            </div>
            <ul class="list-disc pl-7 text-xs text-red-600 font-medium">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    {{-- GRID UTAMA --}}
    <div class="flex-1 min-h-0 grid grid-cols-1 xl:grid-cols-3 gap-8 pb-4">
        
        {{-- BAGIAN KIRI: Form Tambah User (Dibuat lebih ringkas, tanpa scrollbar) --}}
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl shadow-gray-200/50 dark:shadow-none p-6 border border-gray-100 dark:border-gray-700 h-fit relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-green-50 dark:bg-gray-700/30 rounded-bl-full -z-10"></div>
            
            <h3 class="text-lg font-extrabold mb-5 text-gray-800 dark:text-white flex items-center">
                <span class="bg-green-100 text-green-600 p-2 rounded-xl mr-3"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg></span>
                Buat Akun Baru
            </h3>
            
            {{-- Spasi antar input dikurangi (space-y-3) agar lebih fit di layar --}}
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1 uppercase tracking-wide">Nama Lengkap / Jabatan <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-green-500 focus:ring-green-500 text-sm transition-all shadow-sm py-2" placeholder="">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1 uppercase tracking-wide">Email Login <span class="text-red-500">*</span></label>
                    <input type="email" name="email" required class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-green-500 focus:ring-green-500 text-sm transition-all shadow-sm py-2" placeholder="">
                </div>
                
                {{-- FIELD PASSWORD --}}
                <div x-data="{ showPassword: false }">
                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1 uppercase tracking-wide">Password Default <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" name="password" required class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-green-500 focus:ring-green-500 text-sm transition-all shadow-sm py-2 pr-10" placeholder="••••••••">
                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none">
                            <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                            <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1 uppercase tracking-wide">Hak Akses (Role) <span class="text-red-500">*</span></label>
                    <select name="role" required class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-green-500 focus:ring-green-500 text-sm font-medium transition-all shadow-sm py-2 cursor-pointer">
                        <option value="opd">OPD (Staf / Operator)</option>
                        <option value="pimpinan">Pimpinan (Camat / Kadis)</option>
                        <option value="admin">Super Admin (Kominfo)</option>
                    </select>
                </div>
                <div class="pt-1">
                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1 uppercase tracking-wide">Nama Instansi (OPD)</label>
                    <input type="text" name="opd_name" class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-green-500 focus:ring-green-500 text-sm transition-all shadow-sm py-2 bg-gray-50 dark:bg-gray-800" placeholder="">
                    <p class="text-[9px] text-orange-500 font-bold mt-1.5 leading-tight flex items-start"><svg class="w-3 h-3 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg> Wajib diisi identik untuk Staf dan Pimpinannya.</p>
                </div>
                <button type="submit" class="w-full mt-2 bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-700 hover:to-emerald-600 text-white font-extrabold py-3 px-4 rounded-xl transition-all shadow-lg shadow-green-500/30 hover:shadow-green-500/50 hover:-translate-y-0.5 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Simpan Akun
                </button>
            </form>
        </div>

        {{-- Tabel Daftar User Sistem --}}
        <div class="xl:col-span-2 bg-white dark:bg-gray-800 rounded-3xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 flex flex-col h-full overflow-hidden">
            
            {{-- Judul Tabel --}}
            <div class="shrink-0 p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                <h3 class="text-lg font-extrabold text-gray-800 dark:text-white">Daftar Pengguna Sistem</h3>
                <span class="bg-white dark:bg-gray-700 text-xs font-bold px-3 py-1 rounded-full border border-gray-200 dark:border-gray-600 shadow-sm">{{ $users->count() }} Total Akun</span>
            </div>
            
            {{-- AREA SCROLL: Ini satu-satunya area yang bisa digulir ke bawah --}}
            <div class="flex-1 overflow-y-auto overflow-x-auto p-4 custom-scrollbar">
                <table class="w-full text-left border-separate border-spacing-y-2">
                    <thead class="sticky top-0 z-10 bg-white dark:bg-gray-800 shadow-sm">
                        <tr class="text-gray-400 dark:text-gray-500 text-[10px] uppercase tracking-wider font-black">
                            <th class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">Profil Pengguna</th>
                            <th class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">Role</th>
                            <th class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">Instansi Terkait</th>
                            <th class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                        <tr class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl transition-all hover:shadow-md hover:border-green-200 dark:hover:border-green-900 group">
                            <td class="px-4 py-3 rounded-l-xl border-y border-l border-gray-100 dark:border-gray-700 group-hover:border-green-200">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white mr-3 shadow-inner {{ $u->role == 'admin' ? 'bg-gradient-to-br from-red-400 to-rose-600' : ($u->role == 'pimpinan' ? 'bg-gradient-to-br from-blue-400 to-indigo-600' : 'bg-gradient-to-br from-emerald-400 to-green-600') }}">
                                        {{ substr($u->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-extrabold text-gray-800 dark:text-gray-100">{{ $u->name }}</p>
                                        <p class="text-[11px] text-gray-500 font-medium">{{ $u->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 border-y border-gray-100 dark:border-gray-700 group-hover:border-green-200">
                                @if($u->role == 'admin')
                                    <span class="px-2.5 py-1 bg-red-50 text-red-600 border border-red-200 rounded-lg text-[10px] font-black uppercase tracking-widest flex w-fit items-center"><span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>ADMIN</span>
                                @elseif($u->role == 'pimpinan')
                                    <span class="px-2.5 py-1 bg-blue-50 text-blue-600 border border-blue-200 rounded-lg text-[10px] font-black uppercase tracking-widest flex w-fit items-center"><span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5"></span>PIMPINAN</span>
                                @else
                                    <span class="px-2.5 py-1 bg-green-50 text-green-600 border border-green-200 rounded-lg text-[10px] font-black uppercase tracking-widest flex w-fit items-center"><span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>OPD / STAF</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 border-y border-gray-100 dark:border-gray-700 group-hover:border-green-200">
                                @if(empty($u->opd_name) || $u->opd_name == 'null' || $u->opd_name == '')
                                    <span class="text-xs font-bold text-gray-400 bg-gray-50 dark:bg-gray-700 px-2.5 py-1 rounded-md border border-gray-200 dark:border-gray-600 italic">Pusat / Kosong</span>
                                @else
                                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 px-2.5 py-1 rounded-md border border-gray-300 dark:border-gray-600">{{ $u->opd_name }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 rounded-r-xl border-y border-r border-gray-100 dark:border-gray-700 group-hover:border-green-200 text-center">
                                <div class="flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    {{-- Tombol Edit Modal --}}
                                    <button type="button" onclick="openEditModal({{ json_encode($u) }})" class="p-1.5 bg-blue-50 hover:bg-blue-500 text-blue-600 hover:text-white rounded-lg transition-colors border border-blue-200 hover:border-blue-500 tooltip" title="Edit Akun">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>

                                    @if($u->id !== auth()->user()->id)
                                    <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Yakin menghapus permanen akun {{ $u->name }}?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-red-50 hover:bg-red-500 text-red-600 hover:text-white rounded-lg transition-colors border border-red-200 hover:border-red-500 tooltip" title="Hapus Akun">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                    @else
                                    <span class="text-[10px] font-bold text-green-500 bg-green-50 px-2 py-1 rounded border border-green-200">Sedang Login</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT USER --}}
<div id="editUserModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-0">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeEditModal()"></div>
    
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl w-full max-w-md relative z-10 transform transition-all border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex justify-between items-center">
            <h3 class="text-lg font-extrabold text-gray-800 dark:text-white flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Edit Akun Pengguna
            </h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-red-500 bg-gray-100 hover:bg-red-50 p-1.5 rounded-full transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form id="formEditUser" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="edit_name" required class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm shadow-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="edit_email" required class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm shadow-sm">
            </div>

            {{-- FIELD PASSWORD EDIT DENGAN IKON MATA --}}
            <div x-data="{ showPasswordEdit: false }">
                <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Ganti Password (Opsional)</label>
                <div class="relative">
                    <input :type="showPasswordEdit ? 'text' : 'password'" name="password" class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm shadow-sm pr-10" placeholder="Kosongkan jika tidak ingin ganti">
                    <button type="button" @click="showPasswordEdit = !showPasswordEdit" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none">
                        <svg x-show="!showPasswordEdit" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                        <svg x-show="showPasswordEdit" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Role <span class="text-red-500">*</span></label>
                    <select name="role" id="edit_role" required class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm shadow-sm cursor-pointer">
                        <option value="opd">OPD</option>
                        <option value="pimpinan">Pimpinan</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Instansi</label>
                    <input type="text" name="opd_name" id="edit_opd_name" class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm shadow-sm bg-gray-50 dark:bg-gray-800">
                </div>
            </div>
            
            <div class="pt-4 mt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end space-x-3">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-500/30 rounded-xl transition-all">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Menyembunyikan scrollbar bawaan browser tapi tetap bisa discroll untuk tabel */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 20px;
    }
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