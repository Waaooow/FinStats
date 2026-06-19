<x-guest-layout>
    <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex items-center justify-center p-6 transition-colors duration-300"
        x-data="{ tab: 'login' }">
        <div
            class="w-full max-w-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 shadow-2xl transition-colors duration-300">

            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white font-plus transition-colors">Fin<span
                        class="text-emerald-600 dark:text-emerald-400">Stats</span></h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-2">Manage your assets with ease.</p>
            </div>

            <div
                class="flex bg-slate-100 dark:bg-slate-950 p-1 rounded-xl mb-8 border border-slate-200 dark:border-slate-800/50">
                <button @click="tab = 'login'"
                    :class="tab === 'login' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm' :
                        'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                    class="flex-1 py-2.5 text-sm font-semibold rounded-lg transition-all">Login</button>
                <button @click="tab = 'register'"
                    :class="tab === 'register' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm' :
                        'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                    class="flex-1 py-2.5 text-sm font-semibold rounded-lg transition-all">Register</button>
            </div>

            <div x-show="tab === 'login'" x-cloak class="space-y-4">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <input type="email" name="email" placeholder="Email Address"
                        class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 focus:border-emerald-500 rounded-xl text-slate-900 dark:text-white mb-4 placeholder-slate-400 dark:placeholder-slate-600 outline-none">
                    <input type="password" name="password" placeholder="Password"
                        class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 focus:border-emerald-500 rounded-xl text-slate-900 dark:text-white mb-6 placeholder-slate-400 dark:placeholder-slate-600 outline-none">
                    <button
                        class="w-full py-3.5 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 shadow-lg shadow-emerald-500/20 transition-all">Sign
                        In</button>
                </form>
            </div>

            <div x-show="tab === 'register'" x-cloak class="space-y-4">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <input type="text" name="name" placeholder="Full Name"
                        class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 focus:border-emerald-500 rounded-xl text-slate-900 dark:text-white mb-4 placeholder-slate-400 dark:placeholder-slate-600 outline-none">
                    <input type="email" name="email" placeholder="Email Address"
                        class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 focus:border-emerald-500 rounded-xl text-slate-900 dark:text-white mb-4 placeholder-slate-400 dark:placeholder-slate-600 outline-none">
                    <input type="password" name="password" placeholder="Password"
                        class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 focus:border-emerald-500 rounded-xl text-slate-900 dark:text-white mb-4 placeholder-slate-400 dark:placeholder-slate-600 outline-none">
                    <input type="password" name="password_confirmation" placeholder="Confirm Password"
                        class="w-full p-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 focus:border-emerald-500 rounded-xl text-slate-900 dark:text-white mb-6 placeholder-slate-400 dark:placeholder-slate-600 outline-none">
                    <button
                        class="w-full py-3.5 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 shadow-lg shadow-emerald-500/20 transition-all">Create
                        Account</button>
                </form>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-800 text-center transition-colors">
                <p class="text-xs text-slate-500 dark:text-slate-400">Protected by enterprise-grade security</p>
            </div>
        </div>
    </div>
</x-guest-layout>
