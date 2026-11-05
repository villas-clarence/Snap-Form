<x-guest-layout>
    <div class="mb-8">
        <p class="mb-3 text-sm font-semibold uppercase tracking-[0.16em] text-cyan-400">Get started for free</p>
        <h2 class="text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">{{ __('Create your account') }}</h2>
        <p class="mt-3 text-sm leading-6 text-slate-500">{{ __('Set up your account to start creating and sharing forms.') }}</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Full name')" class="mb-2 text-sm font-bold text-slate-700" />
            <x-text-input id="name" class="block w-full rounded-xl !border-cyan-400 px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:!border-cyan-400 focus:!ring-cyan-400" type="text" name="name" :value="old('name')" placeholder="Alex Morgan" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email address')" class="mb-2 text-sm font-bold text-slate-700" />
            <x-text-input id="email" class="block w-full rounded-xl !border-cyan-400 px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:!border-cyan-400 focus:!ring-cyan-400" type="email" name="email" :value="old('email')" placeholder="you@example.com" required autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div x-data="{ visible: false }">
            <x-input-label for="password" :value="__('Password')" class="mb-2 text-sm font-bold text-slate-700" />
            <div class="relative">
                <x-text-input id="password" class="block w-full rounded-xl !border-cyan-400 px-4 py-3 pe-16 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:!border-cyan-400 focus:!ring-cyan-400"
                    x-bind:type="visible ? 'text' : 'password'"
                    name="password"
                    required autocomplete="new-password" />
                <button type="button" class="absolute inset-y-0 right-0 inline-flex items-center px-4 text-xs font-semibold text-slate-500 transition hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" x-on:click="visible = !visible" x-bind:aria-label="visible ? 'Hide password' : 'Show password'" x-text="visible ? 'Hide' : 'Show'"></button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div x-data="{ visible: false }">
            <x-input-label for="password_confirmation" :value="__('Confirm password')" class="mb-2 text-sm font-bold text-slate-700" />
            <div class="relative">
                <x-text-input id="password_confirmation" class="block w-full rounded-xl !border-cyan-400 px-4 py-3 pe-16 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:!border-cyan-400 focus:!ring-cyan-400"
                    x-bind:type="visible ? 'text' : 'password'"
                    name="password_confirmation" required autocomplete="new-password" />
                <button type="button" class="absolute inset-y-0 right-0 inline-flex items-center px-4 text-xs font-semibold text-slate-500 transition hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" x-on:click="visible = !visible" x-bind:aria-label="visible ? 'Hide password' : 'Show password'" x-text="visible ? 'Hide' : 'Show'"></button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-2">
            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-cyan-200/70 bg-cyan-400 px-5 py-3.5 text-sm font-bold uppercase text-slate-950 shadow-lg shadow-cyan-400/30 transition hover:border-cyan-100 hover:bg-cyan-300 hover:shadow-cyan-300/40 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-2 active:bg-cyan-500">
                {{ __('Create account') }}
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M4 10h12m-5-5 5 5-5 5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>

        <p class="text-center text-sm text-slate-500">
            {{ __('Already have an account?') }}
            <a class="font-semibold text-indigo-600 underline-offset-4 transition hover:text-indigo-700 hover:underline focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" href="{{ route('login') }}">
                {{ __('Log in') }}
            </a>
        </p>
    </form>
</x-guest-layout>
