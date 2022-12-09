<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    //
    public function index()
    {
        $data = User::get();
        return view('user-list', compact('data'));
    }

}
