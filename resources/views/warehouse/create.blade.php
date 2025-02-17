{{ Form::open(array('url' => 'warehouse')) }}
<div class="modal-body">
    {{-- start for ai module--}}
    @php
        $plan= \App\Models\Utility::getChatGPTSettings();
    @endphp
    @if($plan->chatgpt == 1)
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="{{ route('generate',['warehouse']) }}"
           data-bs-placement="top" data-title="{{ __('Generate content with AI') }}">
            <i class="fas fa-robot"></i> <span>{{__('Generate with AI')}}</span>
        </a>
    </div>
    @endif
    {{-- end for ai module--}}
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('name', __('Name'),['class'=>'form-label']) }}
            {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-12">
            {{Form::label('address',__('Address'),array('class'=>'form-label')) }}
            {{Form::textarea('address',null,array('class'=>'form-control','rows'=>3 ,'required'=>'required'))}}
        </div>
        <div class="form-group col-md-6">
            {{Form::label('city',__('City'),array('class'=>'form-label')) }}
            {{Form::text('city',null,array('class'=>'form-control'))}}
        </div>
        <div class="form-group col-md-6">
            {{Form::label('city_zip',__('Zip Code'),array('class'=>'form-label')) }}
            {{Form::text('city_zip',null,array('class'=>'form-control','id'=>'pincode'))}}
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}


<script>
    $(document).ready(function() {
        $('#pincode').on('keypress', function(event) {
            var charCode = event.which ? event.which : event.keyCode;
            // Allow only numeric input
            if (charCode < 48 || charCode > 57) {
                event.preventDefault();
            }

            // Limit to 6 characters
            if ($(this).val().length >= 6) {
                event.preventDefault();
            }
        });

        $('#pincode').on('paste', function(event) {
            var pasteData = event.originalEvent.clipboardData.getData('text');
            // Allow paste only if the resulting input is a valid 6-digit number
            var newValue = $(this).val() + pasteData;
            if (!/^\d{1,6}$/.test(newValue)) {
                event.preventDefault();
            }
        });
    });
</script>


