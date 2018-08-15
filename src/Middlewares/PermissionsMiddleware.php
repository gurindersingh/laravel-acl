<?php

namespace Gurinder\LaravelAcl\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;
use Gurinder\LaravelAcl\Exceptions\UnauthorizedExceptione;
use Illuminate\Support\Facades\Route;

class PermissionsMiddleware
{

    public function handle($request, Closure $next, $permission)
    {
        if (app('auth')->guest()) {

            if (Route::has('login')) {
                return redirect()->route('login');
            }

            throw UnauthorizedExceptione::notLoggedIn();
        }

        $permissions = is_array($permission) ? $permission : explode('|', $permission);
        

        foreach ($permissions as $permission) {
            if (Auth::user()->can($permission)) {
                return $next($request);
            }
        }

        throw UnauthorizedExceptione::permissionNotAllowed();
    }

}