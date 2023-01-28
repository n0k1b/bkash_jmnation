@extends('layout.app')
<style>
  .row {
    display: flex;
    align-items: center;
    justify-content: center;
}

.card {
  display: grid;
  place-items: center;
}




</style>
@section('main-panel')
<div class="row">
    <div class="col-lg-4 grid-margin stretch-card print" id="invoice_card">
        <div class="card" >
            <div class="card-header">
            <img src="{{asset('public/assets/melody')}}/images/logo.png"
            alt="logo" />
            </div>
            <div class="card-body">
            <div class="table-responsive">
                    <table class="table">

                      <tbody>
                        <tr>
                          <td><b>Mobile Number</b></td>
                          <td>{{$data->mobile_number}}</td>
                        </tr>

                        <tr>
                          <td><b>Amount</b></td>
                          <td>{{$data->amount}}</td>
                        </tr>


                        <tr>
                          <td><b>Last Four Digit</b></td>
                          <td>{{$data->last_four_digit}}</td>
                        </tr>

                        <tr>
                          <td><b>Requested Date(Italy Time)</b></td>
                          <td>{{date('d-m-Y H:i:s', strtotime($data->created_at))}}</td>
                        </tr>

                        <tr>
                          <td><b>Confirmed Date(BDT Time)</b></td>
                          <td>{{$data->transaction_date ? date('d-m-Y H:i:s', strtotime($data->transaction_date)): null}}</td>
                        </tr>

                      </tbody>
                    </table>
                  </div>



            </div>
            <a href="javascript:void" onclick="printDiv()" rel="noopener" class="btn btn-default">
                      <i class="fas fa-print"></i>
                      Print
                    </a>

        </div>

</div>

@endsection

@section('page-js')

@endsection
<script>
    function printDiv() {
    var printContents = document.getElementById('invoice_card').innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
</script>
