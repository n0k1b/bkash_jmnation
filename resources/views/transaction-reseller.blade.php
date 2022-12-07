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
                <form action="{{ route('create_transaction') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="title">Mobile Number</label>
                        <input type="text" class="form-control" placeholder="Mobile Number" name="mobile_number"
                            value="{{old('mobile_number')}}" required>
                    </div>
                    <div class="form-group">
                        <label for="type">Transaction Type</label>
                        <select class="form-control form-control-lg" id="exampleFormControlSelect2" name="type"
                            value="{{old('type')}}">
                            <option value="Bkash">Bkash</option>
                            <option value="Nagad">Nagad</option>
                            <option value="Rocket">Rocket</option>
                            <option value="Ucash">Ucash</option>
                            <option value="Upay">Upay</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="type">Account Type</label>
                        <select class="form-control form-control-lg" id="exampleFormControlSelect2" name="account_type"
                            value="{{old('account_type')}}">
                            <option value="Personal">Personal</option>
                            <option value="Agent">Agent</option>
                            <option value="Merchant">Merchant</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" placeholder="Amount" name="amount"
                            value="{{old('amount')}}" required>
                    </div>

                    <div class="form-group">
                        <label for="amount">Service Charge</label>
                        <input type="number" class="form-control" placeholder="Amount" name="service_charge"
                            value="{{old('service_charge')}}" required>
                    </div>

                    <div class="form-group">
                        <label for="amount">Pin</label>
                        <input type="password" class="form-control" placeholder="Pin" name="pin" required>
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
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transaction as $t)
                                        <tr>
                                            <td>{{ $t->mobile_number }}</td>
                                            <td>{{ $t->type }}</td>
                                            <td>{{ $t->amount }}</td>
                                            <td><label
                                                    class="badge {{ $t->status=='pending'?'badge-warning':($t->status=='complete'?'badge-success':'badge-danger') }} badge-pill">{{
                                                    $t->status }}</label>
                                            </td>
                                            <td>
                                                @if($t->status == 'pending')
                                                <button type="button" onclick="delete_transaction({{$t->id}})"
                                                    class="btn btn-outline-secondary btn-rounded btn-icon">
                                                    <i class="fas fa-trash text-danger"></i>
                                                </button>
                                                @endif
                                            </td>
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
<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/izitoast/dist/js/iziToast.min.js" type="text/javascript"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $(function () {
        var toast = document.querySelector('.iziToast');
        var message = sessionStorage.getItem('message');
        sessionStorage.removeItem('message');

        if (toast) {
            iziToast.hide({}, toast);
        }
        if (sessionStorage.getItem('error')) {
            sessionStorage.removeItem('error');

            iziToast.error({
                backgroundColor: "#D12C09",
                messageColor: 'white',
                iconColor: 'white',
                titleColor: 'white',
                titleSize: '18',
                messageSize: '18',
                color: 'white',
                position: 'topCenter',
                timeout: 30000,
                title: 'Error',
                message: message,


            });

            //console.log(response.message);

        }

        if (sessionStorage.getItem('success')) {
            sessionStorage.removeItem('success');


            iziToast.success({
                backgroundColor: "Green",
                messageColor: 'white',
                iconColor: 'white',
                titleColor: 'white',
                titleSize: '18',
                messageSize: '18',
                color: 'white',
                position: 'topCenter',
                timeout: 30000,
                title: 'Success',
                message: message,

            });
            //console.log(response.message);

        }
    });
    function delete_transaction(id) {
        var formdata = new FormData();
        formdata.append('id', id);

        swal({
            title: "Are you sure to delete this record?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        processData: false,
                        contentType: false,
                        url: "delete_transaction",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formdata,
                        beforeSend: function () {
                            $('.cover-spin').show(0)
                        },
                        complete: function () { // Set our complete callback, adding the .hidden class and hiding the spinner.
                            $('.cover-spin').hide(0)
                        },
                        success: function (response) {
                            console.log(response)
                            //location.reload();
                            //load_recent_recharge();

                            $('.cover-spin').hide(0)
                            if (response.status == true) {
                                location.reload();
                                sessionStorage.setItem('success', true);
                                sessionStorage.setItem('message', response.message);
                                //console.log(response.message);
                            }
                            else {
                                location.reload();
                                sessionStorage.setItem('error', true);
                                sessionStorage.setItem('message', response.message);
                            }



                        },
                    });
                } else {
                    //location.reload()
                }
            });
    }
</script>