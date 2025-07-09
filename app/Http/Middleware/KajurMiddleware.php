<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KajurMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            return redirect()->route('kajur.login');
        }

        if (Auth::user()->role !== 'kajur') {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
