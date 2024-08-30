<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConversationsController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [ConversationsController::class, 'index'])->name('home');
Route::get('/conversations', [ConversationsController::class, 'index'])->name('conversations');
Route::get('/conversations/{user}', [ConversationsController::class, 'show'])->middleware('can:talkTo,user')->name('conversations.show');
Route::post('/conversations/{user}', [ConversationsController::class, 'store'])->middleware('can:talkTo,user');


require __DIR__.'/api.php';

