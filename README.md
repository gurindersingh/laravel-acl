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
## Step 4 - Config File
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

    // Master roles - All new permission will be added to these roles automatically.
    'master_roles' => ['admin'],

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
## Step 5 - Add function
Add required function to your `helpers.php` file
```php
if (!function_exists('isAdmin')) {

    /**
     * Check if user is admin admin
     *
     * @param \App\Models\User|null $user
     * @return bool
     */
    function isAdmin(\App\Models\User $user = null)
    {
        return $user ? $user->isAdmin() : (auth()->check() && auth()->user()->isAdmin());
    }

}
```

## Step 6 - Add middleware
```php
protected $routeMiddleware = [
    // ...
    'permissions'   => \Gurinder\LaravelAcl\Middlewares\PermissionsMiddleware::class,
    'roles'         => \Gurinder\LaravelAcl\Middlewares\RolesMiddleware::class
];
```

## Usage
Add `AclGuarded` Trait to your user model & `isAdmin()` to your `User` model
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
    // The Current User Can Manage Acl
@elsecan('manage-users')
    // The Current User Can Manage Users
@endcan

// OR

@if (Auth::user()->can('manage-acl'))
    // The Current User Can Manage Acl
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
Make Seeder Class

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

### In Routes
```php
Route::group(['middleware' => ['role:super-admin']], function () {
    //
});

Route::group(['middleware' => ['permission:manage-users']], function () {
    //
});

// Multiple
Route::group(['middleware' => ['role:super-admin|admin','permission:manage-users|manage-posts']], function () {
    //
});
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.