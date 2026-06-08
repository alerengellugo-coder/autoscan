<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\Unique;

/**
 * Controller: RegisteredUserController
 *
 * Handles new client registration.
 * Provides the registration form view and processes registration requests,
 * creating a user with the 'client' role and logging them in.
 */
class RegisteredUserController extends Controller
{
    /**
     * Display the registration form.
     *
     * Returns the registration page. If already authenticated,
     * redirects to the appropriate dashboard.
     */
    public function create()
    {
        if (Auth::check()) {
            return redirect()->route('client.dashboard');
        }

        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * Validates user input (name, email, password, phone), creates a new
     * user with the 'client' role, logs them in automatically, and
     * redirects to the client dashboard.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => [
                'required',
                'string',
                'email',
                'max:255',
                new Unique('users', 'email'),
            ],
            'phone'    => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role'     => 'client',
            'is_active' => true,
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()
            ->route('client.dashboard')
            ->with('success', '¡Bienvenido! Su cuenta ha sido creada exitosamente.');
    }
}
