<?php

namespace Gurinder\LaravelAcl\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;
use Gurinder\LaravelAcl\Exceptions\UnauthorizedExceptione;
use Illuminate\Support\Facades\Route;

class RolesMiddleware
{

    public function handle($request, Closure $next, $roles)
    {
        if (app('auth')->guest()) {

            if (Route::has('login')) {
                return redirect()->route('login');
            }

            throw UnauthorizedException::notLoggedIn();
        }

        $roles = is_array($roles) ? $roles : explode('|', $roles);

        foreach ($roles as $role) {
            if (Auth::user()->hasRole($role)) {
                return $next($request);
            }
        }

        throw UnauthorizedExceptione::rolesNotAssigned();
    }

}