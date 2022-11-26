@extends('layout.app')

@section('main-panel')
<style>
    .owl-stage {
        overflow-y: auto;
        height: 224px;
    }

</style>
@if(\Session::has('error'))
    <div class="alert alert-danger">
        <ul>
            <li>{!! \Session::get('error') !!}</li>
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
        Transaction
    </h3>
</div>
<div class="row">
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('create_transaction') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="title">Mobile Number</label>
                        <input type="text" class="form-control" placeholder="Mobile Number" name="mobile_number"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="type">Type</label>
                        <select class="form-control form-control-lg" id="exampleFormControlSelect2" name="type">
                            <option value="Bkash">Bkash</option>
                            <option value="Nagad">Nagad</option>
                            <option value="Rocket">Rocket</option>
                            <option value="Ucash">Ucash</option>
                            <option value="Upay">Upay</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" id="duration" placeholder="Amount" name="amount"
                            required>
                    </div>
                    <button class="btn btn-primary" type="submit">Create</button>
                </form>

            </div>
        </div>
    </div>
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Last 10 Transaction</h4>
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Number</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transaction as $t)
                                         <tr>
                                            <td>{{$t->mobile_number}}</td>
                                            <td>{{$t->type}}</td>
                                            <td>{{$t->amount}}</td>
                                            <td>{{$t->created_at}}</td>
                                            <td><label class="badge {{$t->status=='pending'?'badge-warning':($t->status=='complete'?'badge-success':'badge-danger')}} badge-pill">{{$t->status}}</label></td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>

@endsection

@section('page-js')
<script type="text/javascript" src="{{ asset('assets/timer') }}/jquery.syotimer.min.js"></script>
@endsection
