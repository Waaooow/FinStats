<x-guest-layout>
    <div
        class="min-h-screen bg-slate-50 dark:bg-slate-950 flex flex-col items-center justify-center p-6 relative transition-colors duration-300">

        <div
            class="absolute w-80 h-80 bg-emerald-500/20 dark:bg-emerald-500/10 rounded-full blur-[100px] pointer-events-none">
        </div>

        <div
            class="w-full max-w-md bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800/80 rounded-3xl p-8 shadow-2xl relative z-10 space-y-6 transition-colors duration-300">

            <div class="text-center space-y-2">
                <div class="flex justify-center mb-4">
                    <div
                        class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400 text-xl border border-emerald-200 dark:border-emerald-500/20">
                        <i class="fa-solid fa-key"></i>
                    </div>
                </div>
                <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight font-plus">Reset
                    Password</h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                    Forgot your password? No problem. Just let us know your email address and we will email you a
                    password reset link.
                </p>
            </div>

            <x-auth-session-status class="mb-4 text-emerald-600 dark:text-emerald-400 text-sm text-center font-medium"
                :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

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
                            class="block w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 rounded-xl transition-all outline-none"
                            type="email" name="email" :value="old('email')" required autofocus />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-rose-500 text-xs" />
                </div>

                <button type="submit"
                    class="w-full flex justify-center items-center py-3.5 px-4 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-xl shadow-md hover:bg-slate-800 dark:hover:bg-slate-200 transition-all">
                    Email Password Reset Link
                </button>

                <div class="text-center pt-2">
                    <a href="{{ route('login') }}"
                        class="text-sm text-slate-500 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors font-medium">Back
                        to Login</a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
