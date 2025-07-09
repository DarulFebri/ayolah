<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            return redirect()->route('mahasiswa.login');
        }

        if (Auth::user()->role !== 'mahasiswa') {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
