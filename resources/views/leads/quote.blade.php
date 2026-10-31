@extends('layouts.app')

@section('title', 'Request a quote | DevKei Solutions')
@section('description', 'Describe what you need and get a written quote from DevKei Solutions.')

@section('content')
<section class="page-head">
    <div class="container">
        <nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Home</a> / Request a quote</nav>
        <h1>Request a quote</h1>
        <p>The more detail you give, the more accurate the quote. Rough numbers are fine.</p>
    </div>
</section>

<section class="section">
    <div class="container form-wrap">
        <form class="form" method="POST" action="{{ route('quote.store') }}" data-ajax novalidate>
            @csrf
            <div data-status role="status" aria-live="polite">
                @if(session('success'))<p class="alert alert-success">{{ session('success') }}</p>@endif
            </div>
            <div class="form-grid">
                @include('leads._contact-fields')
                <div class="field" data-field="user_count">
                    <label for="user_count">Number of users</label>
                    <input id="user_count" name="user_count" type="number" min="1" inputmode="numeric" value="{{ old('user_count') }}">
                    <span class="hint">Students, staff or till operators, whichever applies.</span>
                    @error('user_count')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div class="field" data-field="hosting_preference">
                    <label for="hosting_preference">Hosting</label>
                    <select id="hosting_preference" name="hosting_preference">
                        <option value="">Not sure yet</option>
                        <option value="self_host" @selected(old('hosting_preference') === 'self_host')>We host it ourselves</option>
                        <option value="devkei_host" @selected(old('hosting_preference') === 'devkei_host')>DevKei hosts it, one-time licence</option>
                        <option value="lease" @selected(old('hosting_preference') === 'lease')>DevKei hosts it, leased</option>
                    </select>
                    @error('hosting_preference')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div class="field field-full" data-field="requirements">
                    <label for="requirements">What do you need?</label>
                    <textarea id="requirements" name="requirements" required placeholder="Modules you need, systems to integrate with, data to migrate, deadlines.">{{ old('requirements') }}</textarea>
                    @error('requirements')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div class="field field-full" data-field="budget_range">
                    <label for="budget_range">Budget (optional)</label>
                    <input id="budget_range" name="budget_range" type="text" value="{{ old('budget_range') }}" placeholder="For example: KES 300,000 to 500,000">
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Request quote</button>
                <small>We reply within one business day.</small>
            </div>
        </form>

        <aside class="aside-card">
            <h3>Prefer to talk?</h3>
            <ul class="contact-list">
                <li><a href="tel:+254729434393">+254 729 434 393</a></li>
                <li><a href="mailto:kitilionesmus27@gmail.com">kitilionesmus27@gmail.com</a></li>
            </ul>
        </aside>
    </div>
</section>
@endsection
