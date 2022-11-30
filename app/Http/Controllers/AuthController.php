<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
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
        if (Auth::attempt($credentials)) {
            $user = User::where('email', $req->email)->first();

            Session::put('api_token', $user->createToken("API TOKEN")->plainTextToken);
            return redirect()->intended('/')
                ->withSuccess('Signed in');
        }

        return redirect("login")->withSuccess('Login details are not valid');

    }
    public function logout()
    {
        auth()->logout();
        return redirect()->to('/');
    }
}
