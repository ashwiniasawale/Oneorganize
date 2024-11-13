<?php echo e(Form::open(array('url' => 'product-unit','id'=>'create_product_unit'))); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            <?php echo e(Form::label('name', __('Unit Name'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('name', '', array('class' => 'form-control','required'=>'required' , 'placeholder'=>__('Enter Unit Name')))); ?>

            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <small class="invalid-name" role="alert">
                <strong class="text-danger"><?php echo e($message); ?></strong>
            </small>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" id="unit_submit_button" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>



<script>
  
    $(document).ready(function () {
        $('#create_product_unit').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = new FormData(this);
          
            // Send AJAX request
            $("#unit_submit_button").attr("disabled", true);
            $("#loader").css("display", "flex");

            $.ajax({
                type: $(this).attr('method'), // Get the HTTP method (POST or GET)
                url: $(this).attr('action'), // Get the form's action attribute value
                data: formData, // Set the form data
                processData: false,  // Important! Don't process the data
                contentType: false,  
                success: function (response) {
               
                 // console.log(response); // Log the response to the console
                 $("#loader").css("display", "none");
                 $("#unit_submit_button").attr("disabled", false);
                  if(response.success)
                  {
                    show_toastr('Success', response.success, 'success');
                   // console.log(response.tmp_task)
                   $("#att_table").load(" #att_table");
                         $('#commonModal').modal('hide');
                         
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

   
</script><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/productServiceUnit/create.blade.php ENDPATH**/ ?>