<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800">{{ $form ? 'Edit form' : 'Create a form' }}</h2>
            <a href="{{ $form ? route('forms.show', $form) : route('dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Cancel</a>
        </div>
    </x-slot>

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

    <div class="py-10">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ $form ? route('forms.update', $form) : route('forms.store') }}" class="space-y-6">
                @csrf
                @if ($form)
                    @method('PUT')
                @endif

                <section class="space-y-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div>
                        <x-input-label for="title" value="Form title" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $form?->title)" required maxlength="255" />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="description" value="Description (optional)" />
                        <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $form?->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                </section>

                <div id="question-list" class="space-y-4">
                    @foreach ($builderQuestions as $index => $question)
                        <section class="question-card space-y-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                            @if (! empty($question['id']))
                                <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $question['id'] }}">
                            @endif
                            <div class="flex items-start justify-between gap-4">
                                <h3 class="font-semibold text-gray-900">Question <span class="question-number">{{ $loop->iteration }}</span></h3>
                                <button type="button" class="remove-question text-sm font-medium text-rose-700 hover:text-rose-900">Remove</button>
                            </div>
                            <div>
                                <x-input-label :for="'question-'.$index" value="Question text" />
                                <x-text-input :id="'question-'.$index" :name="'questions['.$index.'][question_text]'" type="text" class="mt-1 block w-full" :value="$question['question_text'] ?? ''" required maxlength="2000" />
                                <x-input-error :messages="$errors->get('questions.'.$index.'.question_text')" class="mt-2" />
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <x-input-label :for="'type-'.$index" value="Answer type" />
                                    <select id="type-{{ $index }}" name="questions[{{ $index }}][type]" class="question-type mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @foreach (['text' => 'Short text', 'textarea' => 'Long text', 'select' => 'Dropdown', 'radio' => 'Single choice', 'checkbox' => 'Multiple choice'] as $type => $label)
                                            <option value="{{ $type }}" @selected(($question['type'] ?? 'text') === $type)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="flex items-end gap-2 pb-2 text-sm text-gray-700">
                                    <input type="checkbox" name="questions[{{ $index }}][is_required]" value="1" @checked((bool) ($question['is_required'] ?? false)) class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    Required question
                                </label>
                            </div>
                            <div class="choice-options @if (! in_array($question['type'] ?? 'text', ['select', 'radio', 'checkbox'], true)) hidden @endif">
                                <x-input-label :for="'options-'.$index" value="Choices (one per line)" />
                                <textarea id="options-{{ $index }}" name="questions[{{ $index }}][options]" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ is_array($question['options'] ?? null) ? implode("\n", $question['options']) : ($question['options'] ?? '') }}</textarea>
                                <x-input-error :messages="$errors->get('questions.'.$index.'.options')" class="mt-2" />
                            </div>
                        </section>
                    @endforeach
                </div>

                <template id="question-template">
                    <section class="question-card space-y-4 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="flex items-start justify-between gap-4">
                            <h3 class="font-semibold text-gray-900">Question <span class="question-number"></span></h3>
                            <button type="button" class="remove-question text-sm font-medium text-rose-700 hover:text-rose-900">Remove</button>
                        </div>
                        <div>
                            <label for="question-__INDEX__" class="block text-sm font-medium text-gray-700">Question text</label>
                            <input id="question-__INDEX__" name="questions[__INDEX__][question_text]" type="text" required maxlength="2000" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="type-__INDEX__" class="block text-sm font-medium text-gray-700">Answer type</label>
                                <select id="type-__INDEX__" name="questions[__INDEX__][type]" class="question-type mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="text">Short text</option>
                                    <option value="textarea">Long text</option>
                                    <option value="select">Dropdown</option>
                                    <option value="radio">Single choice</option>
                                    <option value="checkbox">Multiple choice</option>
                                </select>
                            </div>
                            <label class="flex items-end gap-2 pb-2 text-sm text-gray-700">
                                <input type="checkbox" name="questions[__INDEX__][is_required]" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                Required question
                            </label>
                        </div>
                        <div class="choice-options hidden">
                            <label for="options-__INDEX__" class="block text-sm font-medium text-gray-700">Choices (one per line)</label>
                            <textarea id="options-__INDEX__" name="questions[__INDEX__][options]" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>
                    </section>
                </template>

                <x-input-error :messages="$errors->get('questions')" />
                <div class="flex flex-wrap justify-between gap-3">
                    <button id="add-question" type="button" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Add question</button>
                    <button type="submit" class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">{{ $form ? 'Save changes' : 'Create form' }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (() => {
            const list = document.getElementById('question-list');
            const template = document.getElementById('question-template');
            let nextIndex = {{ count($builderQuestions) }};

            const refreshQuestions = () => {
                list.querySelectorAll('.question-card').forEach((card, index) => {
                    card.querySelector('.question-number').textContent = index + 1;
                });
            };

            const updateOptionsVisibility = (card) => {
                const type = card.querySelector('.question-type').value;
                card.querySelector('.choice-options').classList.toggle('hidden', !['select', 'radio', 'checkbox'].includes(type));
            };

            document.getElementById('add-question').addEventListener('click', () => {
                const html = template.innerHTML.replaceAll('__INDEX__', String(nextIndex++));
                list.insertAdjacentHTML('beforeend', html);
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
