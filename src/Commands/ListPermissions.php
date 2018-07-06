<?php

namespace Gurinder\LaravelAcl\Commands;


use Illuminate\Console\Command;
use Gurinder\LaravelAcl\Package\Models\Permission;

class ListPermissions extends Command
{
    protected $signature = 'permission:list';

    protected $description = 'list all roles';

    public function handle()
    {
        $permissions = Permission::with(['roles'])->get();

        $this->line('----- Roled Lists ----');

        $permissions->each(function($permission) {
            $this->line("Name: {$permission->name}");
            $this->line("Slug: {$permission->slug}");

            $permission->roles->each(function($role) {
                $this->line("   Role Associated with: {$role->name}");
            });

            $this->line("---");
        });


        $this->line('----- End List ----');


    }
}