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
    .redClass{
        color:red;
        font-weight: bold;
    }
</style>
@if(auth()->user()->role =='admin')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

            <div class="row" style="margin-left:10px;margin-top:20px">

                <div class="col-md-3" >
                    <select  class="form-control report_type" name="report_type"
                        id="report_type">
                       <option value="reseller">Reseller</option>
                       <option value="agent">Agent</option>
                    </select>
                </div>



            </div>

            </div>
        </div>
    </div>
</div>
@endif
<div class="row">

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list" style="margin-right: 10px;"></i>
                    <strong>Report</strong>
                </h3>
            </div>

            <div class="row" style="margin-left:10px;margin-top:20px">
                <div class="col-md-3">
                    <div class="date_picker_pair mb-3">
                        <label for="inputSearchDate" class="form-label">Select Date</label>
                        <input type="text" class="form-control" name="daterange" id="inputSearchDate" value="01/01/2018 - 01/15/2018">
                        <input type="hidden" class='start_date' name='start_date'>
                        <input type="hidden" class='end_date' name="end_date">

                        <!-- <input type="text" name="daterange" value="01/01/2018 - 01/15/2018" /> -->
                    </div>
                </div>

                @if(auth()->user()->role == 'admin')
                <div class="col-md-3" style="margin-left:15px">
                    <div class="form-row align-items-center offer_select_option">
                        <label for="inlineFormCustomSelect" style="margin-bottom:14px">Choose Retailer</label>

                        <select data-placeholder="Select an Option" class="custom-select reseller" id="reseller" name="resellerq">
                            <option></option>
                            @foreach ( $resellers as $data )
                            <option value="{{ $data->id }}">
                                {{ $data->first_name." ".$data->last_name }}
                            </option>
                            @endforeach

                        </select>
                    </div>
                </div>

                <div class="col-md-3" style="margin-left:15px">
                    <div class="form-row align-items-center offer_select_option">
                        <label for="inlineFormCustomSelect" style="margin-bottom:14px">Choose Agent</label>

                        <select data-placeholder="Select an Option" class="custom-select agent" id="agnet" name="agent">
                            <option></option>
                            @foreach ( $agents as $data )
                            <option value="{{ $data->id }}">
                                {{ $data->first_name." ".$data->last_name }}
                            </option>
                            @endforeach

                        </select>
                    </div>
                </div>
                @endif
                <div class="col-md-2">
                    <input type="button" onclick="filter()" value="Search" class="btn btn-success" style="margin-top:30px">
                </div>
            </div>


            <!-- /.card-header -->

            <div class="p-3">



                <div class="recharge_input_table table-responsive p-0">
                    <table class="table table-info table-sm table-bordered table-hover table-head-fixed text-nowrap invoice_table table-striped">
                        <thead>
                            <tr>
                                @if(Auth::user()->role == 'admin')
                                <th style="background-color: black;color:white">Reseller</th>
                                <th style="background-color: black;color:white">Agent</th>
                                @endif

                                <th style="background-color: black;color:white">Mobile Number</th>
                                <th style="background-color: black;color:white">Transaction No</th>
                                <th style="background-color: black;color:white">Last Digit</th>
                                <th style="background-color: black;color:white">Type</th>
                                <th style="background-color: black;color:white">Status</th>
                                <th style="background-color: black;color:white">Date</th>
                                <th style="background-color: black;color:white">Amount</th>

                                @if(Auth::user()->role != 'agent')
                                <th style="background-color: black;color:white">Service Charge</th>
                                @endif

                                @if(Auth::user()->role == 'agent')
                                <th style="background-color: black;color:white">Agent Profit</th>
                                @endif

                                @if(Auth::user()->role == 'admin')
                                <th style="background-color: black;color:white">Admin Profit</th>
                                <th style="background-color: black;color:white">Agent Profit</th>
                                @endif

                            </tr>
                        </thead>
                        <tbody id='change'>

                        </tbody>

                        <tfoot class="thead-dark" style="background-color: black">
                            <tr>
                                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'sub')
                                <th scope="col"></th>
                                <th scope="col"></th>
                                @endif
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col">Total</th>
                                <th scope="col"></th>
                                @if(Auth::user()->role != 'agent')
                                <th scope="col"></th>
                                @endif

                                @if(Auth::user()->role == 'agent')
                                <th scope="col"></th>
                                @endif

                                @if(Auth::user()->role == 'admin')
                                <th scope="col"></th>
                                <th scope="col"></th>
                                @endif

                            </tr>

                        </tfoot>

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

