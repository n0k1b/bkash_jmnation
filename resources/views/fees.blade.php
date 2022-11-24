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
        Fees
    </h3>
</div>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-">
        <h3></h3>
        <!-- <a class="btn btn-primary" href="{{url('admin/add-fee')}}">Add Fee</a> -->
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
                        <th>Course</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Address</th>
                        <th>Time Slot</th>
                        <th>Transaction Number</th>
                        <th>Payment Method</th>
                        <th>Day Slot</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($fees as $fee)
                    <tr>
                        <td>{{$fee->id}}</td>
                        <td>{{$fee->course->title}}</td>
                        <td>{{$fee->student_name}}</td>
                        <td>{{$fee->student_mobile}}</td>
                        <td>{{$fee->student_address}}</td>
                        <td>{{$fee->time_slot}}</td>
                        <td>{{$fee->transaction_number}}</td>
                        <td>{{$fee->payment_method}}</td>
                        <td>{{$fee->day_slot}}</td>

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
