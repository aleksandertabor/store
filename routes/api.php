<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'api.'], function () {
    Route::apiResource('products', ProductController::class);
});
