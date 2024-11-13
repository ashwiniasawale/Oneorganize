
{{ Form::open(array('route' => array('project.milestone.store',$project_id))) }}
<div class="modal-body overflow-scroll">
    {{-- start for ai module--}}
    @php
                            $user = \App\Models\User::find(\Auth::user()->creatorId());
                    $plan= \App\Models\Plan::getPlan($user->plan);
    @endphp
    @if($plan->chatgpt == 1)
    <div class="text-end">
        <a href="#" data-size="lg" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="{{ route('generate',['project bug']) }}"
           data-bs-placement="top" data-title="{{ __('Generate content with AI') }}">
            <i class="fas fa-robot"></i> <span>{{__('Generate with AI')}}</span>
        </a>
    </div>
    @endif
    {{-- end for ai module--}}
    <div class="row ">
        <div class="form-group col-md-12 col-sm-12">
            {{ Form::label('title', __('Title'),['class'=>'form-label']) }}
            {!! Form::text('title', null, ['class'=>'form-control','rows'=>'2','required'=>'required']) !!}
        </div>
        <div class="form-group col-md-12">
         
        
        {{ Form::label('start_date', __('Start Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                
        {!! Form::date('start_date', null, ['class'=>'form-control','rows'=>'2']) !!}
        
        </div>
        <div class="form-group col-md-12 col-sm-12">
            {{ Form::label('resources', __('Resources'),['class'=>'form-label']) }}
            {!! Form::textarea('resources', null, ['class'=>'form-control','rows'=>'2']) !!}
        </div>
        <div class="form-group col-md-12 col-sm-12">
            {{ Form::label('deliverables', __('Deliverables'),['class'=>'form-label']) }}
            {!! Form::textarea('deliverables', null, ['class'=>'form-control','rows'=>'2']) !!}
        </div>
        <div class="form-group  col-md-12">
        {{ Form::label('status', __('Status'),['class' => 'form-label']) }}
        {!! Form::select('status',\App\Models\Project::$project_status, null,array('class' => 'form-control select','required'=>'required')) !!}
        @error('client')
        <span class="invalid-client" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
        @enderror
    </div>
        <div class="form-group col-md-12 col-sm-12">
            {{ Form::label('notes', __('Notes'),['class'=>'form-label']) }}
            {!! Form::textarea('notes', null, ['class'=>'form-control','rows'=>'2']) !!}
        </div>
       
       
        
       
    </div>
    
    
   
    </div>
   
   
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>
{{Form::close()}}