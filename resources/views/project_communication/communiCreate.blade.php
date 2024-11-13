{{ Form::open(array('route' => array('project.communication.store',$project_id),'enctype' => 'multipart/form-data')) }}
<div class="modal-body">
    {{-- start for ai module--}}
    @php
                            $user = \App\Models\User::find(\Auth::user()->creatorId());
                    $plan= \App\Models\Plan::getPlan($user->plan);
    @endphp
    @if($plan->chatgpt == 1)
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="{{ route('generate',['project Communication']) }}"
           data-bs-placement="top" data-title="{{ __('Generate content with AI') }}">
            <i class="fas fa-robot"></i> <span>{{__('Generate with AI')}}</span>
        </a>
    </div>
    @endif
    {{-- end for ai module--}}
    <div class="row">
    <div class="form-group  col-md-6">
            {{ Form::label('title', __('Title'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::text('title', '', array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('date', __('Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::date('date', '', array('class' => 'form-control','required'=>'required')) }}
        </div>
       
       
        <div class="form-group  col-md-12">
            {{ Form::label('description', __('Description'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::textarea('description', '', array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-12">
            {{Form::label('attachment',__('Attachment'),['class'=>'form-label'])}}<span class="text-danger">*</span>
            <div class="form-file mb-3">
                <input type="file" class="form-control" name="attachment" required="">
            </div>
        </div>
      
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>
{{Form::close()}}