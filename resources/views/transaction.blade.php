@extends('layout.app')

@section('main-panel')
<style>
    .owl-stage {
        overflow-y: auto;
        height: 224px;
    }

</style>

<div class="page-header">
    <h3 class="page-title">
        Transaction
    </h3>
</div>
<div class="row">
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <button type="button" class="btn btn-primary btn-rounded btn-fw"
                        onclick="fetchNewTransaction()">Fetch New Transaction</button>
                </div>
                <div id="transaction_record">

                </div>

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
                                    <tbody id="all_transaction">


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
<script>
    $(function () {
        $('#transaction_record').hide()
        fetchAllTransaction()
    })
    const base_url = 'https://bkash_copy.test';
    let transactionId;
    fetchNewTransaction = () => {
        axios.get(base_url + '/api/getNewTranasction')
            .then(res => {
                const {
                    status,
                    data
                } = res.data

                transactionId = data.id
                $("#transaction_record").empty()
                $("#transaction_record").show()
                if(data.length>0){

                $('#transaction_record').append(`<div class="card" style="margin-top:18px;background-color:#f5e4e4">
                        <div class="card-body">
                            <form class="forms-sample">

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" style="font-size:16px;font-weight:600">Mobile
                                        Number</label>
                                    <div class="col-sm-9">
                                        <input style="font-size: 14px;font-weight:600" type="text" class="form-control"
                                            value="`+data.mobile_number+`" disabled>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label"
                                        style="font-size:16px;font-weight:600">Type</label>
                                    <div class="col-sm-9">
                                        <input style="font-size: 14px;font-weight:600" type="text" class="form-control"
                                            value="`+data.type+`" disabled>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label"
                                        style="font-size:16px;font-weight:600">Amount</label>
                                    <div class="col-sm-9">
                                        <input style="font-size: 14px;font-weight:600" type="text" class="form-control"
                                            value="`+data.amount+`" disabled>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label"
                                        style="font-size:16px;font-weight:600">Transaction No</label>
                                    <div class="col-sm-9">
                                        <input style="font-size: 14px;font-weight:600" type="text" class="form-control" id="transaction_no">
                                    </div>
                                </div>




                                <button type="button" class="btn btn-primary mr-2" onclick="saveTransaction()">Confirm</button>
                                <button class="btn btn-light">Pass</button>
                            </form>
                        </div>

                    </div>`)
                }
                else{
                    $('#transaction_record').append(`<div class="card" style="margin-top:18px;background-color:#f5e4e4">
                        <div class="card-body">
                            <p>No New Transaction Available</p>
                        </div>

                    </div>`)
                }

            })
            .catch(err => console.log(err));
    }

    fetchAllTransaction = () => {
        axios.get(base_url + '/api/getAllTransaction')
            .then(res => {
                const {
                    status,
                    data
                } = res.data
                const allTransaction = Object.values(data);
                $("#all_transaction").empty()
                // for(d in data){
                //     console.log(d)
                // }
                data.forEach(element => {
                    $('#all_transaction').append(`<tr>
                                            <td>01845318609</td>
                                            <td>Bkash</td>
                                            <td>1000</td>
                                            <td>11-10-2022</td>
                                            <td><label class="badge badge-danger badge-pill">Pending</label></td>
                                        </tr>`)
                });


            })
            .catch(err => console.log(err));
    }
    saveTransaction = () =>{
        var formData = new FormData()
        formData.append('transactionId',transactionId)
        formData.append('transactionNo',$('#transaction_no').val())
        axios.post(base_url + '/api/saveTransaction',formData)
            .then(res => {
                const {
                    status,
                    data
                } = res.data

                if(status == true){
                $("#transaction_record").empty()
                $("#transaction_record").hide()
                iziToast.success({
                    backgroundColor:"Green",
                    messageColor:'white',
                    iconColor:'white',
                    titleColor:'white',
                    titleSize:'18',
                    messageSize:'18',
                    color:'white',
                    position:'topCenter',
                    timeout: 10000,
                    title: 'Success',
                    message: "Transaction Save Successfully",

                });

                }
                fetchAllTransaction()
            })
            .catch(err => console.log(err));

    }



</script>
