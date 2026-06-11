@extends('layouts.app')

@section('content')
<div class="relative min-h-screen flex items-center justify-center bg-[#F8F9FA] dark:bg-gray-950 p-4 sm:p-8 transition-colors duration-500 overflow-hidden">
    
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
                    Verifikasi <span class="text-green-600">Email</span>
                </h1>
                <p class="text-[8px] md:text-[9px] text-gray-400 dark:text-gray-500 mt-2 font-bold uppercase tracking-[3px]">
                    Satu Data Sidoarjo
                </p>
            </div>

            <div class="mb-6 text-[10px] md:text-[11px] text-center text-gray-500 dark:text-gray-400 font-medium leading-relaxed px-2">
                {{ __('Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda dengan mengeklik tautan yang baru saja kami kirimkan. Jika Anda tidak menerima email tersebut, kami dengan senang hati akan mengirimkannya kembali.') }}
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 text-[10px] font-bold text-green-600 bg-green-50 dark:bg-green-900/20 p-3 rounded-xl text-center border border-green-100 dark:border-green-800 animate-pulse">
                    {{ __('Tautan verifikasi baru telah dikirimkan ke alamat email yang Anda berikan saat pendaftaran.') }}
                </div>
            @endif

            <div class="space-y-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full py-3.5 md:py-4 bg-green-600 hover:bg-green-700 text-white rounded-2xl font-black uppercase tracking-[1px] md:tracking-[2px] text-[10px] md:text-[11px] shadow-lg shadow-green-500/30 transition-all active:scale-[0.97] text-center">
                        {{ __('Kirim Ulang Email Verifikasi') }}
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="text-center pt-2">
                    @csrf
                    <button type="submit" 
                            class="text-[9px] font-bold text-gray-400 hover:text-red-500 uppercase tracking-[2px] transition-colors bg-transparent border-none outline-none">
                        {{ __('Log Out') }}
                    </button>
                </form>
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
        overflow-x: hidden;
    }
</style>
@endsection