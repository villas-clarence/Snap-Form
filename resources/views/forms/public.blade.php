<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $form->title }} — SnapForm</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 py-10 text-slate-900">
    <main class="mx-auto max-w-3xl space-y-5 px-4">
        <header class="rounded-2xl bg-slate-950 p-7 text-white shadow-sm sm:p-9">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-300">SnapForm</p>
            <h1 class="mt-3 text-3xl font-bold">{{ $form->title }}</h1>
            @if ($form->description)
                <p class="mt-3 whitespace-pre-wrap leading-7 text-slate-300">{{ $form->description }}</p>
            @endif
        </header>

        <form method="POST" action="{{ route('public.send-verification', $form->slug) }}" class="space-y-4">
            @csrf
            @foreach ($form->activeQuestions as $question)
                <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <label for="answer-{{ $question->id }}" class="block font-medium text-slate-900">
                        {{ $question->question_text }}
                        @if ($question->is_required)<span class="text-rose-600" aria-label="required">*</span>@endif
                    </label>
                    @if ($question->type === 'text')
                        <input id="answer-{{ $question->id }}" name="answers[{{ $question->id }}]" type="text" maxlength="10000" @required($question->is_required) value="{{ old('answers.'.$question->id) }}" class="mt-3 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @elseif ($question->type === 'textarea')
                        <textarea id="answer-{{ $question->id }}" name="answers[{{ $question->id }}]" rows="4" maxlength="20000" @required($question->is_required) class="mt-3 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('answers.'.$question->id) }}</textarea>
                    @elseif ($question->type === 'select')
                        <select id="answer-{{ $question->id }}" name="answers[{{ $question->id }}]" @required($question->is_required) class="mt-3 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Choose an option</option>
                            @foreach ($question->options ?? [] as $option)
                                <option value="{{ $option }}" @selected(old('answers.'.$question->id) === $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                    @else
                        <fieldset class="mt-3 space-y-2">
                            <legend class="sr-only">{{ $question->question_text }}</legend>
                            @foreach ($question->options ?? [] as $option)
                                <label class="flex items-center gap-3 text-sm text-slate-700">
                                    <input
                                        name="answers[{{ $question->id }}]{{ $question->type === 'checkbox' ? '[]' : '' }}"
                                        type="{{ $question->type === 'checkbox' ? 'checkbox' : 'radio' }}"
                                        value="{{ $option }}"
                                        @if ($question->is_required && $loop->first) required @endif
                                        @checked(collect(old('answers.'.$question->id, []))->contains($option))
                                        class="border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                    >
                                    {{ $option }}
                                </label>
                            @endforeach
                        </fieldset>
                    @endif
                    <x-input-error :messages="$errors->get('answers.'.$question->id)" class="mt-2" />
                </section>
            @endforeach

            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <label for="respondent_email" class="block font-medium text-slate-900">Your email address <span class="text-rose-600">*</span></label>
                <p class="mt-1 text-sm text-slate-600">We’ll email you a one-time link to verify this response. You can submit again later.</p>
                <input id="respondent_email" name="respondent_email" type="email" value="{{ old('respondent_email') }}" required maxlength="255" autocomplete="email" class="mt-3 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <x-input-error :messages="$errors->get('respondent_email')" class="mt-2" />
            </section>

            <div class="flex justify-end">
                <button type="submit" class="rounded-lg bg-indigo-600 px-5 py-3 font-semibold text-white shadow-sm hover:bg-indigo-700">Send verification email</button>
            </div>
        </form>
        <p class="text-center text-xs text-slate-500">This form is hosted on a local network. Your response is counted after email verification.</p>
    </main>
</body>
</html>
