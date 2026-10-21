<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SnapForm — Forms for your local network</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-white">
    <main class="mx-auto flex min-h-screen max-w-6xl flex-col justify-center px-6 py-16">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-indigo-300">Local forms, simply</p>
            <h1 class="mt-5 text-5xl font-bold tracking-tight sm:text-7xl">Create forms.<br><span class="text-indigo-300">Collect answers.</span></h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">SnapForm helps you build forms and collect email-verified responses from devices on your local network.</p>
            <div class="mt-9 flex flex-wrap gap-3">
                <a href="{{ route('register') }}" class="rounded-lg bg-indigo-500 px-5 py-3 font-semibold text-white hover:bg-indigo-400">Create owner account</a>
                <a href="{{ route('login') }}" class="rounded-lg border border-slate-700 px-5 py-3 font-semibold text-slate-200 hover:bg-slate-900">Sign in</a>
            </div>
            <p class="mt-5 text-sm text-slate-400">Designed for localhost and trusted local networks—not public internet hosting.</p>
        </div>
    </main>
</body>
</html>
