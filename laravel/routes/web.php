<?php

use App\Http\Controllers\TestController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', [
    TestController::class,
    'test'
]);

Route::get('/api/products', [
    ProductController::class,
    'getProducts'
]);

Route::get('/api/products/{id}', [
    ProductController::class,
    'getProductItem'
]);

Route::post('/api/products', [
    ProductController::class,
    'createProduct'
])->withoutMiddleware([VerifyCsrfToken::class]);

Route::delete('/api/products/{id}', [
    ProductController::class,
    'deleteProduct'
])->withoutMiddleware([VerifyCsrfToken::class]);
