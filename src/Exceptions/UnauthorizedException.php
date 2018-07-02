<?php

namespace Gurinder\LaravelAcl\Exceptions;

use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UnauthorizedExceptione extends HttpException
{
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return void
     */
    public function render($request)
    {
        abort(403, 'Unauthorized action.');
    }

    public static function notLoggedIn(): self
    {
        return new static(403, 'User is not logged in.', null, []);
    }

    public static function permissionNotAllowed()
    {
        return new static(403, 'User does not have proper permissions', null, []);
    }

    public static function rolesNotAssigned()
    {
        return new static(403, 'User does not have proper roles', null, []);
    }
}