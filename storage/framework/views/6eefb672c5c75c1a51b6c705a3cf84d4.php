<?php echo e(Form::open(array('route' => array('bill.custom.debit.note'),'mothod'=>'post','id'=>'create_debit_note'))); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            <?php echo e(Form::label('bill', __('Bill'),['class'=>'form-label'])); ?>

                <select class="form-control select" required="required" id="bill" name="bill">
                    <option value=<?php echo e(null); ?>><?php echo e(__('Select Bill')); ?></option>
                    <?php $__currentLoopData = $bills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$bill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>"><?php echo e(\Auth::user()->billNumberFormat($bill)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
        </div>
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('amount', __('Amount'),['class'=>'form-label'])); ?>


                <?php echo e(Form::number('amount', null, array('class' => 'form-control','required'=>'required','step'=>'0.01' , 'placeholder'=>__('Enter Amount')))); ?>


        </div>
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('date', __('Date'),['class'=>'form-label'])); ?>


                <?php echo e(Form::date('date',null,array('class'=>'form-control','required'=>'required'))); ?>



        </div>

        <div class="form-group col-md-12">
            <?php echo e(Form::label('description', __('Description'),['class'=>'form-label'])); ?>

            <?php echo Form::textarea('description', null, ['class'=>'form-control','rows'=>'2' , 'placeholder'=>__('Enter Description')]); ?>

        </div>

    </div>
</div>

<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" id="submit_debit_button" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>


<script>
     $(document).ready(function () {
        $('#create_debit_note').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = new FormData(this);
         
            // Send AJAX request
            $("#submit_debit_button").attr("disabled", true);
         
            $.ajax({
                type: $(this).attr('method'), // Get the HTTP method (POST or GET)
                url: $(this).attr('action'), // Get the form's action attribute value
                data: formData, // Set the form data
                processData: false,  // Important! Don't process the data
                contentType: false,  
                success: function (response) {
                    // Handle the success response from the server
                  console.log(response); // Log the response to the console
              
                 $("#submit_debit_button").attr("disabled", false);
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

   
</script><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/debitNote/custom_create.blade.php ENDPATH**/ ?>