<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controller: EmailVerificationPromptController
 *
 * Displays the email verification prompt page.
 * If the user has already verified their email, they are redirected
 * to their intended destination or the dashboard.
 */
class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     *
     * Checks if the user has already verified their email address.
     * If so, redirects to the intended URL or dashboard.
     * Otherwise, renders the verification prompt page.
     */
    public function __invoke(Request $request): Response
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(
                $this->redirectByRole($request->user())
            );
        }

        return Inertia::render('Auth/VerifyEmail', [
            'status' => session('status'),
        ]);
    }

    /**
     * Determine the redirect route based on the user's role.
     *
     * @param  \App\Models\User  $user
     * @return string
     */
    private function redirectByRole($user): string
    {
        return match ($user->role) {
            'admin'      => route('admin.dashboard'),
            'technician' => route('technician.dashboard'),
            'client'     => route('client.dashboard'),
            default      => route('dashboard'),
        };
    }
}
