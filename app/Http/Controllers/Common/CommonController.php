<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Product;
use App\Models\Vehicle;
use App\Models\Admin;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\PaymentType;

use Image;
use Auth;

class CommonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function cropper_image_upload(Request $request){
        // dd($request);        
        if($request->method() != 'POST') { return 'Unauthorized request';}
        if(!$request->ajax()) { return 'Unauthorized request';}

        $exploded = explode(',', $request->imgurl);

        $height = $request->height;
        $width = $request->width;

        $decoded = base64_decode($exploded[1]);

        $file_name = time().uniqid().'.jpeg';
        $path = public_path().'/upload/crop-files/'.$file_name;
        // $extention = $this->get_string_between($exploded[0],'/',';');

        $img = Image::make($decoded);
        $img->resize($width,$height);
        $img->encode('jpeg');
        $img->save($path);

        // file_put_contents($path, $decoded);
        return response($file_name);
    }

    public function editor_image_upload(Request $request)
    {
        if($request->method() != 'POST') { return 'Unauthorized request';}
        // if(!$request->ajax()) { return 'Unauthorized request';}

        if($request->hasFile('upload')) {
             $originName = $request->file('upload')->getClientOriginalName();
             $fileName = pathinfo($originName, PATHINFO_FILENAME);
             $extension = $request->file('upload')->getClientOriginalExtension();
             $fileName = $fileName.'_'.time().'.'.$extension;
            
             $request->file('upload')->move(public_path('upload/editor-file'), $fileName);
       
             $CKEditorFuncNum = $request->input('CKEditorFuncNum');
             $url = asset('upload/editor-file/'.$fileName); 
             $msg = ''; 
             // $msg = 'Image uploaded successfully'; 
             $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
                   
             @header('Content-type: text/html; charset=utf-8'); 
             echo $response;
         }
    }


    // ajax
    public function ajax_fetch_customer(Request $request)
    {
        if (!$request->ajax()) { return abort(403,'This action cannot be performed directly through a web browser. Please use the appropriate application or method for this operation'); }

        // $active = $request->get('active');
        $search = $request->get('search');

        $options = [];
        $conditions = [['trash', 0]];

        $query = Customer::query();
        $searchableColumns = ['first_name', 'last_name', 'email', 'phone', 'mobile' , 'city', 'state', 'zip_code'];

        if (isset($search) && $search) {
            foreach($searchableColumns as $column){
                $query->orWhere([['trash', 0],[$column, 'LIKE', '%' . $search . '%']]);
            }
        } else { $query->where('trash', 0); }

        $customers = $query->orderBy('created_at', 'desc')->get();

        if ($customers) {
            foreach ($customers as $key => $value) {
                $options[] = [
                    "id" => $value->id,
                    "text" => $value->first_name.' '.$value->last_name,
                ];
            }
        }

        return response()->json($options);
    }

    public function ajax_fetch_cashier(Request $request)
    {
        if (!$request->ajax()) { return abort(403,'This action cannot be performed directly through a web browser. Please use the appropriate application or method for this operation'); }

        // $active = $request->get('active');
        $search = $request->get('search');

        $options = [];
        $conditions = [['trash', 0]];

        $query = Admin::query();
        $searchableColumns = ['name','email'];

        if (isset($search) && $search) {
            foreach($searchableColumns as $column){
                $query->orWhere([['trash', 0],[$column, 'LIKE', '%' . $search . '%']]);
            }
        } else { $query->where('trash', 0); }

        $cashiers = $query->role('cashier')->orderBy('created_at', 'desc')->get();

        if ($cashiers) {
            foreach ($cashiers as $key => $value) {
                $options[] = [
                    "id" => $value->id,
                    "text" => $value->name,
                ];
            }
        }

        return response()->json($options);
    }

    public function ajax_fetch_vehicle(Request $request)
    {
        if (!$request->ajax()) { return abort(403,'This action cannot be performed directly through a web browser. Please use the appropriate application or method for this operation'); }

        // $active = $request->get('active');
        $search = $request->get('search');
        $customerId = $request->get('additional');

        $options = [];
        $conditions = [['trash', 0]];

        $query = Vehicle::query();
        $searchableColumns = ['license_plate','vin','year','make','model','body_type','trim','color'];

        if (isset($search) && $search) {
            foreach($searchableColumns as $column){
                $query->orWhere([['trash', 0],[$column, 'LIKE', '%' . $search . '%']]);
            }
        } else { $query->where('trash', 0); }

        if ($customerId) { $vehicles = $query->where('customer_id', $customerId)->orderBy('created_at', 'desc')->get(); }
        else { $vehicles = $query->orderBy('created_at', 'desc')->get(); }

        if ($vehicles) {
            foreach ($vehicles as $key => $value) {
                $options[] = [
                    // "additional" => $customerId,
                    "id" => $value->id,
                    "text" => $value->year.', '.$value->make.', '.$value->model.', '.$value->body_type,
                ];
            }
        }

        return response()->json($options);
    }

    public function ajax_fetch_vehicle_data(Request $request)
    {
        if (!$request->ajax()) { return abort(403,'This action cannot be performed directly through a web browser. Please use the appropriate application or method for this operation'); }

        // $active = $request->get('active');
        $dataId = $request->get('dataId');
        $response = Vehicle::where([['trash', 0], ['id', $dataId]])->select('license_plate','vin','year','make','model','body_type','trim','color')->first();

        return response()->json($response);
    }

    public function ajax_fetch_service_person(Request $request)
    {
        if (!$request->ajax()) { return abort(403,'This action cannot be performed directly through a web browser. Please use the appropriate application or method for this operation'); }

        // $active = $request->get('active');
        $search = $request->get('search');

        $options = [];
        $conditions = [['trash', 0]];

        $query = Admin::query();
        $searchableColumns = ['name','email'];

        if (isset($search) && $search) {
            foreach($searchableColumns as $column){
                $query->orWhere([['trash', 0],[$column, 'LIKE', '%' . $search . '%']]);
            }
        } else { $query->where('trash', 0); }

        $cashiers = $query->role('mechanic')->orderBy('created_at', 'desc')->get();

        if ($cashiers) {
            foreach ($cashiers as $key => $value) {
                $options[] = [
                    "id" => $value->id,
                    "text" => $value->name,
                ];
            }
        }

        return response()->json($options);
    }

    public function ajax_fetch_products(Request $request)
    {
        if (!$request->ajax()) { return abort(403,'This action cannot be performed directly through a web browser. Please use the appropriate application or method for this operation'); }

        // $active = $request->get('active');
        $search = $request->get('search');

        $options = [];
        $conditions = [['trash', 0]];

        $query = Product::query();
        $searchableColumns = ['type','identification_code','upc','name','description','manufacturer','base_price','list_price'];

        if (isset($search) && $search) {
            foreach($searchableColumns as $column){
                $query->orWhere([['trash', 0],[$column, 'LIKE', '%' . $search . '%']]);
            }
        } else { $query->where('trash', 0); }

        $products = $query->orderBy('created_at', 'desc')->get();

        if ($products) {
            foreach ($products as $key => $value) {
                $options[] = [
                    "id" => $value->id,
                    "badge" => $value->type,
                    "text" => $value->name,
                ];
            }
        }

        return response()->json($options);
    }

    public function ajax_fetch_products_data(Request $request)
    {
        if (!$request->ajax()) { return abort(403,'This action cannot be performed directly through a web browser. Please use the appropriate application or method for this operation'); }

        // $active = $request->get('active');
        $dataId = $request->get('dataId');
        $response = Product::where([['trash', 0], ['id', $dataId]])->select('id','type','identification_code','upc','name','description','manufacturer','base_price','list_price')->first();

        return response()->json($response);
    }

    public function ajax_fetch_payment_types(Request $request)
    {
        if (!$request->ajax()) { return abort(403,'This action cannot be performed directly through a web browser. Please use the appropriate application or method for this operation'); }

        // $active = $request->get('active');
        $search = $request->get('search');

        $options = [];
        $conditions = [['trash', 0]];

        if (isset($search) && $search) {
            $conditions[] = ['name','like','%'.$search.'%'];
        }

        $paymentTypes = PaymentType::where($conditions)->orderBy('created_at', 'desc')->get();

        if ($paymentTypes) {
            foreach ($paymentTypes as $key => $value) {
                $options[] = [
                    "id" => $value->id,
                    "text" => $value->name,
                ];
            }
        }

        return response()->json($options);
    }

    public function ajax_fetch_orders(Request $request)
    {
        if (!$request->ajax()) { return abort(403,'This action cannot be performed directly through a web browser. Please use the appropriate application or method for this operation'); }

        // $active = $request->get('active');
        $search = $request->get('search');

        $options = [];
        $conditions = [['trash', 0]];

        if (isset($search) && $search) {
            $conditions[] = ['no','like','%'.$search.'%'];
        }

        $orders = Order::where($conditions)->orderBy('created_at', 'desc')->get();

        if (isset($orders) && $orders) {
            foreach ($orders as $key => $value) {
                $options[] = [
                    "id" => $value->id,
                    "text" => $value->no,
                ];
            }
        }

        return response()->json($options);
    }
    // ajax
}
