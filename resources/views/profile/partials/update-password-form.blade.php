<section class="w-full">
    <header>
        <h2 class="text-lg font-bold text-slate-200">
            {{ __('Perbarui Password') }}
        </h2>
        <p class="mt-1 text-xs text-slate-500">
            {{ __('Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5 w-full">
        @csrf
        @method('put')

        <div class="w-full">
            <label for="update_password_current_password"
                class="block text-xs font-semibold text-slate-400 mb-1.5">{{ __('Password Saat Ini') }}</label>
            <input id="update_password_current_password" name="current_password" type="password"
                class="block w-full max-w-full rounded-lg bg-slate-950 border-slate-800 text-sm text-slate-200 focus:border-emerald-500 focus:ring-0 placeholder-slate-700"
                autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-rose-500 text-xs" />
        </div>

        <div class="w-full">
            <label for="update_password_password"
                class="block text-xs font-semibold text-slate-400 mb-1.5">{{ __('Password Baru') }}</label>
            <input id="update_password_password" name="password" type="password"
                class="block w-full max-w-full rounded-lg bg-slate-950 border-slate-800 text-sm text-slate-200 focus:border-emerald-500 focus:ring-0 placeholder-slate-700"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-rose-500 text-xs" />
        </div>

        <div class="w-full">
            <label for="update_password_password_confirmation"
                class="block text-xs font-semibold text-slate-400 mb-1.5">{{ __('Konfirmasi Password') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="block w-full max-w-full rounded-lg bg-slate-950 border-slate-800 text-sm text-slate-200 focus:border-emerald-500 focus:ring-0 placeholder-slate-700"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-rose-500 text-xs" />
        </div>

        <div class="flex items-center gap-4 pt-2 w-full flex-wrap">
            <button type="submit"
                class="px-4 py-2 bg-[#10b981] hover:bg-[#059669] text-slate-950 rounded-lg text-xs font-bold transition shrink-0">
                {{ __('Ubah Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs text-emerald-500 font-bold flex items-center gap-1">
                    <i class="fa-solid fa-check"></i> {{ __('Tersimpan.') }}
                </p>
            @endif
        </div>
    </form>
</section>
