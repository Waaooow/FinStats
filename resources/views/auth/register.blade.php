<x-guest-layout>
    <div
        class="min-h-screen bg-slate-50 dark:bg-slate-950 flex flex-col items-center justify-center p-6 relative transition-colors duration-300">

        <div
            class="absolute w-80 h-80 bg-emerald-500/20 dark:bg-emerald-500/10 rounded-full blur-[100px] pointer-events-none">
        </div>

        <div
            class="w-full max-w-md bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800/80 rounded-3xl p-8 shadow-2xl relative z-10 space-y-6 transition-colors duration-300">

            <div class="text-center space-y-2">
                <div class="flex justify-center mb-2">
                    <a href="/"
                        class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                        <i class="fa-solid fa-chart-pie text-white text-lg"></i>
                    </a>
                </div>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight font-plus">Create
                    Account</h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm">
                    Already have an account?
                    <a href="{{ route('login') }}"
                        class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-500 dark:hover:text-emerald-300 font-medium transition-colors hover:underline">Sign
                        In</a>
                </p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div class="space-y-1.5">
                    <label for="name"
                        class="block text-xs font-semibold text-slate-500 dark:text-slate-400 tracking-wider uppercase">Full
                        Name</label>
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <input id="name"
                            class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 rounded-xl transition-all outline-none"
                            type="text" name="name" :value="old('name')" placeholder="John Doe" required
                            autofocus autocomplete="name" />
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-1 text-rose-500 text-xs" />
                </div>

                <div class="space-y-1.5">
                    <label for="email"
                        class="block text-xs font-semibold text-slate-500 dark:text-slate-400 tracking-wider uppercase">Email
                        Address</label>
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <input id="email"
                            class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 rounded-xl transition-all outline-none"
                            type="email" name="email" :value="old('email')" placeholder="you@company.com" required
                            autocomplete="username" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-rose-500 text-xs" />
                </div>

                <div class="space-y-1.5">
                    <label for="password"
                        class="block text-xs font-semibold text-slate-500 dark:text-slate-400 tracking-wider uppercase">Password</label>
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input id="password"
                            class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 rounded-xl transition-all outline-none"
                            type="password" name="password" placeholder="••••••••" required
                            autocomplete="new-password" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-rose-500 text-xs" />
                </div>

                <div class="space-y-1.5">
                    <label for="password_confirmation"
                        class="block text-xs font-semibold text-slate-500 dark:text-slate-400 tracking-wider uppercase">Confirm
                        Password</label>
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                            <i class="fa-solid fa-lock-open"></i>
                        </div>
                        <input id="password_confirmation"
                            class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 rounded-xl transition-all outline-none"
                            type="password" name="password_confirmation" placeholder="••••••••" required
                            autocomplete="new-password" />
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-rose-500 text-xs" />
                </div>

                <button type="submit"
                    class="w-full flex justify-center items-center gap-2 py-3 px-4 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/35 transition-all mt-2">
                    <i class="fa-solid fa-user-plus"></i> Join FinStats
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
