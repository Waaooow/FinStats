<x-guest-layout>
    <div class="min-h-screen bg-slate-950 flex items-center justify-center p-6" x-data="{ tab: 'login' }">
        <div class="w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white font-plus">Fin<span class="text-emerald-400">Stats</span></h1>
                <p class="text-slate-400 text-sm mt-2">Manage your assets with ease.</p>
            </div>

            <div class="flex bg-slate-950 p-1 rounded-xl mb-8">
                <button @click="tab = 'login'" :class="tab === 'login' ? 'bg-slate-800 text-white' : 'text-slate-500'" class="flex-1 py-2 text-sm font-semibold rounded-lg transition-all">Login</button>
                <button @click="tab = 'register'" :class="tab === 'register' ? 'bg-slate-800 text-white' : 'text-slate-500'" class="flex-1 py-2 text-sm font-semibold rounded-lg transition-all">Register</button>
            </div>

            <div x-show="tab === 'login'" x-cloak class="space-y-4">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <input type="email" name="email" placeholder="Email Address" class="w-full p-3 bg-slate-950 border border-slate-800 rounded-xl text-white mb-4">
                    <input type="password" name="password" placeholder="Password" class="w-full p-3 bg-slate-950 border border-slate-800 rounded-xl text-white mb-6">
                    <button class="w-full py-3 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 transition">Sign In</button>
                </form>
            </div>

            <div x-show="tab === 'register'" x-cloak class="space-y-4">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <input type="text" name="name" placeholder="Full Name" class="w-full p-3 bg-slate-950 border border-slate-800 rounded-xl text-white mb-4">
                    <input type="email" name="email" placeholder="Email Address" class="w-full p-3 bg-slate-950 border border-slate-800 rounded-xl text-white mb-4">
                    <input type="password" name="password" placeholder="Password" class="w-full p-3 bg-slate-950 border border-slate-800 rounded-xl text-white mb-4">
                    <input type="password" name="password_confirmation" placeholder="Confirm Password" class="w-full p-3 bg-slate-950 border border-slate-800 rounded-xl text-white mb-6">
                    <button class="w-full py-3 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 transition">Create Account</button>
                </form>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-800 text-center">
                <p class="text-xs text-slate-500">Live USD-IDR: <span class="text-emerald-400">Rp {{ number_format($kurs, 0, ',', '.') }}</span></p>
            </div>
        </div>
    </div>
</x-guest-layout>