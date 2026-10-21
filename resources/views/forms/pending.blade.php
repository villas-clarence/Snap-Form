<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Check your email — SnapForm</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="grid min-h-screen place-items-center bg-slate-100 px-4">
    <main class="w-full max-w-xl rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600">SnapForm</p>
        <h1 class="mt-4 text-2xl font-bold text-slate-900">Check your inbox</h1>
        <p class="mt-3 leading-7 text-slate-600">We sent a verification link for “{{ $form->title }}” to <strong>{{ $email }}</strong>. Your response will be counted after you verify it.</p>
        <p class="mt-4 text-sm text-slate-500">The link expires in 30 minutes. Check your spam folder if it doesn’t arrive.</p>
        <a href="{{ route('public.show', $form->slug) }}" class="mt-6 inline-flex font-semibold text-indigo-700 hover:text-indigo-900">Return to the form</a>
    </main>
</body>
</html>
