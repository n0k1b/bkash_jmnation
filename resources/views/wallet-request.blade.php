@extends('layout.app')

@section('main-panel')
<style>
    .owl-stage {
        overflow-y: auto;
        height: 224px;
    }

    /* .sorting_disabled{
    display: none !important;
} */
    table.dataTable thead .sorting_asc {
        background-image: none !important;
    }
</style>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list" style="margin-right: 10px;"></i>
                    <strong>Wallet Request</strong>
                </h3>
            </div>
            @if(auth()->user()->role != 'agent')
            <div class="row" style="margin-left:10px;margin-top:20px">
                <div class="col-md-2">
                    <div class="date_picker_pair mb-3">
                        <input type="number" class="form-control" id="amount" placeholder="Amount"
                            style="margin-top:30px">

                    </div>
                </div>

                <div class="col-md-3">
                    <div class="date_picker_pair mb-3">
                        <input type="file" id="document" name="img[]" class="form-control file-upload-default"
                            style="margin-top:30px">
                    </div>
                </div>


                <div class="col-md-3">

                    <textarea class="form-control" id="message" rows="3" style="margin-top:30px"
                        placeholder="Send Message (optional)" style="margin-top:30px"></textarea>

                </div>
                @if(auth()->user()->role == 'admin')
                <div class="col-md-3" style="margin-top:30px">
                    <select data-placeholder="Select an Option" class="form-control agent" name="agent_id"
                        id="agent_id">
                        <option></option>
                        @foreach($agents as $agent)
                        <option value="{{$agent->id}}">{{ $agent->first_name.' '.$agent->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="button" onclick="submit_request()" value="Send Request"
                        class="form-control btn btn-md btn-success" style="margin-top:20px;float:right">
                </div>

                @endif

                @if(auth()->user()->role != 'admin')
                <div class="col-md-2">
                    <input type="button" onclick="submit_request()" value="Send Request"
                        class="form-control btn btn-md btn-success" style="margin-top:40px;float:right">
                </div>
                @endif
            </div>
            @endif


            <!-- /.card-header -->

            <div class="p-3">



                <div class="recharge_input_table table-responsive p-0">
                    <table
                        class="table table-info table-sm table-bordered table-hover table-head-fixed text-nowrap invoice_table table-striped">
                        <thead>
                            <tr>
                                @if(Auth::user()->role == 'admin')
                                <th style="background-color: black;color:white">Reseller/Agent</th>
                                @endif
                                <th style="background-color: black;color:white">Amount</th>
                                <th style="background-color: black;color:white">Request Date</th>
                                <th style="background-color: black;color:white">Accepted/Declined Date</th>
                                <th style="background-color: black;color:white">Document</th>
                                <th style="background-color: black;color:white">Status</th>
                                @if(Auth::user()->role == 'admin')
                                <th style="background-color: black;color:white">Request Type</th>
                                @endif
                                @if(Auth::user()->role != 'reseller')
                                <th style="background-color: black;color:white">Action</th>
                                @endif


                            </tr>
                        </thead>
                        <tbody id='change'>

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('page-js')

@endsection
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" defer></script>
<script src="https://unpkg.com/izitoast/dist/js/iziToast.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.25/api/sum().js" type="text/javascript"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>


    $(function () {

        $('.agent').select2({

            placeholder: function () {
                $(this).data('placeholder');
            }

        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        fetch_table()
    })

    function filter() {

        fetch_table($(".start_date").val(), $(".end_date").val())
    }

    function submit_request() {
        swal({
            title: "Are you sure to send this request?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    event.preventDefault();
                    var formdata = new FormData();
                    formdata.append('amount', $('#amount').val());
                    formdata.append('message', $("#message").val());
                    formdata.append('document', $('#document')[0].files[0]);
                    formdata.append('agent_id', $('.agent option:selected').val() == undefined ? '' : $('.agent option:selected').val())
                    //  formdata.append('wallet_type' $(".wallet_type :selected").val());

                    $.ajax({
                        processData: false,
                        contentType: false,
                        url: "submit_wallet_request",
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
                            fetch_table();
                            //load_recent_recharge();

                            $('.cover-spin').hide(0)
                            $("#amount").val("");
                            $("#document").val("");
                            $("#message").val("");
                            iziToast.success({
                                backgroundColor: "Green",
                                messageColor: 'white',
                                iconColor: 'white',
                                titleColor: 'white',
                                titleSize: '18',
                                messageSize: '18',
                                color: 'white',
                                position: 'topCenter',
                                timeout: 10000,
                                title: 'Success',
                                message: "Your Request has been placed successfully",

                            });


                        },
                    });
                }
            });





    }


    function accept_request(id) {
        var formdata = new FormData();
        formdata.append('id', id);

        swal({
            title: "Are you sure to accept this request?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        processData: false,
                        contentType: false,
                        url: "accept_wallet_request",
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
                            fetch_table();
                            //load_recent_recharge();

                            $('.cover-spin').hide(0)
                            $("#amount").val("");
                            $("#document").val("");
                            $("#message").val("");
                            iziToast.success({
                                backgroundColor: "Green",
                                messageColor: 'white',
                                iconColor: 'white',
                                titleColor: 'white',
                                titleSize: '18',
                                messageSize: '18',
                                color: 'white',
                                position: 'topCenter',
                                timeout: 10000,
                                title: 'Success',
                                message: "Your Request has been placed successfully",

                            });


                        },
                    });
                } else {
                    //location.reload()
                }
            });


    }



    function decline_request(id) {
        var formdata = new FormData();
        formdata.append('id', id);

        swal({
            title: "Are you sure to decline this request?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        processData: false,
                        contentType: false,
                        url: "decline_wallet_request",
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
                            fetch_table();
                            //load_recent_recharge();

                            $('.cover-spin').hide(0)
                            $("#amount").val("");
                            $("#document").val("");
                            $("#message").val("");
                            iziToast.success({
                                backgroundColor: "Green",
                                messageColor: 'white',
                                iconColor: 'white',
                                titleColor: 'white',
                                titleSize: '18',
                                messageSize: '18',
                                color: 'white',
                                position: 'topCenter',
                                timeout: 10000,
                                title: 'Success',
                                message: "Your Request has been placed successfully",

                            });


                        },
                    });
                } else {
                    //location.reload()
                }
            });


    }

    function fetch_table(start_date, end_date) {

        var user_role = $("#user_role").val();

        var table = $('.invoice_table').DataTable();
        //console.log(table.column( 5 ).data().sum());
        table.destroy();

        var table = $('.invoice_table').DataTable({


            processing: true,
            serverSide: true,

            ordering: false,
            searchPanes: {
                orderable: false
            },
            dom: 'Plfrtip',
            language: {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>'
            },
            columnDefs: [
                { "orderable": false, "targets": "_all" } // Applies the option to all columns
            ],
            ajax: {

                "url": 'get_wallet_data_send',
                "type": 'get'
            },
            deferRender: true,
            columns: [
            //   {data: 'sl_no'},
            @if (Auth:: user() -> role == 'admin')
        { data: 'reseller_name', name: 'reseller_name', orderable: false },
        @endif
        { data: 'amount', name: 'amount' },
        { data: 'request_date', name: 'request_date' },
        { data: 'accepted_date', name: 'accepted_date' },
        { data: 'document', name: 'document' },
        { data: 'status', name: 'status' },
        @if (Auth:: user() -> role == 'admin')
        { data: 'request_type', name: 'request_type' },
        @endif
        @if (Auth:: user() -> role != 'reseller' )
        { data: 'action', name: 'action' },
        @endif


            ],


    });


}
</script>