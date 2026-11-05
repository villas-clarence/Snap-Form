<x-app-layout>
    @php
        $builderQuestions = old('questions', $questions->map(fn ($question) => [
            'id' => $question->id,
            'question_text' => $question->question_text,
            'type' => $question->type,
            'options' => implode("\n", $question->options ?? []),
            'is_required' => $question->is_required,
        ])->all());
        if ($builderQuestions === []) {
            $builderQuestions = [['question_text' => '', 'type' => 'text', 'options' => '', 'is_required' => false]];
        }
    @endphp

    <div class="min-h-[calc(100vh-4rem)] bg-gradient-to-br from-slate-50 via-indigo-50/40 to-cyan-50/50 py-8 sm:py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-0 grid items-start gap-6 lg:grid-cols-[minmax(0,1fr)_300px]">
                <div>
                    <a href="{{ $form ? route('forms.show', $form) : route('dashboard') }}" class="mb-3 inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-indigo-700">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M15.5 10h-11m0 0 4.5-4.5M4.5 10l4.5 4.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        {{ __('Back') }}
                    </a>
                    <section class="rounded-2xl border border-slate-200 border-l-4 border-l-cyan-400 bg-white p-5 shadow-md shadow-slate-900/5 sm:rounded-3xl sm:p-7">
                        <div class="flex items-center gap-3">
                            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-950 text-cyan-300 shadow-lg shadow-slate-900/20">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M7 4.75h10A2.25 2.25 0 0 1 19.25 7v10A2.25 2.25 0 0 1 17 19.25H7A2.25 2.25 0 0 1 4.75 17V7A2.25 2.25 0 0 1 7 4.75Z" stroke="currentColor" stroke-width="1.6" />
                                    <path d="M8.5 9h7M8.5 12h7M8.5 15h3.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
                                </svg>
                            </span>
                            <div>
                                <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">{{ $form ? __('Edit your form') : __('Create a form') }}</h1>
                            </div>
                        </div>
                        <p class="mt-4 max-w-2xl text-sm leading-6 text-slate-600 sm:text-base">
                            {{ $form ? __('Fine-tune the details and questions. Your existing response history will stay safe.') : __('Start with the basics, then add the questions you need. You can edit everything later.') }}
                        </p>
                    </section>
                </div>
            </div>

            <div class="grid items-start gap-6 lg:grid-cols-[minmax(0,1fr)_300px]">
                <form method="POST" action="{{ $form ? route('forms.update', $form) : route('forms.store') }}" class="space-y-5">
                @csrf
                @if ($form)
                    @method('PUT')
                @endif

                <section class="relative space-y-5 rounded-2xl border border-slate-200 border-l-4 border-l-cyan-400 bg-white p-5 shadow-md shadow-slate-900/5 transition hover:border-slate-300 hover:border-l-cyan-500 sm:rounded-3xl sm:p-7">
                    <div class="space-y-4">
                        <div>
                            <x-input-label for="title" value="Form title" class="sr-only" />
                            <x-text-input id="title" name="title" type="text" class="block w-full rounded-none border-0 border-b-2 border-slate-100 bg-transparent px-0 py-2 text-3xl font-medium tracking-tight text-slate-950 shadow-none placeholder:text-slate-400 focus:border-indigo-600 focus:ring-0 sm:text-4xl" :value="old('title', $form?->title)" placeholder="Untitled form" required maxlength="255" />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="description" value="Form description (optional)" class="sr-only" />
                            <textarea id="description" name="description" rows="2" placeholder="Form description (optional)" class="block w-full resize-none rounded-none border-0 border-b border-slate-100 bg-transparent px-0 py-2 text-sm leading-6 text-slate-700 shadow-none placeholder:text-slate-400 focus:border-indigo-600 focus:ring-0">{{ old('description', $form?->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                    </div>

                <div class="flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 class="mt-1 text-xl font-bold tracking-tight text-slate-950">{{ __('Questions') }}</h2>
                    </div>
                    <p class="text-xs text-slate-500">{{ __('Add at least one question to continue') }}</p>
                </div>

                <div id="question-list" class="space-y-4">
                    @foreach ($builderQuestions as $index => $question)
                        <section class="question-card relative z-0 space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-md shadow-slate-900/5 transition hover:border-slate-300 sm:rounded-3xl sm:p-8">
                            @if (! empty($question['id']))
                                <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $question['id'] }}">
                            @endif
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div>
                                        <h3 class="font-semibold text-slate-950">{{ __('Question') }} <span class="question-label">{{ $loop->iteration }}</span></h3>
                                        <p class="mt-0.5 text-xs text-slate-500">{{ __('Keep it clear and focused.') }}</p>
                                    </div>
                                </div>
                                <button type="button" class="remove-question rounded-lg px-3 py-2 text-xs font-semibold text-rose-600 transition hover:bg-rose-50 hover:text-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2">{{ __('Remove') }}</button>
                            </div>
                            <div>
                                <textarea id="question-{{ $index }}" name="questions[{{ $index }}][question_text]" rows="3" required maxlength="2000" placeholder="e.g. What would you like us to know?" class="block w-full resize-none rounded-xl border-slate-200 px-4 py-3 text-sm shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500">{{ $question['question_text'] ?? '' }}</textarea>
                                <x-input-error :messages="$errors->get('questions.'.$index.'.question_text')" class="mt-2" />
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <x-input-label :for="'type-'.$index" value="Answer type" class="mb-2 text-sm font-semibold text-slate-700" />
                                    @include('forms.partials.question-type-picker', [
                                        'id' => 'type-'.$index,
                                        'name' => 'questions['.$index.'][type]',
                                        'type' => $question['type'] ?? 'text',
                                    ])
                                </div>
                                <label class="flex min-h-[3.1rem] cursor-pointer items-center justify-between gap-3 self-end rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50/50">
                                    <span>{{ __('Require an answer') }}</span>
                                    <input type="checkbox" name="questions[{{ $index }}][is_required]" value="1" @checked((bool) ($question['is_required'] ?? false)) class="peer sr-only">
                                    <span aria-hidden="true" class="relative h-6 w-11 shrink-0 rounded-full bg-slate-300 transition after:absolute after:left-1 after:top-1 after:h-4 after:w-4 after:rounded-full after:bg-white after:shadow-sm after:transition after:content-[''] peer-checked:bg-cyan-500 peer-checked:after:translate-x-5 peer-focus-visible:ring-2 peer-focus-visible:ring-cyan-500 peer-focus-visible:ring-offset-2"></span>
                                </label>
                            </div>
                            <div class="choice-options rounded-2xl border border-indigo-100 bg-indigo-50/50 p-4 @if (! in_array($question['type'] ?? 'text', ['select', 'radio', 'checkbox'], true)) hidden @endif">
                                <x-input-label :for="'options-'.$index" value="Choices (one per line)" class="mb-2 text-sm font-semibold text-slate-700" />
                                <textarea id="options-{{ $index }}" name="questions[{{ $index }}][options]" rows="4" placeholder="One option per line" class="block w-full resize-none rounded-xl border-slate-200 bg-white px-4 py-3 text-sm shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500">{{ is_array($question['options'] ?? null) ? implode("\n", $question['options']) : ($question['options'] ?? '') }}</textarea>
                                <p class="mt-2 text-xs text-slate-500">{{ __('Each line becomes a choice respondents can select.') }}</p>
                                <x-input-error :messages="$errors->get('questions.'.$index.'.options')" class="mt-2" />
                            </div>
                        </section>
                    @endforeach
                </div>
                </section>

                <template id="question-template">
                    <section class="question-card relative z-0 space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-md shadow-slate-900/5 transition hover:border-slate-300 sm:rounded-3xl sm:p-8">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div>
                                    <h3 class="font-semibold text-slate-950">Question <span class="question-label"></span></h3>
                                    <p class="mt-0.5 text-xs text-slate-500">Keep it clear and focused.</p>
                                </div>
                            </div>
                            <button type="button" class="remove-question rounded-lg px-3 py-2 text-xs font-semibold text-rose-600 transition hover:bg-rose-50 hover:text-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2">Remove</button>
                        </div>
                        <div>
                            <textarea id="question-__INDEX__" name="questions[__INDEX__][question_text]" rows="3" required maxlength="2000" placeholder="e.g. What would you like us to know?" class="block w-full resize-none rounded-xl border-slate-200 px-4 py-3 text-sm shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="type-__INDEX__" class="mb-2 block text-sm font-semibold text-slate-700">Answer type</label>
                                @include('forms.partials.question-type-picker', [
                                    'id' => 'type-__INDEX__',
                                    'name' => 'questions[__INDEX__][type]',
                                    'type' => 'text',
                                ])
                            </div>
                            <label class="flex min-h-[3.1rem] cursor-pointer items-center justify-between gap-3 self-end rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50/50">
                                <span>Require an answer</span>
                                <input type="checkbox" name="questions[__INDEX__][is_required]" value="1" class="peer sr-only">
                                <span aria-hidden="true" class="relative h-6 w-11 shrink-0 rounded-full bg-slate-300 transition after:absolute after:left-1 after:top-1 after:h-4 after:w-4 after:rounded-full after:bg-white after:shadow-sm after:transition after:content-[''] peer-checked:bg-cyan-500 peer-checked:after:translate-x-5 peer-focus-visible:ring-2 peer-focus-visible:ring-cyan-500 peer-focus-visible:ring-offset-2"></span>
                            </label>
                        </div>
                        <div class="choice-options hidden rounded-2xl border border-indigo-100 bg-indigo-50/50 p-4">
                            <label for="options-__INDEX__" class="mb-2 block text-sm font-semibold text-slate-700">Choices (one per line)</label>
                            <textarea id="options-__INDEX__" name="questions[__INDEX__][options]" rows="4" placeholder="One option per line" class="block w-full resize-none rounded-xl border-slate-200 bg-white px-4 py-3 text-sm shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            <p class="mt-2 text-xs text-slate-500">Each line becomes a choice respondents can select.</p>
                        </div>
                    </section>
                </template>

                <x-input-error :messages="$errors->get('questions')" />
                <div class="flex flex-col-reverse justify-between gap-3 rounded-2xl border border-slate-200 bg-white/80 p-4 shadow-sm sm:flex-row sm:items-center">
                    <button id="add-question" type="button" class="inline-flex items-center justify-center gap-2 rounded-xl border border-cyan-200 bg-cyan-50 px-4 py-3 text-sm font-semibold text-cyan-700 transition hover:border-cyan-300 hover:bg-cyan-100 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M10 4v12m6-6H4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                        {{ __('Add a question') }}
                    </button>
                    @if ($form)
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-600/20 transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            {{ __('Save changes') }}
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <path d="M4 10h12m-5-5 5 5-5 5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    @endif
                </div>

                </form>

                <aside class="space-y-5 lg:sticky lg:top-8">
                    <section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="relative">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-cyan-50 text-cyan-700">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M12 3.75 14.3 9.7l5.95 2.3-5.95 2.3L12 20.25l-2.3-5.95-5.95-2.3 5.95-2.3L12 3.75Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <h2 class="mt-5 text-lg font-semibold text-slate-950">{{ __('Make it easy to answer') }}</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-600">{{ __('A clear title and a short description help people understand why their answers matter.') }}</p>
                        </div>
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-sm font-semibold text-slate-950">{{ __('A few quick tips') }}</h2>
                        <ul class="mt-4 space-y-4">
                            <li class="flex gap-3">
                                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-xs font-bold text-indigo-700">1</span>
                                <p class="pt-1 text-sm leading-5 text-slate-600">{{ __('Keep each question focused on one thing.') }}</p>
                            </li>
                            <li class="flex gap-3">
                                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-violet-50 text-xs font-bold text-violet-700">2</span>
                                <p class="pt-1 text-sm leading-5 text-slate-600">{{ __('Use choice questions when answers should be consistent.') }}</p>
                            </li>
                            <li class="flex gap-3">
                                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-cyan-50 text-xs font-bold text-cyan-700">3</span>
                                <p class="pt-1 text-sm leading-5 text-slate-600">{{ __('Only mark questions required when you need an answer.') }}</p>
                            </li>
                        </ul>
                    </section>

                    <div class="flex items-start gap-3 rounded-2xl border border-indigo-100 bg-indigo-50/70 p-4">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-indigo-600" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <circle cx="10" cy="10" r="7" stroke="currentColor" stroke-width="1.5" />
                            <path d="M10 9v4m0-6h.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        <p class="text-xs leading-5 text-indigo-900">{{ __('Responses are kept pending until respondents verify their email address.') }}</p>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const list = document.getElementById('question-list');
            const template = document.getElementById('question-template');
            let nextIndex = {{ count($builderQuestions) }};

            const refreshQuestions = () => {
                list.querySelectorAll('.question-card').forEach((card, index) => {
                    card.querySelector('.question-label').textContent = index + 1;
                });
            };

            const updateOptionsVisibility = (card) => {
                const type = card.querySelector('.question-type').value;
                card.querySelector('.choice-options').classList.toggle('hidden', !['select', 'radio', 'checkbox'].includes(type));
            };

            document.getElementById('add-question').addEventListener('click', () => {
                const html = template.innerHTML.replaceAll('__INDEX__', String(nextIndex++));
                list.insertAdjacentHTML('beforeend', html);
                window.Alpine.initTree(list.lastElementChild);
                refreshQuestions();
            });

            list.addEventListener('click', (event) => {
                if (event.target.closest('.remove-question')) {
                    const cards = list.querySelectorAll('.question-card');
                    if (cards.length > 1) {
                        event.target.closest('.question-card').remove();
                        refreshQuestions();
                    }
                }
            });

            list.addEventListener('change', (event) => {
                if (event.target.matches('.question-type')) {
                    updateOptionsVisibility(event.target.closest('.question-card'));
                }
            });
        })();
    </script>
</x-app-layout>
