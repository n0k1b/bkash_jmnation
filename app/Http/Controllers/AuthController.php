<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash as BcryptHash;
use Hash;
use Auth;

class AuthController extends Controller
{
    //
    public function index()
    {
        return view('login');
    }
    public function login(Request $req)
    {
        $user = user::where('mobile', $req->input('mobile_number'))->first();
        if($user === null) {
            $req->session()->flash('msg', 'User Not Registered');
            return redirect()->route('login-view');
        }
        if(!BcryptHash::check($req->input('password'), $user->password)) {
            $req->session()->flash('msg', 'Wrong phone number/password');
            return redirect()->route('login-view');
        }
        Auth::login($user);
        $req->session()->put('user', $user);

        return redirect()->to('admin');


        //$user = user::where('mobile_number',$req->mobile_number)->where('password')
    }
    public function logout()
    {
        auth()->logout();
        return redirect()->to('/');
    }
}
