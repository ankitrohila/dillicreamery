<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class AdminMiddleware {
    public function handle(Request $request, Closure $next) {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to access admin.');
        }
        if (!auth()->user()->hasRole(['super_admin','admin','manager'])) {
            abort(403, 'Access denied.');
        }
        return $next($request);
    }
}
