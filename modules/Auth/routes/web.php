<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Presentation\Controllers\AuthController;

Route::middleware('web')->group(
    function () {
        
        Route::middleware('guest')->group(
            function()  {
                Route::get('/register', [AuthController::class, 'showRegister'])
                    ->name('register');

                Route::post('/register', [AuthController::class, 'register']);

                Route::get('/login', [AuthController::class, 'showLogin'])
                    ->name('login');

                Route::post('/login', [AuthController::class, 'login']);



            }
        );

        Route::get('/', function (){
            return redirect(route('chat.index'));
        });


        Route::middleware('auth')->group(function() {

            Route::post('/logout', [AuthController::class, 'logout'])
            ->name('logout');

            Route::get('/chat', function(){
                return "logged in";
            })->name('chat.index');
        });
        }
);
