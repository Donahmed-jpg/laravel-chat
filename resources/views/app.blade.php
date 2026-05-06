<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    {{-- Inertia puts the page title here --}}
    <title inertia>{{ config('app.name') }}</title>

    {{-- Vite handles all assets --}}
    @viteReactRefresh
    @vite(['resources/js/app.jsx', 'resources/css/app.css'])

    {{-- Inertia head tags (meta, og tags etc from pages) --}}
    @inertiaHead
</head>
<body class="antialiased bg-gray-50">
    {{-- This is where every React page mounts --}}
    @inertia
</body>
</html>