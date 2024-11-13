<?php echo e(Form::open(array('url'=>'leave','method'=>'post','id'=>'create_leave'))); ?>

    <div class="modal-body">
        
        <?php
            $plan= \App\Models\Utility::getChatGPTSettings();
        ?>
        <div class="row">
          
        
        <?php if(\Auth::user()->type =='company' || \Auth::user()->type =='HR'): ?>
           
                <div class="col-md-6">
                    <div class="form-group">
                        <?php echo e(Form::label('employee_id',__('Employee') ,['class'=>'form-label'])); ?>

                        <?php echo e(Form::select('employee_id',$employees,null,array('class'=>'form-control select2','id'=>'employee_id','placeholder'=>__('Select Employee')))); ?>

                    </div>
                </div>
           
        <?php else: ?>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('employee_id',__('Employee'),['class'=>'form-label'])); ?>

                 <input type="hidden" name="employee_id" id="employee_id" value="<?php echo e($employees->id); ?>">
                 <input type="text" name="emp_name" value="<?php echo e($employees->name); ?>" class="form-control readonly" disabled>
            </div> 
        </div>
        <?php endif; ?>
       
            <?php if(\Auth::user()->type=='company' || \Auth::user()->type=='HR'): ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <?php echo e(Form::label('status',__('Status'),['class'=>'form-label'])); ?>

                        <select name="status" id="status" class="form-control select2">
                            <option value=""><?php echo e(__('Select Status')); ?></option>
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approved</option>
                        </select>
                    </div>
                </div>
            <?php endif; ?>
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo e(Form::label('duration',__('Duration'),['class'=>'form-label'])); ?>

                    <select name="duration" id="duration" class="form-control select2" required onchange="get_end_date(this.value)">
                        <option value=""><?php echo e(__('Select Duration')); ?></option>
                        <option value="full_day">Full Day</option>
                        <option value="multiple">Multiple</option>
                        <option value="first_half">First Half</option>
                        <option value="second_half">Second Half</option>
                        <option value="two_hours_leave">2 Hours Leave</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo e(Form::label('start_date', __('Start Date'),['class'=>'form-label'])); ?>

                    <?php echo e(Form::date('start_date',null,array('class'=>'form-control','id'=>'start_date','required'=>'required','onchange'=>'min_end_date();'))); ?>



                </div>
            </div>
            <div class="col-md-6" id="view_end_date" style="display:none;">
                <div class="form-group">
                    <?php echo e(Form::label('end_date', __('End Date'),['class'=>'form-label'])); ?>

                    <?php echo e(Form::date('end_date',null,array('class'=>'form-control','id'=>'end_date'))); ?>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <?php echo e(Form::label('leave_reason',__('Leave Reason') ,['class'=>'form-label'])); ?>

                    <?php echo e(Form::textarea('leave_reason',null,array('class'=>'form-control','id'=>'leave_reason','required'=>'required','placeholder'=>__('Leave Reason')))); ?>

                </div>
            </div>
        </div>
        
    </div>
    <div class="modal-footer">
        <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" id="create_leave_button" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
    </div>
<?php echo e(Form::close()); ?>

<script>
    function min_end_date()
    {
         var start_date=document.getElementById('start_date').value;
       
        document.getElementById("end_date").setAttribute("min", start_date);
        var startYear = start_date.substring(0, 4); // Extract year (YYYY format)
    
    // Set the max attribute of end_date to the last day of the selected year
    var endDateInput = document.getElementById('end_date');
    endDateInput.max = startYear + '-12-31'; // Set max to last day of the year
       
    }
   
  function get_end_date(value)
  {
    if(value=='multiple')
    {
        document.getElementById('view_end_date').style.display='block';
        document.getElementById('end_date').value='';
        document.getElementById('end_date').setAttribute('required', 'required');
    }else{
        document.getElementById('view_end_date').style.display='none';
        document.getElementById('end_date').value='';
        document.getElementById('end_date').removeAttribute('required');
    }
  }
    $(document).ready(function () {
        $('#create_leave').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = new FormData(this);
         
            // Send AJAX request
            $("#create_leave_button").attr("disabled", true);
         
            $.ajax({
                type: $(this).attr('method'), // Get the HTTP method (POST or GET)
                url: $(this).attr('action'), // Get the form's action attribute value
                data: formData, // Set the form data
                processData: false,  // Important! Don't process the data
                contentType: false,  
                success: function (response) {
                    // Handle the success response from the server
                  console.log(response); // Log the response to the console
              
                 $("#create_leave_button").attr("disabled", false);
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

   
</script><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/leave/create.blade.php ENDPATH**/ ?>