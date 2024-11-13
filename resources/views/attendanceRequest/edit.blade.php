
{{Form::model($attendanceRequest,array('route' => array('attendance_request.update', $attendanceRequest->id),'id'=>'update_att_request', 'method' => 'POST')) }}

<div class="modal-body">
    <div class="row">
       
       
        <div class="form-group col-lg-6 col-md-6">
            {{Form::label('employee_id',__('Employee'))}}
            {{Form::select('employee_id',$employees,null,array('class'=>'form-control select2','required'=>'required'))}}
           
        </div>
     
        <div class="form-group col-lg-6 col-md-6 ">
            <div class="btn-box">
                {{ Form::label('date', __('Date'),['class'=>'form-label'])}}
                {{ Form::date('date',$attendanceRequest->date, array('class' => 'form-control month-btn','id'=>'date','required'=>'required')) }}
            </div>
        </div>
       
        <div class="form-group col-lg-6 col-md-6">
            {{Form::label('clock_in',__('Clock In'))}}
            {{Form::time('clock_in',$attendanceRequest->clock_in,array('class'=>'form-control ','id'=>'clock_in','required'=>'required'))}}

        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{Form::label('clock_out',__('Clock Out'))}}
            {{Form::time('clock_out',$attendanceRequest->clock_out,array('class'=>'form-control ','id'=>'clock_out','required'=>'required'))}}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{Form::label('status',__('Status'))}}
            <select id="status" name="status" class="form-control" required>
                <option value="">--Select Status--</option>
                <option <?php if($attendanceRequest->status=='Pending'){ echo 'selected=selected'; } ?> value="Pending">Pending</option>
                <option value="Approved" <?php if($attendanceRequest->status=='Approved'){ echo 'selected=selected'; } ?> >Approved</option>
                <option value="Reject" <?php if($attendanceRequest->status=='Reject'){ echo 'selected=selected'; } ?> >Reject</option>

            </select>
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{Form::label('attendance_reason',__('Attendance Reason'),['class'=>'form-label'])}}
           {{Form::textarea('attendance_reason',$attendanceRequest->attendance_reason,array('class'=>'form-control','id'=>'attendance_reason','required'=>'required'))}}
        </div>
       
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" id="update_attendance_request" class="btn dark btn-outline" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
</div>
{{Form::close()}}
<script>
  
  $(document).ready(function () {
        $('#update_att_request').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = new FormData(this);
         
            // Send AJAX request
            $("#update_attendance_request").attr("disabled", true);
         
            $.ajax({
                type: $(this).attr('method'), // Get the HTTP method (POST or GET)
                url: $(this).attr('action'), // Get the form's action attribute value
                data: formData, // Set the form data
                processData: false,  // Important! Don't process the data
                contentType: false,  
                success: function (response) {
                    // Handle the success response from the server
                  console.log(response); // Log the response to the console
              
                 $("#update_attendance_request").attr("disabled", false);
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
