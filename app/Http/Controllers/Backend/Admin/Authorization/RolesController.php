<?php

namespace App\Http\Controllers\Backend\Admin\Authorization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// use Illuminate\Support\Facades\DB;

use App\Models\Admin;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Helpers\Meta;

use Session;
use Auth;

class RolesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public $data = [
        'menu' => 'authorization',
        'nav' => 'backend.admin.authorization.breadcrumb'
    ];    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */       

    public function index()
    {
        if (!Auth::user()->can('roles.create') && !Auth::user()->can('roles.view') && !Auth::user()->can('roles.edit') && !Auth::user()->can('roles.delete')) return abort(401);

        $roles = Role::where('guard_name','admin')->get();
        $admins = Admin::select('id','name','email','image')->get();

        $roles = $roles->map(function($role) use ($admins) {
            
            $with_this_role_admins = $admins->reject(function ($admin, $key) use ($role) {
                return $admin->hasRole($role->name) === false;
            });

            $response_data = [];

            if ($with_this_role_admins) {
                foreach ($with_this_role_admins as $key => $value) {
                    $response_data[] = $value;
                }
            }

            $role->accounts = $response_data;

            return $role;
        });


        // dd($roles);

        return view('backend.admin.authorization.roles.index', compact('roles'))->with($this->data);
    }

    public function create()
    {
        if (!Auth::user()->can('roles.create')) return abort(401);

        $permissions = Meta::permissionGroups('admin');
        return view('backend.admin.authorization.roles.create', compact('permissions'))->with($this->data);
    }

    public function create_post(Request $request)
    {
        if (!Auth::user()->can('roles.create')) return abort(401);

        $request->validate([
            'name' => 'required|string|unique:roles',
            // 'group' => 'required|string',
        ]);

        $role = Role::create(['name' => $request->name, 'guard_name' => 'admin']);
        $permissions = $request->permissions;

        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        }

        $message = [ 'type' => 'success', 'message' => 'Role item added', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->back();
    }

    public function view(Role $data)
    {
        if (!Auth::user()->can('roles.view')) return abort(401);

        $permissions = Meta::permissionGroups('admin');
        return view('backend.admin.authorization.roles.view', compact('data','permissions'))->with($this->data);
    }

    public function edit(Role $data)
    {
        if (!Auth::user()->can('roles.edit')) return abort(401);

        $permissions = Meta::permissionGroups('admin');

        // dd(explode('.', $permissions['admin.account'][0]));

        return view('backend.admin.authorization.roles.edit', compact('data','permissions'))->with($this->data);
    }

    public function edit_post(Request $request, Role $data)
    {
        if (!Auth::user()->can('roles.edit')) return abort(401);

        // dd($request);

        $request->validate([
            'name' => 'required|exclude_unless:guard_name,admin|string|unique:roles,name,'.$data->id,
            // 'group' => 'required|string',
        ]);

        // $data->update(['name' => $request->name]);
        $data->name = $request->name;
        $data->save();

        $permissions = $request->permissions;

        $data->syncPermissions($permissions);

        $message = [ 'type' => 'success', 'message' => 'Role data updated', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->back();
    }

    public function remove(Role $data)
    {
        if (!Auth::user()->can('roles.delete')) return abort(401);

        $data->delete();

        $message = [ 'type' => 'success', 'message' => 'Role item removed successfully', 'title' => 'Success' ];
        Session::flash('message', $message);
        return redirect()->route('adminRoles');
    }

    public function remove_multiple(Request $request)
    {
        if (!Auth::user()->can('roles.delete')) return abort(401);

        $ids = $request->get('selected_ids');
        $deleted = [];
        // $msg = 'Custom page changed successfully';

        if (count($ids) > 0) {
            foreach ($ids as $id) {
                $data = Role::where('id',$id)->first();
                $data->delete();

                array_push($deleted, $id);
            }
        }

        return response()->json($deleted);
    }
}
