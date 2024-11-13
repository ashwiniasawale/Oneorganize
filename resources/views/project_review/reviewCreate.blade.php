{{ Form::open(array('route' => array('project.review.store',$project_id),'enctype' => 'multipart/form-data'
    
    
    
    
    
    
    
    
    )) }}
<div class="modal-body">
    {{-- start for ai module--}}
    @php
                            $user = \App\Models\User::find(\Auth::user()->creatorId());
                    $plan= \App\Models\Plan::getPlan($user->plan);
    @endphp
    @if($plan->chatgpt == 1)
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="{{ route('generate',['project bug']) }}"
           data-bs-placement="top" data-title="{{ __('Generate content with AI') }}">
            <i class="fas fa-robot"></i> <span>{{__('Generate with AI')}}</span>
        </a>
    </div>
    @endif
    {{-- end for ai module--}}
    <div class="row">
      
        <div class="form-group  col-md-6">
            {{ Form::label('review_date', __('Review Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::date('review_date', '', array('class' => 'form-control','required'=>'required')) }}
        </div>
       
        <div class="form-group col-md-6">
            {{ Form::label('attended_by', __('attended By'),['class'=>'form-label']) }}
            {{ Form::select('attended_by', $users, null,array('class' => 'form-control select','required'=>'required')) }}
        </div>
        <div class="form-group  col-md-12">
            {{ Form::label('artifacts_of_review', __('Artifacts of Review'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::text('artifacts_of_review', '', array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{Form::label('checklist',__('Checklist'),['class'=>'form-label'])}}<span class="text-danger">*</span>
            <div class="form-file mb-3">
                <input type="file" class="form-control" name="checklist" required="">
            </div>
        </div>
        <!-- <div class="form-group  col-md-6">
            {{ Form::label('review_criteria', __('Review Criteria'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::text('review_criteria', '', array('class' => 'form-control','required'=>'required')) }}
        </div> -->
        <div class="form-group  col-md-12">
            {{ Form::label('requirement', __('Requirement'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::text('requirement', '', array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group  col-md-12">
            {{ Form::label('non_conf_list', __('Non-Confirm List'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::text('non_conf_list', '', array('class' => 'form-control','required'=>'required')) }}
        </div>

        <div class="form-group  col-md-12">
            {{ Form::label('improvement_suggestions', __('Improvement Suggestions'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::text('improvement_suggestions', '', array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('risk_identified', __('Risk Identified'),['class'=>'form-label']) }}
            {{ Form::select('risk_identified', $users, null,array('class' => 'form-control select','required'=>'required')) }}
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('problem_discover', __('Problem Discover'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::text('problem_discover', '', array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('deviation_taken', __('Deviation Taken'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::text('deviation_taken', '', array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('is_updated', __('Is Requirement Sheet Updated'),['class'=>'form-label']) }}
           <select class="form-control select" name="is_updated" required>
                <option value="">Select</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
           </select>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>
{{Form::close()}}