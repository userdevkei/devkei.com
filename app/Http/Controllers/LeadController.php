<?php

namespace App\Http\Controllers;

use App\Models\DemoRequest;
use App\Models\Product;
use App\Models\QuoteRequest;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function demoForm(Request $request)
    {
        return view('leads.demo', [
            'products' => Product::active()->get(),
            'selected' => $request->query('product'),
        ]);
    }

    public function quoteForm(Request $request)
    {
        return view('leads.quote', [
            'products' => Product::active()->get(),
            'selected' => $request->query('product'),
        ]);
    }

    public function storeDemo(Request $request)
    {
        // Honeypot: bots fill hidden "website" field
        if ($request->filled('website')) {
            return $this->ok($request, 'Thanks, we will be in touch.');
        }

        $data = $request->validate([
            'product_id'        => ['nullable', 'exists:products,id'],
            'name'              => ['required', 'string', 'max:120'],
            'email'             => ['required', 'email:rfc', 'max:150'],
            'phone'             => ['required', 'string', 'max:30'],
            'organization'      => ['required', 'string', 'max:150'],
            'organization_type' => ['nullable', 'string', 'max:50'],
            'preferred_date'    => ['nullable', 'date', 'after_or_equal:today'],
            'preferred_time'    => ['nullable', 'string', 'max:20'],
            'message'           => ['nullable', 'string', 'max:2000'],
        ]);

        $demo = DemoRequest::create($data + ['ip_address' => $request->ip()]);

        // Mail::to(config('mail.admin'))->queue(new NewDemoRequest($demo));

        return $this->ok($request, 'Demo request received. We will confirm your slot shortly.');
    }

    public function storeQuote(Request $request)
    {
        if ($request->filled('website')) {
            return $this->ok($request, 'Thanks, we will be in touch.');
        }

        $data = $request->validate([
            'product_id'         => ['nullable', 'exists:products,id'],
            'name'               => ['required', 'string', 'max:120'],
            'email'              => ['required', 'email:rfc', 'max:150'],
            'phone'              => ['required', 'string', 'max:30'],
            'organization'       => ['required', 'string', 'max:150'],
            'organization_type'  => ['nullable', 'string', 'max:50'],
            'user_count'         => ['nullable', 'integer', 'min:1', 'max:1000000'],
            'hosting_preference' => ['nullable', 'in:self_host,devkei_host,lease'],
            'requirements'       => ['required', 'string', 'max:4000'],
            'budget_range'       => ['nullable', 'string', 'max:50'],
        ]);

        $quote = QuoteRequest::create($data + ['ip_address' => $request->ip()]);

        // Mail::to(config('mail.admin'))->queue(new NewQuoteRequest($quote));

        return $this->ok($request, 'Quote request received. Expect a response within 1 business day.');
    }

    private function ok(Request $request, string $message)
    {
        return $request->expectsJson()
            ? response()->json(['message' => $message])
            : back()->with('success', $message);
    }
}
