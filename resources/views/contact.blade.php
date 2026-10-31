@extends('layouts.app')

@section('title', 'Contact | DevKei Solutions')
@section('description', 'Contact DevKei Solutions in Kitengela, Kenya by phone or email.')

@section('content')
<section class="page-head">
    <div class="container">
        <nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Home</a> / Contact</nav>
        <h1>Contact</h1>
        <p>Call or email directly, or use a form if you want us to follow up.</p>
    </div>
</section>

<section class="section">
    <div class="container detail">
        <div>
            <h2>Reach us</h2>
            <ul class="contact-list">
                <li>Phone: <a href="tel:+254729434393">+254 729 434 393</a></li>
                <li>Email: <a href="mailto:kitilionesmus27@gmail.com">kitilionesmus27@gmail.com</a></li>
                <li>Location: Kitengela, Kenya</li>
                <li>Web: <a href="https://devkei.com">devkei.com</a></li>
            </ul>
        </div>
        <aside class="aside-card">
            <h3>Prefer a form?</h3>
            <p>These go straight to our team and get a reply.</p>
            <a class="btn btn-primary" href="{{ route('demo.create') }}">Book a demo</a>
            <a class="btn btn-outline" href="{{ route('quote.create') }}">Request a quote</a>
        </aside>
    </div>
</section>
@endsection
