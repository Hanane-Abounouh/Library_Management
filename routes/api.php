<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\GenreController;

// Authentification
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Routes pour les livres (books)
    Route::apiResource('books', BookController::class);

    // Routes pour les membres (members)
    Route::apiResource('members', MemberController::class);

    // Routes pour les prêts (loans)
    Route::apiResource('loans', LoanController::class);

    // Routes pour les genres (genres)
    Route::apiResource('genres', GenreController::class);
});
