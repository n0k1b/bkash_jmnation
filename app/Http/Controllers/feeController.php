<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\fee;

class feeController extends Controller
{
    public function submit_application(Request $request)
    {
       fee::create($request->all());
    }

    public function index()
    {
        return view('admin.fees',['fees'=>fee::all()]);
    }
    public function add(Request $Request)
    {
        fee::create($Request->all());
        return redirect('admin/fees')->with('message','Fee created successfully');
    }

    public function delete(Request $Request)
    {
        $instructor = fee::where('id',$Request->id)->first();
        if ($instructor->image!=='') {
            if (file_exists('images/'.$instructor->image)) {
                unlink('images/'.$instructor->image);
            }
        }
        $instructor->delete();
        return redirect()->back()->with('message','Instructor deleted successfully');
    }
    public function edit(Request $Request)
    {
        return view('admin.editinstructor',['instructor'=>fee::where('id',$Request->id)->first()]);
    }
    public function update(Request $Request)
    {
        $instructor = fee::where('id',$Request->id)->first();
        $image = $instructor->image;
        if ($Request->hasFile('image')) {
            if ($instructor->image!=='') {
                if (file_exists('images/'.$instructor->image)) {
                    unlink('images/'.$instructor->image);
                }
            }
            $image = time().'.'.$Request->image->extension();
            $Request->image->move(public_path('../images/'),$image);
        }

        $instructor->update(['name'=>$Request->name,'gender'=>$Request->gender,'image'=>$image,'description'=>$Request->description]);
        return redirect('admin/instructors')->with('message','Instructor updated successfully');
    }
}
