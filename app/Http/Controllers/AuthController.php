<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    //
    public function index()
    {
        return view('login');
    }
    public function login(Request $req)
    {
        $credentials = [
            'email' => $req->email,
            'password' => $req->password,
        ];
        $user = User::where('email', $req->email)->first();
        if (!$user) {
            return redirect("login")->withError('Email is not valid');
        }
        if (!Hash::check($req->pin, $user->pin)) {
            return redirect("login")->withError('Pin is not valid');
        }
        if (Auth::attempt($credentials)) {
            Session::put('api_token', $user->createToken("API TOKEN")->plainTextToken);
            return redirect()->intended('/')
                ->withSuccess('Signed in');
        }

        return redirect("login")->withError('Email and password are not valid');
    }
    public function logout()
    {
        auth()->logout();
        return redirect()->to('/');
    }
}
