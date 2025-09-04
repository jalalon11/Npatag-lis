<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class RedirectToSetupIfNoAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if any admin accounts exist
        $adminExists = User::where('role', 'admin')->exists();
        
        if (!$adminExists) {
            // If no admin exists, redirect to setup page
            return redirect()->route('setup.show');
        }
        
        return $next($request);
    }
}