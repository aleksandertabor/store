<?php

use App\Http\Controllers\ProfileController;
use App\Models\Product;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Product/Index', [
        'currentPage' => Paginator::resolveCurrentPage(),
        'query' => request()->query('q'),
    ]);
})->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/products/create', function () {
        return Inertia::render('Product/Create');
    })->name('products.create');
    Route::get('/products/{product:id}/edit', function (Product $product) {
        return Inertia::render('Product/Edit');
    })->name('products.edit');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/products/{product:id}', function (Product $product) {
    return Inertia::render('Product/Show');
})->name('products.show');

require __DIR__.'/auth.php';
