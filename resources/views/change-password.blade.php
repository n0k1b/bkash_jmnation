@extends('layout.app')

@section('main-panel')
<style>
    .owl-stage {
        overflow-y: auto;
        height: 224px;
    }
</style>
@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(\Session::has('success'))
<div class="alert alert-success">
    <ul>
        <li>{!! \Session::get('success') !!}</li>
    </ul>
</div>
@endif

<div class="page-header">
    <h3 class="page-title">
        Change Password
    </h3>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('update_password') }}" method="post">
                    @csrf

                    <div class="form-group">
                        <label for="title">Current Password</label>
                        <input type="password" class="form-control" placeholder="Current Password" name="current_password"
                             required>
                    </div>

                    <div class="form-group">
                        <label for="title">New Password</label>
                        <input type="password" class="form-control" placeholder="New Password" name="password"
                             required>
                    </div>

                    <div class="form-group">
                        <label for="title">Re-type Password</label>
                        <input type="password" class="form-control" placeholder="Re-type Password" name="password_confirmation"
                           required>
                    </div>


                    <button class="btn btn-primary" type="submit">Update Password</button>
                </form>

            </div>
        </div>
    </div>




</div>

@endsection

@section('page-js')
<script type="text/javascript" src="{{ asset('assets/timer') }}/jquery.syotimer.min.js"></script>
@endsection
