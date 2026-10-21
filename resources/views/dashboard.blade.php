<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Your forms') }}</h2>
            <a href="{{ route('forms.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Create form</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-5 px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('status') }}</div>
            @endif

            @forelse ($forms as $form)
                <article class="flex flex-col gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-semibold text-gray-900">{{ $form->title }}</h3>
                            @if ($form->archived_at)
                                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">Archived</span>
                            @elseif (! $form->is_active)
                                <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-800">Closed</span>
                            @endif
                        </div>
                        <p class="mt-1 text-sm text-gray-600">{{ $form->description ?: 'No description' }}</p>
                        <p class="mt-2 text-xs text-gray-500">{{ $form->verified_submissions_count }} verified {{ Str::plural('response', $form->verified_submissions_count) }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('forms.show', $form) }}" class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Manage</a>
                        @if (! $form->archived_at)
                            <a href="{{ route('public.show', $form->slug) }}" target="_blank" rel="noopener" class="rounded-lg border border-indigo-200 px-3 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-50">Open public form</a>
                        @endif
                    </div>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-gray-300 bg-white px-6 py-14 text-center">
                    <h3 class="text-lg font-semibold text-gray-900">Create your first form</h3>
                    <p class="mt-2 text-sm text-gray-600">Build a form, share it on your local network, and collect verified responses.</p>
                    <a href="{{ route('forms.create') }}" class="mt-5 inline-flex rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Create a form</a>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
