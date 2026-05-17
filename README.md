# Laravel Blog Semantic Search

A Laravel blog application with **semantic search** powered by:

- **Laravel**
- **PostgreSQL + pgvector**
- **Ollama embeddings**
- **Redis** for cache, sessions, and queues
- **Docker Compose** for local development

Users can create blog posts and search them using vector similarity rather than only exact keyword matching.

---

## Features

- Create and view published blog posts
- Generate embeddings for blog content on creation
- Semantic search across blog posts
- PostgreSQL vector storage via `pgvector`
- Redis-backed cache, sessions, and queues
- Dockerized local development environment
- Mail testing with MailHog
- Database inspection with Adminer

---

## Tech Stack

| Component | Purpose |
|---|---|
| Laravel | Application framework |
| PHP 8.4 FPM | Runtime |
| PostgreSQL 17 + pgvector | Relational database and vector search |
| Redis 7 | Cache, queues, sessions |
| Ollama | Local embedding generation |
| `mxbai-embed-large` | Embedding model |
| Nginx | Web server |
| MailHog | Local email inbox |
| Adminer | Database UI |

---

## How Semantic Search Works

When a blog post is created, the app generates a vector embedding from:

- title
- excerpt
- content

The embedding is saved in PostgreSQL and later compared against the search query embedding using vector similarity.

The search flow is:

1. User enters a search phrase.
2. Laravel generates an embedding for the phrase.
3. PostgreSQL finds blog embeddings with similar meaning.
4. Results are returned by semantic relevance.

---

## Requirements

Before starting, install:

- Docker
- Docker Compose
- Ollama running on your host machine

The Docker app container expects Ollama to be available at:

```env
OLLAMA_URL=http://host.docker.internal:11434
```

---

## Install the Embedding Model

Pull the embedding model in Ollama:

```bash
ollama pull mxbai-embed-large:latest
```

You can confirm Ollama is running with:

```bash
ollama list
```

---

## Environment Setup

Create your local environment file:

```bash
cp .env.docker .env
```

The project expects values similar to:

```env
APP_NAME=Laravel
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=laravel_db
DB_USERNAME=user
DB_PASSWORD=user123

CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_CLIENT=phpredis
REDIS_HOST=redis
REDIS_PORT=6379

AI_EMBEDDINGS_PROVIDER=ollama
AI_EMBEDDINGS_MODEL=mxbai-embed-large:latest

OLLAMA_URL=http://host.docker.internal:11434
OLLAMA_API_KEY=
```

> Do not commit a real `.env` file to GitHub. Commit `.env.example` or `.env.docker` instead.

---

## Start the Project

Build and start all services:

```bash
docker compose up --build
```

The app entrypoint will:

1. Install Laravel if missing
2. Copy `.env.docker` to `.env` when needed
3. Install Composer dependencies
4. Fix storage permissions
5. Generate the app key if needed
6. Clear Laravel caches
7. Wait for PostgreSQL
8. Run migrations
9. Start PHP-FPM

---

## Local URLs

| Service | URL |
|---|---|
| Application | http://localhost:8000 |
| MailHog | http://localhost:8025 |
| Adminer | http://localhost:8080 |

---

## Database Access

Adminer can be accessed at:

- **System:** PostgreSQL
- **Server:** `postgres`
- **Username:** `user`
- **Password:** `user123`
- **Database:** `laravel_db`

---

## Routes

| Method | Route | Purpose |
|---|---|---|
| GET | `/` | Redirects to blog listing |
| GET | `/blogs` | List posts and semantic search |
| GET | `/blogs/create` | Show blog creation form |
| POST | `/blogs` | Store a new blog post |
| GET | `/blogs/{blog:slug}` | Show a blog post |

---

## Blog Search Logic

The blog index supports semantic search through the `q` query parameter:

```text
/blogs?q=postgres vector database
```

When a search term exists, the application:

```php
->whereVectorSimilarTo(
    'embedding',
    $this->embeddingFor($search),
    minSimilarity: 0.35,
)
```

When no search term is provided, posts are shown by latest publication date.

---

## Embedding Configuration

Embeddings are generated with:

```php
Embeddings::for([$text])
    ->dimensions(1024)
    ->cache()
    ->generate(
        Lab::Ollama,
        config('ai.default_embedding_model'),
    )
    ->embeddings[0];
```

The current model configuration is:

```env
AI_EMBEDDINGS_PROVIDER=ollama
AI_EMBEDDINGS_MODEL=mxbai-embed-large:latest
```

---

## Background Services

### Queue Worker

A dedicated queue container runs:

```bash
php artisan queue:work --tries=3 --timeout=90
```

### Scheduler

A scheduler container runs:

```bash
php artisan schedule:work
```

---

## Useful Docker Commands

Start containers:

```bash
docker compose up -d
```

Rebuild containers:

```bash
docker compose up --build -d
```

Stop containers:

```bash
docker compose down
```

View logs:

```bash
docker compose logs -f
```

Open a shell in the app container:

```bash
docker compose exec app sh
```

Run Artisan commands:

```bash
docker compose exec app php artisan migrate
docker compose exec app php artisan route:list
```

---

## Recommended Repository Files

Before pushing to GitHub, make sure you include:

```text
README.md
.env.example or .env.docker
docker-compose.yml
Dockerfile
docker-entrypoint.sh
nginx/default.conf
```

And make sure `.gitignore` excludes:

```text
.env
/vendor
/node_modules
```

---

## Security Note

The `.env` content shared during development should not be committed with real secrets. If any real credentials or keys have already been committed, rotate them immediately and remove them from Git history.

---

## Future Improvements

Possible next steps:

- Queue embedding generation instead of doing it inside the request cycle
- Regenerate embeddings when blog content is updated
- Chunk long blog posts before embedding
- Add hybrid keyword + semantic search
- Add tests for blog creation and search behavior

---

## License

This project is open-sourced software. Add your preferred license here.
