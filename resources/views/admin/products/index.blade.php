@extends('admin.layout')
@section('title', 'Products')
@section('heading', 'Products')
@section('subtitle', 'What appears on the public website, and in what order.')

@section('actions')
    <a class="btn btn-primary btn-sm" href="{{ route('admin.products.create') }}"><x-admin.icon name="plus" :size="16"/> Add product</a>
@endsection

@section('content')
@if($products->isEmpty())
    <p class="empty">No products yet. Add the first one, or run the ProductSeeder.</p>
@else
<section class="card card-table">
    <table class="dt" style="width:100%"
        data-config="{{ json_encode(['order' => [[0, 'asc']], 'export' => false, 'noSort' => [4], 'pageLength' => 25]) }}">
        <thead><tr><th>Order</th><th>Product</th><th>Category</th><th>Visibility</th><th></th></tr></thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{ $product->sort_order }}</td>
                <td><b>{{ $product->name }}</b><small>/products/{{ $product->slug }}</small></td>
                <td>{{ ucfirst(str_replace('_', ' ', $product->category)) }}</td>
                <td>
                    <span class="badge {{ $product->is_active ? 'badge-green' : 'badge-gray' }}">{{ $product->is_active ? 'Live' : 'Hidden' }}</span>
                    @if($product->is_featured)<span class="badge badge-blue">Featured</span>@endif
                </td>
                <td class="cell-actions">
                    @if($product->is_active)<a class="icon-btn" href="{{ route('products.show', $product) }}" target="_blank" rel="noopener" title="View on website" aria-label="View on website"><x-admin.icon name="external" :size="16"/></a>@endif
                    <a class="icon-btn" href="{{ route('admin.products.edit', $product) }}" title="Edit" aria-label="Edit {{ $product->name }}"><x-admin.icon name="edit" :size="16"/></a>
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                          data-confirm="{{ json_encode(['title' => 'Delete ' . $product->name . '?', 'text' => 'Requests that mention this product are kept.']) }}">
                        @csrf @method('DELETE')
                        <button class="icon-btn icon-btn-danger" type="submit" title="Delete" aria-label="Delete {{ $product->name }}"><x-admin.icon name="trash" :size="16"/></button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</section>
@endif
@endsection
