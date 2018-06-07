<?php

namespace Gurinder\LaravelAcl\Traits;


use Gurinder\LaravelAcl\Contracts\AclLedgerContract;
use Gurinder\LaravelAcl\Package\Models\Permission;
use Gurinder\LaravelAcl\Package\Models\Role;

trait AclGuarded
{

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function rolesWithPermissions()
    {
        return $this->belongsToMany(Role::class)->with('permissions');
    }

    public function syncRoles($roles = [])
    {
        $this->roles()->detach();

        return $this->roles()->sync($roles);
    }

    /**
     * @param Role|integer|string $role
     *
     * @return bool
     */
    public function hasRole($role)
    {
        $userAcl = resolve(AclLedgerContract::class)->getUserAcl($this);

        if ($role instanceof Role) {
            return in_array($role->slug, $userAcl['roles']);
        }

        if (is_numeric($role)) {
            return in_array(Role::whereId($role)->firstOrFail()->slug, $userAcl['roles']);
        }

        if (is_string($role)) {
            return in_array($role, $userAcl['roles']);
        }

        return false;

    }

    public function hasPermission($permission)
    {
        $userAcl = resolve(AclLedgerContract::class)->getUserAcl($this);

        if ($permission instanceof Permission) {
            return in_array($permission->slug, $userAcl['permissions']);
        }

        if (is_numeric($permission)) {
            return in_array(Permission::whereId($permission)->firstOrFail()->slug, $userAcl['permissions']);
        }

        if (is_string($permission)) {
            return in_array($permission, $userAcl['permissions']);
        }

        return false;
    }

}