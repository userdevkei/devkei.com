@extends('admin.layout')
@php $editing = $product->exists; @endphp
@section('title', $editing ? 'Edit ' . $product->name : 'Add product')
@section('heading', $editing ? 'Edit product' : 'Add product')
@section('subtitle', $editing ? $product->name : 'Fill in the basics. You can refine the rest later.')

@section('actions')
    <a class="btn btn-outline btn-sm" href="{{ route('admin.products.index') }}">Cancel</a>
    <button class="btn btn-primary btn-sm" type="submit" form="product-form">{{ $editing ? 'Save changes' : 'Create product' }}</button>
@endsection

@section('content')
<form id="product-form" method="POST" enctype="multipart/form-data"
      action="{{ $editing ? route('admin.products.update', $product) : route('admin.products.store') }}">
    @csrf
    @if($editing) @method('PUT') @endif

    <div class="grid-side">
        <div class="stack">
            <section class="card">
                <header class="card-head"><h2>Basics</h2></header>
                <div class="form-grid">
                    <div class="field @error('name') has-error @enderror">
                        <label for="name">Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $product->name) }}" required>
                        @error('name')<p class="error">{{ $message }}</p>@enderror
                    </div>
                    <div class="field @error('slug') has-error @enderror">
                        <label for="slug">URL slug</label>
                        <input id="slug" name="slug" type="text" value="{{ old('slug', $product->slug) }}" placeholder="Created from the name if empty">
                        @error('slug')<p class="error">{{ $message }}</p>@enderror
                    </div>
                    <div class="field field-full @error('tagline') has-error @enderror">
                        <label for="tagline">Tagline</label>
                        <input id="tagline" name="tagline" type="text" maxlength="200" value="{{ old('tagline', $product->tagline) }}">
                        <span class="hint">One line, shown in lists and search results.</span>
                        @error('tagline')<p class="error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </section>

            <section class="card">
                <header class="card-head"><h2>Content</h2></header>
                <div class="form-grid">
                    <div class="field field-full @error('summary') has-error @enderror">
                        <label for="summary">Summary</label>
                        <textarea id="summary" name="summary" rows="3">{{ old('summary', $product->summary) }}</textarea>
                        @error('summary')<p class="error">{{ $message }}</p>@enderror
                    </div>
                    <div class="field field-full @error('description') has-error @enderror">
                        <label for="description">Full description</label>
                        <textarea id="description" name="description" rows="8">{{ old('description', $product->description) }}</textarea>
                        <span class="hint">Leave a blank line between paragraphs.</span>
                        @error('description')<p class="error">{{ $message }}</p>@enderror
                    </div>
                    <div class="field @error('features') has-error @enderror">
                        <label for="features">Features</label>
                        <textarea id="features" name="features" rows="6">{{ old('features', implode("\n", $product->features ?? [])) }}</textarea>
                        <span class="hint">One per line. The first four show on the home page.</span>
                        @error('features')<p class="error">{{ $message }}</p>@enderror
                    </div>
                    <div class="field @error('modules') has-error @enderror">
                        <label for="modules">Modules</label>
                        <textarea id="modules" name="modules" rows="6">{{ old('modules', implode("\n", $product->modules ?? [])) }}</textarea>
                        <span class="hint">One per line.</span>
                        @error('modules')<p class="error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </section>
        </div>

        <div class="stack">
            <section class="card">
                <header class="card-head"><h2>Listing</h2></header>
                <div class="field @error('category') has-error @enderror">
                    <label for="category">Category</label>
                    <input id="category" name="category" type="text" list="categories" value="{{ old('category', $product->category) }}" required>
                    <datalist id="categories">
                        @foreach($categories as $category)<option value="{{ $category }}">@endforeach
                        <option value="education"><option value="business">
                    </datalist>
                    <span class="hint">Products are grouped by category.</span>
                    @error('category')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div class="field @error('sort_order') has-error @enderror" style="margin-top:1rem">
                    <label for="sort_order">Display order</label>
                    <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $product->sort_order ?? 0) }}">
                    <span class="hint">Lower numbers appear first.</span>
                    @error('sort_order')<p class="error">{{ $message }}</p>@enderror
                </div>
                <label class="switch" style="margin-top:1.25rem"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active))> <span>Show on the website</span></label>
                <label class="switch"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))> <span>Feature on the home page</span></label>
            </section>

            <section class="card">
                <header class="card-head"><h2>Screenshot</h2></header>
                <div class="field @error('image') has-error @enderror">
                    @if($product->image)
                        <img class="thumb" src="{{ asset('storage/' . $product->image) }}" alt="Current screenshot">
                        <label class="switch"><input type="checkbox" name="remove_image" value="1"> <span>Remove current screenshot</span></label>
                    @endif
                    <input id="image" name="image" type="file" accept="image/png,image/jpeg,image/webp">
                    <span class="hint">JPG, PNG or WebP, up to 2 MB.</span>
                    @error('image')<p class="error">{{ $message }}</p>@enderror
                </div>
            </section>
        </div>
    </div>
</form>
@endsection
