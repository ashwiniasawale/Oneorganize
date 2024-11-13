{{Form::open(array('url'=>'customer','method'=>'post','id'=>'create_customer'))}}
<div class="modal-body">

    <h6 class="sub-title">{{__('Basic Info')}}</h6>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{Form::label('name',__('Name'),array('class'=>'form-label')) }}
                {{Form::text('name',null,array('class'=>'form-control','required'=>'required' ,'placeholder'=>__('Enter Name')))}}
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{Form::label('contact',__('Contact'),['class'=>'form-label'])}}
                {{Form::text('contact',null,array('class'=>'form-control','required'=>'required' , 'id'=>'phoneInput','pattern'=>"\d{10,12}",'minlength'=>'10','maxlength' => '12','placeholder'=>__('Enter Contact')))}}
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{Form::label('email',__('Email'),['class'=>'form-label'])}}
                {{Form::email('email',null,array('class'=>'form-control' ,'required'=>'required', 'placeholder'=>__('Enter email')))}}

            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{Form::label('tax_number',__('Tax Number'),['class'=>'form-label'])}}
                {{Form::text('tax_number',null,array('class'=>'form-control' , 'placeholder' => __('Enter Tax Number')))}}
            </div>
        </div>
       
    </div>

    <h6 class="sub-title">{{__('Billing Address')}}</h6>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('billing_name',__('Name'),array('class'=>'','class'=>'form-label')) }}
                {{Form::text('billing_name',null,array('class'=>'form-control' , 'placeholder'=>__('Enter Name')))}}
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('billing_phone',__('Phone'),array('class'=>'form-label')) }}
                {{Form::text('billing_phone',null,array('class'=>'form-control' , 'id'=>'phoneInput','pattern'=>"\d{10,12}",'minlength'=>'10','maxlength' => '12', 'placeholder'=>__('Enter Phone')))}}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('billing_address',__('Address'),array('class'=>'form-label')) }}
                {{Form::textarea('billing_address',null,array('class'=>'form-control','rows'=>3 , 'placeholder'=>__('Enter Address')))}}

            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('billing_country',__('Country'),array('class'=>'form-label')) }}
                {{Form::text('billing_country',null,array('class'=>'form-control' ,'required'=>'required', 'placeholder'=>__('Enter Country')))}}

            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('billing_state',__('State'),array('class'=>'form-label')) }}
                {{Form::text('billing_state',null,array('class'=>'form-control' ,'required'=>'required', 'placeholder'=>__('Enter State')))}}

            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('billing_city',__('City'),array('class'=>'form-label')) }}
                {{Form::text('billing_city',null,array('class'=>'form-control' ,'required'=>'required', 'placeholder'=>__('Enter City')))}}

            </div>
        </div>
       
       

        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('billing_zip',__('Zip Code'),array('class'=>'form-label')) }}
                {{Form::text('billing_zip',null,array('class'=>'form-control' , 'placeholder'=>__('Enter Zip Code')))}}

            </div>
        </div>

    </div>

    @if(App\Models\Utility::getValByName('shipping_display')=='on')
         <div class="col-md-12 text-end">
            <input type="button" id="billing_data" value="{{__('Shipping Same As Billing')}}" class="btn btn-primary">
        </div> 
        <h6 class="sub-title">{{__('Shipping Address')}}</h6>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{Form::label('shipping_name',__('Name'),array('class'=>'form-label')) }}
                    {{Form::text('shipping_name',null,array('class'=>'form-control' , 'placeholder'=>__('Enter Name')))}}

                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{Form::label('shipping_phone',__('Phone'),array('class'=>'form-label')) }}
                    {{Form::text('shipping_phone',null,array('class'=>'form-control' , 'placeholder'=>__('Enter Phone')))}}

                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {{Form::label('shipping_address',__('Address'),array('class'=>'form-label')) }}
                    <label class="form-label" for="example2cols1Input"></label>
                    {{Form::textarea('shipping_address',null,array('class'=>'form-control','rows'=>3 , 'placeholder'=>__('Enter Address')))}}

                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{Form::label('shipping_country',__('Country'),array('class'=>'form-label')) }}
                 
                    {{Form::text('shipping_country',null,array('class'=>'form-control' , 'placeholder'=>__('Enter Country')))}}

               
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{Form::label('shipping_state',__('State'),array('class'=>'form-label')) }}
             
                    {{Form::text('shipping_state',null,array('class'=>'form-control' , 'placeholder'=>__('Enter State')))}}

                   
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{Form::label('shipping_city',__('City'),array('class'=>'form-label')) }}
                    {{Form::text('shipping_city',null,array('class'=>'form-control' , 'placeholder'=>__('Enter City')))}}

                </div>
            </div>
           
          


            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{Form::label('shipping_zip',__('Zip Code'),array('class'=>'form-label')) }}
                    {{Form::text('shipping_zip',null,array('class'=>'form-control' , 'placeholder' => __('Enter Zip Code')))}}

                </div>
            </div>

        </div>
    @endif

</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" id="submit_customer_button" value="{{__('Create')}}" class="btn btn-primary">
</div>
{{Form::close()}}


<script>
    document.getElementById('phoneInput').addEventListener('input', function (e) {
        // Replace any non-digit characters
        e.target.value = e.target.value.replace(/\D/g, '');
    });
    </script>
    <script>
        document.getElementById('phoneInput').addEventListener('input', function(e) {
            // Replace any non-digit characters
            e.target.value = e.target.value.replace(/\D/g, '');
        });
    </script>
    

<script>
  
  $(document).ready(function () {
      $('#create_customer').on('submit', function (e) {
          e.preventDefault(); // Prevent the default form submission

          // Gather form data
          var formData = new FormData(this);
        
          // Send AJAX request
          $("#submit_customer_button").attr("disabled", true);
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
               $("#submit_customer_button").attr("disabled", false);
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

