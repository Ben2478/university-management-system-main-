<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureShortCourseIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $student = $user?->isStudent() ? $user->student : null;

        if (!$student?->isShortCourseExpired()) {
            return $next($request);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->withErrors([
            'identifier' => 'Your short course has ended, and your login is no longer active.',
        ]);
    }
}