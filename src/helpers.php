<?php

if (!function_exists('authUserHasPermission')) {

    /**
     * Check if user has permission
     *
     * @param \Gurinder\LaravelAcl\Package\Models\Permission|String $permission
     * @return bool
     */
    function authUserHasPermission($permission)
    {
        return \Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user()->hasPermission($permission) : false;

    }

}