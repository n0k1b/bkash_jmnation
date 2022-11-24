@extends('layout.app')

@section('main-panel')
<style>
    .owl-stage{
        overflow-y: auto;
        height: 224px;
    }
</style>

<div class="page-header">
    <h3 class="page-title">
        Courses
    </h3>
</div>
<div class="card">
    <div class="card-body">
        <div class="container">
            <form action="{{url('admin/update-demoquestion')}}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{$question->id}}" id="">
                <div class="form-group">
                    <label for="question">Question</label>
                    <input type="text" class="form-control" id="question" placeholder="Question" name="question" required value="{{$question->question}}">
                </div>
                <div class="form-group">
                    <label for="answer">Answer</label>
                    <input type="text" class="form-control" id="answer" placeholder="Answer" name="answer" required value="{{$question->answer}}">
                </div>
                <button class="btn btn-primary" type="submit">Create</button>
            </form>
        </div>
    </div>
</div>

@endsection

@section('page-js')
@endsection
