<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laravel\Ai\Embeddings;
use Laravel\Ai\Enums\Lab;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));

        $blogs = Blog::query()
            ->published()
            ->when(
                $search !== '',
                fn ($query) => $query->whereVectorSimilarTo(
                    'embedding',
                    $this->embeddingFor($search),
                    minSimilarity: 0.45,
                ),
                fn ($query) => $query->latest('published_at')
            )
            ->paginate(10)
            ->withQueryString();

        return view('blogs.index', compact('blogs', 'search'));
    }

    public function create(): View
    {
        return view('blogs.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string', 'min:50'],
        ]);

        $blog = new Blog($validated);
        $blog->slug = Blog::uniqueSlug($validated['title']);
        $blog->published_at = now();

        $blog->embedding = $this->embeddingFor(
            implode("\n\n", array_filter([
                $blog->title,
                $blog->excerpt,
                $blog->content,
            ]))
        );

        $blog->save();

        return redirect()
            ->route('blogs.show', $blog)
            ->with('success', 'Blog post created with semantic embedding.');
    }

    public function show(Blog $blog): View
    {
        abort_unless($blog->published_at, 404);

        return view('blogs.show', compact('blog'));
    }

    private function embeddingFor(string $text): array
    {
        return Embeddings::for([$text])
            ->dimensions(1024)
            ->cache()
            ->generate(
                Lab::Ollama,
                config('ai.default_embedding_model'),
            )
            ->embeddings[0];
    }
}