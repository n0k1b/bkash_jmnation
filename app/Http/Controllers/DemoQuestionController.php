<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\demo_question;

class DemoQuestionController extends Controller
{
    public function index()
    {
        return view('admin.demoquestions',['questions'=>demo_question::all()]);
    }
    public function add(Request $Request)
    {
        $course = demo_question::create($Request->all());
        return redirect('admin/demoquestions')->with('message','Demo question created successfully');
    }
    public function delete(Request $Request)
    {
        $demoquestion = demo_question::where('id',$Request->id)->delete();
        return redirect()->back()->with('message','Demo question deleted successfully');
    }
    public function edit(Request $Request)
    {
        return view('admin.editDemoquestion',['question'=>demo_question::where('id',$Request->id)->first()]);
    }
    public function update(Request $Request)
    {
        demo_question::where('id',$Request->id)->update(['question'=>$Request->question,'answer'=>$Request->answer]);
        return redirect('admin/demoquestions')->with('message','Demo question updated successfully');
    }
}
