<div
    data-type-picker
    x-data="{
        open: false,
        selected: '{{ $type }}',
        options: [
            { value: 'text', label: 'Short answer' },
            { value: 'textarea', label: 'Paragraph' },
            { value: 'radio', label: 'Multiple choice' },
            { value: 'checkbox', label: 'Checkboxes' },
            { value: 'select', label: 'Dropdown' },
        ],
    }"
    x-init="selected = $refs.input.value"
    x-effect="$el.closest('.question-card').classList.toggle('z-20', open)"
    x-on:click.outside="open = false"
    x-on:keydown.escape.stop="open = false; $refs.trigger.focus()"
    class="relative"
>
    <select id="{{ $id }}" name="{{ $name }}" x-ref="input" class="question-type sr-only" tabindex="-1" aria-hidden="true">
        <option value="text" @selected($type === 'text')>Short answer</option>
        <option value="textarea" @selected($type === 'textarea')>Paragraph</option>
        <option value="radio" @selected($type === 'radio')>Multiple choice</option>
        <option value="checkbox" @selected($type === 'checkbox')>Checkboxes</option>
        <option value="select" @selected($type === 'select')>Dropdown</option>
    </select>

    <button
        x-ref="trigger"
        type="button"
        x-on:click="open = !open"
        x-bind:aria-expanded="open"
        aria-haspopup="listbox"
        aria-label="Answer type"
        aria-controls="answer-type-options-{{ $id }}"
        class="flex min-h-[3.1rem] w-full items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-left text-sm text-slate-800 shadow-sm transition hover:border-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
    >
        <span class="flex min-w-0 items-center gap-3">
            <span class="flex h-7 w-7 shrink-0 items-center justify-center text-slate-500">
                <svg x-show="selected === 'text'" class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M3.5 5h3m-3 5h3m-3 5h3m2-10h8m-8 5h8m-8 5h8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" /></svg>
                <svg x-show="selected === 'textarea'" class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M3.5 4h3m-3 4h3m-3 4h3m-3 4h3m2-12h8m-8 4h8m-8 4h8m-8 4h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" /></svg>
                <svg x-show="selected === 'radio'" class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2.2" /><circle cx="12" cy="12" r="4.3" fill="currentColor" /></svg>
                <svg x-show="selected === 'checkbox'" class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true"><rect x="2.75" y="2.75" width="14.5" height="14.5" rx="2" stroke="currentColor" stroke-width="1.8" /><path d="m5.5 10 3 3 6-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>
                <svg x-show="selected === 'select'" class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" /><path d="m8.5 10 3.5 3.5 3.5-3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
            </span>
            <span x-text="options.find(option => option.value === selected)?.label"></span>
        </span>
        <svg class="h-4 w-4 shrink-0 text-slate-500 transition" x-bind:class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="none" aria-hidden="true">
            <path d="m5 7.5 5 5 5-5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </button>

    <div
        id="answer-type-options-{{ $id }}"
        x-cloak
        x-show="open"
        x-transition.origin.top
        role="listbox"
        aria-label="Answer type"
        class="absolute left-0 right-0 top-full z-30 mt-2 overflow-hidden rounded-xl border border-slate-200 bg-white py-1.5 shadow-xl shadow-slate-900/15"
    >
        <template x-for="option in options" :key="option.value">
            <button
                type="button"
                role="option"
                x-bind:aria-selected="selected === option.value"
                x-on:click="$refs.input.value = option.value; selected = option.value; open = false; $refs.input.dispatchEvent(new Event('change', { bubbles: true })); $refs.trigger.focus()"
                x-bind:class="selected === option.value ? 'bg-indigo-50 text-indigo-900' : 'text-slate-700 hover:bg-slate-50'"
                class="flex w-full items-center gap-3 px-4 py-3 text-left text-sm transition focus:outline-none focus:bg-indigo-50"
            >
                <span class="flex h-7 w-7 shrink-0 items-center justify-center text-slate-500">
                    <svg x-show="option.value === 'text'" class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M3.5 5h3m-3 5h3m-3 5h3m2-10h8m-8 5h8m-8 5h8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" /></svg>
                    <svg x-show="option.value === 'textarea'" class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M3.5 4h3m-3 4h3m-3 4h3m-3 4h3m2-12h8m-8 4h8m-8 4h8m-8 4h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" /></svg>
                    <svg x-show="option.value === 'radio'" class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2.2" /><circle cx="12" cy="12" r="4.3" fill="currentColor" /></svg>
                    <svg x-show="option.value === 'checkbox'" class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true"><rect x="2.75" y="2.75" width="14.5" height="14.5" rx="2" stroke="currentColor" stroke-width="1.8" /><path d="m5.5 10 3 3 6-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>
                    <svg x-show="option.value === 'select'" class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" /><path d="m8.5 10 3.5 3.5 3.5-3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                </span>
                <span x-text="option.label"></span>
            </button>
        </template>
    </div>
</div>
