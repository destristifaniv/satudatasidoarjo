@extends('layouts.app')

@section('content')
<div class="relative h-screen flex items-center justify-center bg-[#F8F9FA] dark:bg-gray-950 p-4 transition-colors duration-500 overflow-hidden" 
     x-data="{ showPass: false, showConfirm: false }">
    
    <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
        <div class="absolute top-[-10%] right-[-10%] w-[400px] h-[400px] bg-green-200/30 dark:bg-green-900/10 blur-[100px] rounded-full"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[400px] h-[400px] bg-blue-200/20 dark:bg-blue-900/10 blur-[100px] rounded-full"></div>
    </div>

    <div class="relative z-10 w-full max-w-[400px]">
        
        <a href="{{ route('login') }}" class="inline-flex items-center text-[9px] font-black uppercase tracking-[2px] text-gray-400 hover:text-green-600 transition-colors mb-4 ml-1 group">
            <span class="mr-1.5 group-hover:-translate-x-1 transition-transform">←</span> Back to Login
        </a>

        <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-2xl rounded-[32px] p-8 shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-white/40 dark:border-gray-800">
            
            <div class="text-center mb-6">
                <img src="https://adminweb.sidoarjokab.go.id/upload/link/1703218932.png" 
                     alt="Logo Kabupaten Sidoarjo" 
                     class="w-12 h-12 mx-auto mb-3 object-contain drop-shadow-lg">
                
                <h1 class="text-2xl font-black text-gray-800 dark:text-white tracking-tighter leading-none">
                    Create <span class="text-green-600">Account</span>
                </h1>
                <p class="text-[9px] text-gray-400 dark:text-gray-500 mt-2 font-bold uppercase tracking-[3px]">
                    SATU DATA KABUPATEN SIDOARJO
                </p>
            </div>

            <form action="{{ route('register') }}" method="POST" class="space-y-3.5">
                @csrf
                
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[1px] ml-3 block">
                        Full Name
                    </label>
                    <input type="text" name="name" required value="{{ old('name') }}"
                           class="w-full px-5 py-2.5 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border-none focus:ring-2 focus:ring-green-500/20 text-gray-800 dark:text-white transition-all outline-none text-xs placeholder-gray-300 dark:placeholder-gray-600" 
                           placeholder="Enter your name">
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[1px] ml-3 block">
                        Email Address
                    </label>
                    <input type="email" name="email" required value="{{ old('email') }}"
                           class="w-full px-5 py-2.5 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border-none focus:ring-2 focus:ring-green-500/20 text-gray-800 dark:text-white transition-all outline-none text-xs placeholder-gray-300 dark:placeholder-gray-600" 
                           placeholder="admin@sidoarjokab.go.id">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[1px] ml-3 block">
                            Password
                        </label>
                        <div class="relative group">
                            <input :type="showPass ? 'text' : 'password'" name="password" required 
                                   class="w-full px-5 py-2.5 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border-none focus:ring-2 focus:ring-green-500/20 text-gray-800 dark:text-white transition-all outline-none text-xs placeholder-gray-300 dark:placeholder-gray-600" 
                                   placeholder="••••••••">
                            <button type="button" @click="showPass = !showPass" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-600 transition-colors">
                                {{-- Mata Terbuka --}}
                                <svg x-show="showPass" xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{-- Mata Tertutup (Eye-off dengan garis) --}}
                                <svg x-show="!showPass" xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L4.34 4.34m15.32 15.32l-5.34-5.34M17.614 6.386A10.05 10.05 0 0121.542 12c-1.274 4.057-5.064 7-9.542 7-1.17 0-2.285-.202-3.32-.572M12 5c4.478 0 8.268 2.943 9.542 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[1px] ml-3 block">
                            Confirm
                        </label>
                        <div class="relative group">
                            <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required 
                                   class="w-full px-5 py-2.5 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border-none focus:ring-2 focus:ring-green-500/20 text-gray-800 dark:text-white transition-all outline-none text-xs placeholder-gray-300 dark:placeholder-gray-600" 
                                   placeholder="••••••••">
                            <button type="button" @click="showConfirm = !showConfirm" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-600 transition-colors">
                                {{-- Mata Terbuka --}}
                                <svg x-show="showConfirm" xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{-- Mata Tertutup --}}
                                <svg x-show="!showConfirm" xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L4.34 4.34m15.32 15.32l-5.34-5.34M17.614 6.386A10.05 10.05 0 0121.542 12c-1.274 4.057-5.064 7-9.542 7-1.17 0-2.285-.202-3.32-.572M12 5c4.478 0 8.268 2.943 9.542 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="pt-3">
                    <button type="submit" 
                            class="w-full py-3.5 bg-green-600 hover:bg-green-700 text-white rounded-2xl font-black uppercase tracking-[2px] text-[10px] shadow-lg shadow-green-500/30 transition-all active:scale-[0.97]">
                        Register
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t border-gray-100 dark:border-gray-800 text-center">
                <p class="text-[10px] text-gray-400 font-medium">
                    Sudah memiliki akun? 
                    <a href="{{ route('login') }}" class="text-green-600 font-bold hover:underline">Masuk Disini</a>
                </p>
            </div>
        </div>

        <p class="text-center mt-6 text-[8px] font-black text-gray-400 uppercase tracking-[4px] opacity-50">
            PEMKAB SIDOARJO
        </p>
    </div>
</div>

<style>
    body {
        -webkit-font-smoothing: antialiased;
        overflow: hidden;
    }
</style>
@endsection