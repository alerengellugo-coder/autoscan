<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailVerificationPromptController extends Controller
{
    public function show(Request $request)
    {
        return $this->prompt($request);
    }

    public function __invoke(Request $request)
    {
        return $this->prompt($request);
    }

    private function prompt(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(
                $this->redirectByRole($request->user())
            );
        }

        return view('auth.verify-email', [
            'status' => session('status'),
        ]);
    }

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
