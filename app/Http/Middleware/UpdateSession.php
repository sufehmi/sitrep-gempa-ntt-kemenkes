<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->get('update_unlocked')) {
            return redirect()->route('update.gate');
        }
        return $next($request);
    }
}
