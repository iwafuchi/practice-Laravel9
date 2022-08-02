<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated {
    private const GUARD_USERS = 'users';
    private const GUARD_OWNERS = 'owners';
    private const GUARD_ADMINS = 'admins';
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards) {
        // $guards = empty($guards) ? [null] : $guards;

        // foreach ($guards as $guard) {
        //     if (Auth::guard($guard)->check()) {
        //         return redirect(RouteServiceProvider::HOME);
        //     }
        // }

        if (Auth::guard((self::GUARD_USERS))->check() && $request->routeIs('users.*')) {
            return redirect(RouteServiceProvider::HOME);
        }

        if (Auth::guard((self::GUARD_OWNERS))->check() && $request->routeIs('owners.*')) {
            return redirect(RouteServiceProvider::OWNERS_HOME);
        }

        if (Auth::guard((self::GUARD_ADMINS))->check() && $request->routeIs('admins.*')) {
            return redirect(RouteServiceProvider::ADMINS_HOME);
        }

        return $next($request);
    }
}
