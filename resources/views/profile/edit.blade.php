<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-lg sm:text-xl text-slate-100 leading-tight flex items-center gap-2">
            <i class="fa-solid fa-user-gear text-emerald-500"></i>
            {{ __('Pengaturan Profil') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12 w-full overflow-x-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="p-4 sm:p-8 bg-slate-900 border border-slate-800 shadow-sm rounded-2xl overflow-x-hidden">
                <div class="w-full max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-slate-900 border border-slate-800 shadow-sm rounded-2xl overflow-x-hidden">
                <div class="w-full max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-slate-900 border border-rose-900/50 shadow-sm rounded-2xl overflow-x-hidden">
                <div class="w-full max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
