@extends('layouts.app')

@section('title', 'Book a demo | DevKei Solutions')
@section('description', 'Book a walkthrough of a DevKei Solutions system and see it against how your organisation works.')

@section('content')
<section class="page-head">
    <div class="container">
        <nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Home</a> / Book a demo</nav>
        <h1>Book a demo</h1>
        <p>Tell us who you are and when suits you. We will confirm the slot by email or phone.</p>
    </div>
</section>

<section class="section">
    <div class="container form-wrap">
        <form class="form" method="POST" action="{{ route('demo.store') }}" data-ajax novalidate>
            @csrf
            <div data-status role="status" aria-live="polite">
                @if(session('success'))<p class="alert alert-success">{{ session('success') }}</p>@endif
            </div>
            <div class="form-grid">
                @include('leads._contact-fields')
                <div class="field" data-field="preferred_date">
                    <label for="preferred_date">Preferred date</label>
                    <input id="preferred_date" name="preferred_date" type="date" min="{{ now()->toDateString() }}" value="{{ old('preferred_date') }}">
                    @error('preferred_date')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div class="field" data-field="preferred_time">
                    <label for="preferred_time">Preferred time</label>
                    <select id="preferred_time" name="preferred_time">
                        <option value="">Any time</option>
                        @foreach(['Morning','Afternoon','Evening'] as $slot)
                            <option value="{{ $slot }}" @selected(old('preferred_time') === $slot)>{{ $slot }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field field-full" data-field="message">
                    <label for="message">What would you like to see?</label>
                    <textarea id="message" name="message" placeholder="For example: fee statements and M-Pesa reconciliation for 1,200 students.">{{ old('message') }}</textarea>
                    @error('message')<p class="error">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Request demo</button>
                <small>We only use your details to arrange the demo.</small>
            </div>
        </form>

        <aside class="aside-card">
            <h3>What happens next</h3>
            <ol class="next-steps">
                <li>We confirm a time that suits you.</li>
                <li>We walk through the system using your type of organisation as the example.</li>
                <li>You get a written summary and can request a quote.</li>
            </ol>
        </aside>
    </div>
</section>
@endsection
