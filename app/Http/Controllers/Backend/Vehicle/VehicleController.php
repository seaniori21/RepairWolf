<?php

namespace App\Http\Controllers\Backend\Vehicle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CrudMeta;

use App\Models\Vehicle;

use Session;
use Auth;

class VehicleController extends Controller
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
        'menu' => 'vehicle',
        'nav' => 'backend.vehicle.breadcrumb'
    ];
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function create()
    {
        if (!Auth::user()->can('vehicle.create')) return abort(401);
        return view('backend.vehicle.create')->with($this->data);
    }

    public function create_post(Request $request)
    {
        if (!Auth::user()->can('vehicle.create')) return abort(401);

        $request->validate([
            'customer' => 'required',
            'license_plate' => 'required|max:191|unique:vehicles',
            'vin' => 'required|max:191|unique:vehicles',
            'year' => 'required|max:191',
            'make' => 'required|max:191',
            'model' => 'required|max:191',
            'body_type' => 'nullable|max:191',
            'trim' => 'nullable|max:191',
            'color' => 'required|max:191',
        ]);

        $data = New Vehicle();
        $data->customer_id = $request->customer;
        $data->license_plate = $request->license_plate;
        $data->vin = $request->vin;
        $data->year = $request->year;
        $data->make = $request->make;
        $data->model = $request->model;
        $data->body_type = $request->body_type;
        $data->trim = $request->trim;
        $data->color = $request->color;
        $data->save();

        $message = [ 'type' => 'success', 'message' => 'Vehicle data saved successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        if (Auth::user()->can('vehicle.edit')) {
            return redirect()->route('vehicleUpdate', ['data' => $data->id]);
        } else {
            return redirect()->back();
        }
    }

    public function records()
    {
        if (!Auth::user()->can('vehicle.history') && !Auth::user()->can('vehicle.view') && !Auth::user()->can('vehicle.edit') && !Auth::user()->can('vehicle.delete')) return abort(401);

        $vehicles = Vehicle::where('trash', 0)->orderBy('created_at', 'desc')->get();

        return view('backend.vehicle.records', compact('vehicles'))->with($this->data);
    }

    public function trashed_records()
    {
        if (!Auth::user()->can('vehicle.delete')) return abort(401);

        $vehicles = Vehicle::where('trash', 1)->orderBy('created_at', 'desc')->get();

        return view('backend.vehicle.trash', compact('vehicles'))->with($this->data);
    }

    public function view(Vehicle $data)
    {
        if (!Auth::user()->can('vehicle.view')) return abort(401);

        $activityLog = $data->crudMeta();

        // dd($activityLog);

        if ($activityLog) {
            $activityLog->sortBy([['id', 'desc']]);
        }

        // dd($activityLog);

        return view('backend.vehicle.view', compact('data', 'activityLog'))->with($this->data);
    }

    public function update(Vehicle $data)
    {
        if (!Auth::user()->can('vehicle.edit')) return abort(401);        

        return view('backend.vehicle.edit', compact('data'))->with($this->data);
    }

    public function update_post(Request $request, Vehicle $data)
    {
        if (!Auth::user()->can('vehicle.edit')) return abort(401);

        // dd($request);

        $request->validate([
            'customer' => 'required',
            'license_plate' => 'required|max:191|unique:vehicles,license_plate,'.$data->id,
            'vin' => 'required|max:191|unique:vehicles,vin,'.$data->id,
            'year' => 'required|max:191',
            'make' => 'required|max:191',
            'model' => 'required|max:191',
            'body_type' => 'nullable|max:191',
            'trim' => 'nullable|max:191',
            'color' => 'required|max:191',
        ]);

        $data->customer_id = $request->customer;
        $data->license_plate = $request->license_plate;
        $data->vin = $request->vin;
        $data->year = $request->year;
        $data->make = $request->make;
        $data->model = $request->model;
        $data->body_type = $request->body_type;
        $data->trim = $request->trim;
        $data->color = $request->color;
        $data->save();

        $message = [ 'type' => 'success', 'message' => 'Vehicle data updated successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->back();
    }

    public function remove(Vehicle $data)
    {
        if (!Auth::user()->can('vehicle.delete')) return abort(401);

        $data->trash = 1;
        $data->save();

        $message = [ 'type' => 'success', 'message' => 'Vehicle data removed successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('vehicleRecordsPage');
    }

    public function trashed_record_restore(Vehicle $data)
    {
        if (!Auth::user()->can('vehicle.delete')) return abort(401);

        $data->trash = 0;
        $data->save();

        $message = [ 'type' => 'success', 'message' => 'Trashed vehicle data restored successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('vehicleTrashRecordsPage');
    }

    public function remove_history_item(Vehicle $data, CrudMeta $item)
    {
        if (!Auth::user()->can('vehicle.delete')) return abort(401);

        $item->delete();

        $message = [ 'type' => 'success', 'message' => 'Vehicle history item removed successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('vehicleView', ['data' => $data->id]);
    }

    public function remove_multiple(Request $request)
    {
        if (!Auth::user()->can('vehicle.delete')) return abort(401);

        $ids = $request->get('selected_ids');
        $deleted = [];
        // $msg = 'Custom page changed successfully';

        if (count($ids) > 0) {
            foreach ($ids as $id) {
                $data = Vehicle::where('id',$id)->first();

                $data->trash = 1;
                $data->save();
                
                array_push($deleted, $id);
            }
        }

        return response()->json($deleted);
    }
}
