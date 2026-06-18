<x-guest-layout>
    <div class="min-h-screen bg-slate-950 flex flex-col items-center justify-center p-6 relative">
        
        <div class="absolute w-80 h-80 bg-emerald-500/10 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="w-full max-w-md bg-slate-900 border border-slate-800/80 rounded-3xl p-8 shadow-2xl relative z-10 space-y-8">
            
            <div class="text-center space-y-2">
                <div class="flex justify-center mb-4">
                    <a href="/" class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                        <i class="fa-solid fa-chart-pie text-white text-lg"></i>
                    </a>
                </div>
                <h2 class="text-3xl font-extrabold text-white tracking-tight font-plus">Sign In</h2>
                <p class="text-slate-400 text-sm">
                    Don't have an account yet? 
                    <a href="{{ route('register') }}" class="text-emerald-400 hover:text-emerald-300 font-medium transition-colors hover:underline">Create free account</a>
                </p>
            </div>

            <div class="bg-slate-950 border border-slate-800 rounded-2xl p-4 flex justify-between items-center">
                <div class="text-xs font-semibold text-slate-400 tracking-wider uppercase">Live USD to IDR</div>
                <div class="text-sm font-bold text-emerald-400 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                    Rp {{ number_format($kurs, 0, ',', '.') }}
                </div>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-semibold text-slate-400 tracking-wider uppercase">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input id="email" class="block w-full pl-10 pr-4 py-3 bg-slate-950 border border-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-white placeholder-slate-600 rounded-xl transition-all outline-none" type="email" name="email" :value="old('email')" placeholder="you@example.com" required autofocus />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="space-y-1.5">
                    <div class="flex justify-between items-center">
                        <label for="password" class="block text-xs font-semibold text-slate-400 tracking-wider uppercase">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-slate-500 hover:text-emerald-400 transition-colors">Forgot?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input id="password" class="block w-full pl-10 pr-4 py-3 bg-slate-950 border border-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-white placeholder-slate-600 rounded-xl transition-all outline-none" type="password" name="password" placeholder="••••••••" required />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <button type="submit" class="w-full flex justify-center items-center gap-2 py-3.5 px-4 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/35 transition-all">
                    Sign In to Dashboard
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>