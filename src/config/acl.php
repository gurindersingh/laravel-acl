<?php

return [

    'cache_key' => 'gurinder.laravel-acl',

    'cache_expiration_time' => 60 * 24,

    'route_prefix' => 'admin',

    'route_as' => 'admin.',

    'master_roles' => ['admin'],

    'freezed_roles' => ['admin'],

    'freezed_permissions' => ['manage-acl', 'manage-users'],

    'back_link' => [
        'label' => 'Dashboard',
        'url'   => '/home'
    ],

    'user_search_columns' => [
        'id',
        'first_name',
        'last_name',
        'email'
    ]

];
