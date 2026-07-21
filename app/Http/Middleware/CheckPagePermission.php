<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPagePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, $page, $action = 'view'): Response
    {
        if (auth()->check() && !auth()->user()->hasPagePermission($page, $action)) {
            abort(403, 'You do not have permission to ' . $action . ' this page.');
        }

        app()->instance('current_page_permission_key', $page);

        return $next($request);
    }
}
