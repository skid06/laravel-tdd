<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        $roles = [
            'super_admin' => 1,
            'professor' => 2,
            'dean' => 3,
            'registrar' => 4,
            'admin' => 5,
            'student' => 6,
        ];

        if($request->user()->role != $roles[$role]){
            return response()->json('User is not authorized for this action.', 403);
        }
        return $next($request);
    }
}
