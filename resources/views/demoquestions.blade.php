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
        Questions
    </h3>
</div>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-">
        <h3></h3>
        <a class="btn btn-primary" href="{{url('admin/add-demoquestion')}}">Add Courses</a>
    </div>
    <div class="card-body">
        @if (session('message'))
        <div class="alert alert-success">{{session('message')}}</div>
        @endif
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Question</th>
                        <th>Answer</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($questions as $question)
                    <tr>
                        <td>{{$question->id}}</td>
                        <td>{{$question->question}}</td>
                        <td>{{$question->answer}}</td>
                        <td>
                            <a class="btn btn-warning" href="{{url('admin/edit-demoquestion/'.$question->id)}}">Edit</a>
                        </td>
                        <td>
                            <a class="btn btn-danger" href="{{url('admin/delete-demoquestion/'.$question->id)}}">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('page-js')
@endsection
