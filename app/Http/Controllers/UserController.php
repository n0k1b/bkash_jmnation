<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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

}
