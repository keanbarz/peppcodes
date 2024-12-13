<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckFieldOffice
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Check if the user's label matches the specified value
            if (Auth::user()->field_office == '') {
                return $next($request);
            }
        }

        // Optionally, redirect or abort if the condition is not met
        return abort(403/*, 'Only the admin can access this resource.'*/);
    }
}
