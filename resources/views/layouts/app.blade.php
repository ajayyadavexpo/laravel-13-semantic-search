<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'AI Blog' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900">

    <nav class="bg-white border-b">
        <div class="max-w-5xl mx-auto px-4 py-4 flex justify-between">
            <a href="{{ route('blogs.index') }}" class="font-bold text-xl">
                AI Blog
            </a>

            <a href="{{ route('blogs.create') }}"
               class="bg-black text-white px-4 py-2 rounded-lg">
                Write Blog
            </a>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 py-8">

        @if(session('success'))
            <div class="mb-6 bg-green-100 text-green-800 p-4 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')

    </main>

</body>
</html>