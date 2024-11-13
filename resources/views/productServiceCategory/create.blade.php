{{ Form::open(array('url' => 'product-category','id'=>'create_product_category')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('name', __('Category Name'),['class'=>'form-label']) }}
            {{ Form::text('name', '', array('class' => 'form-control','required'=>'required' , 'placeholder'=>__('Enter Category Name'))) }}
        </div>
        <div class="form-group col-md-12 d-block">
            {{ Form::label('type', __('Category Type'),['class'=>'form-label']) }}
            {{ Form::select('type',$types,null, array('class' => 'form-control select cattype ','required'=>'required')) }}
        </div>
     
        <div class="form-group col-md-12">
            {{ Form::label('color', __('Category Color'),['class'=>'form-label']) }}
            {{ Form::text('color', '', array('class' => 'form-control jscolor','required'=>'required')) }}
            <small>{{__('For chart representation')}}</small>
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" id="category_submit_button" value="{{__('Create')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}

<script>
  
    $(document).ready(function () {
        $('#create_product_category').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = new FormData(this);
          
            // Send AJAX request
            $("#category_submit_button").attr("disabled", true);
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
                 $("#category_submit_button").attr("disabled", false);
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

   
</script>
