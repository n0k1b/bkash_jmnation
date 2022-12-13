@extends('layout.app')

@section('main-panel')
<style>
    .owl-stage {
        overflow-y: auto;
        height: 224px;
    }

    .switch {
    position: relative;
    display: inline-block;
    width: 43px;
    height: 21px;
    }
    .round{
        padding: 10px;
        color:black;
    }
    select2-container {
    z-index:10050;
}
    .slider:before {

    position: absolute;
    content: "";
    height: 19px;
    width: 16px;
    left: 1px;
    bottom: 1px;

    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
}
    table.dataTable thead .sorting_asc {
        background-image: none !important;
    }

</style>

<div class="row">

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">

            <div class="p-3">

                <div class="recharge_input_table table-responsive p-0">
                    <table id="datatable"
                        class="table table-info table-sm table-bordered table-hover table-head-fixed text-nowrap invoice_table table-striped">
                        <thead>
                            <tr>
                                <th style="background-color: black;color:white">Name</th>
                                <th style="background-color: black;color:white">Email</th>
                                <th style="background-color: black;color:white">Wallet</th>
                                <th style="background-color: black;color:white">Status</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $d )
                                <tr>
                                <?php
$checked = $d->status == '1' ? 'checked' : '';
?>
                                    <td>{{ $d->first_name.' '.$d->last_name }}</td>
                                    <td>{{ $d->email }}</td>
                                    <td>{{ $d->wallet }}</td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" onclick="user_active_status({{ $d->id }})"
                                                {{ $checked }} />
                                            <span class="slider round"></span>
                                        </label>
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
    $(function () {
        $('#datatable').DataTable();
    })

    function user_active_status(id) {
    $.ajax({
        processData: false,
        contentType: false,
        type: 'GET',
        url: 'user_active_status_update/' + id,
        success: function(data) {


        }
    })
}

</script>
