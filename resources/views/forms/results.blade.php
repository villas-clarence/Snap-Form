<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm text-gray-500"><a href="{{ route('forms.show', $form) }}" class="hover:text-indigo-700">{{ $form->title }}</a> / Responses</p>
                <h2 class="mt-1 font-semibold text-xl text-gray-800">Verified responses</h2>
            </div>
            <a href="{{ route('forms.export', $form) }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Download CSV</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-5xl space-y-5 px-4 sm:px-6 lg:px-8">
            <p class="text-sm text-gray-600">{{ $submissions->count() }} verified {{ Str::plural('response', $submissions->count()) }}</p>
            @forelse ($submissions as $submission)
                <article class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap justify-between gap-2 border-b border-gray-100 pb-3">
                        <h3 class="font-semibold text-gray-900">{{ $submission->respondent_email }}</h3>
                        <time class="text-sm text-gray-500">{{ $submission->created_at->format('M j, Y g:i A') }}</time>
                    </div>
                    <dl class="mt-4 space-y-4">
                        @foreach ($submission->answers as $answer)
                            <div>
                                <dt class="text-sm font-medium text-gray-700">{{ $answer->question_text_snapshot }}</dt>
                                <dd class="mt-1 whitespace-pre-wrap text-sm text-gray-900">
                                    @if ($answer->question_type_snapshot === 'checkbox')
                                        {{ implode(', ', json_decode($answer->answer_text, true, 512, JSON_THROW_ON_ERROR)) }}
                                    @else
                                        {{ $answer->answer_text }}
                                    @endif
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-gray-300 bg-white p-10 text-center text-sm text-gray-600">No verified responses yet.</div>
            @endforelse
        </div>
    </div>
</x-app-layout>
