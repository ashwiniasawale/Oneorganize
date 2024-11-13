<?php echo e(Form::open(['url' => 'mark_holiday_store', 'id'=>'mark_holiday','method' => 'post'])); ?>

   <style>
    .text-wrap{
        margin-right: 10px;
    }
    </style>
   <div class="modal-body">
        
        
        <div class="row">
                <div class="col-md-12">
                    
                    <div class="form-group">
                    <?php echo e(Form::label('holiday_year', __('Holiday Year'), ['class' => 'form-label'])); ?>

                    <?php
                        $years = range(date('Y') - 10, date('Y') + 10); // Adjust the range as needed
                    ?>
                    <select name="holiday_year" id="holiday_year" class="form-control" required>
                    <?php
                     $current=date('Y');
                     foreach($years as $years)
                    { ?>
                    <option <?php if($current==$years){ echo 'selected=selected'; } ?> value="<?php echo e($years); ?>"><?php echo e($years); ?></option>
                    <?php } ?>
                    </select>
                  
                </div>
                </div>
           
      
       
         
            
            <div class="col-md-12">
                <div class="form-group">
                    <?php echo e(Form::label('mark_days', __('Mark days for default Holidays'),['class'=>'form-label'])); ?>

                 

                    <div class="d-flex mt-2">
                            <div class="mr-3 mb-2 mr-2 mr-lg-2 mr-md-2">
                                <div class="form-check">
                                <input class="form-check-input"  type="checkbox" value="Monday" name="mark_days[]" id="mark_days1">
                                <label class="form-check-label form_custom_label text-dark-grey pl-2 mr-3 justify-content-start cursor-pointer checkmark-20 text-wrap" >
                                Monday
                                </label>
                                </div>
                            </div>
                            <div class="mr-3 mb-2 mr-2 mr-lg-2 mr-md-2">
                                <div class="form-check">
                                <input class="form-check-input"  type="checkbox" value="Tuesday" name="mark_days[]" id="mark_days2">
                                <label class="form-check-label form_custom_label text-dark-grey pl-2 mr-3 justify-content-start cursor-pointer checkmark-20  text-wrap" >
                                Tuesday
                                </label>
                                </div>
                            </div>
                            <div class="mr-3 mb-2 mr-2 mr-lg-2 mr-md-2">
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Wednesday" name="mark_days[]" id="mark_days3">
                                <label class="form-check-label form_custom_label text-dark-grey pl-2 mr-3 justify-content-start cursor-pointer checkmark-20 text-wrap" >
                                Wednesday
                                </label>
                                </div>
                            </div>
                            <div class="mr-3 mb-2 mr-2 mr-lg-2 mr-md-2">
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Thursday" name="mark_days[]" id="mark_days4">
                                <label class="form-check-label form_custom_label text-dark-grey pl-2 mr-3 justify-content-start cursor-pointer checkmark-20 text-wrap" >
                                Thursday
                                </label>
                                </div>
                            </div>
                            <div class="mr-3 mb-2 mr-2 mr-lg-2 mr-md-2">
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Friday" name="mark_days[]" id="mark_days5">
                                <label class="form-check-label form_custom_label text-dark-grey pl-2 mr-3 justify-content-start cursor-pointer checkmark-20  text-wrap" for="open_5">
                                Friday
                                </label>
                                </div>
                            </div>
                            <div class="mr-3 mb-2 mr-2 mr-lg-2 mr-md-2">
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Saturday" name="mark_days[]" id="mark_days6">
                                <label class="form-check-label form_custom_label text-dark-grey pl-2 mr-3 justify-content-start cursor-pointer checkmark-20  text-wrap" for="open_6">
                                Saturday
                                </label>
                                </div>
                            </div>
                            <div class="mr-3 mb-2 mr-2 mr-lg-2 mr-md-2">
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Sunday" name="mark_days[]" id="mark_days7">
                                <label class="form-check-label form_custom_label text-dark-grey pl-2 mr-3 justify-content-start cursor-pointer checkmark-20  text-wrap" for="open_0">
                                Sunday
                                </label>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
           
        </div>
        
        
    </div>
    <div class="modal-footer">
        <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" id="create_mark_button" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
    </div>

    <?php echo e(Form::close()); ?>

    <script>
           $(document).ready(function () {
        $('#mark_holiday').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = new FormData(this);
         
            // Send AJAX request
            $("#create_mark_button").attr("disabled", true);
         
            $.ajax({
                type: $(this).attr('method'), // Get the HTTP method (POST or GET)
                url: $(this).attr('action'), // Get the form's action attribute value
                data: formData, // Set the form data
                processData: false,  // Important! Don't process the data
                contentType: false,  
                success: function (response) {
                    // Handle the success response from the server
                  //console.log(response); // Log the response to the console
              
                 $("#create_mark_button").attr("disabled", false);
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

   
</script><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/holiday/create_default_holiday.blade.php ENDPATH**/ ?>