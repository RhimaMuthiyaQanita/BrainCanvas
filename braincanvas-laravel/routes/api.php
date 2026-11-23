<?php

use App\Http\Controllers\Api\CardController;
use Illuminate\Support\Facades\Route;

// Membuat 5 endpoint REST sekaligus, dengan awalan /api:
// GET /api/cards, POST /api/cards, GET /api/cards/{id},
// PUT/PATCH /api/cards/{id}, DELETE /api/cards/{id}
Route::apiResource('cards', CardController::class);
