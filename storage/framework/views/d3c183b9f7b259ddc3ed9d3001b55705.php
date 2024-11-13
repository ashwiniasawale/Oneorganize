
<?php echo e(Form::open(['url' => 'appraisal', 'method' => 'post','id'=>'create_apprisal'])); ?>

<div class="modal-body">
    <div class="row">
       

        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('type',__('Type*'),['class'=>'form-label'])); ?>

                <select name="type" id="type" class="form-control" required>
                    <option value=''>--Select Type--</option>
                    <option value="Increament">Increament</option>
                    <option value="Appraisal">Appraisal</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('employee', __('Employee*'), ['class' => 'form-label'])); ?>

                <select name="employee" id="employee" class="form-control select2 " required>
                    <option value=''>--Select Employee--</option>
                    <?php foreach($employee as $employee)
                    { ?>
                    <option value="<?php echo e($employee->id); ?>"><?php echo e($employee->name); ?></option>
                    <?php } ?>
                </select>
               
            </div>
        </div>


        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('appraisal_date', __('Select Month*'), ['class' => 'col-form-label'])); ?>

                <?php echo e(Form::month('appraisal_date', '', ['class' => 'form-control ','id'=>'appraisal_date','autocomplete'=>'off' ,'required' => 'required'])); ?>

            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('appraisal_salary', __('Salary (e.g. if 3 LPA then 300000)'), ['class' => 'col-form-label'])); ?>

                <?php echo e(Form::number('appraisal_salary', null, ['class' => 'form-control','id'=>'appraisal_salary','required'=>'required'])); ?>

            </div>
        </div>
    </div>
   
</div>

<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" id="appraisal_submit_button" value="<?php echo e(__('Create')); ?>" class="btn btn-primary">
</div>
<?php echo e(Form::close()); ?>


<script>
     $(document).ready(function () {
        $('#create_apprisal').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = new FormData(this);
         
            // Send AJAX request
            $("#appraisal_submit_button").attr("disabled", true);
         
            $.ajax({
                type: $(this).attr('method'), // Get the HTTP method (POST or GET)
                url: $(this).attr('action'), // Get the form's action attribute value
                data: formData, // Set the form data
                processData: false,  // Important! Don't process the data
                contentType: false,  
                success: function (response) {
                    // Handle the success response from the server
                  console.log(response); // Log the response to the console
              
                 $("#appraisal_submit_button").attr("disabled", false);
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










<?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/appraisal/create.blade.php ENDPATH**/ ?>