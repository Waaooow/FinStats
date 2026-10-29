<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-lg sm:text-xl text-slate-800 dark:text-slate-100 leading-tight flex items-center gap-2">
            <i class="fa-solid fa-pen-to-square text-emerald-500"></i>
            Edit Pocket
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 shadow-sm">
                <form action="{{ route('pockets.update', $pocket->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">
                            Nama Pocket
                        </label>
                        <input type="text" name="name" required value="{{ old('name', $pocket->name) }}"
                            class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 focus:border-emerald-500 focus:ring-0">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">
                            Target Tabungan (Rp) <span class="text-slate-400 text-xs font-normal">(opsional)</span>
                        </label>
                        <input type="number" name="target" min="0" step="1000"
                            value="{{ old('target', $pocket->target ?? '') }}"
                            class="w-full rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 focus:border-emerald-500 focus:ring-0">
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">Kosongkan untuk menghapus target.</p>
                    </div>

                    <div class="pt-4 flex justify-end gap-2">
                        <a href="{{ route('pockets.show', $pocket->id) }}"
                            class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-semibold hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white dark:text-slate-950 rounded-xl text-xs font-bold shadow-md transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
