{{ Form::open(array('url' => 'payment','enctype' => 'multipart/form-data','id'=>'create_payment')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('vender_id', __('Vendor'),['class'=>'form-label']) }}
            {{ Form::select('vender_id', $venders,null, array('class' => 'form-control select','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('date', __('Date'),['class'=>'form-label']) }}
            {{Form::date('date',null,array('class'=>'form-control','required'=>'required'))}}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('amount', __('Amount'),['class'=>'form-label']) }}
            {{ Form::number('amount', '', array('class' => 'form-control','required'=>'required', 'placeholder'=>__('Enter Amount'))) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('category_id', __('Category'),['class'=>'form-label']) }}
            {{ Form::select('category_id', $categories,null, array('class' => 'form-control select','required'=>'required')) }}
        </div>
     
        <div class="form-group col-md-6">
            {{ Form::label('reference', __('Reference'),['class'=>'form-label']) }}
            {{ Form::text('reference', '', array('class' => 'form-control' , 'placeholder'=>__('Enter Reference'))) }}
        </div>
    
        <div class="form-group  col-md-12">
            {{ Form::label('description', __('Description'),['class'=>'form-label']) }}
            {{ Form::textarea('description', '', array('class' => 'form-control','rows'=>3 , 'placeholder'=>__('Enter Description'))) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" id="create_payment_button" value="{{__('Create')}}" class="btn btn-primary">
</div>

{{ Form::close() }}


<script>
    document.getElementById('files').onchange = function () {
        var src = URL.createObjectURL(this.files[0])
        document.getElementById('image').src = src
    }
</script>
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

   
</script>
