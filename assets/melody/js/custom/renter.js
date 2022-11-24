$(function(){
    $.ajaxSetup({

        headers: {

            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

        }

    });

    readData();
    view_notification();
    rent_details();
    service_charge_details();
    gas_bill_details();



});
function rent_details()
{
    $.ajax({
        processData:false,
        contentType:false,
        type:'GET',
        url:"get_rent_details",
        success:function(data){

          $("#rent_details").html(data);

        }
    })

}

function service_charge_details()
{
    $.ajax({
        processData:false,
        contentType:false,
        type:'GET',
        url:"get_service_charge_details",
        success:function(data){

          $("#rent_details").html(data);

        }
    })

}
function complain_box()
{
    var message = document.getElementById("complain_text").value.trim();
    var formdata = new FormData();
    formdata.append('message',message);
    $.ajax({
        processData:false,
        contentType:false,
        data:formdata,
        type:'post',
        url:"submit_complain",
        success:function(data){

          alert('Complain submit successfully');
          location.reload();

        }
    })

}
function gas_bill_details()
{
    $.ajax({
        processData:false,
        contentType:false,
        type:'GET',
        url:"get_gas_bill_details",
        success:function(data){

          $("#rent_details").html(data);

        }
    })

}
function readData() {

    $.ajax({
        processData:false,
        contentType:false,
        type:'GET',
        url:"get_all_booking",
        success:function(data){

          $("#booking_list_table").html(data);

        }
    })
}

function show_apartment_details(id)
{
    var formdata = new FormData();
    formdata.append('id',id);
     $.ajax({
    processData:false,
    contentType:false,
    data:formdata,
    type:'post',
    url:"show_apartment_details",
    success:function(data){

      $("#appartment_details").html(data);
      $("#booking_list_details_model").modal('show');

    }
})

}
function view_notification()
{
    $.ajax({
        processData:false,
        contentType:false,
        type:'GET',
        url:"get_notification",
        success:function(data){

          $("#notification_list").html(data);

        }
    })
}

function cancel_booking(id)
{
    var formdata = new FormData();
    formdata.append('id',id);
    var conf = confirm("Are you sure?");
    if(conf)
    {
     $.ajax({
    processData:false,
    contentType:false,
    data:formdata,
    type:'post',
    url:"cancel_booking",
    success:function(data){

        readData();

    }
})
    }
}
