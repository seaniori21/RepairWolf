<?php

namespace App\Http\Controllers\Backend\Order;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Helpers\OrderHelper;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderComment;
use App\Models\Payment;
use App\Models\CrudMeta;

use Ramsey\Uuid\Uuid;

use PDF;
use Session;
use Auth;
use File;

class OrderController extends Controller
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
        'menu' => 'orderCreate',
        'nav' => 'backend.order.breadcrumb'
    ];
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function create()
    {
        if (!Auth::user()->can('order.create')) return abort(401);

        $lastOrderNo = Order::max('no');

        return view('backend.order.create', compact('lastOrderNo'))->with($this->data);
    }

    public function create_post(Request $request)
    {
        if (!Auth::user()->can('order.create')) return abort(401);
        
        // dd($request);

        $request->validate([
            'order_no' => 'required|unique:orders,no',
            'date' => 'required',
            'status' => 'required',

            'customer' => 'required',
            'vehicle' => 'required',
            'cashier' => 'required',
            'service_person' => 'required',

            'product.*' => 'nullable|array',
            'product.*.id' => 'required',
            'product.*.quantity' => 'required|numeric|min:1',
            'product.*.base_price' => 'required|numeric|min:0.00',
            'product.*.list_price' => 'nullable|numeric|min:0.00',
            
            'scheduled_notification.*' => 'nullable|array',
            'scheduled_notification.*.notification_uuid' => 'nullable|uuid',
            'scheduled_notification.*.product_id' => 'exclude_unless:scheduled_notification.*.status,active|required',
            'scheduled_notification.*.date' => 'exclude_unless:scheduled_notification.*.status,active|required',
            'scheduled_notification.*.text' => 'exclude_unless:scheduled_notification.*.status,active|required',

            'payment.*' => 'nullable|array',
            'payment.*.payment_type' => 'required',
            'payment.*.amount' => 'required|numeric|min:0.00',
            'payment.*.auth_approval_code' => 'nullable',
            'payment.*.credit_card_number' => 'nullable',
            'payment.*.expiration_date' => 'nullable|date',
            'payment.*.security_code' => 'nullable',

            'comment.*' => 'nullable|array',
            'comment.*.text' => 'required',
            'comment.*.file' => 'nullable|file',

            'tax' => 'nullable|numeric|min:0.00',
            'convenience_fee' => 'nullable|numeric|min:0.00',
            'discount' => 'nullable|numeric|min:0.00',
        ]);

        $others = ['tax' => $request->tax, 'convenience_fee' => $request->convenience_fee, 'discount' => $request->discount];
        $calculations = OrderHelper::calculations($request->product, $request->payment , $others);

        // dd($request);
        // dd($calculations);

        $data = New Order();
        $data->no = $request->order_no;
        $data->order_date = $request->date;
        $data->status = $request->status;

        $data->customer_id = $request->customer;
        $data->vehicle_id = $request->vehicle;
        $data->cashier_id = $request->cashier;
        $data->service_person_id = $request->service_person;
        
        $data->tax = $calculations['tax'];
        $data->convenience_fee = $calculations['convenience_fee'];
        $data->discount = $calculations['discount'];

        $data->base_total = $calculations['base_total'];
        $data->list_total = $calculations['list_total'];
        $data->grand_total = $calculations['grand_total'];

        $data->paid_amount = $calculations['paid_amount'];
        $data->due_amount = $calculations['due_amount'];
        $data->save();

        if (!empty($request->product)) {
            foreach ($request->product as $key => $value) {
                $orderProductItem = OrderProduct::create([
                    'order_id' => $data->id,
                    'product_id' => $value['id'],
                    'quantity' => intval($value['quantity']),
                    'base_price' => $value['base_price'],
                    'list_price' => $value['list_price'],
                ]);

                $scheduledNotificationItem = $request->scheduled_notification[$key];

                if ($scheduledNotificationItem && $orderProductItem) {
                    if ($scheduledNotificationItem['status'] === 'active') {
                        $customId = Uuid::uuid4()->toString();
                        $orderProductItem->update(['scheduled_notify_at' => $scheduledNotificationItem['date'],'scheduled_notify_id' => $customId]);

                        $notifyData = [
                            'via' => ["App\Notifications\Channel\MessageChannel","database"],
                            'to' => $request->customer,
                            'greeting' => "",
                            'subject' => "Scheduled Notification",
                            'notificationFromBackend' => true,

                            'product_id' => $scheduledNotificationItem['product_id'],
                            'order_product_id' => $orderProductItem->id,
                            'scheduled_date' => $scheduledNotificationItem['date'],
                            'line' => $scheduledNotificationItem['text'],
                        ];

                        DB::table('notifications')->insert([
                            'id' => $customId,
                            'type' => 'App\Notifications\CustomerNotify',
                            'notifiable_type' => 'App\Models\Customer',
                            'notifiable_id' => $request->customer,
                            'data' => json_encode($notifyData),
                            'read_at' => null, // Set to null for unread notification
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }

        if (!empty($request->payment)) {
            foreach ($request->payment as $value) {
                Payment::create([
                    'order_id' => $data->id,
                    'payment_type_id' => $value['payment_type'],
                    'amount' => floatval($value['amount']),
                    'authorization_approval_code' => $value['auth_approval_code'],
                    'credit_card_number' => $value['credit_card_number'],
                    'expiration_date' => $value['expiration_date'],
                    'security_code' => $value['security_code'],
                ]);
            }
        }

        if (!empty($request->comment)) {
            foreach ($request->comment as $key => $value) {
                if (!empty($request->file('comment')[$key]['file'])) {
                    // attachment available
                    $file = $request->file('comment')[$key]['file'];
                    $orginalFileName = $file->getClientOriginalName();
                    $orginalFileExtension = $file->getClientOriginalExtension();
                    $newFileName = time().'.'.$orginalFileExtension;
                    $file->storeAs('attachments', $newFileName, 'upload');
                    // attachment available

                    $fileData = [ 'file_name' => $orginalFileName, 'in_path_filename' => $newFileName ];
                } else { $fileData = [ 'file_name' => null, 'in_path_filename' => null ]; }

                OrderComment::create([
                    'order_id' => $data->id,
                    'text' => $value['text'],
                    'attachment_name' => $fileData['file_name'],
                    'attachment' => $fileData['in_path_filename'],
                ]);
            }
        }

        $message = [ 'type' => 'success', 'message' => 'Order data saved successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        if (Auth::user()->can('order.edit')) {
            return redirect()->route('admin.order.edit', ['data' => $data->id]);
        } else {
            return redirect()->back();
        }
    }

    public function records(Request $request)
    {
        $this->data['menu'] = 'orderRecords';
        if (!Auth::user()->can('order.history') && !Auth::user()->can('order.receipt') && !Auth::user()->can('order.view') && !Auth::user()->can('order.edit') && !Auth::user()->can('order.delete')) return abort(401);

        $from = $request->input('from');
        $to = $request->input('to');
        $paid = $request->input('paid');
        $due = $request->input('due');
        $status = $request->input('status');

        // dd($request);

        // $orders = Order::where('trash',0)->orderBy('created_at', 'desc')->get();

        $orders = Order::when($from, function ($query) use ($from) {
            return $query->where('order_date', '>=', $from);
        })
        ->when($to, function ($query) use ($to) {
            return $query->where('order_date', '<=', $to);
        })
        ->when($paid, function ($query) use ($paid) {
            if ($paid === 'yes') { return $query->where('due_amount','=', 0.00);}
            if ($paid === 'no') { return $query->where('due_amount','>', 0.00); }
        })
        ->when($due, function ($query) use ($due) {
            if ($due === 'yes') { return $query->where('due_amount','>', 0.00);}
            if ($due === 'no') { return $query->where('due_amount','=', 0.00); }
        })
        ->when($status, function ($query) use ($status) {
            return $query->where('status', $status);
        })
        ->orderBy('created_at', 'desc')->get();

        return view('backend.order.records', compact('orders'))->with($this->data);
    }

    public function trashed_records()
    {
        $this->data['menu'] = 'trashedOrderRecords';
        if (!Auth::user()->can('order.delete')) return abort(401);

        $orders = Order::where('trash',1)->orderBy('created_at', 'desc')->get();

        return view('backend.order.trash', compact('orders'))->with($this->data);
    }

    public function trashed_product_records(Order $data)
    {
        $this->data['menu'] = 'trashedOrderRecords';
        if (!Auth::user()->can('order.delete')) return abort(401);

        $orderProducts = OrderProduct::where([['order_id', $data->id],['trash', 1]])->orderBy('created_at', 'desc')->get();

        return view('backend.order.product-trash', compact('data','orderProducts'))->with($this->data);
    }

    public function trashed_payment_records(Order $data)
    {
        $this->data['menu'] = 'trashedOrderRecords';
        if (!Auth::user()->can('order.delete')) return abort(401);

        $orderPayments = Payment::where([['order_id', $data->id],['trash', 1]])->orderBy('created_at', 'desc')->get();

        return view('backend.order.payment-trash', compact('data','orderPayments'))->with($this->data);
    }

    public function trashed_comment_records(Order $data)
    {
        $this->data['menu'] = 'trashedOrderRecords';
        if (!Auth::user()->can('order.delete')) return abort(401);

        $orderComments = OrderComment::where([['order_id', $data->id],['trash', 1]])->orderBy('created_at', 'desc')->get();

        return view('backend.order.comment-trash', compact('data','orderComments'))->with($this->data);
    }

    public function update(Order $data)
    {
        $this->data['menu'] = 'orderRecords';
        if (!Auth::user()->can('order.edit')) return abort(401);
        
        // if (!$data) { return abort(404); }

        return view('backend.order.edit', compact('data'))->with($this->data);
    }

    public function update_post(Request $request, Order $data)
    {
        if (!Auth::user()->can('order.edit')) return abort(401);

        // dd($request);

        $request->validate([
            'order_no' => 'required|unique:orders,no,'.$data->id,
            // 'date' => 'required',
            'status' => 'required',

            'customer' => 'required',
            'vehicle' => 'required',
            'cashier' => 'required',
            'service_person' => 'required',

            'product.*' => 'nullable|array',
            'product.*.id' => 'required',
            'product.*.quantity' => 'required|numeric|min:1',
            'product.*.base_price' => 'required|numeric|min:0.00',
            'product.*.list_price' => 'nullable|numeric|min:0.00',

            'scheduled_notification.*' => 'nullable|array',
            'scheduled_notification.*.notification_uuid' => 'nullable|uuid',
            'scheduled_notification.*.product_id' => 'exclude_unless:scheduled_notification.*.status,active|required',
            'scheduled_notification.*.date' => 'exclude_unless:scheduled_notification.*.status,active|required',
            'scheduled_notification.*.text' => 'exclude_unless:scheduled_notification.*.status,active|required',

            'payment.*' => 'nullable|array',
            'payment.*.payment_type' => 'required',
            'payment.*.amount' => 'required|numeric|min:0.00',
            'payment.*.auth_approval_code' => 'nullable',
            'payment.*.credit_card_number' => 'nullable',
            'payment.*.expiration_date' => 'nullable|date',
            'payment.*.security_code' => 'nullable',

            'comment.*' => 'nullable|array',
            'comment.*.text' => 'required',
            'comment.*.file' => 'nullable|file',

            'tax' => 'nullable|numeric|min:0.00',
            'convenience_fee' => 'nullable|numeric|min:0.00',
            'discount' => 'nullable|numeric|min:0.00',
        ]);

        $others = ['tax' => $request->tax, 'convenience_fee' => $request->convenience_fee, 'discount' => $request->discount];
        $calculations = OrderHelper::calculations($request->product, $request->payment, $others);

        $data->no = $request->order_no;
        // $data->order_date = $request->date;
        $data->status = $request->status;

        $data->customer_id = $request->customer;
        $data->vehicle_id = $request->vehicle;
        $data->cashier_id = $request->cashier;
        $data->service_person_id = $request->service_person;

        $data->tax = $calculations['tax'];
        $data->convenience_fee = $calculations['convenience_fee'];
        $data->discount = $calculations['discount'];

        $data->base_total = $calculations['base_total'];
        $data->list_total = $calculations['list_total'];        
        $data->grand_total = $calculations['grand_total'];

        $data->paid_amount = $calculations['paid_amount'];
        $data->due_amount = $calculations['due_amount'];
        $data->save();

        if (!empty($request->product)) {
            foreach ($request->product as $key => $value) {
                if (!empty($value['item_id'])) {
                    // old data
                    $orderProductItem = OrderProduct::where([['id', $value['item_id']],['order_id', $data->id]])->first();

                    if ($orderProductItem) {
                        $orderProductItem->order_id = $data->id;
                        $orderProductItem->product_id = $value['id'];
                        $orderProductItem->quantity = intval($value['quantity']);
                        $orderProductItem->base_price = $value['base_price'];
                        $orderProductItem->list_price = $value['list_price'];
                        $orderProductItem->save();
                    }
                } else {
                    // new data
                    $orderProductItem = OrderProduct::create([
                        'order_id' => $data->id,
                        'product_id' => $value['id'],
                        'quantity' => intval($value['quantity']),
                        'base_price' => $value['base_price'],
                        'list_price' => $value['list_price'],
                    ]);
                }

                $scheduledNotificationItem = $request->scheduled_notification[$key];

                if ($scheduledNotificationItem && $orderProductItem) {
                    if ($scheduledNotificationItem['status'] === 'active') {
                        if (isset($scheduledNotificationItem['notification_uuid']) && !empty($scheduledNotificationItem['notification_uuid'])) {
                            $notificationData = DB::table('notifications')->where('id', $scheduledNotificationItem['notification_uuid'])->first();

                            if ($notificationData) {
                                if (strtotime($orderProductItem->scheduled_notify_at) != strtotime($scheduledNotificationItem['date'])) {
                                    $orderProductItem->update(['notification_sent' => 0,'scheduled_notify_at' => $scheduledNotificationItem['date']]);
                                } else {
                                    $orderProductItem->update(['scheduled_notify_at' => $scheduledNotificationItem['date']]);                                
                                }

                                $notifyData = [
                                    'via' => ["App\Notifications\Channel\MessageChannel","database"],
                                    'to' => $request->customer,
                                    'greeting' => "",
                                    'subject' => "Scheduled Notification",
                                    'notificationFromBackend' => true,

                                    'product_id' => $scheduledNotificationItem['product_id'],
                                    'order_product_id' => $orderProductItem->id,
                                    'scheduled_date' => $scheduledNotificationItem['date'],
                                    'line' => $scheduledNotificationItem['text'],
                                ];

                                DB::table('notifications')->where('id', $scheduledNotificationItem['notification_uuid'])->update([
                                    'data' => json_encode($notifyData),
                                    'updated_at' => now(),
                                ]);
                            }
                        } else {
                            $customId = Uuid::uuid4()->toString();
                            $orderProductItem->update(['scheduled_notify_at' => $scheduledNotificationItem['date'],'scheduled_notify_id' => $customId]);

                            $notifyData = [
                                'via' => ["App\Notifications\Channel\MessageChannel","database"],
                                'to' => $request->customer,
                                'greeting' => "",
                                'subject' => "Scheduled Notification",
                                'notificationFromBackend' => true,

                                'product_id' => $scheduledNotificationItem['product_id'],
                                'order_product_id' => $orderProductItem->id,
                                'scheduled_date' => $scheduledNotificationItem['date'],
                                'line' => $scheduledNotificationItem['text'],
                            ];

                            DB::table('notifications')->insert([
                                'id' => $customId,
                                'type' => 'App\Notifications\CustomerNotify',
                                'notifiable_type' => 'App\Models\Customer',
                                'notifiable_id' => $request->customer,
                                'data' => json_encode($notifyData),
                                'read_at' => null, // Set to null for unread notification
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }
        }

        if (!empty($request->payment)) {
            foreach ($request->payment as $key => $value) {
                if (!empty($value['payment_id'])) {
                    // old data
                    $query = Payment::where([['id', $value['payment_id']],['order_id', $data->id]])->first();

                    if ($query) {
                        $query->order_id = $data->id;
                        $query->payment_type_id = $value['payment_type'];
                        $query->amount = floatval($value['amount']);
                        $query->authorization_approval_code = $value['auth_approval_code'];
                        $query->credit_card_number = $value['credit_card_number'];
                        $query->expiration_date = $value['expiration_date'];
                        $query->security_code = $value['security_code'];
                        $query->save();
                    }
                } else {
                    // new data
                    Payment::create([
                        'order_id' => $data->id,
                        'payment_type_id' => $value['payment_type'],
                        'amount' => floatval($value['amount']),
                        'authorization_approval_code' => $value['auth_approval_code'],
                        'credit_card_number' => $value['credit_card_number'],
                        'expiration_date' => $value['expiration_date'],
                        'security_code' => $value['security_code'],
                    ]);
                }
            }
        }

        if (!empty($request->comment)) {
            foreach ($request->comment as $key => $value) {
                if (!empty($request->file('comment')[$key]['file'])) {
                    // attachment available
                    $file = $request->file('comment')[$key]['file'];
                    $orginalFileName = $file->getClientOriginalName();
                    $orginalFileExtension = $file->getClientOriginalExtension();
                    $newFileName = time().'.'.$orginalFileExtension;
                    $file->storeAs('attachments', $newFileName, 'upload');
                    // attachment available
                    $fileData = [ 'file_name' => $orginalFileName, 'in_path_filename' => $newFileName ];
                } else { $fileData = [ 'file_name' => null, 'in_path_filename' => null ]; }

                if (!empty($value['id'])) {
                    // old data
                    $query = OrderComment::where([['id', $value['id']],['order_id', $data->id]])->first();

                    if ($query) {
                        $query->order_id = $data->id;
                        $query->text = $value['text'];
                        
                        if (!empty($fileData['file_name'])) {
                            $query->attachment_name = $fileData['file_name'];
                            $query->attachment = $fileData['in_path_filename'];
                        }

                        $query->save();
                    }
                } else {
                    // new data
                    OrderComment::create([
                        'order_id' => $data->id,
                        'text' => $value['text'],
                        'attachment_name' => $fileData['file_name'],
                        'attachment' => $fileData['in_path_filename'],
                    ]);
                }
            }
        }

        $message = [ 'type' => 'success', 'message' => 'Order data updated successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->back();
    }

    public function view(Order $data)
    {
        $this->data['menu'] = 'orderRecords';
        if (!Auth::user()->can('order.view')) return abort(401);
        
        // dd($data->comments);

        $activityLog = $data->crudMeta();

        // dd($activityLog);

        if($data->productItems) {
            foreach ($data->productItems as $key => $value) {

                if ($value->crudMeta()) {
                    $activityLog = $activityLog->concat($value->crudMeta());
                }
            }
        }

        if($data->payments) {
            foreach ($data->payments as $key => $value) {

                if ($value->crudMeta()) {
                    $activityLog = $activityLog->concat($value->crudMeta());
                }
            }
        }

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

        return view('backend.order.view', compact('data', 'activityLog'))->with($this->data);
    }

    public function remove(Order $data)
    {
        if (!Auth::user()->can('order.delete')) return abort(401);

        $data->trash = 1;
        $data->save();

        $message = [ 'type' => 'success', 'message' => 'Order data removed successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('admin.order.records');
    }

    public function remove_multiple(Request $request)
    {
        if (!Auth::user()->can('order.delete')) return abort(401);

        $ids = $request->get('selected_ids');
        $deleted = [];
        // $msg = 'Custom page changed successfully';

        if (count($ids) > 0) {
            foreach ($ids as $id) {
                $data = Order::where('id',$id)->first();
                    $data->trash = 1;
                $data->save();
                
                array_push($deleted, $id);
            }
        }

        return response()->json($deleted);
    }

    public function remove_history_item(Order $data, CrudMeta $item)
    {
        if (!Auth::user()->can('order.delete')) return abort(401);

        $item->delete();

        $message = [ 'type' => 'success', 'message' => 'Order history item removed successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('admin.order.view', ['data' => $data->id]);
    }

    public function remove_product_item(Order $data, OrderProduct $item)
    {
        if (!Auth::user()->can('order.delete')) return abort(401);

        $item->trash = 1;
        $item->save();

        OrderHelper::syncCalculations($data);

        $message = [ 'type' => 'success', 'message' => 'Order product item removed successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('admin.order.edit', ['data' => $data->id]);
    }

    public function remove_payment_item(Order $data, Payment $item)
    {
        if (!Auth::user()->can('order.delete')) return abort(401);

        $item->trash = 1;
        $item->save();

        OrderHelper::syncCalculations($data);

        $message = [ 'type' => 'success', 'message' => 'Order payment item removed successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('admin.order.edit', ['data' => $data->id]);
    }

    public function remove_comment_item(Order $data, OrderComment $item)
    {
        if (!Auth::user()->can('order.delete')) return abort(401);

        $item->trash = 1;
        $item->save();

        $message = [ 'type' => 'success', 'message' => 'Order comment removed successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('admin.order.edit', ['data' => $data->id]);
    }

    public function trashed_record_restore(Order $data)
    {
        if (!Auth::user()->can('order.delete')) return abort(401);

        $data->trash = 0;
        $data->save();

        $message = [ 'type' => 'success', 'message' => 'Trashed order data restored successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('admin.order.records.trashed');
    }

    public function trashed_product_restore(Order $data, OrderProduct $item)
    {
        if (!Auth::user()->can('order.delete')) return abort(401);

        $item->trash = 0;
        $item->save();

        OrderHelper::syncCalculations($data);

        $message = [ 'type' => 'success', 'message' => 'Trashed order product restored successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('admin.order.product.trashed', ['data' => $data->id]);
    }

    public function trashed_payment_restore(Order $data, Payment $item)
    {
        if (!Auth::user()->can('order.delete')) return abort(401);

        $item->trash = 0;
        $item->save();

        OrderHelper::syncCalculations($data);

        $message = [ 'type' => 'success', 'message' => 'Trashed order payment restored successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('admin.order.payment.trashed', ['data' => $data->id]);
    }

    public function trashed_comment_restore(Order $data, OrderComment $item)
    {
        if (!Auth::user()->can('order.delete')) return abort(401);

        $item->trash = 0;
        $item->save();

        $message = [ 'type' => 'success', 'message' => 'Trashed order comment restored successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('admin.order.comment.trashed', ['data' => $data->id]);
    }

    public function receipt_base(Order $data)
    {
        if (!Auth::user()->can('order.receipt')) return abort(401);

        // $data = $data->with('productItems','productItems.product','customer','vehicle','servicePerson', 'cashier')->first();

        return view('backend.order.receipt.view-base', compact('data'));
    }

    public function receipt_list(Order $data)
    {
        if (!Auth::user()->can('order.receipt')) return abort(401);

        // $data = $data->with('productItems','productItems.product','customer','vehicle','servicePerson', 'cashier')->first();

        return view('backend.order.receipt.view-list', compact('data'));
    }

    public function receipt_base_download(Order $data)
    {
        if (!Auth::user()->can('order.receipt')) return abort(401);

        $receiptData = Order::with('productItems','productItems.product','customer','vehicle','servicePerson', 'cashier')->findOrFail($data->id)->toArray();

        $receiptFinalData = [ 'data' => $receiptData ];

        $pdf = PDF::loadView('backend.order.receipt.download-base', $receiptFinalData);
        return $pdf->setOption(['debugLayout' => false])->download('bnap-i'.$data['no'].'.pdf');
    }

    public function receipt_list_download(Order $data)
    {
        if (!Auth::user()->can('order.receipt')) return abort(401);

        $receiptData = Order::with('productItems','productItems.product','customer','vehicle','servicePerson', 'cashier')->findOrFail($data->id)->toArray();

        $receiptFinalData = [ 'data' => $receiptData ];

        $pdf = PDF::loadView('backend.order.receipt.download-list', $receiptFinalData);
        return $pdf->setOption(['debugLayout' => false])->download('bnap-i'.$data['no'].'.pdf');
    }
}
