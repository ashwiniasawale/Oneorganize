<?php echo e(Form::open(array('url'=>'resignation','method'=>'post','id'=>'create_resignation'))); ?>


<div class="modal-body">
    
   
    
    <div class="row">
       
            <div class="form-group col-lg-6 col-md-6">
                <?php echo e(Form::label('employee_id', __('Employee'),['class'=>'form-label'])); ?>

                <?php echo e(Form::select('employee_id', $employees,null, array('class' => 'form-control select','required'=>'required'))); ?>

            </div>
      
        <div class="form-group col-lg-6 col-md-6">
            <?php echo e(Form::label('notice_date',__('Notice Date'),['class'=>'form-label'])); ?>

            <?php echo e(Form::date('notice_date',null,array('class'=>'form-control','required'=>'required'))); ?>

        </div>
        <div class="form-group col-lg-6 col-md-6">
            <?php echo e(Form::label('resignation_date',__('Resignation Date (Last Working)'),['class'=>'form-label'])); ?>

            <?php echo e(Form::date('resignation_date',null,array('class'=>'form-control','required'=>'required'))); ?>

        </div>
        <div class="form-group col-lg-12">
            <?php echo e(Form::label('description',__('Description'),['class'=>'form-label'])); ?>

            <?php echo e(Form::textarea('description',null,array('class'=>'form-control','placeholder'=>__('Enter Description'),'required'=>'required'))); ?>

        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" id="create_resignation_button" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>

    <?php echo e(Form::close()); ?>

    <script>
    $(document).ready(function () {
        $('#create_resignation').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = new FormData(this);
         
            // Send AJAX request
            $("#create_resignation_button").attr("disabled", true);
         
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

   
</script><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/resignation/create.blade.php ENDPATH**/ ?>