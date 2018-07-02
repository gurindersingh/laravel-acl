<?php

namespace Gurinder\LaravelAcl\Http\Controllers;

use Illuminate\Http\Request;
use Gurinder\LaravelAcl\Package\Models\Role;
use Gurinder\LaravelAcl\Package\Models\Permission;
use Gurinder\LaravelAcl\Contracts\AclLedgerContract;

class RolesController extends Controller
{

    public function index(AclLedgerContract $ledger)
    {
        $roles = $ledger->getRoles(true, request('page') ?? 1, 15);

        return view('acl::roles.index', compact('roles'));
    }

    public function create(AclLedgerContract $ledger)
    {
        $permissions = $ledger->getPermissions()->groupByFirstLetter('name');

        return view('acl::roles.create', compact('permissions'));
    }

    public function store(Request $request, AclLedgerContract $ledger)
    {
        $slug = str_slug($request->role_name);

        $data = $request->validate([
            'role_name'     => [
                'required',
                'unique:roles,name',
                function ($attribute, $value, $fail) use ($slug) {
                    if (Role::whereSlug($slug)->exists()) {
                        return $fail("Choose Different name, its already been used");
                    }
                    return $value;
                }
            ],
            'permissions'   => 'array|nullable',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::create([
            'name' => $request->role_name,
            'slug' => $slug
        ]);

        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        $ledger->reset();

        return redirect()->route(config('acl.route_as') . 'roles.index');
    }

    public function edit(Request $request, $role)
    {
        $role = Role::whereId($role)->with(['permissions'])->firstOrFail();

        if (collect(config('acl.freezed_roles'))->contains($role->slug)) {
            abort(403, "This role can not be edited");
        }

        $permissions = Permission::get()->groupByFirstLetter('name');

        return view('acl::roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, AclLedgerContract $ledger, $role)
    {
        $role = Role::whereId($role)->firstOrFail();

        if (collect(config('acl.freezed_roles'))->contains($role->slug)) {
            abort(403, "This role can not be edited");
        }

        $data = $request->validate([
            'permissions'   => 'array|nullable',
            'permissions.*' => 'exists:permissions,id'
        ]);

        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        $ledger->reset();

        return redirect()->route(config('acl.route_as') . 'roles.index');
    }

    public function destroy(Request $request, AclLedgerContract $ledger, $role)
    {
        $role = Role::whereId($role)->firstOrFail();

        if (collect(config('acl.freezed_roles'))->contains($role->slug)) {
            abort(403, "This role can not be edited");
        }

        $role->delete();

        $ledger->reset();

        return redirect()->route(config('acl.route_as') . 'roles.index');
    }
}