<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\course;
use App\Models\fee;
class CourseController extends Controller
{


    public function view_course()
    {

        return view('course',['courses'=>course::all()]);
    }
    public function index()
    {
        return view('admin.courses',['courses'=>course::all()]);
    }
    public function addCourse(Request $Request)
    {
        $image = time().'.'.$Request->image->extension();
        $Request->image->move(public_path('../images/'),$image);
        $course = course::create($Request->except('image'));
        $course->update(['image'=>$image]);
        return redirect('admin/courses')->with('message','Course created successfully');
    }
    public function delete(Request $Request)
    {
        $course = course::where('id',$Request->id)->first();
        if ($course->image!=='') {
            if (file_exists('images/'.$course->image)) {

                unlink('images/'.$course->image);
            }
        }
        $course->delete();
        return redirect()->back()->with('message','Course deleted successfully');
    }
    public function editCourse(Request $Request)
    {
        return view('admin.editCourse',['course'=>course::where('id',$Request->id)->first()]);
    }
    public function updateCourse(Request $Request)
    {
        $course = course::where('id',$Request->id)->first();
        $image = $course->image;
        if ($Request->hasFile('image')) {
            unlink('images/'.$course->image);
            $image = time().'.'.$Request->image->extension();
            $Request->image->move(public_path('../images/'),$image);
            $Request->image = $image;
        }
        $course->update(['title'=>$Request->title,'duration'=>$Request->duration,'description'=>$Request->description,'image'=>$image,'price'=>$Request->price]);
        return redirect('admin/courses')->with('message','Course updated successfully');
    }
}
