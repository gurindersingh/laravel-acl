<?php

namespace Gurinder\LaravelAcl\Commands;


use Illuminate\Console\Command;
use Gurinder\LaravelAcl\Package\Models\Permission;

class CreatePermission extends Command
{
    protected $signature = 'permission:create {name : The name of the permission - Name Only}';

    protected $description = 'Create a Permission';

    public function handle()
    {
        $slug = str_slug($this->argument('name'), '-');

        if (!Permission::whereSlug($slug)->exists()) {

            $permission = Permission::create([
                'name' => $this->argument('name'),
                'slug'  => $slug
            ]);

            $this->attachPermissionToMasterRoles($role, $permission);

            $this->info("Permission '`{$permission->name}`' created");

        } else {

            $this->error("Permission '`{$permission->name}`' already exists");

        }

    }

    /**
     * @param $role
     * @param $permission
     */
    protected function attachPermissionToMasterRoles($role, $permission): void
    {
        $roles = config('acl.master_roles');

        if (is_string($roles)) {
            if ($role = Role::whereSlug($role)->first()) {
                $role->permissions()->attach($permission->id);
            }
        }

        if(is_array($roles)) {
            foreach ($roles as $role) {
                if($role = Role::whereSlug($role)->first()) {
                    $role->permissions()->attach($permission->id);
                }
            }
        }
    }
}