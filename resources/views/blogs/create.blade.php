@extends('layouts.app')

@section('content')


    <h1 class="text-3xl font-bold mb-6">Write Blog Post</h1>

    <form method="POST" action="{{ route('blogs.store') }}" class="bg-white border rounded-xl p-6 space-y-5">
        @csrf

        <div>
            <label class="font-medium">Title</label>
            <input
                name="title"
                value="{{ old('title') }}"
                class="w-full border rounded-lg px-4 py-3 mt-1"
                required
            >
            @error('title')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="font-medium">Excerpt</label>
            <textarea
                name="excerpt"
                rows="3"
                class="w-full border rounded-lg px-4 py-3 mt-1"
            >{{ old('excerpt') }}</textarea>
            @error('excerpt')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="font-medium">Content</label>
            <textarea
                name="content"
                rows="12"
                class="w-full border rounded-lg px-4 py-3 mt-1"
                required
            >{{ old('content') }}</textarea>
            @error('content')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button class="bg-black text-white px-6 py-3 rounded-lg">
            Publish with Embedding
        </button>
    </form>

    
@endsection