@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-5xl">
        <div class="mb-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                        Blog Posts
                    </h1>

                    <p class="mt-2 text-slate-600">
                        Search articles using semantic AI-powered search.
                    </p>
                </div>
            </div>

            <form
                method="GET"
                action="{{ route('blogs.index') }}"
                class="mt-6 flex flex-col gap-3 sm:flex-row"
            >
                <div class="relative flex-1">
                    <svg
                        class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-4.35-4.35m1.6-5.4a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                    </svg>

                    <input
                        type="search"
                        name="q"
                        value="{{ $search }}"
                        placeholder="Search blogs..."
                        class="w-full rounded-xl border border-slate-300 bg-white py-3 pl-12 pr-4 text-slate-900 shadow-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-100"
                    >
                </div>

                <button
                    type="submit"
                    class="rounded-xl bg-slate-900 px-6 py-3 font-medium text-white shadow-sm transition hover:bg-slate-800"
                >
                    Search
                </button>

                @if($search)
                    <a
                        href="{{ route('blogs.index') }}"
                        class="rounded-xl border border-slate-300 px-6 py-3 text-center font-medium text-slate-700 transition hover:bg-slate-50"
                    >
                        Clear
                    </a>
                @endif
            </form>

            @if($search)
                <p class="mt-4 text-sm text-slate-600">
                    Showing semantic results for
                    <span class="font-semibold text-slate-900">“{{ $search }}”</span>
                </p>
            @endif
        </div>

        <div class="space-y-4">
            @forelse($blogs as $blog)
                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-slate-300 hover:shadow-md">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0 flex-1">
                            <h2 class="text-xl font-semibold tracking-tight text-slate-900">
                                <a
                                    href="{{ route('blogs.show', $blog) }}"
                                    class="transition hover:text-slate-600"
                                >
                                    {{ $blog->title }}
                                </a>
                            </h2>

                            @if($blog->excerpt)
                                <p class="mt-2 leading-7 text-slate-600">
                                    {{ $blog->excerpt }}
                                </p>
                            @endif

                            <p class="mt-4 text-sm text-slate-500">
                                Published {{ $blog->published_at->diffForHumans() }}
                            </p>
                        </div>

                        <a
                            href="{{ route('blogs.show', $blog) }}"
                            class="inline-flex shrink-0 items-center justify-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                        >
                            Read
                        </a>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">
                        No blogs found
                    </h2>

                    <p class="mt-2 text-slate-600">
                        Try searching with a different phrase.
                    </p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $blogs->links() }}
        </div>
    </div>
@endsection