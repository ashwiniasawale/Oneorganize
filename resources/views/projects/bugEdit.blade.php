{{ Form::model($bug, array('route' => array('task.bug.update', $project_id,$bug->id ), 'method' => 'POST')) }}
<div class="modal-body">
    {{-- start for ai module--}}
    @php
        $plan= \App\Models\Utility::getChatGPTSettings();
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
    <input type="hidden" id="p_start_date" value="<?php echo $project->start_date; ?>">
        <div class="form-group col-md-6">
            {{ Form::label('title', __('Title'),['class'=>'form-label']) }}
            {{ Form::text('title', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('priority', __('Priority'),['class'=>'form-label']) }}
            {!! Form::select('priority', $priority, null,array('class' => 'form-control select','required'=>'required')) !!}
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('start_date', __('Start Date'),['class'=>'form-label']) }}
            {{ Form::date('start_date', null, array('class' => 'form-control','required'=>'required','id'=>'start_date','onchange'=>'getdate(this.value);')) }}
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('due_date', __('Due Date'),['class'=>'form-label']) }}
            {{ Form::date('due_date', null, array('class' => 'form-control','required'=>'required','id'=>'due_date')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('status', __('Bug Status'),['class'=>'form-label']) }}
            {!! Form::select('status', $status, null,array('class' => 'form-control select','required'=>'required')) !!}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('assign_to', __('Assigned To'),['class'=>'form-label']) }}
            {{ Form::select('assign_to', $users, null,array('class' => 'form-control select','required'=>'required')) }}
        </div>
    </div>
    <div class="row">
        <div class="form-group  col-md-12">
            {{ Form::label('proposed_correction_action', __('Proposed Correction Action'),['class'=>'form-label']) }}
            {!! Form::text('proposed_correction_action', null, ['class'=>'form-control']) !!}
        </div>
    </div>
    <div class="row">
        <div class="form-group  col-md-12">
            {{ Form::label('solution_implemented', __('Solution Implemented'),['class'=>'form-label']) }}
            {!! Form::textarea('solution_implemented', null, ['class'=>'form-control','rows'=>'2']) !!}
        </div>
    </div>
    <div class="form-group col-md-12">
            {{ Form::label('review', __('Review'),['class'=>'form-label']) }}
            {!! Form::select('review', $users, null,array('class' => 'form-control','required'=>'required')) !!}
        </div>
    <div class="row">
        <div class="form-group  col-md-12">
            {{ Form::label('problem_discover', __('Problem Discover'),['class'=>'form-label']) }}
            {!! Form::textarea('problem_discover', null, ['class'=>'form-control','rows'=>'2']) !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>
{{Form::close()}}

 <script>
var p_start_date=document.getElementById('p_start_date').value;
       var start_date=document.getElementById('start_date').value;
  document.getElementById("start_date").setAttribute("min", p_start_date);
  document.getElementById("due_date").setAttribute("min", start_date);
   function getdate(start_date)
   {
       document.getElementById("due_date").setAttribute("min", start_date);
   }
   </script>