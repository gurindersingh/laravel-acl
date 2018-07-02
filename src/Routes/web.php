<?php

use \Illuminate\Support\Facades\Route;

Route::prefix(config('acl.route_prefix'))
    ->as(config('acl.route_as'))
    ->middleware(['web', 'auth', 'permissions:manage-acl'])
    ->group(function () {

        // Roles Routes
        Route::resource('roles', '\Gurinder\LaravelAcl\Http\Controllers\RolesController')
            ->except('show');
        // Permission Routes
        Route::resource('permissions', '\Gurinder\LaravelAcl\Http\Controllers\PermissionsController')
            ->except(['edit', 'update', 'show']);
        // Users Routes
        Route::resource('roles/users', '\Gurinder\LaravelAcl\Http\Controllers\UsersController');

    });