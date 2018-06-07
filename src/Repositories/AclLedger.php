<?php

namespace Gurinder\LaravelAcl\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Gurinder\LaravelAcl\Package\Models\Role;
use Illuminate\Pagination\LengthAwarePaginator;
use Gurinder\LaravelAcl\Package\Models\Permission;
use Gurinder\LaravelAcl\Contracts\AclLedgerContract;

class AclLedger implements AclLedgerContract
{

    protected $usersAcl = [];

    /**
     * @param bool $paginate
     * @param int  $page
     * @param int  $perPage
     * @return Collection|LengthAwarePaginator|mixed
     */
    public function getRoles($paginate = false, $page = 1, $perPage = 15)
    {
        $roles = Cache::remember($this->getCacheKey('roles'), $this->getExpiration(), function () {
            return Role::orderBy('name')->with([
                'permissions' => function ($query) {
                    $query->orderBy('name');
                }
            ])->get();
        });

        return $paginate ? $this->paginate($roles, $page, $perPage, ['path' => request()->getBaseUrl()]) : $roles;

    }

    /**
     * @param bool $paginate
     * @param int  $page
     * @param int  $perPage
     * @return Collection|LengthAwarePaginator|mixed
     */
    public function getPermissions($paginate = false, $page = 1, $perPage = 15)
    {
        $permissions = Cache::remember($this->getCacheKey('permissions'), $this->getExpiration(), function () {
            return Permission::orderBy('name')->with([
                'roles' => function ($query) {
                    $query->orderBy('name');
                }
            ])->get();
        });

        return $paginate ? $this->paginate($permissions, $page, $perPage, ['path' => request()->getBaseUrl()]) : $permissions;

    }

    /**
     * @param $user
     * @return mixed
     */
    public function getUserAcl($user)
    {
        if (isset($this->usersAcl[$user->id])) {
            return $this->usersAcl[$user->id];
        }

        $acls = Cache::remember($this->getCacheKey('user', $user), $this->getExpiration(), function () use ($user) {
            return $user->rolesWithPermissions()->get();
        });

        $acls->each(function ($item, $key) use ($user) {

            $roleSlug = $item['slug'];
            $this->usersAcl[$user->id]['roles'][] = $roleSlug;

            collect($item['permissions'])->each(function ($item, $key) use ($user) {
                $permissionSlug = $item['slug'];
                $this->usersAcl[$user->id]['permissions'][] = $permissionSlug;
            });

        });

        return $this->usersAcl[$user->id];
    }

    /**
     * @param       $items
     * @param null  $page
     * @param int   $perPage
     * @param array $options
     * @return LengthAwarePaginator|mixed
     */
    public function paginate($items, $page = null, $perPage = 15, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    /**
     * @param null $for
     * @param null $user
     * @return string
     */
    protected function getCacheKey($for = null, $user = null)
    {
        $configKey = config('acl.cache_key');

        $key = $for == 'user' ? "{$configKey}.acl.user.{$user->id}" : "{$configKey}.{$for}";

        return md5($key);
    }

    /**
     * @return float|\Illuminate\Config\Repository|int|mixed
     */
    protected function getExpiration()
    {
        return config('acl.cache_expiration_time') ?? 60 * 24;
    }

    /**
     * @return mixed|void
     */
    public function reset()
    {
        Cache::forget($this->getCacheKey('roles'));
        Cache::forget($this->getCacheKey('permissions'));
    }

    public function resetUserAcl($user)
    {
        Cache::forget($this->getCacheKey('user', $user));
    }

}