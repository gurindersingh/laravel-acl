<?php

namespace Gurinder\LaravelAcl\Middlewares;


use Closure;
use Illuminate\Support\Facades\Auth;
use Gurinder\LaravelAcl\Exceptions\UnauthorizedExceptione;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Closure                  $next
     * @return mixed
     * @throws UnauthorizedExceptione
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->hasPermission('manage-acl')) {
            return $next($request);
        }

        throw new UnauthorizedExceptione();
    }

}