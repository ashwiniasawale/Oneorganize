{{Form::open(array('route'=>'attendance_request.store','method'=>'post','id'=>'create_att_request'))}}
<div class="modal-body">
    <div class="row">
       
       
      
        @if(\Auth::user()->type =='company' || \Auth::user()->type =='HR')
           
           <div  class="form-group col-lg-6 col-md-6">
           {{Form::label('employee_id',__('Employee'))}}
            {{Form::select('employee_id',$employees,null,array('class'=>'form-control select2','required'=>'required'))}}
           
           </div>
      
        @else
        <div class="form-group col-lg-6 col-md-6">
            
                {{Form::label('employee_id',__('Employee'),['class'=>'form-label'])}}
                    <input type="hidden" name="employee_id" id="employee_id" value="{{$employees->id}}">
                    <input type="text" name="emp_name" value="{{$employees->name}}" class="form-control readonly" disabled>
            
        </div>
        @endif
        <div class="form-group col-lg-6 col-md-6 ">
            <div class="btn-box">
                {{ Form::label('date', __('Date'),['class'=>'form-label'])}}
                {{ Form::date('date',date('Y-m-d'), array('class' => 'form-control month-btn','id'=>'date','required'=>'required')) }}
            </div>
        </div>
       
        <div class="form-group col-lg-6 col-md-6">
            {{Form::label('clock_in',__('Clock In'))}}
            {{Form::time('clock_in',$company_start_time,array('class'=>'form-control ','id'=>'clock_in','required'=>'required'))}}

        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{Form::label('clock_out',__('Clock Out'))}}
            {{Form::time('clock_out',$company_end_time,array('class'=>'form-control ','id'=>'clock_out','required'=>'required'))}}
        </div>
        <div class="form-group col-lg-12 col-md-12">
            {{Form::label('attendance_reason',__('Attendance Reason'),['class'=>'form-label'])}}
           {{Form::textarea('attendance_reason','',array('class'=>'form-control','id'=>'attendance_reason','required'=>'required'))}}
        </div>
       
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" id="submit_attendance_request" class="btn dark btn-outline" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
</div>
{{Form::close()}}
<script>
  
  $(document).ready(function () {
        $('#create_att_request').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = new FormData(this);
         
            // Send AJAX request
            $("#submit_attendance_request").attr("disabled", true);
         
            $.ajax({
                type: $(this).attr('method'), // Get the HTTP method (POST or GET)
                url: $(this).attr('action'), // Get the form's action attribute value
                data: formData, // Set the form data
                processData: false,  // Important! Don't process the data
                contentType: false,  
                success: function (response) {
                    // Handle the success response from the server
                  console.log(response); // Log the response to the console
              
                 $("#submit_attendance_request").attr("disabled", false);
                  if(response.success)
                  {
                    show_toastr('Success', response.success, 'success');
                    $('#commonModal').modal('hide');
                    $("#att_table").load(" #att_table");
                  }else{
                  
                    show_toastr('Error', response.error, 'error');
                  }
              
                   
                  
                },
                error: function (xhr, status, error) {
                    // Handle errors
                    console.error(error); // Log the error to the console
                }
            });
        });
    });
</script>
