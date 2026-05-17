@extends('layouts.app')

@section('content')


    <article class="bg-white border rounded-xl p-8">
        <h1 class="text-4xl font-bold mb-3">
            {{ $blog->title }}
        </h1>

        <p class="text-gray-500 mb-8">
            Published {{ $blog->published_at->format('M d, Y') }}
        </p>

        @if($blog->excerpt)
            <p class="text-xl text-gray-700 mb-8">
                {{ $blog->excerpt }}
            </p>
        @endif

        <div class="prose max-w-none whitespace-pre-line">
            {{ $blog->content }}
        </div>
    </article>

@endsection