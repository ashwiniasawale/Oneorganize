{{Form::model($attendanceEmployee,array('route' => array('attendanceemployee.update', $attendanceEmployee->id),'id'=>'edit_attend', 'method' => 'PUT')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-lg-6  ">
            {{Form::label('employee_id',__('Employee'), ['class' => 'form-label'])}}
            {{Form::select('employee_id',$employees,null,array('class'=>'form-control select2','readonly'=>'readonly'))}}
        </div>
        <div class="form-group col-lg-6 ">
            {{Form::label('date',__('Date'), ['class' => 'form-label'])}}
            {{Form::date('date',null,array('class'=>'form-control','readonly'=>'readonly'))}}
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-6 ">
            {{Form::label('clock_in',__('Clock In'), ['class' => 'form-label'])}}
            {{Form::time('clock_in',null,array('class'=>'form-control ','required'=>'required'))}}
        </div>

        <div class="form-group col-lg-6 ">
            {{Form::label('clock_out',__('Clock Out'), ['class' => 'form-label'])}}
            {{Form::time('clock_out',null,array('class'=>'form-control ','required'=>'required'))}}
        </div>
    </div>
    <!-- <div class="row">
        <div class="form-group col-lg-6 ">
            {{Form::label('late_mark',__('Late Mark'),['class'=>'form-label'])}}
            <select name="late_mark" class="form-control select" required>
                <option <?php if($attendanceEmployee->late_mark=='No'){ echo 'selected=selected'; } ?> value="No">No</option>
                <option <?php if($attendanceEmployee->late_mark=='Yes'){ echo 'selected=selected'; } ?> value="Yes">Yes</option>
            </select>
        </div>
        <div class="form-group col-lg-6">
            {{Form::label('half_day',__('Half Day'),['class'=>'form-label'])}}
            <select name="half_day" class="form-control select" required>
                <option <?php if($attendanceEmployee->half_day=='No'){ echo 'selected=selected'; } ?> value="No">No</option>
                <option <?php if($attendanceEmployee->half_day=='Yes'){ echo 'selected=selected'; } ?> value="Yes">Yes</option>
            </select>
        </div>
    </div> -->
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" id="edit_attend_button" value="{{__('Update')}}" class="btn btn-primary">
</div>
{{ Form::close() }}
<script>
      $('#edit_attend').on('submit', function (e) {
         e.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = new FormData(this);
            // Send AJAX request
            $("#edit_attend_button").attr("disabled", true);
          
            $.ajax({
                type: $(this).attr('method'), // Get the HTTP method (POST or GET)
                url: $(this).attr('action'), // Get the form's action attribute value
                data: formData, // Set the form data
                processData: false,  // Important! Don't process the data
                contentType: false,  
                success: function (response) {
                   
                 $("#edit_attend_button").attr("disabled", false);
                  if(response.success)
                  {
                 
                   show_toastr('Success', response.success, 'success');
                 
                   $('#commonModal').modal('hide');
                    load_data();
                   
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
</script>



