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
            <form action="{{url('admin/add-instructor')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" name="image" id="image" class="d-block" required>
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" placeholder="Name" name="name" required>
                </div>
                <div class="form-group">
                    <label class="ml-3" for="male">Male</label>
                    <input type="radio" id="male" name="gender" value="male" checked>
                    <label class="ml-3" for="female">Female</label>
                    <input type="radio" id="female" name="gender" value="female">
                    <label class="ml-3" for="others">Others</label>
                    <input type="radio" id="others" name="gender" value="others">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" placeholder="Description" class="form-control" required></textarea>
                </div>
                <button class="btn btn-primary" type="submit">Create</button>
            </form>
        </div>
    </div>
</div>

@endsection

@section('page-js')
@endsection
