<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class OnlyAccessibleByDevUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user()->isDevUser()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Not authorized.'],403);
            }

            return abort('403', "Not authorized.");

        }

        return $next($request);
    }
}
