{{-- Shared contact fields. Expects $products and $selected. --}}
<div class="field" data-field="name">
    <label for="name">Full name</label>
    <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required>
    @error('name')<p class="error">{{ $message }}</p>@enderror
</div>
<div class="field" data-field="email">
    <label for="email">Email</label>
    <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
    @error('email')<p class="error">{{ $message }}</p>@enderror
</div>
<div class="field" data-field="phone">
    <label for="phone">Phone</label>
    <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" placeholder="+254 7XX XXX XXX" required>
    @error('phone')<p class="error">{{ $message }}</p>@enderror
</div>
<div class="field" data-field="organization">
    <label for="organization">Organisation</label>
    <input id="organization" name="organization" type="text" value="{{ old('organization') }}" autocomplete="organization" required>
    @error('organization')<p class="error">{{ $message }}</p>@enderror
</div>
<div class="field" data-field="organization_type">
    <label for="organization_type">Type of organisation</label>
    <select id="organization_type" name="organization_type">
        <option value="">Select one</option>
        @foreach(['University','College','Secondary school','Primary school','Retail or wholesale','Other business','Other'] as $type)
            <option value="{{ $type }}" @selected(old('organization_type') === $type)>{{ $type }}</option>
        @endforeach
    </select>
    @error('organization_type')<p class="error">{{ $message }}</p>@enderror
</div>
<div class="field" data-field="product_id">
    <label for="product_id">Product</label>
    <select id="product_id" name="product_id">
        <option value="">Not sure yet</option>
        @foreach($products as $product)
            <option value="{{ $product->id }}" @selected((string) old('product_id', $selected) === (string) $product->id)>{{ $product->name }}</option>
        @endforeach
    </select>
    @error('product_id')<p class="error">{{ $message }}</p>@enderror
</div>
<div class="hp" aria-hidden="true">
    <label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
</div>
