<?php

namespace Gurinder\LaravelAcl\Contracts;


use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Gate;

interface AclRegistrarContract
{

    public function registerPermissions();
}