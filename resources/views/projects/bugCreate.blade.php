{{ Form::open(array('route' => array('task.bug.store',$project_id))) }}
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
       
        <div class="form-group col-md-6">
            {{ Form::label('title', __('Title'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::text('title', '', array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('priority', __('Priority'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{-- {!! Form::select('priority', $priority, null,array('class' => 'form-control select','required'=>'required')) !!} --}}
            <select name="priority" class="form-control select"  required>
                <option value=''>Select Priority</option>
                <?php foreach($priority as $key=>$value)
                {
                    ?><option value='<?php echo $key; ?>'><?php echo $value; ?></option>
                    <?php
                }?>
                </select>
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('start_date', __('Start Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::date('start_date', $project->start_date, array('class' => 'form-control','required'=>'required','id'=>'start_date','onchange'=>'getdate(this.value)')) }}
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('due_date', __('Due Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::date('due_date', '', array('class' => 'form-control','required'=>'required','id'=>'due_date')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('status', __('Bug Status'),['class'=>'form-label']) }}<span class=" text-danger">*</span>
           
            <select name="status" class="form-control select" required>
            <option value=''>Select Status</option>
            <?php foreach($status as $key=>$value)
            {
                ?><option value='<?php echo $key; ?>'><?php echo $value; ?></option>
                <?php
            }?>
            </select>
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('assign_to', __('Assigned To'),['class'=>'form-label']) }}<span class=" text-danger">*</span>
            {{-- {{ Form::select('assign_to', $users, null,array('class' => 'form-control select',)) }} --}}
            <select name="assign_to" class="form-control select"  required>
                <option value=''>Select User</option>
                <?php foreach($users as $key=>$value)
                {
                    ?><option value='<?php echo $key; ?>'><?php echo $value; ?></option>
                    <?php
                }?>
                </select>
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
            {{ Form::label('review', __('Review'),['class'=>'form-label '])  }}<span class=" text-danger">*</span>
            {{-- {!! Form::select('review', $users, null,array('class' => 'form-control','required'=>'required')) !!} --}}
            <select name="review" class="form-control select"  required>
                <option value=''>Select     Reviewer</option>
                <?php foreach($users as $key=>$value)
                {
                    ?><option value='<?php echo $key; ?>'><?php echo $value; ?></option>
                    <?php
                }?>
                </select>
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
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>
{{Form::close()}}
<script>
   var p_start_date=document.getElementById('start_date').value;
  
   document.getElementById("start_date").setAttribute("min", p_start_date);
   document.getElementById("due_date").setAttribute("min", p_start_date);
    function getdate(start_date)
    {
       
        document.getElementById("due_date").setAttribute("min", start_date);
    }
   
</script>
