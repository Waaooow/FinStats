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
                        <i class="fa-regular fa-envelope-open"></i>
                    </div>
                </div>
                <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight font-plus">Verify your
                    email</h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                    Thanks for signing up! Before getting started, could you verify your email address by clicking on
                    the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div
                    class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-xl text-sm font-medium text-center">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            @endif

            <div class="flex flex-col space-y-4 pt-2">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex justify-center items-center py-3.5 px-4 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/35 transition-all">
                        Resend Verification Email
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="text-center">
                    @csrf
                    <button type="submit"
                        class="text-sm text-slate-500 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 font-medium transition-colors">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
