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
        Instructors
    </h3>
</div>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-">
        <h3></h3>
        <a class="btn btn-primary" href="{{url('admin/add-instructor')}}">Add Instructor</a>
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
                        <th>Image</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($instructors as $instructor)
                    <tr>
                        <td>{{$instructor->id}}</td>
                        <td><img src="{{asset('images/'.$instructor->image)}}"></td>
                        <td>{{$instructor->name}}</td>
                        <td>{{$instructor->gender}}</td>
                        <td>{{$instructor->description}}</td>
                        <td>
                            <a class="btn btn-warning" href="{{url('admin/edit-instructor/'.$instructor->id)}}">Edit</a>
                        </td>
                        <td>
                            <a class="btn btn-danger" href="{{url('admin/delete-instructor/'.$instructor->id)}}">Delete</a>
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
