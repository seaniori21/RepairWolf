<?php

namespace App\Http\Controllers\Backend\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Admin;

use Session;
use Hash;
use Auth;
use File;

class ProfileController extends Controller
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
        'menu' => 'profile',
        'nav' => 'backend.profile.breadcrumb'
    ];

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (!Auth::user()->can('profile.view') && !Auth::user()->can('profile.edit-info') && !Auth::user()->can('profile.edit-password') && !Auth::user()->can('profile.edit-image')) return abort(401);

        return view('backend.profile.index')->with($this->data);
    }

    public function generate_token(Request $request)
    {
        Session::flash('accordin', 'api');
        
        $data = Auth::user();
        $data->tokens()->delete();
        $token = $data->createToken('api-access-token')->plainTextToken;
        $data->update(['api_token' => $token]);
        
        $message = [ 'type' => 'success', 'message' => $token.'Api token generated', 'title' => 'Success' ];
        Session::flash('message', $message);
        return redirect()->back();
    }

    public function remove_token($id)
    {
        Session::flash('accordin', 'api');
        
        $data = Auth::user();
        $data->tokens()->where('id', $id)->delete();

        $message = [ 'type' => 'danger', 'message' => 'Api token removed', 'title' => 'Success' ];
        Session::flash('message', $message);
        return redirect()->back();
    }

    public function profile_update(Request $request)
    {
        if (!Auth::user()->can('profile.edit-info')) return abort(401);

        Session::flash('accordin', 'info');

        $request->validate([
            'name' => 'required|max:191',
            'email' => 'required|email|unique:admins,email,'.Auth()->id().'|max:191',
            // 'timezone' => 'required',
        ]);

        $data = Auth::user();
        $data->name = $request->name;
        $data->email = $request->email;
        // $data->tz = $request->timezone;
        $data->save();


        $message = [ 'type' => 'success', 'message' => 'Profile information updated', 'title' => 'Success' ];
        Session::flash('message', $message);
        return redirect()->back();
    }

    public function password_update(Request $request)
    {
        if (!Auth::user()->can('profile.edit-password')) return abort(401);

        Session::flash('accordin', 'password');
        
        $messages = [
            'current_password.required' => 'Please enter current password',
            'password.required' => 'Please enter password',
            'password_confirmation.required' => 'Password confirmation is required',
            'password_confirmation.same' => 'Password confirmation does not match',
        ];

        $request->validate([
            'current_password' => 'required|max:191',
            'password' => 'required|min:4',
            'password_confirmation' => 'required|same:password',
        ],$messages);

        if(Hash::check($request->current_password, Auth::user()->password)) {
            $data = Auth::user();
            $data->password = Hash::make($request->password);
            $data->save();

            $message = [ 'type' => 'success', 'message' => 'Password updated', 'title' => 'Success' ];
            Session::flash('message', $message);
        } else {
            $message = [ 'type' => 'danger', 'message' => 'Current password is incorrect', 'title' => 'Warning' ];
            Session::flash('message', $message);
        }

        return redirect()->back();
    }

    public function ajax_profile_picture_save(Request $request)
    {
        if (!Auth::user()->can('profile.edit-image')) return abort(401);

        $image = $request->get('image');
        $msg = 'Profile picture changed successfully';

        $data = Admin::where([['id','=', Auth()->id()]])->first();

        $this->removeOldImage($data->image);
    
        $data->image = $image;
        $data->save();

        return response()->json($msg);
    }

    protected function removeOldImage($img)
    {
        if (!$img) return;
        
        $old = explode('/', $img);
        $old = public_path().'/upload/crop-files/'.$old[count($old)-1];
        if (File::exists($old)) File::delete($old);
    }
}
