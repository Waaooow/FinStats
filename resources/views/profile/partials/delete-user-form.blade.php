<section class="space-y-6 w-full">
    <header>
        <h2 class="text-lg font-bold text-rose-500 flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation text-sm"></i> {{ __('Hapus Akun') }}
        </h2>
        <p class="mt-1 text-xs text-slate-400">
            {{ __('Sekali akun Anda dihapus, semua sumber daya dan data di dalamnya akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi yang ingin Anda simpan.') }}
        </p>
    </header>

    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-4 py-2 bg-rose-500/10 border border-rose-500/50 hover:bg-rose-500 hover:text-white text-rose-500 rounded-lg text-xs font-bold transition">
        {{ __('Hapus Akun Saya') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}"
            class="p-4 sm:p-6 bg-slate-900 border border-slate-800 rounded-xl w-full">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-slate-200">
                {{ __('Apakah Anda yakin ingin menghapus akun ini?') }}
            </h2>

            <p class="mt-1 text-xs text-slate-400">
                {{ __('Sekali akun Anda dihapus, semua data akan hilang permanen. Silakan masukkan password Anda untuk mengonfirmasi bahwa Anda benar-benar ingin menghapus akun ini.') }}
            </p>

            <div class="mt-6 w-full">
                <label for="password" class="sr-only">{{ __('Password') }}</label>
                <input id="password" name="password" type="password"
                    class="block w-full max-w-full rounded-lg bg-slate-950 border-slate-800 text-sm text-slate-200 focus:border-rose-500 focus:ring-0 placeholder-slate-700"
                    placeholder="{{ __('Masukkan Password') }}" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-rose-500 text-xs" />
            </div>

            <div class="mt-6 flex justify-end gap-3 w-full flex-wrap">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-4 py-2 border border-slate-700 hover:bg-slate-800 text-slate-300 rounded-lg text-xs font-semibold transition">
                    {{ __('Batal') }}
                </button>

                <button type="submit"
                    class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold transition">
                    {{ __('Ya, Hapus Akun') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
