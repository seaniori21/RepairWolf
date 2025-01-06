<?php

namespace App\Http\Controllers\Backend\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helpers\OrderHelper;

use App\Models\Order;
use App\Models\CrudMeta;

use App\Models\PaymentType;
use App\Models\Payment;

use Session;
use Auth;

class PaymentController extends Controller
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

    public function create()
    {
        if (!Auth::user()->can('payment.create')) return abort(401);
        return view('backend.payment.create')->with($this->data);
    }

    public function create_post(Request $request)
    {
        if (!Auth::user()->can('payment.create')) return abort(401);

        $request->validate([
            'payment_type' => 'required',
            'order' => 'required',
            'amount' => 'required|numeric|min:0',
            'authorization_approval_code' => 'nullable',
            'credit_card_number' => 'nullable',
            'expiration_date' => 'nullable',
            'security_code' => 'nullable',
        ]);

        $data = New Payment();
        $data->payment_type_id = $request->payment_type;
        $data->order_id = $request->order;
        $data->amount = $request->amount;
        $data->authorization_approval_code = $request->authorization_approval_code;
        $data->credit_card_number = $request->credit_card_number;
        $data->expiration_date = $request->expiration_date;
        $data->security_code = $request->security_code;
        $data->save();

        $orderData = Order::where('id', $request->order)->first();
        if ($orderData) { OrderHelper::syncCalculations($orderData); }

        $message = [ 'type' => 'success', 'message' => 'Payment data saved successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        if (Auth::user()->can('payment.edit')) {
            return redirect()->route('paymentUpdate', ['data' => $data->id]);
        } else {
            return redirect()->back();
        }
    }

    public function records()
    {
        if (!Auth::user()->can('payment.history') && !Auth::user()->can('payment.view') && !Auth::user()->can('payment.edit') && !Auth::user()->can('payment.delete')) return abort(401);

        $payments = Payment::where('trash', 0)->orderBy('created_at', 'desc')->get();

        return view('backend.payment.records', compact('payments'))->with($this->data);
    }

    public function trashed_records()
    {
        if (!Auth::user()->can('payment.delete')) return abort(401);

        $payments = Payment::where('trash', 1)->orderBy('created_at', 'desc')->get();

        return view('backend.payment.trash', compact('payments'))->with($this->data);
    }

    public function view(Payment $data)
    {
        if (!Auth::user()->can('payment.view')) return abort(401);

        $activityLog = $data->crudMeta();

        // dd($activityLog);

        if ($activityLog) {
            $activityLog->sortBy([['id', 'desc']]);
        }

        // dd($activityLog);

        return view('backend.payment.view', compact('data', 'activityLog'))->with($this->data);
    }

    public function update(Payment $data)
    {
        if (!Auth::user()->can('payment.edit')) return abort(401);        

        return view('backend.payment.edit', compact('data'))->with($this->data);
    }

    public function update_post(Request $request, Payment $data)
    {
        if (!Auth::user()->can('payment.edit')) return abort(401);

        // dd($request);

        $request->validate([
            'payment_type' => 'required',
            'order' => 'required',
            'amount' => 'required|numeric|min:0',
            'authorization_approval_code' => 'nullable',
            'credit_card_number' => 'nullable',
            'expiration_date' => 'nullable',
            'security_code' => 'nullable',
        ]);

        $data->payment_type_id = $request->payment_type;
        $data->order_id = $request->order;
        $data->amount = $request->amount;
        $data->authorization_approval_code = $request->authorization_approval_code;
        $data->credit_card_number = $request->credit_card_number;
        $data->expiration_date = $request->expiration_date;
        $data->security_code = $request->security_code;
        $data->save();

        $orderData = Order::where('id', $request->order)->first();
        if ($orderData) { OrderHelper::syncCalculations($orderData); }

        $message = [ 'type' => 'success', 'message' => 'Payment data updated successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->back();
    }

    public function remove(Payment $data)
    {
        if (!Auth::user()->can('payment.delete')) return abort(401);

        $data->trash = 1;
        $data->save();

        $orderData = Order::where('id', $data->order_id)->first();
        if ($orderData) { OrderHelper::syncCalculations($orderData); }

        $message = [ 'type' => 'success', 'message' => 'Payment data removed successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('paymentRecordsPage');
    }

    public function trashed_record_restore(Payment $data)
    {
        if (!Auth::user()->can('payment.delete')) return abort(401);

        $data->trash = 0;
        $data->save();

        $orderData = Order::where('id', $data->order_id)->first();
        if ($orderData) { OrderHelper::syncCalculations($orderData); }

        $message = [ 'type' => 'success', 'message' => 'Trashed payment data restored successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('paymentTrashRecordsPage');
    }

    public function remove_history_item(Payment $data, CrudMeta $item)
    {
        if (!Auth::user()->can('payment.delete')) return abort(401);

        $item->delete();

        $message = [ 'type' => 'success', 'message' => 'Payment history item removed successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('paymentView', ['data' => $data->id]);
    }

    public function remove_multiple(Request $request)
    {
        if (!Auth::user()->can('payment.delete')) return abort(401);

        $ids = $request->get('selected_ids');
        $deleted = [];
        // $msg = 'Custom page changed successfully';

        if (count($ids) > 0) {
            foreach ($ids as $id) {
                $data = Payment::where('id',$id)->first();

                $data->trash = 1;
                $data->save();
                
                $orderData = Order::where('id', $data->order_id)->first();
                if ($orderData) { OrderHelper::syncCalculations($orderData); }

                array_push($deleted, $id);
            }
        }

        return response()->json($deleted);
    }
}
