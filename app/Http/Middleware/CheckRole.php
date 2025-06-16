<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is logged in first
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Check role
        if ($request->user()->role !== $role) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            
            // If admin trying to access petani routes
            if ($request->user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            
            // If petani trying to access admin routes
            if ($request->user()->role === 'petani') {
                return redirect()->route('dashboard')
                    ->with('error', 'Unauthorized access');
            }
            
            // Fallback
            return redirect('/');
        }

        return $next($request);
    }
}