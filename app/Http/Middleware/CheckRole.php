<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
         * Handle an incoming request.
         *
         * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Jika user belum login atau role-nya tidak sesuai
        if (!Auth::check()|| Auth::user()->role !== $role) {
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin.');
        }

        // if (!auth()->check() || auth()->user()->role !== 'admin' ) {
        //     abort(403);
        // }

        return $next($request);
    }
}
