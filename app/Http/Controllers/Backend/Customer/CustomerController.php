<?php

namespace App\Http\Controllers\Backend\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CrudMeta;

use App\Models\Customer;
use App\Models\CustomerComment;

use Session;
use Auth;

class CustomerController extends Controller
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
        'menu' => 'customer',
        'nav' => 'backend.customer.breadcrumb'
    ];
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function create()
    {
        if (!Auth::user()->can('customer.create')) return abort(401);
        return view('backend.customer.create')->with($this->data);
    }

    public function create_post(Request $request)
    {
        if (!Auth::user()->can('customer.create')) return abort(401);

        $request->validate([
            'first_name' => 'required|max:191',
            'last_name' => 'required|max:191',
            'email' => 'nullable|email|max:191',
            'phone' => 'nullable',
            'mobile' => 'required',
            'address_line_1' => 'nullable',
            'address_line_2' => 'nullable',
            'city' => 'nullable',
            'state' => 'nullable',
            'zip_code' => 'nullable',
            'comment.*' => 'nullable',
        ]);

        $data = New Customer();
        $data->first_name = $request->first_name;
        $data->last_name = $request->last_name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->mobile = $request->mobile;
        $data->address_line_1 = $request->address_line_1;
        $data->address_line_2 = $request->address_line_2;
        $data->city = $request->city;
        $data->state = $request->state;
        $data->zip_code = $request->zip_code;
        $data->save();
        
        if (isset($request->comment) && count($request->comment) > 0) {
            CustomerComment::where('customer_id', $data->id)->delete();

            foreach ($request->comment as $comment) {
                CustomerComment::create(['customer_id' => $data->id, 'text' => $comment]);
            }
        }

        $message = [ 'type' => 'success', 'message' => 'Customer data saved successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        if (Auth::user()->can('customer.edit')) {
            return redirect()->route('customerUpdate', ['data' => $data->id]);
        } else {
            return redirect()->back();
        }
    }

    public function records()
    {
        if (!Auth::user()->can('customer.history') && !Auth::user()->can('customer.view') && !Auth::user()->can('customer.edit') && !Auth::user()->can('customer.delete')) return abort(401);

        $customers = Customer::where('trash', 0)->orderBy('created_at', 'desc')->get();

        return view('backend.customer.records', compact('customers'))->with($this->data);
    }

    public function trashed_records()
    {
        if (!Auth::user()->can('customer.delete')) return abort(401);

        $customers = Customer::where('trash', 1)->orderBy('created_at', 'desc')->get();

        return view('backend.customer.trash', compact('customers'))->with($this->data);
    }

    public function trashed_comment_records(Customer $data)
    {
        if (!Auth::user()->can('customer.delete')) return abort(401);

        $customerComments = CustomerComment::where([['customer_id', $data->id],['trash', 1]])->orderBy('created_at', 'desc')->get();

        return view('backend.customer.comment-trash', compact('data','customerComments'))->with($this->data);
    }

    public function view(Customer $data)
    {
        if (!Auth::user()->can('customer.view')) return abort(401);

        $activityLog = $data->crudMeta();

        // dd($activityLog);

        if($data->comments) {
            foreach ($data->comments as $key => $value) {

                if ($value->crudMeta()) {
                    $activityLog = $activityLog->concat($value->crudMeta());
                }
            }
        }

        if ($activityLog) {
            $activityLog->sortBy([['id', 'desc']]);
        }

        // dd($activityLog);

        return view('backend.customer.view', compact('data', 'activityLog'))->with($this->data);
    }

    public function update(Customer $data)
    {
        if (!Auth::user()->can('customer.edit')) return abort(401);        

        return view('backend.customer.edit', compact('data'))->with($this->data);
    }

    public function update_post(Request $request, Customer $data)
    {
        if (!Auth::user()->can('customer.edit')) return abort(401);

        // dd($request);

        $request->validate([
            'first_name' => 'required|max:191',
            'last_name' => 'required|max:191',
            'email' => 'nullable|email|max:191',
            'phone' => 'nullable',
            'mobile' => 'required',
            'address_line_1' => 'nullable',
            'address_line_2' => 'nullable',
            'city' => 'nullable',
            'state' => 'nullable',
            'zip_code' => 'nullable',
            'comment.*' => 'nullable',
        ]);

        $data->first_name = $request->first_name;
        $data->last_name = $request->last_name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->mobile = $request->mobile;
        $data->address_line_1 = $request->address_line_1;
        $data->address_line_2 = $request->address_line_2;
        $data->city = $request->city;
        $data->state = $request->state;
        $data->zip_code = $request->zip_code;
        $data->save();
        
        if (!empty($request->comment)) {
            $availableCommentIds = array_keys($request->comment);

            if (!empty($availableCommentIds)) { 
                $trashed = CustomerComment::where('customer_id', $data->id)->whereNotIn('id', $availableCommentIds)->get();

                if ($trashed) {
                    foreach ($trashed as $key => $value) {
                        $value->trash = 1;
                        $value->save();
                    }
                }
            }

            foreach ($request->comment as $comment_id => $text) {
                $query = CustomerComment::where([['id', $comment_id],['customer_id', $data->id]]);

                if ($query->first()) {
                    if ($query->get()) {
                        foreach ($query->get() as $key => $value) {
                            $value->text = $text;
                            $value->save();
                        }
                    }
                } else {
                    CustomerComment::create([
                        'customer_id' => $data->id,
                        'text' => $text,
                    ]);
                }
            }
        } else {
            CustomerComment::where('customer_id', $data->id)->update(['trash' => 1]);
        }

        $message = [ 'type' => 'success', 'message' => 'Customer data updated successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->back();
    }

    public function remove(Customer $data)
    {
        if (!Auth::user()->can('customer.delete')) return abort(401);

        $data->trash = 1;
        $data->save();

        $message = [ 'type' => 'success', 'message' => 'Customer data removed successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('customerRecordsPage');
    }

    public function trashed_record_restore(Customer $data)
    {
        if (!Auth::user()->can('customer.delete')) return abort(401);

        $data->trash = 0;
        $data->save();

        $message = [ 'type' => 'success', 'message' => 'Trashed customer data restored successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('customerTrashRecordsPage');
    }

    public function trashed_comment_restore(Customer $data, CustomerComment $item)
    {
        if (!Auth::user()->can('customer.delete')) return abort(401);

        $item->trash = 0;
        $item->save();

        $message = [ 'type' => 'success', 'message' => 'Trashed customer comment restored successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('customerTrashCommentRecordsPage', ['data' => $data->id]);
    }

    public function remove_history_item(Customer $data, CrudMeta $item)
    {
        if (!Auth::user()->can('customer.delete')) return abort(401);

        $item->delete();

        $message = [ 'type' => 'success', 'message' => 'Customer history item removed successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('customerView', ['data' => $data->id]);
    }

    public function remove_multiple(Request $request)
    {
        if (!Auth::user()->can('customer.delete')) return abort(401);

        $ids = $request->get('selected_ids');
        $deleted = [];
        // $msg = 'Custom page changed successfully';

        if (count($ids) > 0) {
            foreach ($ids as $id) {
                $data = Customer::where('id',$id)->first();
                
                $data->trash = 1;
                $data->save();
                
                array_push($deleted, $id);
            }
        }

        return response()->json($deleted);
    }
}
