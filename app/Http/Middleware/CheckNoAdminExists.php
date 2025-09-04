<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class CheckNoAdminExists
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
        
        if ($adminExists) {
            // If admin exists, redirect to login page
            return redirect()->route('login')->with('error', 'System setup has already been completed.');
        }
        
        return $next($request);
    }
}