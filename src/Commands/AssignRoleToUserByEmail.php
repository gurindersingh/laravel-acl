<?php

namespace Gurinder\LaravelAcl\Commands;


use Gurinder\LaravelAcl\Package\Models\Role;

class AssignRoleToUserByEmail
{
    protected $signature = 'role:assign {roleSlug : role slug to assign} {email : Email of the user to assign role}';

    protected $description = 'Assign role to the user by email';

    public function handle()
    {

        if($role = Role::whereSlug($this->argument('roleSlug'))->first()) {

            $userModel = resolve(config('auth.providers.users.model'));

            if($user = $userModel->where('email', $this->argument('email'))->first()) {

                $user->roles()->attach($role->id);

                return $this->info("Role `{$this->argument('roleSlug')}` attached to user of email `{$this->argument('email')}`");

            }

            return $this->error("User with email `{$this->argument('email')}` does not exist");

        }

        return $this->error("Role `{$this->argument('roleSlug')}` does not exist");
    }
}