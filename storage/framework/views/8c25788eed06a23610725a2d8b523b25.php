    <?php echo e(Form::open(array('url'=>'leavetype','method'=>'post','id'=>'create_leavetype'))); ?>

    <div class="modal-body">

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('title',__('Leave Type'),['class'=>'form-label'])); ?>

                <?php echo e(Form::text('title',null,array('class'=>'form-control','required'=>'required','placeholder'=>__('Enter Leave Type Name')))); ?>

                <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span class="invalid-name" role="alert">
                    <strong class="text-danger"><?php echo e($message); ?></strong>
                </span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('leave_year',__('Leave Year'),['class'=>'form-label'])); ?>

                <?php
                    $years = range(date('Y') - 10, date('Y') + 10); // Adjust the range as needed
                ?>

                <?php echo e(Form::select('leave_year', array_combine($years, $years), isset($_GET['year']) ? $_GET['year'] : date('Y'), ['class' => 'form-control','required'=>'required'])); ?>

            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('leave_paid_status',__('Leave Paid Status'),['class'=>'form-label'])); ?>

                <select name="leave_paid_status" class="form-control select" required>
                    <option>-Select-</option>
                    <option value="Paid">Paid</option>
                    <option value="Unpaid">Unpaid</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('days',__('No of Leaves'),['class'=>'form-label'])); ?>

                <?php echo e(Form::number('days',0,array('class'=>'form-control','required'=>'required','placeholder'=>__('No of Leaves'),'min' => '0', 'pattern' => '[0-9]*'))); ?>

            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('monthly_limit',__('Monthly Limit'),['class'=>'form-label'])); ?>

                <?php echo e(Form::number('monthly_limit',0,array('class'=>'form-control','required'=>'required','placeholder'=>__('Monthly Limit'),'min'=>'0','pattern'=>'[0-9]*'))); ?>

            </div>
        </div>

    </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
    </div>
    <?php echo e(Form::close()); ?>


    <script>
  
  $(document).ready(function () {
      $('#create_leavetype').on('submit', function (e) {
          e.preventDefault(); // Prevent the default form submission

          // Gather form data
          var formData = $(this).serialize();

          // Send AJAX request
          $.ajax({
              type: $(this).attr('method'), // Get the HTTP method (POST or GET)
              url: $(this).attr('action'), // Get the form's action attribute value
              data: formData, // Set the form data
              success: function (response) {
                 
                  //console.log(response); // Log the response to the console
                  if(response.success)
                  {
                    show_toastr('Success', response.success, 'success');
                  }else{
                    show_toastr('Error', response.error, 'error');
                  }
                 
                  $('#commonModal').modal('hide');
                   load_data();
                 
                  // You can perform any further actions based on the response
              },
              error: function (xhr, status, error) {
                  // Handle errors
                  console.error(error); // Log the error to the console
              }
          });
      });
  });
</script><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/leavetype/create.blade.php ENDPATH**/ ?>