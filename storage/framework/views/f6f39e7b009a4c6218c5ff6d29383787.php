<?php echo e(Form::open(array('url'=>'attendanceemployee','method'=>'post','id'=>'mark_id'))); ?>

<div class="modal-body">
    <div class="row">
       
        <div class="form-group col-lg-6 col-md-6 ">
        <label class="form-label"><?php echo e(__('Mark Attendance By')); ?></label> <br>

        <div class="form-check form-check-inline form-group">
            <input type="radio" id="month" value="month" name="type" class="form-check-input" checked required>
            <label class="form-check-label" for="month"><?php echo e(__('Month')); ?></label>
        </div>
        <div class="form-check form-check-inline form-group">
            <input type="radio" id="daily" value="daily" name="type" class="form-check-input" >
            <label class="form-check-label" for="daily"><?php echo e(__('Date')); ?></label>
        </div>
        </div>
        <div class="form-group col-lg-6 col-md-6">
            <?php echo e(Form::label('employee_id',__('Employee'))); ?>

            <!-- <?php echo e(Form::select('employee_id',$employees,null,array('class'=>'form-control select2','required'=>'required'))); ?> -->
            <select name="employee_id" class="form-control select2" required id="employee_id">
                <option value="">--Select Employee--</option>
                <option value='all'>-All Employee-</option>
          
                <?php foreach($employees as $employee)
                { ?>
                <option value="<?php echo $employee->id; ?>"><?php echo $employee->name; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group col-lg-6 col-md-6 months">
            <div class="btn-box">
                <?php echo e(Form::label('month',__('Month'),['class'=>'form-label'])); ?>

                <?php echo e(Form::month('month_date',date('Y-m'),array('class'=>'month-btn form-control month-btn','id'=>'months','required'=>'required'))); ?>

            </div>
        </div>
        <div class="form-group col-lg-6 col-md-6 dates">
            <div class="btn-box">
                <?php echo e(Form::label('date', __('Date'),['class'=>'form-label'])); ?>

                <?php echo e(Form::date('date',date('Y-m-d'), array('class' => 'form-control month-btn','id'=>'dates','required'=>'required'))); ?>

            </div>
        </div>
       
        <div class="form-group col-lg-6 col-md-6">
            <?php echo e(Form::label('clock_in',__('Clock In'))); ?>

            <?php echo e(Form::time('clock_in',$company_start_time,array('class'=>'form-control ','id'=>'clock_in','required'=>'required'))); ?>


        </div>
        <div class="form-group col-lg-6 col-md-6">
            <?php echo e(Form::label('clock_out',__('Clock Out'))); ?>

            <?php echo e(Form::time('clock_out',$company_end_time,array('class'=>'form-control ','id'=>'clock_out','required'=>'required'))); ?>

        </div>
        <div class="form-group col-lg-6 col-md-6">
            <?php echo e(Form::label('late_mark',__('Late Mark'),['class'=>'form-label'])); ?>

            <select name="late_mark" class="form-control select" required>
                <option value="No">No</option>
                <option value="Yes">Yes</option>
            </select>
        </div>
        <div class="form-group col-lg-6">
            <?php echo e(Form::label('half_day',__('Half Day'),['class'=>'form-label'])); ?>

            <select name="half_day" class="form-control select" required>
                <option value="No">No</option>
                <option value="Yes">Yes</option>
            </select>
        </div>
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn dark btn-outline" data-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
    <?php echo e(Form::submit(__('Create'),array('class'=>'btn btn-primary'))); ?>

</div>
<?php echo e(Form::close()); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: 'Select an option',
            allowClear: true,
            width: '100%'
        });
    });
    </script>
<script>
  
    $(document).ready(function () {
        $('#mark_id').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = $(this).serialize();

            // Send AJAX request
            $.ajax({
                type: $(this).attr('method'), // Get the HTTP method (POST or GET)
                url: $(this).attr('action'), // Get the form's action attribute value
                data: formData, // Set the form data
                success: function (response) {
                    // Handle the success response from the server
                   console.log(response); // Log the response to the console
                    
                   if(response.success)
                  {
                    show_toastr('Success', response.success, 'success');
                  }else{
                    show_toastr('Error', response.error, 'error');
                  }
                    $('#commonModal').modal('hide');
                    
                    // You can perform any further actions based on the response
                },
                error: function (xhr, status, error) {
                    // Handle errors
                    console.error(error); // Log the error to the console
                }
            });
        });
    });
</script>
<script>
    var month=document.getElementById('months').value;
   
   document.getElementById("months").setAttribute("max", month);
   var date=document.getElementById('dates').value;
  document.getElementById("dates").setAttribute("max", date);
        $('input[name="type"]:radio').on('change', function (e) {
            var type = $(this).val();

            if (type == 'month') {
                $('.months').addClass('d-block');
                $('.months').removeClass('d-none');
                $('.dates').addClass('d-none');
                $('.dates').removeClass('d-block');
            } else {
                $('.dates').addClass('d-block');
                $('.dates').removeClass('d-none');
                $('.months').addClass('d-none');
                $('.months').removeClass('d-block');
            }
        });

        $('input[name="type"]:radio:checked').trigger('change');

    </script><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/attendance/create.blade.php ENDPATH**/ ?>