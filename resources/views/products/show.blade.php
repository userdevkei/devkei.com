@extends('layouts.app')

@section('title', $product->name . ' | DevKei Solutions')
@section('description', $product->tagline ?: \Illuminate\Support\Str::limit(strip_tags((string) $product->summary), 155))

@section('content')
<section class="page-head">
    <div class="container">
        <nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Home</a> / <a href="{{ route('products.index') }}">Products</a> / {{ $product->name }}</nav>
        <h1>{{ $product->name }}</h1>
        @if($product->tagline)<p>{{ $product->tagline }}</p>@endif
    </div>
</section>

<section class="section">
    <div class="container detail">
        <div>
            @if($product->summary)
                <h2>Overview</h2>
                <p>{{ $product->summary }}</p>
            @endif

            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }} screenshot" loading="lazy" style="border:1px solid var(--line);border-radius:10px;margin-top:1rem">
            @endif

            @if($product->description)
                @foreach(preg_split("/\n{2,}/", trim($product->description)) as $para)
                    <p>{{ $para }}</p>
                @endforeach
            @endif

            @if($product->features)
                <h2>What it does</h2>
                <ul class="check-list">
                    @foreach($product->features as $feature)<li>{{ $feature }}</li>@endforeach
                </ul>
            @endif

            @if($product->modules)
                <h2>Modules</h2>
                <ul class="chips">
                    @foreach($product->modules as $module)<li>{{ $module }}</li>@endforeach
                </ul>
            @endif
        </div>

        <aside class="aside-card">
            <h3>Interested in {{ $product->name }}?</h3>
            <p>See it working, or tell us your requirements and we will send a written quote.</p>
            <a class="btn btn-primary" href="{{ route('demo.create', ['product' => $product->id]) }}">Book a demo</a>
            <a class="btn btn-outline" href="{{ route('quote.create', ['product' => $product->id]) }}">Request a quote</a>
        </aside>
    </div>
</section>
@endsection
