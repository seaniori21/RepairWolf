<?php

namespace App\Http\Controllers\Backend\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helpers\Notification\Customer as CustomerNofify;
use App\Helpers\Notification\Twilio;

use App\Models\Customer;
use App\Models\CrudMeta;

// temp
use Illuminate\Support\Carbon;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
// temp

use Illuminate\Support\Facades\DB;

use Session;
use Auth;

class NotificationController extends Controller
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
        'menu' => 'notification',
        'nav' => 'backend.notification.breadcrumb'
    ];
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function create(Request $request, $customer_id = null,$type = null,$greeting = null,$subject = null,$text = null)
    {
        if (!Auth::user()->can('notification.create')) return abort(401);

        $predefinedData = [
            'customer' => $customer_id,
            'notificationType' => $type,
            'greeting' => $greeting,
            'subject' => $subject,
            'text' => $text
        ];

        // dd($predefinedData);

        return view('backend.notification.create', compact('predefinedData'))->with($this->data);
    }

    public function temp()
    {
        $datetimeNow = date('Y-m-d h:i:s');

        $orderProductItems = OrderProduct::whereNotNull('scheduled_notify_id')
            ->where([
                ['scheduled_notify_at', '<=', $datetimeNow],
                ['notification_sent', 0],
                ['trash', 0]
            ])
            ->get();

        if (!empty($orderProductItems)) {
            foreach ($orderProductItems as $key => $value) {
                $order = isset($value->order) && !empty($value->order) ? $value->order : null;
                $customer = isset($value->order->customer) && !empty($value->order->customer) ? $value->order->customer : null;
                $productItem = $value;
                $notificationData = DB::table('notifications')->where('id', $value->scheduled_notify_id)->first();

                if (!empty($order) && $order->trash != 1 && !empty($customer) && !empty($productItem) && !empty($notificationData)) {
                    // sms send logic
                    CustomerNofify::scheduledSMS($order, $customer, $notificationData, $productItem);
                    info('Order No: "'.$order->no.'" scheduled sms sent.');
                    // sms send logic
                }
            }
        }
    }

    public function create_post(Request $request)
    {
        if (!Auth::user()->can('notification.create')) return abort(401);
        
        $request->validate([
            'customer' => 'required',
            'via.*' => 'required',
            'greeting' => 'required|max:191',
            'subject' => 'required|max:191',
            'text' => 'required',
        ]);

        $customer = Customer::findOrFail($request->customer);

        // Twilio::send();
        // dd($request);

        if (!$customer) {
            $message = [ 'type' => 'warning', 'message' => 'Invalid customer data', 'title' => 'Warning' ];
            Session::flash('message', $message);
            return redirect()->back();
        }

        if (in_array('mail', $request->via) && empty($customer->email)) {
            $message = [ 'type' => 'warning', 'message' => 'Customer does not have email data', 'title' => 'Warning' ];
            Session::flash('message', $message);
            return redirect()->back();
        }

        if (in_array('sms', $request->via) && empty($customer->mobile)) {
            $message = [ 'type' => 'warning', 'message' => 'Customer does not have mobile number', 'title' => 'Warning' ];
            Session::flash('message', $message);
            return redirect()->back();
        }

        $notifyData = [
            'notificationFromBackend' => true,
            'to' => $customer->id,
            'via' => $request->via,
            'greeting' => $request->greeting,
            'subject' => $request->subject,
            'line' => $request->text,
        ];

        try {
            $response = CustomerNofify::custom($notifyData);            
        } catch (Exception $e) {
            $response = $e; 
        }

        // dd($response->getMessage());

        if ($response != null) {
            $message = [ 'type' => 'warning', 'message' => $response->getMessage(), 'title' => 'Warning' ];
            Session::flash('message', $message);
            return redirect()->back();
        }

        $message = [ 'type' => 'success', 'message' => 'Notification send successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->back();
    }

    public function records()
    {
        if (!Auth::user()->can('notification.view') && !Auth::user()->can('notification.delete')) return abort(401);

        $notifications = DB::table('notifications')->count();
        
        if (DB::table('notifications')->count() > 300) {
            $notifications = DB::table('notifications')->orderBy('created_at','desc')->paginate(10);
        } else {
            $notifications = DB::table('notifications')->orderBy('created_at','desc')->get();
        }        

        return view('backend.notification.records', compact('notifications'))->with($this->data);
    }

    public function view($data)
    {
        if (!Auth::user()->can('notification.view')) return abort(401);

        $data = DB::table('notifications')
            ->where('id','=',$data)
            ->first();

        if (empty($data)) return abort(404);

        return view('backend.notification.view', compact('data'))->with($this->data);
    }

    public function remove($data)
    {
        if (!Auth::user()->can('notification.delete')) return abort(401);
        if (empty(DB::table('notifications')->where('id','=',$data)->first())) return abort(404);

        DB::table('notifications')->where('id','=',$data)->delete();

        $message = [ 'type' => 'success', 'message' => 'Notification removed successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('notificationRecordsPage');
    }

    public function remove_multiple(Request $request)
    {
        if (!Auth::user()->can('notification.delete')) return abort(401);

        $ids = $request->get('selected_ids');
        $deleted = [];
        // $msg = 'Custom page changed successfully';

        if (count($ids) > 0) {
            foreach ($ids as $id) {
                if (!empty(DB::table('notifications')->where('id','=',$id)->first())) {
                    DB::table('notifications')->where('id','=',$id)->delete();
                    array_push($deleted, $id);
                }
            }
        }

        return response()->json($deleted);
    }
}
