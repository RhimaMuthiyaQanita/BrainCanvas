<?php

use App\Http\Controllers\CardController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/cards'));

// Route hanya menunjuk ke Controller; logika ada di CardController
Route::get('/cards', [CardController::class, 'index']);           // READ
Route::get('/cards/create', [CardController::class, 'create']);   // form tambah
Route::post('/cards', [CardController::class, 'store']);          // CREATE
Route::get('/cards/{id}/edit', [CardController::class, 'edit']);  // form edit
Route::put('/cards/{id}', [CardController::class, 'update']);     // UPDATE
Route::delete('/cards/{id}', [CardController::class, 'destroy']); // DELETE
