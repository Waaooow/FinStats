<section class="w-full">
    <header>
        <h2 class="text-lg font-bold text-slate-200">
            {{ __('Informasi Profil') }}
        </h2>
        <p class="mt-1 text-xs text-slate-500">
            {{ __('Perbarui informasi nama dan alamat email akun Anda.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5 w-full">
        @csrf
        @method('patch')

        <div class="w-full">
            <label for="name"
                class="block text-xs font-semibold text-slate-400 mb-1.5">{{ __('Nama Lengkap') }}</label>
            <input id="name" name="name" type="text"
                class="block w-full max-w-full rounded-lg bg-slate-950 border-slate-800 text-sm text-slate-200 focus:border-emerald-500 focus:ring-0 placeholder-slate-700"
                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2 text-rose-500 text-xs" :messages="$errors->get('name')" />
        </div>

        <div class="w-full">
            <label for="email" class="block text-xs font-semibold text-slate-400 mb-1.5">{{ __('Email') }}</label>
            <input id="email" name="email" type="email"
                class="block w-full max-w-full rounded-lg bg-slate-950 border-slate-800 text-sm text-slate-200 focus:border-emerald-500 focus:ring-0 placeholder-slate-700"
                value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2 text-rose-500 text-xs" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-slate-400">
                        {{ __('Alamat email Anda belum diverifikasi.') }}
                        <button form="send-verification"
                            class="underline text-sm text-emerald-500 hover:text-emerald-400 rounded-md focus:outline-none">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-emerald-400">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2 w-full flex-wrap">
            <button type="submit"
                class="px-4 py-2 bg-[#10b981] hover:bg-[#059669] text-slate-950 rounded-lg text-xs font-bold transition shrink-0">
                {{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs text-emerald-500 font-bold flex items-center gap-1">
                    <i class="fa-solid fa-check"></i> {{ __('Tersimpan.') }}
                </p>
            @endif
        </div>
    </form>
</section>
