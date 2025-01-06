<?php

namespace App\Http\Controllers\Backend\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Admin;

use Session;
use Auth;

class DashboardController extends Controller
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
        'menu' => 'dashboard',
        'nav' => ''
        // 'nav' => 'system.dashboard.breadcrumb'
    ];

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // if (!Auth::user()->can('dashboard.view')) return abort(401);        
        return view('backend.dashboard.index')->with($this->data);
    }

    public function left_aside_status(Request $request)
    {
        $status = $request->get('status');
        $value = ($status == 'on' ? 1:0);
        $data = Admin::where('id', Auth()->id())->update(['aside' => $value]);

        return response()->json($status);
    }
}
