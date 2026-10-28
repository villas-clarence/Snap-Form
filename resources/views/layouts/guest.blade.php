<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen bg-slate-100 px-4 py-5 sm:px-6 sm:py-8 lg:flex lg:items-center lg:justify-center">
            <div class="mx-auto grid min-h-[min(760px,calc(100vh-4rem))] w-full max-w-6xl overflow-hidden rounded-[2rem] bg-white shadow-2xl shadow-slate-900/10 lg:grid-cols-[0.95fr_1.05fr]">
                <aside class="relative hidden flex-col justify-between overflow-hidden bg-slate-950 p-12 text-white lg:flex xl:p-14">
                    <div class="absolute -right-32 -top-32 h-96 w-96 rounded-full bg-indigo-500/20 blur-3xl"></div>
                    <div class="absolute -bottom-40 -left-32 h-96 w-96 rounded-full bg-cyan-400/10 blur-3xl"></div>

                    <a href="/" class="relative inline-flex w-fit items-center gap-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-500 text-white shadow-lg shadow-indigo-950/40">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M7 4.75h10A2.25 2.25 0 0 1 19.25 7v10A2.25 2.25 0 0 1 17 19.25H7A2.25 2.25 0 0 1 4.75 17V7A2.25 2.25 0 0 1 7 4.75Z" stroke="currentColor" stroke-width="1.7" />
                                <path d="M8.5 9h7M8.5 12h7M8.5 15h3.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
                                <path d="m15.4 15.1 1.1 1.1 2.2-2.4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <span class="text-xl font-semibold tracking-tight">SnapForm</span>
                    </a>

                    <div class="relative my-12">
                        <p class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs font-medium tracking-wide text-indigo-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-cyan-300"></span>
                            FORMS, WITHOUT THE FRICTION
                        </p>
                        <h1 class="max-w-md text-4xl font-semibold leading-tight tracking-tight xl:text-5xl">
                            Good questions make great things happen.
                        </h1>
                        <p class="mt-5 max-w-md text-base leading-7 text-slate-300">
                            Create a form, share it with your team, and keep every response in one place.
                        </p>

                        <div class="mt-10 space-y-4">
                            <div class="flex items-center gap-3 text-sm text-slate-200">
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white/10 text-cyan-300">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="m5 10 3.2 3.2L15.5 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                </span>
                                Build and edit forms in minutes
                            </div>
                            <div class="flex items-center gap-3 text-sm text-slate-200">
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white/10 text-cyan-300">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M10 3.5v13m-6.5-6.5h13" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" /></svg>
                                </span>
                                Share a link across your local network
                            </div>
                            <div class="flex items-center gap-3 text-sm text-slate-200">
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white/10 text-cyan-300">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M4.5 5.5h11v10h-11zM7 3.5v4M13 3.5v4M4.5 9h11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                </span>
                                Review and export responses anytime
                            </div>
                        </div>
                    </div>

                    <p class="relative text-xs text-slate-400">A clearer way to collect what matters.</p>
                </aside>

                <main class="flex items-center justify-center px-6 py-10 sm:px-12 lg:px-14 xl:px-20">
                    <div class="w-full max-w-md">
                        <a href="/" class="mb-10 inline-flex items-center gap-2.5 rounded-lg text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 lg:hidden">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-white">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M7 4.75h10A2.25 2.25 0 0 1 19.25 7v10A2.25 2.25 0 0 1 17 19.25H7A2.25 2.25 0 0 1 4.75 17V7A2.25 2.25 0 0 1 7 4.75Z" stroke="currentColor" stroke-width="1.7" />
                                    <path d="M8.5 9h7M8.5 12h7M8.5 15h3.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
                                </svg>
                            </span>
                            <span class="text-lg font-semibold tracking-tight">SnapForm</span>
                        </a>

                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
