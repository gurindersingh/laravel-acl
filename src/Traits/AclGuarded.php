<?php

namespace Gurinder\LaravelAcl\Traits;


use Gurinder\LaravelAcl\Package\Models\Role;
use Gurinder\LaravelAcl\Package\Models\Permission;
use Gurinder\LaravelAcl\Contracts\AclLedgerContract;

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
     * @param Role|integer $roles |string $roles|array $roles
     *
     * @return Roleable
     */
    public function assignRole($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                $this->assignSingleRole($role);
            }
        } else {
            return $this->assignSingleRole($roles);
        }
    }

    protected function assignSingleRole($role)
    {
        if ($role instanceof Role) {
            return $this->roles()->attach($role->id);
        }

        if (is_numeric($role) && $role = Role::whereId($role)->first()) {
            return $this->roles()->attach($role->id);
        }

        if (is_string($role) && $role = Role::whereSlug($role)->first()) {
            return $this->roles()->attach($role->id);
        }
    }

    /**
     * @param Role|integer|string $role
     *
     * @return bool
     */
    public function hasRole($role)
    {
        $userAcl = resolve(AclLedgerContract::class)->getUserAcl($this);

        if (is_string($role)) {
            return in_array($role, $userAcl['roles']);
        }

        if ($role instanceof Role && $slug = $role->slug) {
            return in_array($slug, $userAcl['roles']);
        }

        if (is_numeric($role) && $slug = optional(Role::whereId($role)->first())->slug) {
            return in_array($slug, $userAcl['roles']);
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
            return in_array($permission, optional($userAcl)['permissions'] ?? []);
        }

        return false;
    }

    /**
     * Check if user is admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

}