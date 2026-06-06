<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controller: AuthenticatedSessionController
 *
 * Handles user authentication (login).
 * Provides the login form view and processes login attempts,
 * redirecting users to their role-specific dashboard upon success.
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login form.
     *
     * Returns the Inertia login page. If already authenticated, redirects
     * to the appropriate dashboard based on the user's role.
     */
    public function create(): Response
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return Inertia::render('Auth/Login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * Validates the email and password credentials, attempts to authenticate
     * the user, and redirects to the role-specific dashboard on success.
     * On failure, throws a ValidationException with an error message.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'remember' => ['boolean'],
        ]);

        if (! Auth::attempt(
            $request->only('email', 'password'),
            $request->boolean('remember')
        )) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        // Check if user account is active
        if (! Auth::user()->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Su cuenta ha sido desactivada. Contacte al administrador.',
            ]);
        }

        return $this->redirectByRole(Auth::user());
    }

    /**
     * Destroy an authenticated session (logout).
     *
     * Logs the user out, invalidates the session, regenerates the CSRF token,
     * and redirects to the home page.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * Redirect the user to their role-specific dashboard.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectByRole($user)
    {
        return redirect()->route(match ($user->role) {
            'admin'      => 'admin.dashboard',
            'technician' => 'technician.dashboard',
            'client'     => 'client.dashboard',
            default      => 'dashboard',
        });
    }
}
