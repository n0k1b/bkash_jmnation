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
        Fee
    </h3>
</div>
<div class="card">
    <div class="card-body">
        <div class="container">
            <form action="{{url('admin/add-fee')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="course">Course</label>
                    <select name="course_id" id="course" class="form-control" required>
                        <option value="">Select a course</option>
                        @foreach ($courses as $course)
                            <option value="{{$course->id}}">{{$course->title}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" placeholder="Name" name="student_name" required>
                </div>
                <div class="form-group">
                    <label for="mobile">Mobile</label>
                    <input type="text" class="form-control" id="mobile" placeholder="Phone number" name="student_mobile" required>
                </div>
                <div class="form-group">
                    <label for="mobile">Address</label>
                    <input type="text" class="form-control" id="mobile" placeholder="Address" name="student_address" required>
                </div>
                <div class="form-group">
                    <label for="Fee_status">Fee Confirmation</label>
                    <input type="checkbox"  id="Fee_status" name="fee_confirmation">
                </div>
                <div class="form-group">
                    <label for="course_status">Course complete status</label>
                    <input type="checkbox"  id="course_status" name="student_address">
                </div>
                <button class="btn btn-primary" type="submit">Create</button>
            </form>
        </div>
    </div>
</div>

@endsection

@section('page-js')
@endsection
