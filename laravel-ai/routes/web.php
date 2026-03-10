<?php

use App\Http\Controllers\AssistantController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlightController;
use App\Ai\Agents\SalesCoach;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/flights/chat', [FlightController::class, 'index'])->name('flights.chat');
Route::post('/flights/search-flights', [FlightController::class, 'search'])->name('flights.search');


Route::get('/coach', function () {
    return (new SalesCoach)->stream('Analyze this sales transcript...');
});

Route::get('/chat', [AssistantController::class, 'index'])->name('chat');
Route::post('/chat/stream', [AssistantController::class, 'store'])->name('chat.store');
//Route::post('/chat/stream', function () {
//    return (new SalesCoach)->forUser(\App\Models\User::find(1))->stream(request('message'));
//});
