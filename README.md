# Laravel Access Control List (ACL) System

## Installation
### Step 1 
Include via Composer

```bash
composer require gurinder/laravel-acl
```
### Step 2
Publish the migration, views(If required to customize), config(Optional)
``` bash
php artisan vendor:publish --tag="acl::migrations"

// Optional
php artisan vendor:publish --tag="acl::views"

// Optional
php artisan vendor:publish --tag="acl::config"
```
### Step 3
Run migration
```bash
php artisan migrate
```
##Config File
Configuration file information

```php
return [

    // Cache key to store acl data
    'cache_key' => 'gurinder.laravel-acl',
    
    // Cache Exiration time
    'cache_expiration_time' => 60 * 24,
    
    // Route prefix e.g. 'example.com/admin
    'route_prefix' => 'admin',

    // Route name as e.g use -> route('admin.roles.index')
    'route_as' => 'admin.',

    // Roles which are not editable
    'freezed_roles' => ['admin'],
    
    // Permissions which are not editable
    'freezed_permissions' => ['manage-acl', 'manage-users'],
    
    // Link back to dashboard or anywhere else
    'back_link' => [
        'label' => 'Dashboard',
        'url'   => '/home'
    ],
    
    // Search column on user models
    'user_search_columns' => [
        'id',
        'name',
        'email'
    ]

];
```

##Usage
Add `AclGuarded` Trait to your user model
```php
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Gurinder\LaravelAcl\Traits\AclGuarded;

class User extends Authenticatable
{
    use Notifiable, AclGuarded;
    
    ...
}
```

Check if user has Role or Permission
```php
Gurinder\LaravelAcl\Package\Models\Role
Gurinder\LaravelAcl\Package\Models\Permission

$user->hasRole($role) // $role can be string, ID, or Role Model instance
$user->hasPermission($permission) // $permission can be string, ID, or Permission Model instance
```

You can also use permissions in blade directives
```php
@can('manage-acl')
    <!-- The Current User Can Manage Acl -->
@elsecan('manage-users')
    <!-- The Current User Can Manage Users -->
@endcan

// OR

@if (Auth::user()->can('manage-acl'))
    <!-- The Current User Can Manage Acl -->
@endif

```

## Database Seeding
Make factories for roles and models
```php
use Faker\Generator as Faker;

$factory->define(\Gurinder\LaravelAcl\Package\Models\Role::class, function (Faker $faker) {
    return [
        'name' => $faker->word
    ];
});

$factory->define(\Gurinder\LaravelAcl\Package\Models\Permission::class, function (Faker $faker) {
    return [
        'name' => $faker->word
    ];
});
```

```php
use Gurinder\LaravelAcl\Package\Models\Permission;
use Gurinder\LaravelAcl\Package\Models\Role;
use Illuminate\Database\Seeder;

class AccessControlListTableSeeder extends Seeder
{

    protected $roles = [ 'admin','editor'];

    protected $permissions = [
        'manage users',
        'manage acl',
        'manage posts'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = $this->seedRoles();
        $permissionsIds = $this->seedPermissions();
        $roles['admin']->syncPermissions($permissionsIds);
    }

    /**
     * @return array
     */
    protected function seedRoles()
    {
        $roles = [];
        
        foreach ($this->roles as $role) {
            $role = factory(Role::class)->create(['name' => $role, 'slug' => str_slug($role)]);
            $roles[$role->slug] = $role;
        }

        return $roles;
    }

    protected function seedPermissions()
    {
        $ids = [];

        foreach ($this->permissions as $label) {
            $ids[] = factory(Permission::class)->create(['name' => $label, 'slug' => str_slug($label)])->id;
        }

        return $ids;
    }
}
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.