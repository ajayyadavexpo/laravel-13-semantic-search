<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;
use Laravel\Ai\Embeddings;
use Laravel\Ai\Enums\Lab;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Getting Started with Laravel Queues',
                'excerpt' => 'Master background job processing in Laravel with Redis, database, and SQS drivers.',
                'content' => 'Laravel queues allow you to defer long-running tasks such as sending emails, processing images, generating reports, syncing third-party APIs, and handling webhook payloads. Learn how to set up queue workers, handle failed jobs, implement retry logic, and monitor queue performance using Horizon. Understand the difference between sync, database, redis, and SQS drivers, and when to use each one for optimal performance.',
            ],
            [
                'title' => 'Why Semantic Search is Better Than Keyword Search',
                'excerpt' => 'Discover how vector embeddings and cosine similarity revolutionize search relevance by understanding user intent rather than just matching keywords.',
                'content' => 'Traditional keyword search relies on exact word matching, which fails when users use synonyms, misspellings, or different phrasings. Embedding-based search converts text into high-dimensional vectors that capture semantic meaning. This allows your application to find related content even when the user searches using completely different words. For example, searching "car" can find articles about "automobile," "vehicle," or "transportation." Implement this using PostgreSQL pgvector, Pinecone, or Weaviate databases for real-time similarity search.',
            ],
            [
                'title' => 'Building Clean Controllers in Laravel',
                'excerpt' => 'Best practices for writing maintainable controllers using form requests, action classes, services, and repository patterns.',
                'content' => 'Senior Laravel developers keep controllers thin by following single responsibility principles. Use form requests for validation, services for business logic, action classes for single operations, and repository patterns for database queries. Implement DTOs (Data Transfer Objects) for type-safe data handling. Learn to avoid fat controllers that mix validation, business logic, and database queries. Apply SOLID principles and dependency injection for testable, maintainable code that scales with your application.',
            ],
            [
                'title' => 'PostgreSQL pgvector with Laravel',
                'excerpt' => 'Integrate vector similarity search directly in PostgreSQL using the pgvector extension for AI embeddings and recommendation systems.',
                'content' => 'PostgreSQL pgvector extension allows applications to store embeddings and run similarity search directly in the database using cosine distance, L2 distance, or inner product. Learn to install pgvector, create vector columns, generate embeddings using OpenAI, Ollama, or Hugging Face models, and perform ANN (Approximate Nearest Neighbor) searches using IVFFlat or HNSW indexes. Perfect for building recommendation engines, document search, image similarity, and RAG (Retrieval-Augmented Generation) systems without additional vector databases.',
            ],
            [
                'title' => 'Authentication vs Authorization in Laravel',
                'excerpt' => 'Understand the crucial difference between verifying user identity and controlling access permissions using Laravel Gates, Policies, and Roles.',
                'content' => 'Authentication answers "Who are you?" while authorization answers "What can you do?" Laravel provides multiple tools for both: authentication via Sanctum, Passport, or session-based auth; authorization via Gates for simple checks, Policies for model-based permissions, and middleware for route protection. Implement role-based access control (RBAC) with Spatie Permission package or build custom solutions. Learn to protect API endpoints, validate user actions, and implement fine-grained permissions for multi-tenant applications.',
            ],
            [
                'title' => 'Optimizing MySQL Queries for Laravel Applications',
                'excerpt' => 'Database performance tuning techniques including indexing strategies, eager loading, query optimization, and using EXPLAIN analyzer.',
                'content' => 'Slow database queries are the #1 performance killer in Laravel applications. Master eager loading to solve N+1 problems, use database indexes on frequently queried columns, leverage query scopes for reusable conditions, and implement caching for expensive operations. Learn to analyze query performance using Laravel Debugbar, Clockwork, or EXPLAIN statements. Understand when to use chunk() vs cursor() for large datasets, implement read/write connections for database scaling, and use Eloquent resources for efficient API responses.',
            ],
            [
                'title' => 'Real-time Features with Laravel WebSockets',
                'excerpt' => 'Build live chat, notifications, and real-time dashboards using Laravel WebSockets and Pusher protocol without third-party services.',
                'content' => 'Laravel Broadcasting enables real-time features through WebSockets. Implement private and presence channels for user-specific events, broadcast events to thousands of connected clients, and handle authentication for secure channels. Use Laravel WebSockets package for a pure PHP solution that replaces Pusher. Build live notifications, collaborative editing features, real-time stock tickers, gaming leaderboards, or live comment sections. Learn to scale horizontally using Redis and manage connection limits with the WebSocket dashboard.',
            ],
            [
                'title' => 'Testing Laravel Applications with Pest PHP',
                'excerpt' => 'Write beautiful, expressive tests using Pest PHP framework for unit testing, feature testing, and browser testing.',
                'content' => 'Pest PHP brings simplicity and elegance to testing Laravel applications with its expressive syntax, higher-order tests, and architectural testing capabilities. Learn to write unit tests for individual components, feature tests for HTTP endpoints, database assertions, and mock external services. Implement test-driven development (TDD) workflows, create custom test helpers, use factories for realistic data, and test API responses. Master Laravels testing tools including HTTP tests, console command tests, job and event faking, and browser tests with Laravel Dusk.',
            ],
            [
                'title' => 'Machine Learning Basics for PHP Developers',
                'excerpt' => 'Introduction to ML concepts including regression, classification, clustering, and how to integrate predictions into Laravel applications.',
                'content' => 'PHP developers can leverage machine learning through APIs and libraries like Rubix ML or by calling Python services. Learn core concepts: supervised learning (regression for predictions, classification for categorization), unsupervised learning (clustering for grouping data), and reinforcement learning. Implement recommendation engines for e-commerce, fraud detection for payments, sentiment analysis for reviews, churn prediction for SaaS, or price optimization for marketplaces. Integrate TensorFlow models via TensorFlow PHP, use OpenAI APIs for NLP tasks, or build simple prediction systems using PHP-ML library.',
            ],
            [
                'title' => 'Securing Laravel APIs with JWT and OAuth2',
                'excerpt' => 'Implement stateless authentication and authorization using JSON Web Tokens and OAuth2 providers for robust API security.',
                'content' => 'Modern APIs require stateless authentication mechanisms. JSON Web Tokens (JWT) provide self-contained user claims signed for verification, while OAuth2 enables delegated authorization flows. Implement JWT using tymon/jwt-auth package, set up refresh tokens, blacklist compromised tokens, and validate token scopes. Configure OAuth2 with Laravel Passport for first-party clients, PKCE for mobile apps, client credentials for machine-to-machine communication, and social login providers. Learn token storage best practices, CSRF protection, rate limiting, and API versioning strategies.',
            ],
        ];

        foreach ($posts as $post) {
            // Combine key searchable fields into one embedding input
            $text = "Title: " . $post['title'] . "\n\n" .
                    "Summary: " . $post['excerpt'] . "\n\n" .
                    "Content: " . $post['content'];

            // Generate embedding with specific dimensions
            $response = Embeddings::for([$text])
                ->dimensions(1024)
                ->generate(
                    Lab::Ollama,
                    'mxbai-embed-large:latest'
                );

            $embedding = $response->embeddings[0];

            // Validate embedding dimensions
            if (count($embedding) !== 1024) {
                throw new \RuntimeException(
                    'Expected 1024 embedding dimensions, got ' . count($embedding) . 
                    ' for post: ' . $post['title']
                );
            }

            Blog::create([
                ...$post,
                'slug' => Blog::uniqueSlug($post['title']),
                'embedding' => $embedding,
                'published_at' => now(),
                'views' => rand(0, 5000),
            ]);
        }
    }
}