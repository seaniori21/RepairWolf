<?php

namespace App\Http\Controllers\Backend\Admin\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Admin;
use Spatie\Permission\Models\Role;

use Session;
use Hash;
use Gate;
use Auth;

class AccountsController extends Controller
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
        'menu' => 'mngadmin',
        'nav' => 'backend.admin.accounts.breadcrumb'
    ];
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function records()
    {
        if (!Auth::user()->can('account.history') && !Auth::user()->can('account.view') && !Auth::user()->can('account.edit') && !Auth::user()->can('account.delete')) return abort(401);

        $admins = Admin::where('trash', 0)->orderBy('created_at', 'desc')->get();
        return view('backend.admin.accounts.records', compact('admins'))->with($this->data);
    }

    public function trashed_records()
    {
        if (!Auth::user()->can('account.delete')) return abort(401);

        $admins = Admin::where('trash', 1)->orderBy('created_at', 'desc')->get();

        return view('backend.admin.accounts.trash', compact('admins'))->with($this->data);
    }

    public function view(Admin $admin_data)
    {
        if (!Auth::user()->can('account.view')) return abort(401);

        $activityLog = $admin_data->crudMeta();

        // dd($activityLog);

        if ($activityLog) {
            $activityLog->sortBy([['id', 'desc']]);
        }

        // dd($activityLog);

        return view('backend.admin.accounts.view', compact('admin_data', 'activityLog'))->with($this->data);
    }

    public function update($id)
    {
        if (!Auth::user()->can('account.edit')) return abort(401);

        $admin = Admin::where('id', $id)->first();
        $roles = Role::where('guard_name','admin')->get();

        return view('backend.admin.accounts.edit', compact('admin','roles'))->with($this->data);
    }

    public function update_post(Request $request, Admin $admin_data)
    {
        if (!Auth::user()->can('account.edit')) return abort(401);

        $request->validate([
            'name' => 'required|max:191',
            'email' => 'required|email|max:191|unique:admins,email,'.$admin_data->id,
            'password' => 'nullable|min:4',
            'image' => 'nullable',
            'roles' => 'required',
            // 'timezone' => 'required',
        ]);

        $data = $admin_data;
        $data->name = $request->name;
        $data->email = $request->email;
        // $data->tz = $request->timezone;

        if ($request->password) {
            $data->password = Hash::make($request->password);
        }

        if ($request->image) {
            $data->image = $request->image;
        }
        
        $data->save();

        if ($request->roles) {
            $data->syncRoles($request->roles);
        }

        $message = [ 'type' => 'success', 'message' => 'Account updated', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->back();
    }

    public function create()
    {
        if (!Auth::user()->can('account.create')) return abort(401);

        $roles = Role::where('guard_name','admin')->get();
        return view('backend.admin.accounts.create', compact('roles'))->with($this->data);
    }

    public function create_post(Request $request)
    {
        if (!Auth::user()->can('account.create')) return abort(401);

        $request->validate([
            'name' => 'required|max:191',
            'email' => 'required|email|max:191|unique:admins',
            'password' => 'nullable|min:4',
            'image' => 'required',
            'roles' => 'required',
            // 'timezone' => 'required',
        ]);

        // dd($admin_data);

        $data = New Admin();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->image = $request->image;
        // $data->tz = $request->timezone;
        $data->password = Hash::make($request->password);
        $data->save();
        
        if ($request->roles) {
            $data->assignRole($request->roles);
        }

        // $data->roles()->attach($request->roles);

        $message = [ 'type' => 'success', 'message' => 'Account created successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->back();
    }

    public function status(Request $request)
    {
        if (!Auth::user()->can('account.delete')) return abort(401);

        $id = $request->get('ids');
        $status = $request->get('status');
        $msg = 'Account status changed successfully';

    
        $data = Admin::where('id',$id)->first();
        
        if ($status === "true") {
            $data->status = 1;
        } else {
            $data->status = 0;            
        }

        $data->save();

        return response()->json($msg);
    }

    public function trashed_record_restore(Admin $data)
    {
        if (!Auth::user()->can('account.delete')) return abort(401);

        $data->trash = 0;
        $data->save();

        $message = [ 'type' => 'success', 'message' => 'Trashed admin data restored successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('adminTrashRecordsPage');
    }

    public function remove_history_item(Admin $data, CrudMeta $item)
    {
        if (!Auth::user()->can('account.delete')) return abort(401);

        $item->delete();

        $message = [ 'type' => 'success', 'message' => 'Admin account\'s  history item removed successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('adminView', ['data' => $data->id]);
    }

    public function remove(Admin $admin_data)
    {
        if (!Auth::user()->can('account.delete')) return abort(401);

        // dd($admin_data);
        $admin_data->trash = 1;
        $admin_data->save();

        // $admin_data->roles()->detach();
        // $admin_data->delete();

        $message = [ 'type' => 'success', 'message' => 'Account removed successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('adminRecordsPage');
    }

    public function remove_multiple(Request $request)
    {
        if (!Auth::user()->can('account.delete')) return abort(401);

        $ids = $request->get('selected_ids');
        $deleted = [];
        // $msg = 'Custom page changed successfully';

        if (count($ids) > 0) {
            foreach ($ids as $id) {
                $data = Admin::where('id',$id)->first();
                // $data->delete();

                $data->trash = 1;
                $data->save();
                
                array_push($deleted, $id);
            }
        }

        return response()->json($deleted);
    }
}
