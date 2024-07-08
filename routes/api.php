<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\GenreController;

// Authentification
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [RegisterController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/users', [AuthController::class, 'getUsers'])->middleware('auth:sanctum');



Route::middleware('auth:sanctum')->group(function () {
    Route::get('books', [BookController::class, 'index']);
    Route::post('books', [BookController::class, 'store']);
    Route::get('books/{id}', [BookController::class, 'show']);
    Route::put('books/{id}', [BookController::class, 'update']);
    Route::delete('books/{id}', [BookController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('genres', [GenreController::class, 'index']);
    Route::post('genres', [GenreController::class, 'store']);
    Route::get('genres/{id}', [GenreController::class, 'show']);
    Route::put('genres/{id}', [GenreController::class, 'update']);
    Route::delete('genres/{id}', [GenreController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('members', [MemberController::class, 'index']);
    Route::post('members', [MemberController::class, 'store']);
    Route::get('members/{id}', [MemberController::class, 'show']);
    Route::put('members/{id}', [MemberController::class, 'update']);
    Route::delete('members/{id}', [MemberController::class, 'destroy']);
});


Route::middleware('auth:sanctum')->group(function () {
   

    Route::apiResource('loans', LoanController::class);
  

});
