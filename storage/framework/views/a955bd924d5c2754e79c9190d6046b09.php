<?php echo e(Form::open(array('route' => 'otherpayment.store','method'=>'post','id'=>'create_payment'))); ?>


<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6">
            <?php echo e(Form::label('title', __('Title'),['class'=>'form-label'])); ?>

          
            <select class="form-control select2" name="title" id="title" required>
                <option value="">--Select Title--</option>
                <option value="Other-Allowance">Other Allowance</option>
                <option value="Reimbursement">Reimbursement</option>
                <option value="LWF">LWF</option>
                <option value="Other-Deduction">Other Deduction</option>
            </select>
        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('employee_id', __('Employee'),['class'=>'form-label'])); ?>

           <select class="form-control select2 " id="employee_id" name="employee_id" required>
           <option value=''>-Select Employee-</option>
          
           <?php foreach($employee as $employee)
           { ?>
           <option value="<?php echo $employee->id; ?>"><?php echo $employee->name; ?></option>
           <?php } ?>
           <option value='all'>-All Employee-</option>
          
           </select>
        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('year_month', __('Year Month'),['class'=>'form-label'])); ?>

            <?php echo e(Form::month('year_month',date('Y-m'), array('class' => 'form-control ','required'=>'required'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('amount', __('Amount'),['class'=>'form-label amount_label'])); ?>

            <?php echo e(Form::number('amount',null, array('class' => 'form-control ','required'=>'required' ))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('payment_option', __('Payment Option'),['class'=>'form-label'])); ?>

           <select class="form-control select2  " id="payment_option" name="payment_option" required>
           <option value=''>-Select Payment Option-</option>
           <option value='deduction'>Deduction</option>
           <option value='allowance'>Allowance</option>
           </select>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" id="create_payment_button" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>

<?php echo e(Form::close()); ?>

<script>
     $(document).ready(function () {
        $('#create_payment').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = new FormData(this);
         
            // Send AJAX request
            $("#create_payment_button").attr("disabled", true);
         
            $.ajax({
                type: $(this).attr('method'), // Get the HTTP method (POST or GET)
                url: $(this).attr('action'), // Get the form's action attribute value
                data: formData, // Set the form data
                processData: false,  // Important! Don't process the data
                contentType: false,  
                success: function (response) {
                    // Handle the success response from the server
                  console.log(response); // Log the response to the console
              
                 $("#create_payment_button").attr("disabled", false);
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

</script><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/otherpayment/create.blade.php ENDPATH**/ ?>