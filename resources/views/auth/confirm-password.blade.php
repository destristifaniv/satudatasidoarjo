@extends('layouts.app')

@section('content')
<div class="relative min-h-screen flex items-center justify-center bg-[#F8F9FA] dark:bg-gray-950 p-4 sm:p-8 transition-colors duration-500 overflow-hidden" 
     x-data="{ showPass: false }">
    
    {{-- Background Efek Blur --}}
    <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
        <div class="absolute top-[-10%] right-[-10%] w-[250px] h-[250px] md:w-[400px] md:h-[400px] bg-green-200/30 dark:bg-green-900/10 blur-[80px] md:blur-[100px] rounded-full"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[250px] h-[250px] md:w-[400px] md:h-[400px] bg-blue-200/20 dark:bg-blue-900/10 blur-[80px] md:blur-[100px] rounded-full"></div>
    </div>

    <div class="relative z-10 w-full max-w-[320px] sm:max-w-[360px] md:max-w-[400px]">
        
        <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-2xl rounded-[32px] p-6 md:p-8 shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-white/40 dark:border-gray-800">
            
            {{-- Header/Logo --}}
            <div class="text-center mb-6">
                <img src="https://adminweb.sidoarjokab.go.id/upload/link/1703218932.png" 
                     alt="Logo Kabupaten Sidoarjo" 
                     class="w-12 h-12 md:w-14 md:h-14 mx-auto mb-4 object-contain drop-shadow-lg">
                
                <h1 class="text-xl md:text-2xl font-black text-gray-800 dark:text-white tracking-tighter leading-none">
                    Konfirmasi <span class="text-green-600">Password</span>
                </h1>
                <p class="text-[8px] md:text-[9px] text-gray-400 dark:text-gray-500 mt-2 font-bold uppercase tracking-[3px]">
                    Area Aman Aplikasi
                </p>
            </div>

            <div class="mb-6 text-[10px] md:text-[11px] text-center text-gray-500 dark:text-gray-400 font-medium leading-relaxed px-2">
                {{ __('Ini adalah area aman aplikasi. Harap konfirmasi password Anda sebelum melanjutkan.') }}
            </div>

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
                @csrf

                <div class="space-y-1.5">
                    <label for="password" class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[1px] ml-3 block text-left">
                        Password Anda
                    </label>
                    <div class="relative group">
                        <input id="password" :type="showPass ? 'text' : 'password'" name="password" required autocomplete="current-password"
                               class="w-full px-4 py-3 md:px-5 md:py-3 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border-none focus:ring-2 focus:ring-green-500/20 text-gray-800 dark:text-white transition-all outline-none text-sm placeholder-gray-300 dark:placeholder-gray-600 text-left" 
                               placeholder="••••••••">
                        
                        <button type="button" @click="showPass = !showPass" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-600 transition-colors">
                            <svg x-show="showPass" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="!showPass" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L4.34 4.34m15.32 15.32l-5.34-5.34M17.614 6.386A10.05 10.05 0 0121.542 12c-1.274 4.057-5.064 7-9.542 7-1.17 0-2.285-.202-3.32-.572M12 5c4.478 0 8.268 2.943 9.542 7" />
                            </svg>
                        </button>
                    </div>
                    
                    @if ($errors->has('password'))
                        <p class="text-[9px] text-red-500 font-bold ml-3 mt-1 text-left">{{ $errors->first('password') }}</p>
                    @endif
                </div>

                <div class="pt-3 text-center">
                    <button type="submit" 
                            class="w-full py-3.5 md:py-4 bg-green-600 hover:bg-green-700 text-white rounded-2xl font-black uppercase tracking-[2px] text-[10px] md:text-[11px] shadow-lg shadow-green-500/30 transition-all active:scale-[0.97] text-center">
                        {{ __('Konfirmasi') }}
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center mt-6 text-[8px] font-black text-gray-400 uppercase tracking-[4px] opacity-50">
            PEMKAB SIDOARJO
        </p>
    </div>
</div>

<style>
    body {
        -webkit-font-smoothing: antialiased;
        overflow-x: hidden;
    }
</style>
@endsection