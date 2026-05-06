<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Auth\Actions\LoginUser;
use Modules\Auth\Actions\RegisterUser;
use Modules\Auth\Exceptions\EmailAlreadyTakenException;
use Modules\Auth\Exceptions\InvalidCredentialsException;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\RegisterRequest;

/**
 * PRESENTATION layer — nothing else.
 * 
 * Every method does exactly three things:
 *   1. Extract data from the request (via Form Request)
 *   2. Call an Action
 *   3. Return a response
 * 
 * No business logic. No queries. No if/else domain decisions.
 */

class AuthController
{
    public function __construct(
        private readonly RegisterUser $registerUser,
        private readonly LoginUser    $loginUser
    ) { }

    // ── Pages ──────────────────────────────────────────────

    public function showRegister(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function showLogin(): Response
    {
        return Inertia::render('Auth/Login');
    }

    // ── Actions ────────────────────────────────────────────

    public function register(RegisterRequest $request): RedirectResponse
    {
        // dd($request);
        try {
            $this->registerUser->execute($request->toDTO());
        } catch (EmailAlreadyTakenException $e) {
            // return back()->withErrors(['email' => $e->getMessage()]);
            throw ValidationException::withMessages([
                'email' => $e->getMessage()
            ]);
        }

        return redirect()->route('login')
            ->with('success', 'Account created! Please log in.');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        try {
            $this->loginUser->execute($request->toDTO());
        } catch (InvalidCredentialsException $e) {
            // return back()->withErrors(['email' => $e->getMessage()]);
            throw ValidationException::withMessages([
                'email' => $e->getMessage()
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('chat.index'));
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }
}