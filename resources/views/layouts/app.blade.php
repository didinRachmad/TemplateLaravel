<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('icons/icon.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-sans antialiased" data-page="{{ $page ?? 'default' }}" data-action="{{ $action ?? 'index' }}">
    <!-- Loading Screen -->
    <div id="loading-screen">
        <div class="wave">
            <span></span><span></span><span></span><span></span><span></span><span></span><span></span>
        </div>
    </div>

    @include('layouts.navigation')
    <div class="min-h-screen bg-gray-100" style="padding-top: 60px;">

        <!-- Page Heading -->
        {{-- @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif --}}

        <!-- Page Content -->
        <div class="container py-6">
            <main>
                @yield('content')
                {{-- {{ $slot }} --}}
            </main>
        </div>
    </div>
</body>
{{-- @vite(['resources/js/app.js']) --}}

@if (session('success'))
    <script type="module">
        showToast("{{ session('success') }}", "success");
    </script>
@endif

@if (session('error'))
    <script type="module">
        showToast("{{ session('error') }}", "error");
    </script>
@endif

@if ($errors->any())
    <script type="module">
        showToast("Gagal", "error", `{!! join('<br>', $errors->all()) !!}`);
    </script>
@endif


</html>
