@extends('layouts.app')

@section('title', 'About | DevKei Solutions')
@section('description', 'DevKei Solutions is a Kenyan software company with 5+ years of experience building school, HR, POS, stock and accounting systems.')

@section('content')
<section class="page-head">
    <div class="container">
        <nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Home</a> / About</nav>
        <h1>A software team that stays after go-live.</h1>
        <p>DevKei Solutions is a Kenyan software company with more than five years of experience building and maintaining systems for schools and businesses.</p>
    </div>
</section>

<section class="section">
    <div class="container detail">
        <div>
            <h2>What we do</h2>
            <p>We design, build and support management software, and we integrate it with the systems an organisation already depends on. Most of our work is on Laravel, which keeps systems maintainable and easy to extend.</p>
            <ul class="check-list">
                <li>School management for universities, colleges, secondary and primary schools</li>
                <li>HR and payroll, point of sale, stock management and accounting systems</li>
                <li>Database-driven websites and web applications</li>
                <li>Software customisation and system integration</li>
            </ul>
            <h2>How we work</h2>
            <p>We start from your processes, not a feature list. Systems are configured to your structure, your records are migrated and checked, and your staff are trained on their real tasks before launch. Afterwards we stay available for fixes, updates and new requirements.</p>
        </div>
        <aside class="aside-card">
            <h3>Talk to us</h3>
            <p>Tell us what you are trying to fix and we will say honestly whether we can help.</p>
            <a class="btn btn-primary" href="{{ route('demo.create') }}">Book a demo</a>
            <a class="btn btn-outline" href="{{ route('quote.create') }}">Request a quote</a>
        </aside>
    </div>
</section>
@endsection
