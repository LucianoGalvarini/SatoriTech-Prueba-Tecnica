<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CharacterController;

Route::get('/', [CharacterController::class, 'index']);
Route::post('/save-character', [CharacterController::class, 'saveCharacter']);
Route::get('/show-characters', [CharacterController::class, 'showCharacters']);
