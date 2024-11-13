<?php echo e(Form::model($creditNote, array('route' => array('invoice.edit.credit.note',$creditNote->invoice, $creditNote->id),'id'=>'update_credit', 'method' => 'post'))); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('date', __('Date'),['class'=>'form-label'])); ?>

            <?php echo e(Form::date('date',null,array('class'=>'form-control','required'=>'required'))); ?>

        </div>
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('amount', __('Amount'),['class'=>'form-label'])); ?>

            <?php echo e(Form::number('amount', null, array('class' => 'form-control','required'=>'required','step'=>'0.01'))); ?>

        </div>
        <div class="form-group col-md-12">
            <?php echo e(Form::label('description', __('Description'),['class'=>'form-label'])); ?>

            <?php echo Form::textarea('description', null, ['class'=>'form-control','rows'=>'3']); ?>

        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" id="update_credit_button" value="<?php echo e(__('Update')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>


<script>
     $(document).ready(function () {
        $('#update_credit').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = new FormData(this);
         
            // Send AJAX request
            $("#update_credit_button").attr("disabled", true);
         
            $.ajax({
                type: $(this).attr('method'), // Get the HTTP method (POST or GET)
                url: $(this).attr('action'), // Get the form's action attribute value
                data: formData, // Set the form data
                processData: false,  // Important! Don't process the data
                contentType: false,  
                success: function (response) {
                    // Handle the success response from the server
                 // console.log(response); // Log the response to the console
              
                 $("#update_credit_button").attr("disabled", false);
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

   
</script><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/creditNote/edit.blade.php ENDPATH**/ ?>