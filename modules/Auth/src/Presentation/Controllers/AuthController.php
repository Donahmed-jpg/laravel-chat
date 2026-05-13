<?php

namespace Modules\Auth\Presentation\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Auth\Application\Contracts\SessionManager;
use Modules\Auth\Application\UseCases\LoginUser;
use Modules\Auth\Application\UseCases\RegisterUser;
use Modules\Auth\Domain\Exceptions\EmailAlreadyTakenException;
use Modules\Auth\Domain\Exceptions\InvalidCredentialsException;
use Modules\Auth\Presentation\Requests\LoginRequest;
use Modules\Auth\Presentation\Requests\RegisterRequest;

/**
 * Presentation layer only.
 *
 * Responsibilities:
 *   1. Render Inertia pages
 *   2. Translate HTTP requests into commands
 *   3. Call use cases
 *   4. Map domain exceptions to HTTP errors
 *   5. Delegate session management to SessionManager
 *   6. Return redirects or Inertia responses
 *
 * Does NOT:
 *   - Contain business logic
 *   - Query the database
 *   - Know about Eloquent
 *   - Know about Laravel's Auth facade directly
 */
class AuthController
{
    public function __construct(
        private readonly RegisterUser   $registerUser,
        private readonly LoginUser      $loginUser,
        private readonly SessionManager $session,
        // ↑ needed to resolve the domain entity for session start
        // after the use case returns a UserResponse
    ) {}

    // ── Pages ─────────────────────────────────────────────────────

    public function showRegister(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function showLogin(): Response
    {
        return Inertia::render('Auth/Login');
    }

    // ── Actions ───────────────────────────────────────────────────

    public function register(RegisterRequest $request): RedirectResponse
    {
        try {

            $command = $request->toCommand();
            
            // Response is not used but keep for future options
            $userResponse = $this->registerUser->execute($command);
        } catch (EmailAlreadyTakenException $e) {
            // Map domain exception → HTTP validation error
            // This mapping is a Presentation concern
            throw ValidationException::withMessages([
                'email' => $e->getMessage(),
            ]);
        }

        return redirect()->route('login')
            ->with('success', 'Account created. Please sign in.');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $command = $request->toCommand();

        try {
            // Response is not used but keep for future options
            $userResponse = $this->loginUser->execute($command);
            
        } catch (InvalidCredentialsException $e) {
            throw ValidationException::withMessages([
                'email' => $e->getMessage(),
            ]);
        }
        return redirect(route('chat.index'));

        // return redirect()->intended(route('chat.index'));
    }

    public function logout(): RedirectResponse
    {
        $this->session->endSession();

        return redirect()->route('login');
    }
}