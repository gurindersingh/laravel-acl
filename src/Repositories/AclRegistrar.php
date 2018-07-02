<?php

namespace Gurinder\LaravelAcl\Repositories;


use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Gurinder\LaravelAcl\Contracts\AclLedgerContract;
use Gurinder\LaravelAcl\Contracts\AclRegistrarContract;

class AclRegistrar implements AclRegistrarContract
{
    /**
     * @var AclLedgerContract
     */
    protected $ledger;

    public function __construct(AclLedgerContract $ledger)
    {
        $this->ledger = $ledger;
    }

    public function registerPermissions()
    {
        // $permissions = $this->ledger->getPermissions();

        Gate::before(function (Authorizable $user, string $ability) {
            return $user ? $this->checkIfUserHasPermission($ability, $user) : false;
        });

        // if (is_array($permissions)) {
        //     foreach ($permissions as $permission) {
        //         // $slug = $permission->slug;
        //
        //         // Gate::before(function (Authorizable $user, string $ability) {
        //         //     return $true;
        //         //     return $user ? $this->checkIfUserHasPermission($ability, $user) : false;
        //         // });
        //
        //         // Gate::define($permission->slug, function (Authenticatable $user) use ($permission) {
        //         //     return $user ? $this->checkIfUserHasPermission($permission->slug, $user) : false;
        //         // });
        //     }
        // }
    }

    protected function checkIfUserHasPermission($permissionSlug, $user)
    {
        $permissions = optional($this->ledger->getUserAcl($user))['permissions'] ?? [];

        return in_array($permissionSlug, $permissions);
    }

}