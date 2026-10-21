<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm text-gray-500"><a href="{{ route('dashboard') }}" class="hover:text-indigo-700">Your forms</a> / Form</p>
                <h2 class="mt-1 font-semibold text-xl text-gray-800">{{ $form->title }}</h2>
            </div>
            <div class="flex flex-wrap gap-2">
                @if (! $form->archived_at)
                    <a href="{{ route('forms.edit', $form) }}" class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Edit</a>
                    <a href="{{ route('public.show', $form->slug) }}" target="_blank" rel="noopener" class="rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700">Open public form</a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('status') }}</div>
            @endif

            <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="text-sm leading-6 text-gray-600">{{ $form->description ?: 'No description provided.' }}</p>
                        <p class="mt-3 text-sm text-gray-500">{{ $form->verified_submissions_count }} verified responses</p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $form->archived_at ? 'bg-gray-100 text-gray-600' : ($form->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800') }}">
                        {{ $form->archived_at ? 'Archived' : ($form->is_active ? 'Accepting responses' : 'Closed') }}
                    </span>
                </div>
                <div class="mt-5">
                    <label for="public-link" class="block text-sm font-medium text-gray-700">Share this local-network link</label>
                    <input id="public-link" readonly value="{{ route('public.show', $form->slug) }}" onclick="this.select()" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 text-sm shadow-sm">
                    <p class="mt-1 text-xs text-gray-500">Open SnapForm using the host computer’s local network address before sharing this link with other devices.</p>
                </div>
            </section>

            <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">Questions</h3>
                    <a href="{{ route('forms.results', $form) }}" class="text-sm font-semibold text-indigo-700 hover:text-indigo-900">View responses</a>
                </div>
                <ol class="mt-4 space-y-3">
                    @foreach ($form->activeQuestions as $question)
                        <li class="rounded-lg bg-gray-50 p-4">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <span class="font-medium text-gray-900">{{ $loop->iteration }}. {{ $question->question_text }}</span>
                                <span class="text-xs text-gray-500">{{ ucfirst($question->type) }}{{ $question->is_required ? ' · required' : '' }}</span>
                            </div>
                            @if ($question->options)
                                <p class="mt-2 text-sm text-gray-600">{{ implode(', ', $question->options) }}</p>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </section>

            @if (! $form->archived_at)
                <form method="POST" action="{{ route('forms.archive', $form) }}" onsubmit="return confirm('Archive this form? Existing responses will be kept, but no new responses will be accepted.')">
                    @csrf
                    <button type="submit" class="rounded-lg border border-rose-200 px-4 py-2 text-sm font-semibold text-rose-700 hover:bg-rose-50">Archive form</button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
