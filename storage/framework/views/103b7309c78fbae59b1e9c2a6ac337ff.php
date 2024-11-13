<?php echo e(Form::open(['route' => 'letters.storeoffer','id' => 'create_offer','method'=>'post'])); ?>

<div class="modal-body">
        
        <?php
            $plan= \App\Models\Utility::getChatGPTSettings();
        ?>
        <div class="row">
          
        
       
            <?php if(\Auth::user()->type=='company' || \Auth::user()->type=='HR'): ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <?php echo e(Form::label('employee_type',__('Employee Type'),['class'=>'form-label'])); ?>

                        <select name="employee_type" id="employee_type" class="form-control select2" required>
                            <option value=""><?php echo e(__('Select Employee Type')); ?></option>
                            <option value="Fresher">Fresher</option>
                            <option value="Experienced">Experienced</option>
                        </select>
                    </div>
                </div>
            <?php endif; ?>
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo e(Form::label('title',__('Title (Mr/Mrs/Ms)'),['class'=>'form-label'])); ?>

                  
                    <select class="form-control" name="title" required>
                                        <option value="">-Select Salutation-</option>
                                        <option value="Mr.">Mr</option>
                                        <option value="Miss.">Miss</option>
                                        <option value="Mrs.">Mrs</option>
                                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo e(Form::label('employee_name', __('Employee Name'),['class'=>'form-label'])); ?>

                    <?php echo e(Form::text('employee_name',null,array('class'=>'form-control','id'=>'employee_name','required'=>'required'))); ?>

                </div>
            </div>
            <div class="col-md-6" >
                <div class="form-group">
                    <?php echo e(Form::label('offer_date', __('Offer Accept Date'),['class'=>'form-label'])); ?>

                    <?php echo e(Form::date('offer_date',null,array('class'=>'form-control','id'=>'offer_date','required'=>'required'))); ?>

                </div>
            </div>
            <div class="col-md-6" >
                <div class="form-group">
                    <?php echo e(Form::label('joining_date', __('Date of Joining'),['class'=>'form-label'])); ?>

                    <?php echo e(Form::date('joining_date',null,array('class'=>'form-control','id'=>'joining_date','required'=>'required'))); ?>

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo e(Form::label('ref_no', __('Reference No'),['class'=>'form-label'])); ?>

                    <?php echo e(Form::text('ref_no',null,array('class'=>'form-control','id'=>'ref_no','required'=>'required'))); ?>

                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <?php echo e(Form::label('address',__('Address') ,['class'=>'form-label'])); ?>

                    <?php echo e(Form::textarea('address',null,array('class'=>'form-control','rows'=>'3','id'=>'address','required'=>'required','placeholder'=>__('Address')))); ?>

                </div>
            </div>
       
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo e(Form::label('designation', __('Designation'),['class'=>'form-label'])); ?>

                    <?php echo e(Form::text('designation',null,array('class'=>'form-control','id'=>'designation','required'=>'required'))); ?>


                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo e(Form::label('probation', __('Probation Period in Months'),['class'=>'form-label'])); ?>

                    <?php echo e(Form::number('probation',null,array('class'=>'form-control','id'=>'probation','required'=>'required'))); ?>


                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo e(Form::label('notice_period', __('Notice Period in Days'),['class'=>'form-label'])); ?>

                    <?php echo e(Form::number('notice_period',null,array('class'=>'form-control','id'=>'notice_period','required'=>'required'))); ?>


                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo e(Form::label('salary', __('Salary (e.g. if 3 LPA then 300000)'),['class'=>'form-label'])); ?>

                    <?php echo e(Form::number('salary',null,array('class'=>'form-control','id'=>'salary','required'=>'required'))); ?>


                </div>
            </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" id="create_offer_button" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
    </div>
<?php echo e(Form::close()); ?>

<script>
   
    $(document).ready(function () {
        $('#create_offer').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = new FormData(this);
         
            // Send AJAX request
            $("#create_offer_button").attr("disabled", true);
         
            $.ajax({
                type: $(this).attr('method'), // Get the HTTP method (POST or GET)
                url: $(this).attr('action'), // Get the form's action attribute value
                data: formData, // Set the form data
                processData: false,  // Important! Don't process the data
                contentType: false,  
                success: function (response) {
                    // Handle the success response from the server
                  console.log(response); // Log the response to the console
              
                 $("#create_offer_button").attr("disabled", false);
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

   
</script><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/template_letter/create_offer.blade.php ENDPATH**/ ?>