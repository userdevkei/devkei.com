<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'DevKei Solutions | Software for schools, retail and finance teams in Kenya')</title>
    <meta name="description" content="@yield('description', 'DevKei Solutions builds school management, HR, POS, stock and accounting systems for Kenyan institutions and businesses, with custom development and integrations.')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="DevKei Solutions">
    <meta property="og:title" content="@yield('title', 'DevKei Solutions')">
    <meta property="og:description" content="@yield('description', 'Management software for schools, retail and finance teams in Kenya.')">
    <meta property="og:url" content="{{ url()->current() }}">

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&family=Young+Serif&display=swap">
    <link rel="stylesheet" href="{{ asset('css/devkei.css') }}?v=1">
    @stack('head')

    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'DevKei Solutions',
        'url' => 'https://devkei.com',
        'email' => 'kitilionesmus27@gmail.com',
        'telephone' => '+254729434393',
        'address' => ['@type' => 'PostalAddress', 'addressLocality' => 'Kitengela', 'addressCountry' => 'KE'],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
</head>
<body>
    <a class="skip-link" href="#main">Skip to content</a>
    @include('partials.header')
    <main id="main">
        @yield('content')
    </main>
    @include('partials.footer')
    <script src="{{ asset('js/devkei.js') }}?v=1" defer></script>
    @stack('scripts')
</body>
</html>
