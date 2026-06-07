<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

/**
 * Controller: PasswordResetLinkController
 *
 * Handles password reset link requests.
 * Provides the "forgot password" form view and processes the
 * reset link sending request.
 */
class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request form.
     *
     * Returns the page for requesting a password reset link.
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * Validates the email address and dispatches a password reset
     * notification to the user if the email exists in the system.
     *
     * Returns the previous page with a status message regardless of
     * whether the email was found (to avoid email enumeration).
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }
}
