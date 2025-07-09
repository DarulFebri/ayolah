<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaprodiMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            return redirect()->route('kaprodi.login');
        }

        if (Auth::user()->role !== 'kaprodi') {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
