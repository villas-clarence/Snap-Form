<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="font-bold text-slate-700" />
            <x-text-input id="email" class="block mt-1 w-full !border-cyan-400 focus:!border-cyan-400 focus:!ring-cyan-400" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="font-bold text-slate-700" />

            <x-text-input id="password" class="block mt-1 w-full !border-cyan-400 focus:!border-cyan-400 focus:!ring-cyan-400"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="mt-4 flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-cyan-400 shadow-sm focus:ring-cyan-400" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <div class="mt-6 flex justify-center">
            <button type="submit" class="inline-flex items-center justify-center rounded-xl border border-cyan-200/70 bg-cyan-400 px-5 py-3 text-sm font-extrabold uppercase tracking-widest text-slate-950 shadow-lg shadow-cyan-400/30 transition hover:border-cyan-100 hover:bg-cyan-300 hover:shadow-cyan-300/40 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-2 active:bg-cyan-500">
                {{ __('Log in') }}
            </button>
        </div>
    </form>
</x-guest-layout>
