<?php

namespace Gurinder\LaravelAcl\Commands;


use Gurinder\LaravelAcl\Package\Models\Role;

class CreateRole
{
    protected $signature = 'role:create {name : The name of the role - Name Only}';

    protected $description = 'Create a Role';

    public function handle()
    {
        $slug = str_slug($this->argument('name'), '-');

        if (!Role::whereSlug($slug)->exists()) {

            $role = Role::create([
                'name' => $this->argument('name'),
                'slug'  => $slug
            ]);

            return $this->info("Role '`{$role->name}`' created");

        }

        $this->error("Role '`{$role->name}`' already exists");

    }
}