<?php

namespace Gurinder\LaravelAcl\Commands;


use Illuminate\Console\Command;
use Gurinder\LaravelAcl\Package\Models\Role;

class CreateRole extends Command
{
    protected $signature = 'role:create {name : The name of the role - Name Only}';

    protected $description = 'Create a Role';

    public function handle()
    {
        $slug = str_slug($this->argument('name'), '-');

        if (!Role::whereSlug($slug)->exists()) {

            $role = Role::create([
                'name' => $this->argument('name'),
                'slug' => $slug
            ]);

            $this->info("Role `{$role->name}` created");

        } else {

            $this->error("Role `{$role->name}` already exists");

        }


    }
}