<script>
    $(function() {

        $('.reseller').select2({

            placeholder: function() {
                $(this).data('placeholder');
            }

        });

        $('.agent').select2({

            placeholder: function() {
                $(this).data('placeholder');
            }

        });

        $(".reseller").change(function() {
            fetch_table($(".start_date").val(), $(".end_date").val())

        });

        $(".report_type").change(function() {
            fetch_table($(".start_date").val(), $(".end_date").val())

        });

        $(".agent").change(function() {
            fetch_table($(".start_date").val(), $(".end_date").val())

        });

        $("#ExampleSelect").change(function() {
            fetch_table($(".start_date").val(), $(".end_date").val())

        });


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        var start = moment().subtract(29, 'days');
        var end = moment();
        $(".start_date").val(start.format('YYYY-MM-DD'));
        $(".end_date").val(end.format('YYYY-MM-DD'));



        $('input[name="daterange"]').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, function(start, end, label) {

            $(".start_date").val(start.format('YYYY-MM-DD'));
            $(".end_date").val(end.format('YYYY-MM-DD'));

            fetch_table($(".start_date").val(), $(".end_date").val());
        });

        fetch_table($(".start_date").val(), $(".end_date").val())


    });

    function filter() {

        fetch_table($(".start_date").val(), $(".end_date").val())
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
            columnDefs: [{
                    "orderable": false,
                    "targets": "_all"
                } // Applies the option to all columns
            ],
            ajax: {

                "url": 'get_all_report',
                "type": 'POST',
                dataSrc: function(data) {
                    if (data.data.length != 0) {
                        total_cost = data.data[0].total_cost
                        total_service_charge = data.data[0].total_service_charge
                        total_agent_profit = data.data[0].total_agent_profit
                        total_admin_profit = data.data[0].total_admin_profit
                    } else {
                        total_cost = 0
                        total_agent_profit = 0
                        total_service_charge = 0
                        total_admin_profit = 0


                    }
                    return data.data;

                },
                "data": {
                    'start_date': $(".start_date").val(),
                    'end_date': $(".end_date").val(),
                    'type': $('#ExampleSelect option:selected').val(),
                    'retailer_id': $('#reseller option:selected').val(),
                    'agent_id': $('#agent option:selected').val(),
                    'report_type': $('#report_type option:selected').val()

                }


            },
            createdRow: function( row, data, dataIndex){
                console.log(data['status'])
                if( data['status'] ==  'error'){
                    $(row).addClass('redClass');
                }
            },
            deferRender: true,
            columns: [
                //   {data: 'sl_no'},
                @if(Auth::user()->role == 'admin') {
                    data: 'reseller_name',
                    name: 'reseller_name',
                    orderable: false
                },
                {
                    data: 'agent_name',
                    name: 'agent_name',
                    orderable: false
                },
                @endif
                {
                    data: 'mobile_number',
                    name: 'mobile_number'
                },
                {
                    data: 'transaction_no',
                    name: 'transaction_no'
                },
                {
                    data: 'last_four_digit',
                    name: 'last_four_digit'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },

                @if(Auth::user()->role != 'agent') {
                    data: 'service_charge',
                    name: 'service_charge'
                },
                @endif

                @if(Auth::user()->role == 'agent') {
                    data: 'agent_profit',
                    name: 'agent_profit'
                },
                @endif

                @if(Auth::user()->role == 'admin' ) {
                    data: 'admin_profit',
                    name: 'admin_profit'
                },
                {
                    data: 'agent_profit',
                    name: 'agent_profit'
                },
                @endif


            ],


            drawCallback: function() {
                var api = this.api();

                @if(Auth::user()->role == 'admin')
                $(api.column(8).footer()).html(
                    total_cost
                );

                $(api.column(9).footer()).html(
                    total_service_charge
                );

                $(api.column(10).footer()).html(
                    total_admin_profit
                );

                $(api.column(11).footer()).html(
                    total_agent_profit
                );


                @else
                $(api.column(6).footer()).html(
                    total_cost
                );

                @endif

                @if(Auth::user()->role == 'reseller')
                $(api.column(7).footer()).html(
                    total_service_charge
                );
                @endif

                @if(Auth::user()->role == 'agent')
                $(api.column(7).footer()).html(
                    total_agent_profit
                );
                @endif

                //datatable_sum(api, false);
            }


        });


    }
</script>
