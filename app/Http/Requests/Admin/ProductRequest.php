<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Routes are already behind EnsureAdmin.
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'category' => Str::slug((string) $this->input('category'), '_'),
            'slug' => $this->filled('slug') ? Str::slug((string) $this->input('slug')) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:160', Rule::unique('products', 'slug')->ignore($this->route('product')?->id)],
            'tagline' => ['nullable', 'string', 'max:200'],
            'category' => ['required', 'string', 'max:50'],
            'summary' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string', 'max:20000'],
            'features' => ['nullable', 'string', 'max:5000'],
            'modules' => ['nullable', 'string', 'max:3000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }
}
