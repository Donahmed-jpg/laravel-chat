<?php

use Illuminate\Support\Facades\Route;

// Redirect root to login for now
// This will be replaced with the chat route in Step 6
Route::get('/', function () {
    return redirect()->route('login');
});

// Placeholder for after login redirect
// We'll build this properly in Step 6
Route::get('/chat', function () {
    return inertia('Messaging/Chat');
})->middleware('auth')->name('chat.index');