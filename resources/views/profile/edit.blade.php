<x-app-layout>
    <div class="min-h-[calc(100vh-4rem)] bg-slate-50 py-8 sm:py-12">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <section class="relative overflow-hidden rounded-[2rem] bg-slate-950 px-6 py-8 text-white shadow-xl shadow-slate-900/10 sm:px-10 sm:py-10 lg:px-12">
                <div class="absolute -right-20 -top-32 h-80 w-80 rounded-full bg-indigo-500/20 blur-3xl"></div>
                <div class="absolute -bottom-40 right-1/3 h-72 w-72 rounded-full bg-cyan-400/10 blur-3xl"></div>
                <div class="relative">
                    <p class="mb-3 text-sm font-semibold uppercase tracking-[0.16em] text-cyan-300">{{ __('Your account') }}</p>
                    <h1 class="text-3xl font-semibold tracking-tight sm:text-4xl">{{ __('Profile') }}</h1>
                    <p class="mt-3 max-w-xl text-sm leading-6 text-slate-300 sm:text-base">{{ __('Manage your profile details, password, and account settings.') }}</p>
                </div>
            </section>

            <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:rounded-3xl sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:rounded-3xl sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:rounded-3xl sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
            </div>
        </div>
    </div>
</x-app-layout>
