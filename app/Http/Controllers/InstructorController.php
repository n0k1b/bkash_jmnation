<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\instructor;

class InstructorController extends Controller
{
    public function view_all_instructor()
    {
        return view('instructor',['instructors'=>instructor::all()]);
    }
    public function index()
    {
        return view('admin.instructors',['instructors'=>instructor::all()]);
    }
    public function add(Request $Request)
    {
        $image = time().'.'.$Request->image->extension();
        $Request->image->move(public_path('../images/'),$image);
        $instructor = instructor::create($Request->except('image'));
        $instructor->update(['image'=>$image]);
        return redirect('admin/instructors')->with('message','Instructor created successfully');
    }

    public function delete(Request $Request)
    {
        $instructor = instructor::where('id',$Request->id)->first();
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
        return view('admin.editinstructor',['instructor'=>instructor::where('id',$Request->id)->first()]);
    }
    public function update(Request $Request)
    {
        $instructor = instructor::where('id',$Request->id)->first();
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
