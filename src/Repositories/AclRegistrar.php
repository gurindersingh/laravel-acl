<?php

namespace Gurinder\LaravelAcl\Repositories;


use Gurinder\LaravelAcl\Contracts\AclLedgerContract;
use Gurinder\LaravelAcl\Contracts\AclRegistrarContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Gate;

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
        $permissions = $this->ledger->getPermissions();

        foreach ($permissions as $permission) {
            Gate::define($permission->slug, function (Authenticatable $user) use ($permission) {
                return $user ? $this->checkIfUserHasPermission($permission->slug, $user) : false;
            });
        }
    }

    protected function checkIfUserHasPermission($permissionSlug, $user)
    {
        return in_array($permissionSlug, $this->ledger->getUserAcl($user)['permissions']);
    }

}