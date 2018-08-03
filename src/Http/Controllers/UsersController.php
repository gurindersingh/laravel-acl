<?php

namespace Gurinder\LaravelAcl\Http\Controllers;


use Gurinder\LaravelAcl\Contracts\AclLedgerContract;
use Illuminate\Http\Request;

class UsersController extends Controller
{

    protected $userModel;

    public function __construct()
    {
        $this->userModel = resolve(config('auth.providers.users.model'));
    }

    public function index()
    {
        $query = $this->userModel;

        if ($q = request()->get('q')) {

            $columns = config('acl.user_search_columns');

            foreach ($columns as $column) {
                $query = $query->orWhere($column, 'LIKE', '%' . $q . '%');
            }
        }

        $users = $query->paginate(10);

        if(config('acl.custom_views')) {
            return view('acl.users.index', compact('users'));
        }

        return view('acl::users.index', compact('users'));
    }

    public function edit(Request $request, AclLedgerContract $ledger, $user)
    {
        $user = $this->userModel->whereId($user)->with('roles')->firstOrFail();

        $roles = $ledger->getRoles();

        if(config('acl.custom_views')) {
            return view('acl.users.edit', compact('user', 'roles'));
        }

        return view('acl::users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $user)
    {
        if (!auth()->user()->can('manage-acl')) {
            abort(403, "Unauthorize");
        }

        $user = $this->userModel->whereId($user)->firstOrFail();

        $data = $request->validate([
            'roles'   => 'array|nullable',
            'roles.*' => 'exists:roles,id'
        ]);

        $user->syncRoles($data['roles']);

        return redirect()->route(config('acl.route_as') . 'users.index', $user->id);
    }

}