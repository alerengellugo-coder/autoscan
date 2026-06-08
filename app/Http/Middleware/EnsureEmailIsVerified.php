<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() ||
            (!$request->user()->hasVerifiedEmail() &&
             !$request->user()->is_admin)) {
            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
