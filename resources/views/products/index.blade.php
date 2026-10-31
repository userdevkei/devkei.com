@extends('layouts.app')

@section('title', 'Products | DevKei Solutions')
@section('description', 'School management, HR, POS, stock and accounting systems from DevKei Solutions.')

@section('content')
<section class="page-head">
    <div class="container">
        <nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Home</a> / Products</nav>
        <h1>Products</h1>
        <p>Systems for schools, retail and finance teams. Pick one to see what it covers.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        @forelse($products->groupBy('category') as $category => $group)
            <div class="catalog-group">
                <h2>{{ ucfirst(str_replace('_', ' ', $category)) }}</h2>
                <ul class="product-list">
                    @foreach($group as $product)
                    <li>
                        <a class="product-row" href="{{ route('products.show', $product) }}">
                            <h3>{{ $product->name }}</h3>
                            <span class="row-cta">View product</span>
                            <p>{{ $product->summary ?: $product->tagline }}</p>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        @empty
            <p class="empty">No products have been published yet.</p>
        @endforelse
    </div>
</section>
@endsection
