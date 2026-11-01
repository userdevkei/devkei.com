<?php

use App\Http\Middleware\TrackVisit;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

use App\Http\Controllers\{ProductController, LeadController, HomeController};

Route::middleware(TrackVisit::class)->group(function () {
    Route::view('/', 'home')->name('home');
    Route::view('/about', 'about')->name('about');
    Route::view('/contact', 'contact')->name('contact');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

    Route::get('/book-demo', [LeadController::class, 'demoForm'])->name('demo.create');
    Route::get('/request-quote', [LeadController::class, 'quoteForm'])->name('quote.create');

    Route::middleware('throttle:5,1')->group(function () {
        Route::post('/book-demo', [LeadController::class, 'storeDemo'])->name('demo.store');
        Route::post('/request-quote', [LeadController::class, 'storeQuote'])->name('quote.store');
    });

    Route::get('/', HomeController::class)->name('home');
});


require __DIR__.'/admin.php';

Route::get('/test-ip', function (Illuminate\Http\Request $request) {
    $ip = $request->ip();

    $location = Http::get("https://ipwho.is/{$ip}")->json();

    return [
        'ip' => $ip,
        'country' => $location['country'] ?? null,
        'country_code' => $location['country_code'] ?? null,
        'region' => $location['region'] ?? null,
        'city' => $location['city'] ?? null,
        'latitude' => $location['latitude'] ?? null,
        'longitude' => $location['longitude'] ?? null,
        'timezone' => $location['timezone']['id'] ?? null,
        'isp' => $location['connection']['isp'] ?? null,
    ];
});
