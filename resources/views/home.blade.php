<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Snap Form — Forms for your local network</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-white">
    <main class="home-animated-background relative isolate mx-auto flex min-h-screen max-w-6xl flex-col justify-center overflow-hidden px-6 py-16">
        <div class="relative z-10 max-w-3xl">
            <h1 class="mt-5 text-5xl font-bold tracking-tight sm:text-7xl">Create forms.<br><span class="text-cyan-300">Collect answers.</span></h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">Snap Form helps you build forms and collect email-verified responses from devices on your local network.</p>
            <div class="mt-9 flex flex-wrap gap-3">
                <a href="{{ route('register') }}" class="rounded-xl border border-cyan-200/70 bg-cyan-400 px-5 py-3 font-bold text-slate-950 shadow-lg shadow-cyan-400/30 transition hover:border-cyan-100 hover:bg-cyan-300 hover:shadow-cyan-300/40">Create account</a>
                <a href="{{ route('login') }}" class="rounded-lg border border-slate-700 px-5 py-3 font-semibold text-slate-200 hover:bg-slate-900">Sign in</a>
            </div>
        </div>
    </main>
</body>
</html>
