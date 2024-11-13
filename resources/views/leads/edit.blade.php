{{ Form::model($lead, array('route' => array('leads.update', $lead->id), 'method' => 'PUT')) }}
<div class="modal-body">
    {{-- start for ai module--}}
    @php
        $plan= \App\Models\Utility::getChatGPTSettings();
    @endphp
    @if($plan->chatgpt == 1)
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="{{ route('generate',['lead']) }}"
           data-bs-placement="top" data-title="{{ __('Generate content with AI') }}">
            <i class="fas fa-robot"></i> <span>{{__('Generate with AI')}}</span>
        </a>
    </div>
    @endif
    {{-- end for ai module--}}
    <div class="row">
        <div class="col-6 form-group">
            {{ Form::label('subject', __('Subject'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::text('subject', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('user_id', __('Created By'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::select('user_id', $users,null, array('class' => 'form-control select','required'=>'required')) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('name', __('Client Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('email', __('Client Email'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::email('email', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('phone', __('Client Phone Number'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::text('phone',null, ['class' => 'form-control', 'required' => 'required','pattern'=>"\d{10,12}",'placeholder' => __('Enter Employee Phone number'), 'minlength'=>'10','maxlength' => '12','id' => 'phoneInput']) }}
        </div>
          <div class="col-6 form-group">
            {{ Form::label('Location', __('Client Location'),['class'=>'form-label']) }}
            {{ Form::text('location', null, array('class' => 'form-control','required'=>'required' , 'placeholder' => __(' Enter Location'))) }}
        </div>
        <div class="col-4 form-group">
           
            {{ Form::label('country', __('Country'), ['class'=>'form-label']) }}
           
            <select name="country" id="country" class="form-control select" onchange="get_state();" required>
            <option value="">Select Country</option>
            <?php foreach($country as $country)
            {
                if($country->country_id==$selected_country)
                {
                    $country_select='selected=selected';
                }else{
                    $country_select='';
                }
              ?>
             <option <?php echo $country_select; ?> value="<?php echo $country->country_id; ?>"><?php echo $country->country_name; ?></option>
            <?php } ?>
        </select>
        </div>
      <div class="col-4 form-group">
            {{ Form::label('state', __('State'), ['class'=>'form-label']) }}
            <select name="state" id="state" class="form-control select"  required>
            <?php foreach($state as $state)
            {
                if($state->state_id==$selected_state)
                {
                    $state_select='selected=selected';
                }else{
                    $state_select='';
                }
              ?>
             <option <?php echo $state_select; ?> value="<?php echo $state->state_id; ?>"><?php echo $state->state_name; ?></option>
            <?php } ?>
        </select>
        </div>
        <div class="col-4 form-group">
            {{ Form::label('city', __('City'), ['class'=>'form-label']) }}
            {{ Form::text('city', null, ['class' => 'form-control', 'required'=>'required', 'placeholder' => __('Select')]) }}
        </div>
           <div class="col-12 form-group">
            {{ Form::label('requirements', __('Client Requirements'), ['class'=>'form-label']) }}
            {{ Form::textarea('requirements', null, ['class' => 'form-control', 'required'=>'required', 'placeholder' => __('Enter Client Requirements'), 'style' => 'height: 100px;']) }}
        </div>
         <div class="col- form-group">
            {{ Form::label('responsible_person', __('Responsible Person'), ['class'=>'form-label']) }}
         
            {{ Form::select('responsible_person', $users,null, array('class' => 'form-control select','required'=>'required')) }}
        </div> 
        <div class="col-6 form-group">
            {{ Form::label('pipeline_id', __('Pipeline'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::select('pipeline_id', $pipelines,null, array('class' => 'form-control select','required'=>'required')) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('stage_id', __('Stage'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::select('stage_id', [''=>__('Select Stage')],null, array('class' => 'form-control select','required'=>'required')) }}
        </div>
        <div class="col-12 form-group">
            {{ Form::label('sources', __('Sources'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::select('sources[]', $sources,null, array('class' => 'form-control select2','id'=>'choices-multiple2','multiple'=>'')) }}
        </div>
        <div class="col-12 form-group">
            {{ Form::label('products', __('Products'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::select('products[]', $products,null, array('class' => 'form-control select2','id'=>'choices-multiple1','multiple'=>'')) }}
        </div>
        <div class="col-12 form-group">
            {{ Form::label('notes', __('Notes'),['class'=>'form-label']) }}
            {{ Form::textarea('notes',null, array('class' => 'summernote-simple')) }}
        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>

{{Form::close()}}
<script>
    document.getElementById('phoneInput').addEventListener('input', function (e) {
        // Replace any non-digit characters
        e.target.value = e.target.value.replace(/\D/g, '');
    });
    </script>

<script>
    function get_state()
    {
        var country_id = $("#country").val();  
     
      $.ajax({  
         type:"POST",  
         url: '{{route('leads.get_state')}}',
         data:{_token: $('meta[name="csrf-token"]').attr('content'),country_id:country_id},  
         success: function (data) 
         {
           
            $("#state").empty();
            $("#state").html(data);
           // alert(data)
          },
          error: function (data) 
          {
                            
          } 
      }); 
    }
</script>

<script>
    var stage_id = '{{$lead->stage_id}}';

    $(document).ready(function () {
        var pipeline_id = $('[name=pipeline_id]').val();
        getStages(pipeline_id);
    });

    $(document).on("change", "#commonModal select[name=pipeline_id]", function () {
        var currVal = $(this).val();
        console.log('current val ', currVal);
        getStages(currVal);
    });

    function getStages(id) {
        $.ajax({
            url: '{{route('leads.json')}}',
            data: {pipeline_id: id, _token: $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            success: function (data) {
                var stage_cnt = Object.keys(data).length;
                $("#stage_id").empty();
                if (stage_cnt > 0) {
                    $.each(data, function (key, data1) {
                        var select = '';
                        if (key == '{{ $lead->stage_id }}') {
                            select = 'selected';
                        }
                        $("#stage_id").append('<option value="' + key + '" ' + select + '>' + data1 + '</option>');
                    });
                }
                $("#stage_id").val(stage_id);
                $('#stage_id').select2({
                    placeholder: "{{__('Select Stage')}}"
                });
            }
        })
    }
</script>
