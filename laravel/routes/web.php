<?php

use App\Http\Controllers\TestController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\LoanController;


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

Route::post('/api/products/{id}', [
    ProductController::class,
    'updateProduct'
])->withoutMiddleware([VerifyCsrfToken::class]);


// Book Routes
Route::get('/api/books', [BookController::class, 'getBooks']);
Route::get('/api/books/{id}', [BookController::class, 'getBookItem']);
Route::post('/api/books', [BookController::class, 'createBook'])
    ->withoutMiddleware([VerifyCsrfToken::class]);
Route::delete('/api/books/{id}', [BookController::class, 'deleteBook'])
    ->withoutMiddleware([VerifyCsrfToken::class]);
Route::patch('/api/books/{id}', [BookController::class, 'updateBook'])
    ->withoutMiddleware([VerifyCsrfToken::class]);

// AuthorRepository Routes
Route::get('/api/authors', [AuthorController::class, 'getAuthors']);
Route::get('/api/authors/{id}', [AuthorController::class, 'getAuthorItem']);
Route::post('/api/authors', [AuthorController::class, 'createAuthor'])
    ->withoutMiddleware([VerifyCsrfToken::class]);
Route::delete('/api/authors/{id}', [AuthorController::class, 'deleteAuthor'])
    ->withoutMiddleware([VerifyCsrfToken::class]);
Route::patch('/api/authors/{id}', [AuthorController::class, 'updateAuthor'])
    ->withoutMiddleware([VerifyCsrfToken::class]);

// Genre Routes
Route::get('/api/genres', [GenreController::class, 'getGenres']);
Route::get('/api/genres/{id}', [GenreController::class, 'getGenreItem']);
Route::post('/api/genres', [GenreController::class, 'createGenre'])
    ->withoutMiddleware([VerifyCsrfToken::class]);
Route::delete('/api/genres/{id}', [GenreController::class, 'deleteGenre'])
    ->withoutMiddleware([VerifyCsrfToken::class]);
Route::patch('/api/genres/{id}', [GenreController::class, 'updateGenre'])
    ->withoutMiddleware([VerifyCsrfToken::class]);

// Publisher Routes
Route::get('/api/publishers', [PublisherController::class, 'getPublishers']);
Route::get('/api/publishers/{id}', [PublisherController::class, 'getPublisherItem']);
Route::post('/api/publishers', [PublisherController::class, 'createPublisher'])
    ->withoutMiddleware([VerifyCsrfToken::class]);
Route::delete('/api/publishers/{id}', [PublisherController::class, 'deletePublisher'])
    ->withoutMiddleware([VerifyCsrfToken::class]);
Route::patch('/api/publishers/{id}', [PublisherController::class, 'updatePublisher'])
    ->withoutMiddleware([VerifyCsrfToken::class]);

// Loan Routes
Route::get('/api/loans', [LoanController::class, 'getLoans']);
Route::get('/api/loans/{id}', [LoanController::class, 'getLoanItem']);
Route::post('/api/loans', [LoanController::class, 'createLoan'])
    ->withoutMiddleware([VerifyCsrfToken::class]);
Route::delete('/api/loans/{id}', [LoanController::class, 'deleteLoan'])
    ->withoutMiddleware([VerifyCsrfToken::class]);
Route::patch('/api/loans/{id}', [LoanController::class, 'updateLoan'])
    ->withoutMiddleware([VerifyCsrfToken::class]);
