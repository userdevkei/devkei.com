<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="{{ route('home') }}" aria-label="DevKei Solutions home">
            @include('partials.logo')
            <span>DevKei</span>
        </a>
        <button class="nav-toggle" type="button" data-nav-toggle aria-expanded="false" aria-controls="site-nav">Menu</button>
        <div class="site-nav" id="site-nav">
            <nav class="nav" aria-label="Main">
                <a href="{{ route('products.index') }}" @if(request()->routeIs('products.*')) aria-current="page" @endif>Products</a>
                <a href="{{ route('home') }}#solutions">Solutions</a>
                <a href="{{ route('about') }}" @if(request()->routeIs('about')) aria-current="page" @endif>About</a>
                <a href="{{ route('contact') }}" @if(request()->routeIs('contact')) aria-current="page" @endif>Contact</a>
            </nav>
            <div class="header-cta">
                <a class="btn btn-outline btn-sm" href="{{ route('quote.create') }}">Request a quote</a>
                <a class="btn btn-primary btn-sm" href="{{ route('demo.create') }}">Book a demo</a>
            </div>
        </div>
    </div>
</header>
