<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! in_array($request->user()->role_name, $roles)) {
            // return redirect()->route('no-privilege'); // You can make a custom page
            abort(403, 'You do not have permission to access this resource.');
        }
        return $next($request);
    }
}
