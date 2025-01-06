<?php

namespace App\Http\Controllers\Backend\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CrudMeta;

use App\Models\PaymentType;

use Session;
use Auth;

class PaymentTypeController extends Controller
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
        'menu' => 'payment',
        'nav' => 'backend.payment.breadcrumb'
    ];
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        if (!Auth::user()->can('paymentType.history') && !Auth::user()->can('paymentType.create')  && !Auth::user()->can('paymentType.view') && !Auth::user()->can('paymentType.edit') && !Auth::user()->can('paymentType.delete')) return abort(401);

        $paymentTypes = PaymentType::where('trash', 0)->orderBy('created_at', 'desc')->get();

        return view('backend.payment.types.index', compact('paymentTypes'))->with($this->data);
    }

    public function index_post(Request $request)
    {
        if (!Auth::user()->can('paymentType.create')) return abort(401);

        $request->validate([
            'name' => 'required|max:191',
        ]);

        $data = New PaymentType();
        $data->name = $request->name;
        $data->save();

        $message = [ 'type' => 'success', 'message' => 'Payment type data added successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        if (Auth::user()->can('paymentType.edit')) {
            return redirect()->route('paymentTypeUpdate', ['data' => $data->id]);
        } else {
            return redirect()->back();
        }
    }

    public function trashed_records()
    {
        if (!Auth::user()->can('paymentType.delete')) return abort(401);

        $paymentTypes = PaymentType::where('trash', 1)->orderBy('created_at', 'desc')->get();

        return view('backend.payment.types.trash', compact('paymentTypes'))->with($this->data);
    }

    public function view(PaymentType $data)
    {
        if (!Auth::user()->can('paymentType.view')) return abort(401);

        $activityLog = $data->crudMeta();

        // dd($activityLog);

        if ($activityLog) {
            $activityLog->sortBy([['id', 'desc']]);
        }

        // dd($activityLog);

        return view('backend.payment.types.view', compact('data', 'activityLog'))->with($this->data);
    }

    public function update(PaymentType $data)
    {
        if (!Auth::user()->can('paymentType.edit')) return abort(401);        

        return view('backend.payment.types.edit', compact('data'))->with($this->data);
    }

    public function update_post(Request $request, PaymentType $data)
    {
        if (!Auth::user()->can('paymentType.edit')) return abort(401);

        // dd($request);

        $request->validate([
            'name' => 'required|max:191',
        ]);

        $data->name = $request->name;
        $data->save();

        $message = [ 'type' => 'success', 'message' => 'Payment type data updated successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->back();
    }

    public function remove(PaymentType $data)
    {
        if (!Auth::user()->can('paymentType.delete')) return abort(401);

        $data->trash = 1;
        $data->save();

        $message = [ 'type' => 'success', 'message' => 'Payment type data removed successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('paymentTypeRecordsPage');
    }

    public function trashed_record_restore(PaymentType $data)
    {
        if (!Auth::user()->can('paymentType.delete')) return abort(401);

        $data->trash = 0;
        $data->save();

        $message = [ 'type' => 'success', 'message' => 'Trashed payment type data restored successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('paymentTypeTrashRecordsPage');
    }

    public function remove_history_item(PaymentType $data, CrudMeta $item)
    {
        if (!Auth::user()->can('paymentType.delete')) return abort(401);

        $item->delete();

        $message = [ 'type' => 'success', 'message' => 'Payment type history item removed successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('paymentTypeView', ['data' => $data->id]);
    }

    public function remove_multiple(Request $request)
    {
        if (!Auth::user()->can('paymentType.delete')) return abort(401);

        $ids = $request->get('selected_ids');
        $deleted = [];
        // $msg = 'Custom page changed successfully';

        if (count($ids) > 0) {
            foreach ($ids as $id) {
                $data = PaymentType::where('id',$id)->first();

                $data->trash = 1;
                $data->save();
                
                array_push($deleted, $id);
            }
        }

        return response()->json($deleted);
    }
}
