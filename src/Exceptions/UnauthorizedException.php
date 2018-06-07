<?php

namespace Gurinder\LaravelAcl\Exceptions;

use Exception;

class UnauthorizedExceptione extends Exception
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
}