<?php

namespace App\Http\Controllers\Backend\IpAllowList;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CrudMeta;

use App\Models\AllowedIpAddress;

use Session;
use Auth;

class IpAllowListController extends Controller
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
        'menu' => 'IpAllowList',
        'nav' => 'backend.ip_allow_list.breadcrumb'
    ];
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        if (!Auth::user()->can('ipallowlist.history') && !Auth::user()->can('ipallowlist.view') && !Auth::user()->can('ipallowlist.edit') && !Auth::user()->can('ipallowlist.delete')) return abort(401);

        $ipAllowLists = AllowedIpAddress::where('trash', 0)->orderBy('created_at', 'desc')->get();

        return view('backend.ip_allow_list.index', compact('ipAllowLists'))->with($this->data);
    }

    public function index_post(Request $request)
    {
        if (!Auth::user()->can('ipallowlist.create')) return abort(401);

        $request->validate([
            'ip_address' => 'required|ip|unique:allowed_ip_addresses',
            'note' => 'nullable|max:191',
        ]);

        $data = New AllowedIpAddress();
        $data->ip_address = $request->ip_address;
        $data->note = $request->note;
        $data->save();

        $message = [ 'type' => 'success', 'message' => 'Allowed ip address data added successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->back();
    }

    public function trashed_records()
    {
        if (!Auth::user()->can('ipallowlist.delete')) return abort(401);

        $ipAllowLists = AllowedIpAddress::where('trash', 1)->orderBy('created_at', 'desc')->get();

        return view('backend.ip_allow_list.trash', compact('ipAllowLists'))->with($this->data);
    }

    public function view(AllowedIpAddress $data)
    {
        if (!Auth::user()->can('ipallowlist.view')) return abort(401);

        $activityLog = $data->crudMeta();

        // dd($activityLog);

        if ($activityLog) {
            $activityLog->sortBy([['id', 'desc']]);
        }

        // dd($activityLog);

        return view('backend.ip_allow_list.view', compact('data', 'activityLog'))->with($this->data);
    }

    public function update(AllowedIpAddress $data)
    {
        if (!Auth::user()->can('ipallowlist.edit')) return abort(401);        

        return view('backend.ip_allow_list.edit', compact('data'))->with($this->data);
    }

    public function update_post(Request $request, AllowedIpAddress $data)
    {
        if (!Auth::user()->can('ipallowlist.edit')) return abort(401);

        // dd($request);

        $request->validate([
            'ip_address' => 'required|ip|unique:allowed_ip_addresses,ip_address,'.$data->id,
            'note' => 'nullable|max:191',
        ]);

        $data->ip_address = $request->ip_address;
        $data->note = $request->note;
        $data->save();

        $message = [ 'type' => 'success', 'message' => 'Allowed ip address data updated successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->back();
    }

    public function remove(AllowedIpAddress $data)
    {
        if (!Auth::user()->can('ipallowlist.delete')) return abort(401);

        $data->trash = 1;
        $data->save();

        $message = [ 'type' => 'success', 'message' => 'Allowed ip address data removed successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('ipAllowListRecordsPagePost');
    }

    public function trashed_record_restore(AllowedIpAddress $data)
    {
        if (!Auth::user()->can('ipallowlist.delete')) return abort(401);

        $data->trash = 0;
        $data->save();

        $message = [ 'type' => 'success', 'message' => 'Trashed ip address restored successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('ipAllowListTrashRecordsPage');
    }

    public function remove_history_item(AllowedIpAddress $data, CrudMeta $item)
    {
        if (!Auth::user()->can('ipallowlist.delete')) return abort(401);

        $item->delete();

        $message = [ 'type' => 'success', 'message' => 'Allowed ip address history item removed successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('ipAllowListView', ['data' => $data->id]);
    }

    public function remove_multiple(Request $request)
    {
        if (!Auth::user()->can('ipallowlist.delete')) return abort(401);

        $ids = $request->get('selected_ids');
        $deleted = [];
        // $msg = 'Custom page changed successfully';

        if (count($ids) > 0) {
            foreach ($ids as $id) {
                $data = AllowedIpAddress::where('id',$id)->first();

                $data->trash = 1;
                $data->save();
                
                array_push($deleted, $id);
            }
        }

        return response()->json($deleted);
    }
}
