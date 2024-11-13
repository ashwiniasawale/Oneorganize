{{ Form::open(array('url' => 'leads')) }}
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
            {{ Form::label('subject', __('Subject'),['class'=>'form-label']) }}
            {{ Form::text('subject', null, array('class' => 'form-control','required'=>'required' , 'placeholder'=>__('Enter Subject'))) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('user_id', __('Created By'),['class'=>'form-label']) }}
            {{ Form::select('user_id', $users,null, array('class' => 'form-control select','required'=>'required')) }}
            @if(count($users) == 1)
                <div class="text-muted text-xs">
                    {{_('Please create new users')}} <a href="{{route('users.index')}}">{{_('here')}}</a>.
                </div>
            @endif
        </div>
        <div class="col-6 form-group">
            {{ Form::label('name', __(' Client Name'),['class'=>'form-label']) }}
            {{ Form::text('name', null, array('class' => 'form-control','required'=>'required' , 'placeholder' => __('Enter client Name'))) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('email', __(' Client Email'),['class'=>'form-label']) }}
            {{ Form::email('email', null, array('class' => 'form-control','required'=>'required' , 'placeholder' => __('Enter client email'))) }}
            @error('email')
            <span class="error invalid-email text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        </div>
        <div class="col-6 form-group">
            {{ Form::label('phone', __('Client Phone Number'),['class'=>'form-label']) }}
              {{ Form::tel('phone', null, ['class' => 'form-control', 'required' => 'required','pattern'=>"\d{10,12}",'placeholder' => __('Enter Client Phone number'), 'minlength'=>'10','maxlength' => '12','id' => 'phoneInput']) }}

              @error('phone')
              <span class="error invalid-email text-danger" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
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
              ?>
             <option value="<?php echo $country->country_id; ?>"><?php echo $country->country_name; ?></option>
            <?php } ?>
        </select>
</div>
    <div class="col-4 form-group">
        {{ Form::label('state', __('State'), ['class'=>'form-label']) }}
        <select name="state" id="state" class="form-control select"  required>
            
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
            @if(count($users) == 1)
                <div class="text-muted text-xs">
                    {{_('Please create new users')}} <a href="{{route('users.index')}}">{{_('here')}}</a>.
                </div>
            @endif
    </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>

{{Form::close()}}

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
    document.getElementById('phoneInput').addEventListener('input', function (e) {
        // Replace any non-digit characters
        e.target.value = e.target.value.replace(/\D/g, '');
    });
    </script>
    