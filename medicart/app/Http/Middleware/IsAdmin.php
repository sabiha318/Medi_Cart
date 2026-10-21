<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Ensure the authenticated user has the 'admin' role.
     * Redirects customers back to their dashboard, and guests to login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if (! $request->user()->isAdmin()) {
            abort(403, 'You do not have permission to access the admin area.');
        }

        return $next($request);
    }
}
