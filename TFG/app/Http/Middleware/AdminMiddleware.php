<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->check()){
            $adminRoleId= Role::where('role', 'admin')->pluck('id')->toArray();

            if(auth()->user()->roles()->whereIn('role_id', $adminRoleId)->exists()){
                return $next($request);
            }
        }
        abort(403, 'No eres un administardor del sistema');
    }
}
