<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        return view('admin.products.index', [
            'products' => Product::orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.products.form', [
            'product' => new Product(['is_active' => true, 'sort_order' => 0]),
            'categories' => $this->categories(),
        ]);
    }

    public function store(ProductRequest $request)
    {
        $data = $this->payload($request);
        $data['slug'] = $this->uniqueSlug($request->input('slug') ?: $request->input('name'));

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', [
            'product' => $product,
            'categories' => $this->categories(),
        ]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $data = $this->payload($request);
        $data['slug'] = $this->uniqueSlug($request->input('slug') ?: $product->slug, $product->id);

        if ($request->boolean('remove_image') && $product->image) {
            Storage::disk('public')->delete($product->image);
            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete(); // demo/quote requests keep their data; product_id becomes null.

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    private function payload(ProductRequest $request): array
    {
        return [
            'name' => $request->input('name'),
            'tagline' => $request->input('tagline'),
            'category' => $request->input('category'),
            'summary' => $request->input('summary'),
            'description' => $request->input('description'),
            'features' => $this->lines($request->input('features')) ?: null,
            'modules' => $this->lines($request->input('modules')) ?: null,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
            'sort_order' => (int) $request->input('sort_order', 0),
        ];
    }

    /** One item per line, blanks removed. */
    private function lines(?string $value): array
    {
        return collect(preg_split('/\R/', (string) $value))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    private function uniqueSlug(string $base, ?string $ignoreId = null): string
    {
        $slug = Str::slug($base) ?: 'product';
        $candidate = $slug;
        $i = 2;

        while (Product::where('slug', $candidate)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $candidate = "{$slug}-{$i}";
            $i++;
        }

        return $candidate;
    }

    private function categories()
    {
        return Product::query()->distinct()->orderBy('category')->pluck('category');
    }
}
