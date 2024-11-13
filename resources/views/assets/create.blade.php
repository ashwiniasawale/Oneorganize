{{ Form::open(array('url' => 'account-assets','id'=>'create_asset')) }}
<div class="modal-body">
   
    {{-- end for ai module--}}
    <div class="row">
      
        <div class="form-group col-md-6">
            {{ Form::label('name', __('Asset Name'),['class'=>'form-label']) }}
            {{ Form::text('name', '', array('class' => 'form-control','required'=>'required' , 'placeholder'=>__('Enter Name'))) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('serial_number', __('Serial Number'),['class'=>'form-label']) }}
            {{ Form::text('serial_number', '', array('class' => 'form-control','required'=>'required')) }}
        </div>

      
        <div class="form-group col-md-6">
            {{ Form::label('total_count', __('Total Count'),['class'=>'form-label']) }}
            {{ Form::number('total_count','', array('class' => 'form-control ','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{Form::label('status',__('Status'),['class'=>'Form-label'])}}
            <select id="status" name="status" class="form-control" required>
                <option value="">--Select Status--</option>
                <option value="functional">Functional</option>
                <option value="non_functional">Non Functional</option>
            </select>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('description', __('Description'),['class'=>'form-label']) }}
            {{ Form::textarea('description', '', array('class' => 'form-control','rows'=>3 , 'placeholder'=>__('Enter Description'))) }}
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" id="asset_submit_button" value="{{__('Create')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}



<script>
  
    $(document).ready(function () {
        $('#create_asset').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = new FormData(this);
          
            // Send AJAX request
            $("#asset_submit_button").attr("disabled", true);
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
                 $("#asset_submit_button").attr("disabled", false);
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
