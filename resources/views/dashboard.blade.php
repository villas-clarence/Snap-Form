<x-app-layout>
    @php
        $activeFormsCount = $forms->filter(fn ($form) => ! $form->archived_at && $form->is_active)->count();
        $archivedFormsCount = $forms->filter(fn ($form) => $form->archived_at)->count();
        $verifiedResponsesCount = $forms->sum('verified_submissions_count');
    @endphp

    <div class="min-h-[calc(100vh-4rem)] bg-slate-50 py-8 sm:py-12">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div role="status" class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800 shadow-sm">
                    <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <path d="m5 10 3.2 3.2L15.5 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <section class="relative overflow-hidden rounded-[2rem] bg-slate-950 px-6 py-8 text-white shadow-xl shadow-slate-900/10 sm:px-10 sm:py-10 lg:px-12">
                <div class="absolute -right-20 -top-32 h-80 w-80 rounded-full bg-indigo-500/20 blur-3xl"></div>
                <div class="absolute -bottom-40 right-1/3 h-72 w-72 rounded-full bg-cyan-400/10 blur-3xl"></div>
                <div class="relative flex flex-col gap-7 sm:flex-row sm:items-end sm:justify-between">
                    <div class="max-w-2xl">
                        <p class="mb-3 text-sm font-semibold uppercase tracking-[0.16em] text-cyan-300">{{ __('Your workspace') }}</p>
                        <h1 class="text-3xl font-semibold tracking-tight sm:text-4xl">{{ __('Good to see you, :name', ['name' => auth()->user()->name]) }}</h1>
                        <p class="mt-3 max-w-xl text-sm leading-6 text-slate-300 sm:text-base">{{ __('Create forms, share them with your network, and keep track of every verified response.') }}</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <button type="button" data-theme-toggle aria-pressed="false" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-slate-950">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M20.2 15.1A8.5 8.5 0 0 1 8.9 3.8 8.5 8.5 0 1 0 20.2 15.1Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span data-theme-toggle-label>Dark mode</span>
                        </button>
                        <a href="{{ route('forms.create') }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-cyan-200/70 bg-cyan-400 px-5 py-3 text-sm font-bold text-slate-950 shadow-lg shadow-cyan-400/30 transition hover:border-cyan-100 hover:bg-cyan-300 hover:shadow-cyan-300/40 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-slate-950">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <path d="M10 4v12m6-6H4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                            </svg>
                            {{ __('Create a form') }}
                        </a>
                    </div>
                </div>
            </section>

            <section aria-label="{{ __('Workspace overview') }}" class="grid gap-4 sm:grid-cols-3">
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex items-center justify-between">
                        <p class="text-base font-extrabold text-slate-950">{{ __('Total forms') }}</p>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <path d="M5.5 3.75h6l3 3v9.5H5.5a1 1 0 0 1-1-1v-10a1.5 1.5 0 0 1 1-1.5Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                                <path d="M11.5 3.75v3h3m-7 3h4m-4 3h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </div>
                    <p class="mt-4 text-3xl font-semibold tracking-tight text-slate-950">{{ $forms->count() }}</p>
                    <p class="mt-1 text-xs text-slate-500">{{ __('Across your workspace') }}</p>
                </article>

                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex items-center justify-between">
                        <p class="text-base font-extrabold text-slate-950">{{ __('Accepting responses') }}</p>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <circle cx="10" cy="10" r="6.25" stroke="currentColor" stroke-width="1.5" />
                                <path d="M7.5 10.1 9.2 11.8l3.5-3.6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </div>
                    <p class="mt-4 text-3xl font-semibold tracking-tight text-slate-950">{{ $activeFormsCount }}</p>
                    <p class="mt-1 text-xs text-slate-500">{{ __('Live and ready to share') }}</p>
                </article>

                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex items-center justify-between">
                        <p class="text-base font-extrabold text-slate-950">{{ __('Verified responses') }}</p>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-50 text-cyan-700">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <path d="M4.25 15.75V10m5.75 5.75V4.25m5.75 11.5V7.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
                            </svg>
                        </span>
                    </div>
                    <p class="mt-4 text-3xl font-semibold tracking-tight text-slate-950">{{ $verifiedResponsesCount }}</p>
                    <p class="mt-1 text-xs text-slate-500">{{ __('Collected across all forms') }}</p>
                </article>
            </section>

            <section x-data="{ search: '', filter: 'all' }" class="space-y-5">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-slate-950">{{ __('Your forms') }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ __('Manage forms and keep an eye on incoming responses.') }}</p>
                    </div>
                    @if ($forms->isNotEmpty())
                        <label class="relative block w-full sm:max-w-xs">
                            <span class="sr-only">{{ __('Search forms') }}</span>
                            <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <circle cx="8.75" cy="8.75" r="5.75" stroke="currentColor" stroke-width="1.5" />
                                <path d="m13 13 3.5 3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            <input x-model="search" type="search" placeholder="{{ __('Search your forms...') }}" class="w-full rounded-xl border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500">
                        </label>
                    @endif
                </div>

                @if ($forms->isNotEmpty())
                    <div class="flex flex-wrap gap-2" role="group" aria-label="{{ __('Filter forms') }}">
                        <button type="button" x-on:click="filter = 'all'" x-bind:aria-pressed="filter === 'all'" x-bind:class="filter === 'all' ? 'border-cyan-200 bg-cyan-400 text-slate-950' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-100'" class="rounded-full border px-4 py-2 text-xs font-semibold transition focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-2">
                            {{ __('All') }} <span class="ms-1 opacity-75">{{ $forms->count() }}</span>
                        </button>
                        <button type="button" x-on:click="filter = 'active'" x-bind:aria-pressed="filter === 'active'" x-bind:class="filter === 'active' ? 'border-cyan-200 bg-cyan-400 text-slate-950' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-100'" class="rounded-full border px-4 py-2 text-xs font-semibold transition focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-2">
                            {{ __('Active') }} <span class="ms-1 opacity-75">{{ $activeFormsCount }}</span>
                        </button>
                        <button type="button" x-on:click="filter = 'archived'" x-bind:aria-pressed="filter === 'archived'" x-bind:class="filter === 'archived' ? 'border-cyan-200 bg-cyan-400 text-slate-950' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-100'" class="rounded-full border px-4 py-2 text-xs font-semibold transition focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-2">
                            {{ __('Archived') }} <span class="ms-1 opacity-75">{{ $archivedFormsCount }}</span>
                        </button>
                    </div>

                    <div class="grid gap-4 lg:grid-cols-2">
                        @foreach ($forms as $form)
                            @php
                                $formStatus = $form->archived_at ? 'archived' : ($form->is_active ? 'active' : 'closed');
                                $formSearchText = mb_strtolower($form->title.' '.$form->description);
                            @endphp
                            <article
                                x-show="(filter === 'all' || filter === $el.dataset.status) && $el.dataset.search.includes(search.trim().toLowerCase())"
                                x-cloak
                                data-status="{{ $formStatus }}"
                                data-search="{{ $formSearchText }}"
                                class="group flex min-w-0 flex-col justify-between rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:shadow-lg hover:shadow-slate-900/5 sm:p-6"
                            >
                                <div>
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="min-w-0">
                                            <h3 class="truncate text-base font-semibold text-slate-950">{{ $form->title }}</h3>
                                            <p class="mt-2 line-clamp-2 min-h-10 text-sm leading-5 text-slate-500">{{ $form->description ?: __('No description added') }}</p>
                                        </div>
                                        <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold {{ $form->archived_at ? 'bg-slate-100 text-slate-600' : ($form->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700') }}">
                                            <span class="h-1.5 w-1.5 rounded-full {{ $form->archived_at ? 'bg-slate-400' : ($form->is_active ? 'bg-emerald-500' : 'bg-amber-500') }}"></span>
                                            {{ $form->archived_at ? __('Archived') : ($form->is_active ? __('Active') : __('Closed')) }}
                                        </span>
                                    </div>

                                    <div class="mt-5 flex flex-wrap items-center gap-x-5 gap-y-2 border-t border-slate-100 pt-4 text-xs text-slate-500">
                                        <span class="inline-flex items-center gap-1.5">
                                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                                <path d="M10 10a2.25 2.25 0 1 0 0-4.5A2.25 2.25 0 0 0 10 10Zm-4.5 5.25a4.5 4.5 0 0 1 9 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                            </svg>
                                            <span class="font-semibold text-slate-700">{{ $form->verified_submissions_count }}</span>
                                            {{ __('verified :responses', ['responses' => Str::plural('response', $form->verified_submissions_count)]) }}
                                        </span>
                                        <span class="inline-flex items-center gap-1.5">
                                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                                <circle cx="10" cy="10" r="6.25" stroke="currentColor" stroke-width="1.5" />
                                                <path d="M10 6.5v3.8l2.4 1.4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            {{ __('Created :date', ['date' => $form->created_at->format('M j, Y')]) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-5 flex flex-wrap items-center gap-2">
                                    <a href="{{ route('forms.show', $form) }}" class="inline-flex items-center justify-center rounded-lg bg-slate-950 px-3.5 py-2.5 text-xs font-semibold text-white transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                                        {{ __('Manage form') }}
                                    </a>
                                    @if (! $form->archived_at)
                                        <a href="{{ route('public.show', $form->slug) }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 px-3.5 py-2.5 text-xs font-semibold text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                            {{ __('Open form') }}
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                                <path d="M11.5 4.5h4v4m-.5-3.5-6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M14.5 10.5v4a1 1 0 0 1-1 1h-8a1 1 0 0 1-1-1v-8a1 1 0 0 1 1-1h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>

                @else
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center shadow-sm">
                        <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600">
                            <svg class="h-7 w-7" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <path d="M5.5 3.75h6l3 3v9.5H5.5a1 1 0 0 1-1-1v-10a1.5 1.5 0 0 1 1-1.5Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                                <path d="M11.5 3.75v3h3m-4.5 2v5m-2.5-2.5h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <h3 class="mt-5 text-lg font-semibold text-slate-950">{{ __('Your first form starts here') }}</h3>
                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">{{ __('Build a form, share it on your local network, and collect verified responses. Your forms will be easy to manage from this dashboard.') }}</p>
                        <a href="{{ route('forms.create') }}" class="mt-6 inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-600/20 transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <path d="M10 4v12m6-6H4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                            </svg>
                            {{ __('Create your first form') }}
                        </a>
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
