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

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list" style="margin-right: 10px;"></i><strong>User
                        Action</strong></h3>

                <div class="card-tools retailer_active_search">
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-bordered table-sm table-hover table-head-fixed text-nowrap text-center">
                    <thead>
                        <tr>
                            <th style="background: #faaeae;">Name</th>
                            <th style="background: #faaeae;">Email</th>
                            <th style="background: #faaeae;">Status</th>



                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $retailer)
                            <tr class="bg-ocean">
                                <td>{{ $retailer->first_name.' '.$retailer->last_name }}</td>
                                <td>{{ $retailer->email }}</td>
                                <td>
                                <input data-id="{{$retailer->id}}" class="toggle-class recharge" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="Inactive" {{ $retailer->recharge_permission ? 'checked' : '' }}>
                              </td>


                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>


</div>

@endsection

@section('page-js')

@endsection
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script>
$(function() {
    $('.recharge').bootstrapToggle();
  $('.recharge').change(function() {

      var check = 0;
      var status = $(this).prop('checked') == true ? 1 : 0;
      var user_id = $(this).data('id');
      $.ajax({
          type: "GET",
          dataType: "json",
          url: 'changeStatus',
          data: {'status': status, 'user_id': user_id},
          success: function(data){

              if(data.message =='error')
              {
                 // $(this).prop('unchecked')
                  $('.recharge').bootstrapToggle('off')

                  // alert('You do not have this access')
              }
          }
      });
  })
})
</script>
