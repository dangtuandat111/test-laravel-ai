<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlightController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/flights/chat', [FlightController::class, 'index'])->name('flights.chat');
Route::post('/flights/search-flights', [FlightController::class, 'search'])->name('flights.search');
