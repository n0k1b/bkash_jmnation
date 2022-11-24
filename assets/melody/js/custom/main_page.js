$(function(){
    $.ajaxSetup({

        headers: {

            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

        }

    });

   check_notification();
  rent_details_insertion();




});
function rent_details_insertion()
{
    $.ajax({
        processData:false,
        contentType:false,
        type:'GET',
        url:rent_details_insert,
        success:function(data){



        }
    })
}

function check_notification()
{
    //alert('hello');

    $.ajax({
        processData:false,
        contentType:false,
        type:'GET',
        url:check_notifi,
        success:function(data){

          $("#notification_count").html(data);

        }
    })
}

