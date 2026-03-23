<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return $next($request);
        }

        if (!empty($user->must_change_password)) {
            $routeName = optional($request->route())->getName();

            // Allow the password change routes + logout + assets
            $allowed = [
                'password.change',
                'password.change.update',
                'logout',
            ];

            if (!in_array($routeName, $allowed, true)) {
                return redirect()->route('password.change')->with('error', 'Please change your password to continue.');
            }
        }

        return $next($request);
    }
}
