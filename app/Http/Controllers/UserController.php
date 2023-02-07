<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {

        $data = User::where('role', '!=', 'admin')->where('role', $request->type)->get();
        return view('user-list', compact('data'));
    }

    public function user_active_status_update(Request $request)
    {
        $id = $request->id;
        $status = User::where('id', $id)->first()->status;
        if ($status == 1) {
            User::where('id', $id)->update(['status' => 0]);
        } else {
            User::where('id', $id)->update(['status' => 1]);
        }
    }

    public function change_password()
    {
        return view('change-password');
    }

    public function change_pin()
    {
        return view('change-pin');
    }

    public function update_password(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!\Hash::check($value, \Auth::user()->password)) {
                    $fail('Your current password is incorrect.');
                }
            }],
            'password' => ['required', 'confirmed'],
        ]);
        if ($validator->fails()) {
            //Log::info();
            return redirect()->back()->withErrors($validator)->withInput();

        }
        $user = \Auth::user();
        $user->password = \Hash::make($request->password);
        $user->save();
        auth()->logout();

        return redirect()->route('login-view')->with('success', 'Password changed successfully! Please login again');
    }

    public function update_pin(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'current_pin' => ['required', function ($attribute, $value, $fail) {
                if (!\Hash::check($value, \Auth::user()->pin)) {
                    $fail('Your current pin is incorrect.');
                }
            }],
            'pin' => ['required', 'confirmed', 'numeric', 'min:4'],
        ]);
        if ($validator->fails()) {
            //Log::info();
            return redirect()->back()->withErrors($validator)->withInput();

        }
        $user = \Auth::user();
        $user->pin = \Hash::make($request->pin);
        $user->save();
        auth()->logout();

        return redirect()->route('login-view')->with('success', 'Pin changed successfully! Please login again');
    }

}
