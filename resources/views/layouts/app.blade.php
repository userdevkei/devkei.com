<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        $seoTitle = trim($__env->yieldContent('title')) ?: 'DevKei Solutions | Software for schools, retail and finance teams in Kenya';
        $seoDesc  = trim($__env->yieldContent('description')) ?: 'DevKei Solutions builds school management, HR, POS, stock and accounting systems for Kenyan institutions and businesses, with custom development and integrations.';
        $seoImage = trim($__env->yieldContent('image')) ?: asset('images/og-default.png'); // 1200x630
        $seoCanon = url()->current();
    @endphp

    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDesc }}">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="canonical" href="{{ $seoCanon }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="DevKei Solutions">
    <meta property="og:locale" content="en_KE">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDesc }}">
    <meta property="og:url" content="{{ $seoCanon }}">
    <meta property="og:image" content="{{ $seoImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDesc }}">
    <meta name="twitter:image" content="{{ $seoImage }}">

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&family=Young+Serif&display=swap">
    <link rel="stylesheet" href="{{ asset('css/devkei.css') }}?v=1">
    @stack('head')

    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@graph' => [[
                '@type' => 'Organization',
                '@id' => 'https://devkei.com/#org',
                'name' => 'DevKei Solutions',
                'alternateName' => ['DevKei', 'Devkei Solutions Kenya'],
                'url' => 'https://devkei.com',
                'logo' => asset('images/logo.png'),
                'description' => 'Software development and systems integration company in Kenya: school management, HR, POS, stock and accounting systems.',
                'email' => 'kitilionesmus27@gmail.com',
                'telephone' => '+254729434393',
                'areaServed' => 'KE',
                'address' => ['@type' => 'PostalAddress', 'addressLocality' => 'Kitengela', 'addressCountry' => 'KE'],
                'sameAs' => [
                    // REPLACE with real profiles; delete any you don't have
                    'https://www.linkedin.com/company/YOUR-PAGE',
                    'https://github.com/YOUR-ORG',
                    'https://www.facebook.com/YOUR-PAGE',
                ],
            ], [
                '@type' => 'WebSite',
                '@id' => 'https://devkei.com/#website',
                'url' => 'https://devkei.com',
                'name' => 'DevKei Solutions',
                'publisher' => ['@id' => 'https://devkei.com/#org'],
            ]],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    @stack('jsonld')
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
