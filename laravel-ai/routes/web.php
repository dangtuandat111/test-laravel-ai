<?php

use App\Http\Controllers\AssistantController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FileController;
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
Route::get('/chat/document', [AssistantController::class, 'indexfile'])->name('chat.document');
Route::post('/chat/stream', [AssistantController::class, 'store'])->name('chat.store');
Route::post('/chat/ask', [AssistantController::class, 'ask'])->name('chat.ask');
//Route::post('/chat/stream', function () {
//    return (new SalesCoach)->forUser(\App\Models\User::find(1))->stream(request('message'));
//});


Route::get('documents', [DocumentController::class, 'index'])->name('documents.index');
Route::get('search', [DocumentController::class, 'search'])->name('documents.search');

Route::group(['prefix' => 'file'], function () {
    Route::get('upload', [FileController::class, 'upload'])->name('file.upload');
    Route::get('ask', [FileController::class, 'chat'])->name('file.ask');
    Route::post('store', [FileController::class, 'store'])->name('file.store');
    Route::post('ask', [FileController::class, 'ask'])->name('file.ask.post');
});