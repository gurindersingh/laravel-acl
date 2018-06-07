<?php

namespace Gurinder\LaravelAcl\Http\Controllers;

use Illuminate\Http\Request;
use Gurinder\LaravelAcl\Package\Models\Role;
use Gurinder\LaravelAcl\Package\Models\Permission;
use Gurinder\LaravelAcl\Contracts\AclLedgerContract;

class PermissionsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(AclLedgerContract $ledger)
    {
        $permissions = $ledger->getPermissions(true, request('page') ?? 1, 15);

        return view('acl::permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('acl::permissions.create');
    }

    public function store(Request $request, AclLedgerContract $ledger)
    {
        $slug = str_slug($request->permission_name);

        $request->validate([
            'permission_name' => [
                'required',
                'unique:permissions,name',
                function ($attribute, $value, $fail) use ($slug) {
                    if (Permission::whereSlug($slug)->exists()) {
                        return $fail("Choose Different name, its already been used");
                    }
                    return $value;
                }
            ],
            'roles'           => 'array|nullable',
            'roles.*'         => 'exists:roles,id'
        ]);

        $permission = Permission::create([
            'name' => $request->permission_name,
            'slug' => $slug
        ]);

        $this->assignPermissionToMasterRoles($permission);

        $ledger->reset();

        return redirect()->route(config('acl.route_as') . 'permissions.index');
    }

    public function destroy(Request $request, AclLedgerContract $ledger, $permission)
    {
        $permission = Permission::whereId($permission)->firstOrFail();

        if (collect(config('acl.freezed_permissions'))->contains($permission->slug)) {
            abort(403, "This Permission can not be edited");
        }

        $permission->delete();

        $ledger->reset();

        return redirect()->route(config('acl.route_as') . 'permissions.index');
    }

    protected function assignPermissionToMasterRoles($permission)
    {
        $roles = config('acl.master_roles');

        if(is_array($roles)) {
            foreach ($roles as $role) {
                if($role = Role::whereSlug($role)->first()) {
                    $role->permissions()->attach($permission->id);
                }
            }
        }

        if(is_string($roles)) {
            if($role = Role::whereSlug($role)->first()) {
                $role->permissions()->attach($permission->id);
            }
        }
    }
}