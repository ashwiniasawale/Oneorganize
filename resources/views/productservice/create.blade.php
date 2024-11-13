{{ Form::open(array('url' => 'productservice','enctype' => "multipart/form-data",'id'=>'create_product')) }}
<div class="modal-body">
   
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('name', __('Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('sku', __('SKU'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                {{ Form::text('sku', '', array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('sale_price', __('Sale Price'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                {{ Form::number('sale_price', '', array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
            </div>
        </div>
       
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('purchase_price', __('Purchase Price'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                {{ Form::number('purchase_price', '', array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
            </div>
        </div>
       
        <div class="form-group col-md-6">
            {{ Form::label('category_id', __('Category'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::select('category_id', $category,null, array('class' => 'form-control select','required'=>'required')) }}

            <div class=" text-xs">
                {{__('Please add constant category. ')}}<a href="{{route('product-category.index')}}"><b>{{__('Add Category')}}</b></a>
            </div>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('unit_id', __('Unit'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::select('unit_id', $unit,null, array('class' => 'form-control select','required'=>'required')) }}
        </div>
        <!-- <div class="col-md-6 form-group">
            {{Form::label('pro_image',__('Product Image'),['class'=>'form-label'])}}
            <div class="choose-file ">
                <label for="pro_image" class="form-label">
                    <input type="file" class="form-control" name="pro_image" id="pro_image" data-filename="pro_image_create">
                    <img id="image" class="mt-3" style="width:25%;"/>

                </label>
            </div>
        </div> -->
        <div class="row">
        <div class="col-md-6 form-group">
            {{Form::label('tax',__('Tax'),['class'=>'form-label'])}}
            <div class="choose-file ">
                <label for="tax" class="form-label">
                  <input type="checkbox" name="tax" id="tax_cgst" value="CGST" > CGST
                  <input type="checkbox" name="tax" id="tax_sgst" value="SGST" > SGST
                  <input type="checkbox" name="tax" id="tax_igst" value="IGST" > IGST
                </label>
            </div>
        </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('cgst_tax_rate', __('CGST Tax Rate %'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                {{ Form::number('cgst_tax_rate', '0', array('class' => 'form-control','required'=>'required','step'=>'0.01','readonly'=>'readonly')) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('sgst_tax_rate', __('SGST Tax Rate %'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                {{ Form::number('sgst_tax_rate', '0', array('class' => 'form-control','required'=>'required','step'=>'0.01','readonly'=>'readonly')) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('igst_tax_rate', __('IGST Tax Rate %'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                {{ Form::number('igst_tax_rate', '0', array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
            </div>
        </div>
       

        <div class="col-md-6">
            <div class="form-group">
                <div class="btn-box">
                    <label class="d-block form-label">{{__('Type')}}</label>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input type" id="customRadio5" name="type" value="product" checked="checked" >
                                <label class="custom-control-label form-label" for="customRadio5">{{__('Product')}}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input type" id="customRadio6" name="type" value="service" >
                                <label class="custom-control-label form-label" for="customRadio6">{{__('Service')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group col-md-6 quantity">
            {{ Form::label('quantity', __('Quantity'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::text('quantity',null, array('class' => 'form-control' ,'required'=>'required')) }}
        </div>

        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description'),['class'=>'form-label']) }}
            {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'2']) !!}
        </div>

     
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" id="submit_product_button" value="{{__('Create')}}" class="btn  btn-primary">
</div>
{{Form::close()}}


<script>
 function updateTaxRates() {
            const isCGSTChecked = $('#tax_cgst').is(':checked');
            const isIGSTChecked = $('#tax_igst').is(':checked');
            const isSGSTChecked = $('#tax_sgst').is(':checked');

            $('#cgst_tax_rate').prop('readonly', !isCGSTChecked);
            $('#sgst_tax_rate').prop('readonly', !isSGSTChecked);
            $('#igst_tax_rate').prop('readonly', isCGSTChecked || !isIGSTChecked);

            if (isCGSTChecked) {
              
                $('#cgst_tax_rate').prop('readonly', false);
                $('#igst_tax_rate').val('0');
               
                $('#tax_igst').prop('disabled', true);
            } else {
                $('#cgst_tax_rate').prop('readonly', true);
                $('#tax_igst').prop('disabled', false);
            }
            if (isSGSTChecked) {
                $('#sgst_tax_rate').prop('readonly', false);
                $('#igst_tax_rate').val('0');
                $('#tax_igst').prop('disabled', true);
            } else {
                $('#sgst_tax_rate').prop('readonly', true);
                $('#tax_igst').prop('disabled', false);
            }

            if (isIGSTChecked) {
                $('#igst_tax_rate').prop('readonly', false);
                $('#cgst_tax_rate').val('0');
                $('#sgst_tax_rate').val('0');
                $('#tax_cgst').prop('disabled', true);
                $('#tax_sgst').prop('disabled', true);
                $('#tax_igst').prop('disabled', false);
                $('#tax_cgst').prop('checked', false);
                $('#tax_sgst').prop('checked', false);
            } else {
              
                $('#igst_tax_rate').prop('readonly', true);
                $('#tax_cgst').prop('disabled', false);
                $('#tax_sgst').prop('disabled', false);
               
            }
        }
        $('#tax_cgst').on('change', updateTaxRates);
        $('#tax_sgst').on('change', updateTaxRates);
        $('#tax_igst').on('change', updateTaxRates);
    // document.getElementById('pro_image').onchange = function () {
    //     var src = URL.createObjectURL(this.files[0])
    //     document.getElementById('image').src = src
    // }

    //hide & show quantity

    $(document).on('click', '.type', function ()
    {
        var type = $(this).val();
        if (type == 'product') {
            $('.quantity').removeClass('d-none')
            $('.quantity').addClass('d-block');
        } else {
            $('.quantity').addClass('d-none')
            $('.quantity').removeClass('d-block');
        }
    });
</script>

<script>
  
    $(document).ready(function () {
        $('#create_product').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = new FormData(this);
          
            // Send AJAX request
            $("#submit_product_button").attr("disabled", true);
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
                 $("#submit_product_button").attr("disabled", false);
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