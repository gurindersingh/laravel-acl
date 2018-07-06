<?php

namespace Gurinder\LaravelAcl\Commands;


use Illuminate\Console\Command;
use Gurinder\LaravelAcl\Package\Models\Role;

class ListRoles extends Command
{
    protected $signature = 'role:list';

    protected $description = 'list all roles';

    public function handle()
    {
        $roles = Role::get()->toArray();

        $this->line('----- Roled Lists ----');

        foreach ($roles as $role) {
            $this->line("Name: {$role['name']}");
            $this->line("Slug: {$role['slug']}");
            $this->line('-------');
        }

        $this->line('----- End List ----');


    }
}