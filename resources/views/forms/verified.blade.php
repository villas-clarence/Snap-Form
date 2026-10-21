<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Response verified — SnapForm</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="grid min-h-screen place-items-center bg-slate-100 px-4">
    <main class="w-full max-w-xl rounded-2xl border border-emerald-200 bg-white p-8 text-center shadow-sm">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-700">SnapForm</p>
        <h1 class="mt-4 text-2xl font-bold text-slate-900">Email verified</h1>
        <p class="mt-3 leading-7 text-slate-600">Your response to “{{ $form->title }}” has been verified and recorded. Thank you.</p>
    </main>
</body>
</html>
