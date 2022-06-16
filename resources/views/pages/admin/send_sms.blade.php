@extends('layouts.master')

@section('page_title', 'SMS')

@section('content')
   <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Select Contact</h6>
            {!! Qs::getPanelOptions() !!}
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('payments.invoice_class') }}">
                @csrf
              <div class="row">
                  <div class="col">
                      <div class="form-group">
                        <label for="my_class_id" class="col-form-label font-weight-bold">Class:</label>
                          <select required id="my_class_id" name="my_class_id" class="form-control select">
                              <option value="">Select Class</option>
                                @foreach($my_classes as $c)
                                  <option {{ ($selected && $my_class_id == $c->id) ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col">
                      <div class="form-group">
                          <label for="select_student" class="col-form-label font-weight-bold">Student</label>
                          <select name="student_number" id="student_number" class="form-control select">
                              <option value="">Select Student</option>
                          </select>
                      </div>
                  </div>
              </div>
            <br style="clear:both">
            <div class="form-group">                                
                <label id="messageLabel" class="form-label font-weight-bold" for="message">Message </label>
                <textarea class="form-control input-sm " type="textarea" id="message" placeholder="Message" maxlength="140" rows="7"></textarea>
                <span class="help-block"><p id="characterLeft" class="help-block ">You have reached the limit</p></span>                    
            </div>
            <br style="clear:both">
            <div class="form-group col-md-2">
                <button class="form-control input-sm btn btn-success disabled" id="btnSmsSubmit" name="btnSmsSubmit" type="button" style="height:35px"> Send</button>    
            </div>
          </form>
        </div>
   </div>
   <style>
    .red{
        color:red;
    }

   </style>

   <script>
    $(document).ready(function(){ 
        $('#characterLeft').text('140 characters left');
        $('#message').keyup(function () {
            var max = 140;
            var len = $(this).val().length;
            if (len >= max) {
                $('#characterLeft').text('You have reached the limit');
                $('#characterLeft').addClass('red');
                $('#btnSmsSubmit').addClass('disabled');            
            } 
            else {
                var ch = max - len;
                $('#characterLeft').text(ch + ' characters left');
                $('#btnSmsSubmit').removeClass('disabled');
                $('#characterLeft').removeClass('red');            
            }
        });    
    });

    $("#my_class_id").change(function(){
        $("#student_number").empty()
        ajax("{{ route('class.student') }}", "POST", {_token:token(),'class_id':fieldValue("my_class_id")}, function(response){
            var obj = JSON.parse(response.responseText)

            Object.values(obj).forEach(val => $("#student_number").append("<option value="+val.user.phone+"> "+val.user.name+"  "+val.user.phone+"  </option>"));
        })
    })

    $("#btnSmsSubmit").click(function(){

        ajax("{{ route('sms.send_to_parent') }}", "POST", {_token:token(), 'phone_number':fieldValue("student_number"),'message':fieldValue("message")}, function(response){
            console.log(response)
        })

    })

   </script>
@endsection

