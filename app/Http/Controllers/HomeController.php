<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }


    public function resetPassword()
    {
        $title = "Reset Password";
        return view('reset-password', ["title"=> $title]);
    }

    public function updatepassword(Request $request)
    {
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error",trans('resetpassword.mainPasswordNotMatch') );
        }
        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error",trans('resetpassword.newPasswordCurrentPasswordSame') );
        }
        if(strcmp($request->get('new-password'), $request->get('new-password-confirm')) !== 0){
            //Current password and new password are same
            return redirect()->back()->with("error",trans('resetpassword.confirmPasswordFail'));
        }
        //^[a-zA-Z0-9]{8,16}$
        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => ["required","string","min:8","max:15"],
        ]);
        // /(?=^.{8,15}$)(?=.*\\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&amp;*()_+}{&quot;:;'?\/&gt;.&lt;,])(?!.*\s).*$/
        if(!preg_match('/(?=^.{8,15}$)(?=.*\\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&amp;*()_+}{&quot;:;\'?\/&gt;.&lt;,])(?!.*\s).*$/', $request->get('new-password'))){
            return redirect()->back()->with("error",trans('resetpassword.passwordMatchString'));
        }
        // dd($validatedData);
        //Change Password
        $user = Auth::user();
        $user->password = Hash::make($request->get('new-password'));
        $user->save();
        return redirect()->back()->with("success",trans('resetpassword.success'));
    }
}
