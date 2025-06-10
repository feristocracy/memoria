<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;

Route::get('/', [GameController::class, 'index']);
Route::post('/start', [GameController::class, 'start'])->name('start');
Route::post('/score', [GameController::class, 'score'])->name('score');
Route::get('/scores', [GameController::class, 'scores'])->name('scores');
