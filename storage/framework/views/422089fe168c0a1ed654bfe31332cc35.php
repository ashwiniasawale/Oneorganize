<?php echo e(Form::open(array('url' => 'revenue','enctype' => 'multipart/form-data','id'=>'create_revenue'))); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6">
            <?php echo e(Form::label('date', __('Date'),['class'=>'form-label'])); ?>

            <?php echo e(Form::date('date',null,array('class'=>'form-control','required'=>'required'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('amount', __('Amount'),['class'=>'form-label'])); ?>

            <?php echo e(Form::number('amount', '', array('class' => 'form-control','required'=>'required', 'placeholder'=>__('Enter Amount')))); ?>

        </div>
      
        <div class="form-group col-md-6">
            <?php echo e(Form::label('customer_id', __('Customer'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('customer_id', $customers,null, array('class' => 'form-control select','required'=>'required'))); ?>

        </div>
        <div class="form-group  col-md-12">
            <?php echo e(Form::label('description', __('Description'),['class'=>'form-label'])); ?>

            <?php echo e(Form::textarea('description', '', array('class' => 'form-control','rows'=>3 , 'placeholder'=>__('Enter Description')))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('category_id', __('Category'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('category_id', $categories,null, array('class' => 'form-control select','required'=>'required'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('reference', __('Reference'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('reference', '', array('class' => 'form-control' , 'placeholder'=>__('Enter Reference')))); ?>

        </div>

      

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" id="revenue_submit_button" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>


<script>
    document.getElementById('files').onchange = function () {
        var src = URL.createObjectURL(this.files[0])
        document.getElementById('image').src = src
    }
</script>

<script>
     $(document).ready(function () {
        $('#create_revenue').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = new FormData(this);
         
            // Send AJAX request
            $("#revenue_submit_button").attr("disabled", true);
         
            $.ajax({
                type: $(this).attr('method'), // Get the HTTP method (POST or GET)
                url: $(this).attr('action'), // Get the form's action attribute value
                data: formData, // Set the form data
                processData: false,  // Important! Don't process the data
                contentType: false,  
                success: function (response) {
                    // Handle the success response from the server
                 //console.log(response); // Log the response to the console
              
                 $("#revenue_submit_button").attr("disabled", false);
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
<?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/revenue/create.blade.php ENDPATH**/ ?>