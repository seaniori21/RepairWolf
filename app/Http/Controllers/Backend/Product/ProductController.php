<?php

namespace App\Http\Controllers\Backend\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CrudMeta;

use App\Models\Product;

use Session;
use Auth;

class ProductController extends Controller
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
        'menu' => 'product',
        'nav' => 'backend.product.breadcrumb'
    ];
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function create()
    {
        if (!Auth::user()->can('product.create')) return abort(401);
        return view('backend.product.create')->with($this->data);
    }

    public function create_post(Request $request)
    {
        if (!Auth::user()->can('product.create')) return abort(401);

        $request->validate([
            'type' => 'required',
            'identification_code' => 'required|max:191',
            'upc' => 'nullable|max:191',
            'name' => 'required|max:191',
            'description' => 'nullable|max:191',
            'manufacturer' => 'required|max:191',
            'base_price' => 'required|min:0',
            'list_price' => 'nullable|min:0',
        ]);

        $data = New Product();
        $data->type = $request->type;
        $data->identification_code = $request->identification_code;
        $data->upc = $request->upc;
        $data->name = $request->name;
        $data->description = $request->description;
        $data->manufacturer = $request->manufacturer;
        $data->base_price = $request->base_price;
        $data->list_price = $request->list_price;
        $data->save();

        $message = [ 'type' => 'success', 'message' => 'Product data saved successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        if (Auth::user()->can('product.edit')) {
            return redirect()->route('productUpdate', ['data' => $data->id]);
        } else {
            return redirect()->back();
        }
    }

    public function records()
    {
        if (!Auth::user()->can('product.history') && !Auth::user()->can('product.view') && !Auth::user()->can('product.edit') && !Auth::user()->can('product.delete')) return abort(401);

        $products = Product::where('trash', 0)->orderBy('created_at', 'desc')->get();

        return view('backend.product.records', compact('products'))->with($this->data);
    }

    public function trashed_records()
    {
        if (!Auth::user()->can('product.delete')) return abort(401);

        $products = Product::where('trash', 1)->orderBy('created_at', 'desc')->get();

        return view('backend.product.trash', compact('products'))->with($this->data);
    }

    public function view(Product $data)
    {
        if (!Auth::user()->can('product.view')) return abort(401);

        $activityLog = $data->crudMeta();

        // dd($activityLog);

        if ($activityLog) {
            $activityLog->sortBy([['id', 'desc']]);
        }

        // dd($activityLog);

        return view('backend.product.view', compact('data', 'activityLog'))->with($this->data);
    }

    public function update(Product $data)
    {
        if (!Auth::user()->can('product.edit')) return abort(401);        

        return view('backend.product.edit', compact('data'))->with($this->data);
    }

    public function update_post(Request $request, Product $data)
    {
        if (!Auth::user()->can('product.edit')) return abort(401);

        // dd($request);

        $request->validate([
            'type' => 'required',
            'identification_code' => 'required|max:191',
            'upc' => 'nullable|max:191',
            'name' => 'required|max:191',
            'description' => 'nullable|max:191',
            'manufacturer' => 'required|max:191',
            'base_price' => 'required|min:0',
            'list_price' => 'nullable|min:0',
        ]);

        $data->type = $request->type;
        $data->identification_code = $request->identification_code;
        $data->upc = $request->upc;
        $data->name = $request->name;
        $data->description = $request->description;
        $data->manufacturer = $request->manufacturer;
        $data->base_price = $request->base_price;
        $data->list_price = $request->list_price;
        $data->save();

        $message = [ 'type' => 'success', 'message' => 'Product data updated successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->back();
    }

    public function remove(Product $data)
    {
        if (!Auth::user()->can('product.delete')) return abort(401);

        $data->trash = 1;
        $data->save();

        $message = [ 'type' => 'success', 'message' => 'Product data removed successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('productRecordsPage');
    }

    public function trashed_record_restore(Product $data)
    {
        if (!Auth::user()->can('product.delete')) return abort(401);

        $data->trash = 0;
        $data->save();

        $message = [ 'type' => 'success', 'message' => 'Trashed product data restored successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('productTrashRecordsPage');
    }

    public function remove_history_item(Product $data, CrudMeta $item)
    {
        if (!Auth::user()->can('product.delete')) return abort(401);

        $item->delete();

        $message = [ 'type' => 'success', 'message' => 'Product history item removed successfully', 'title' => 'Success' ];
        Session::flash('message', $message);

        return redirect()->route('productView', ['data' => $data->id]);
    }

    public function remove_multiple(Request $request)
    {
        if (!Auth::user()->can('product.delete')) return abort(401);

        $ids = $request->get('selected_ids');
        $deleted = [];
        // $msg = 'Custom page changed successfully';

        if (count($ids) > 0) {
            foreach ($ids as $id) {
                $data = Product::where('id',$id)->first();

                $data->trash = 1;
                $data->save();
                
                array_push($deleted, $id);
            }
        }

        return response()->json($deleted);
    }
}
