@extends('layouts.app')

@section('content')
<div class="relative h-screen flex items-center justify-center bg-[#F8F9FA] dark:bg-gray-950 p-4 transition-colors duration-500 overflow-hidden">
    
    <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
        <div class="absolute top-[-10%] right-[-10%] w-[400px] h-[400px] bg-green-200/30 dark:bg-green-900/10 blur-[100px] rounded-full"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[400px] h-[400px] bg-blue-200/20 dark:bg-blue-900/10 blur-[100px] rounded-full"></div>
    </div>

    <div class="relative z-10 w-full max-w-[360px]">
        
        <a href="{{ route('login') }}" class="inline-flex items-center text-[9px] font-black uppercase tracking-[2px] text-gray-400 hover:text-green-600 transition-colors mb-4 ml-1 group">
            <span class="mr-1.5 group-hover:-translate-x-1 transition-transform">←</span> Back to Login
        </a>

        <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-2xl rounded-[32px] p-8 shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-white/40 dark:border-gray-800">
            
            <div class="text-center mb-6">
                <img src="https://adminweb.sidoarjokab.go.id/upload/link/1703218932.png" 
                     alt="Logo Kabupaten Sidoarjo" 
                     class="w-14 h-14 mx-auto mb-4 object-contain drop-shadow-lg">
                
                <h1 class="text-2xl font-black text-gray-800 dark:text-white tracking-tighter leading-none">
                    Reset <span class="text-green-600">Password</span>
                </h1>
                <p class="text-[9px] text-gray-400 dark:text-gray-500 mt-2 font-bold uppercase tracking-[3px]">
                    Satu Data Sidoarjo
                </p>
            </div>

            <div class="mb-6 text-[10px] text-center text-gray-500 dark:text-gray-400 font-medium leading-relaxed px-2">
                {{ __('Lupa password? Masukkan alamat email Anda dan kami akan mengirimkan link reset password yang baru.') }}
            </div>

            @if (session('status'))
                <div class="mb-4 text-[10px] font-bold text-green-600 bg-green-50 dark:bg-green-900/20 p-3 rounded-xl text-center border border-green-100 dark:border-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf
                
                <div class="space-y-1.5">
                    <label for="email" class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[1px] ml-3 block">
                        Email Address
                    </label>
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus
                           class="w-full px-5 py-3 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border-none focus:ring-2 focus:ring-green-500/20 text-gray-800 dark:text-white transition-all outline-none text-sm placeholder-gray-300 dark:placeholder-gray-600" 
                           placeholder="name@example.com">
                    
                    @if ($errors->has('email'))
                        <p class="text-[9px] text-red-500 font-bold ml-3 mt-1">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full py-4 bg-green-600 hover:bg-green-700 text-white rounded-2xl font-black uppercase tracking-[2px] text-[10px] shadow-lg shadow-green-500/30 transition-all active:scale-[0.97]">
                        {{ __('Send Reset Link') }}
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
        overflow: hidden;
    }
</style>
@endsection