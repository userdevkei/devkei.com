<?php

use App\Http\Middleware\TrackVisit;
use Illuminate\Support\Facades\Route;

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
