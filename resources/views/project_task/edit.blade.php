{{ Form::model($task, ['route' => ['projects.tasks.update',[$project->id, $task->id]], 'id' => 'edit_task', 'method' => 'POST']) }}
<style>
    .choices__inner{
        height: 130px; overflow-y: auto;
    }
</style>
<div class="modal-body">
   
    {{-- start for ai module--}}
    @php
        $plan= \App\Models\Utility::getChatGPTSettings();
    @endphp
    @if($plan->chatgpt == 1)
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="{{ route('generate',['project task']) }}"
           data-bs-placement="top" data-title="{{ __('Generate content with AI') }}">
            <i class="fas fa-robot"></i> <span>{{__('Generate with AI')}}</span>
        </a>
    </div>
    @endif
    {{-- end for ai module--}}
    <div class="row">
        <input type="hidden" id="p_start_date" value="<?php echo $project->start_date; ?>">
        <input type="hidden" id="p_end_date" value="<?php echo $project->end_date; ?>">
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('name', __('Task name'),['class' => 'form-label']) }}<span class="text-danger">*</span>
                {{ Form::text('name', null, ['class' => 'form-control','required'=>'required']) }}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('milestone_id', __('Milestone'),['class' => 'form-label']) }}
                <select class="form-control select" name="milestone_id" id="milestone_id">
                    <option value="0" class="text-muted">{{__('Select Milestone')}}</option>
                    @foreach($project->milestones as $m_val)
                        <option value="{{ $m_val->id }}" {{ ($task->milestone_id == $m_val->id) ? 'selected':'' }}>{{ $m_val->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'),['class' => 'form-label']) }}
                <small class="form-text text-muted mb-2 mt-0">{{__('This textarea will autosize while you type')}}</small>
                {{ Form::textarea('description', null, ['class' => 'form-control','rows'=>'1','data-toggle' => 'autosize']) }}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{Form::label('comment',__('Comment'),['class'=>'form-label'])}} <small>(Optional)</small>
                {{Form::textarea('comment',$task->comment,['class'=>'form-control','rows'=>'1'])}}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{Form::label('remark',__('Remark'),['class'=>'form-label'])}} <small>(Optional)</small>
                {{Form::textarea('remark',$task->remark,['class'=>'form-control','rows'=>'1'])}}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('estimated_hrs', __('Estimated Hours'),['class' => 'form-label']) }}<span class="text-danger">*</span>
                <small class="form-text text-muted mb-2 mt-0">{{__('allocated total ').$hrs['allocated'].__(' hrs in other tasks')}}</small>
                {{ Form::number('estimated_hrs', null, ['class' => 'form-control','required' => 'required','min'=>'0','maxlength' => '8']) }}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('priority', __('Priority'),['class' => 'form-label']) }}
                <small class="form-text text-muted mb-2 mt-0">{{__('Set Priority of your task')}}</small>
                <select class="form-control select" name="priority" id="priority" required>
                    @foreach(\App\Models\ProjectTask::$priority as $key => $val)
                        <option value="{{ $key }}" {{ ($key == $task->priority) ? 'selected' : '' }} >{{ __($val) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('start_date', __('Start Date'),['class' => 'form-label']) }}
                {{ Form::date('start_date', null, ['class' => 'form-control','id'=>'start_date','required'=>'required','onchange'=>'getdate(this.value);']) }}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('end_date', __('End Date'),['class' => 'form-label']) }}
                {{ Form::date('end_date', null, ['class' => 'form-control','id'=>'end_date','required'=>'required']) }}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
            {{ Form::label('deliverables', __('Deliverables'),['class' => 'form-label']) }}
            {{ Form::text('deliverables', null, ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
          
            {{ Form::label('stage_id', __('Status'),['class' => 'form-label']) }}<span class="text-danger">*</span>
            <select name="stage_id" id="stage_id" class="form-control select"  required>
            <option value="">Select Status</option>
            <?php foreach($stages as $stage)
            {
              if($stage->id==$task->stage_id)
              {
                $stage_selected='selected=selected';
              }else{
                $stage_selected='';
              }
              ?>
             <option <?php echo $stage_selected; ?> value="<?php echo $stage->id; ?>"><?php echo $stage->name; ?></option>
            <?php } ?>
        </select>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">

                {{ Form::label('task_activity', __('Select Activity'),['class' => 'form-label']) }}<span class="text-danger">*</span></br>
                <input type="radio" id="task_activity1" name="task_activity" <?php if($task->task_activity=='hardware'){ echo 'checked=checked'; } ?> value="hardware" onclick="get_activity_type(this.value);" required>
                <label for="task_activity1">Hardware</label>

                <input type="radio" id="task_activity2" name="task_activity" <?php if($task->task_activity=='software'){ echo 'checked=checked'; } ?> value="software" onclick="get_activity_type(this.value);" required>
                <label for="task_activity2">Software</label>
                <input type="radio" id="task_activity3" name="task_activity" <?php if($task->task_activity=='general'){ echo 'checked=checked'; } ?> value="general" onclick="get_activity_type(this.value);" required>
                <label for="task_activity3">General</label>
            </div>
        </div>
        <div class="col-6 form-group">
            {{ Form::label('task_activity_type', __('Activity Type'), ['class'=>'form-label']) }}
           
            <select name="task_activity_type" id="task_activity_type" class="form-control select"  required>
            <?php foreach($activity_type as $key=>$value)
            {
                if($key==$task->task_activity_type)
                {
                    $type_select='selected=selected';
                }else{
                    $type_select='';
                }
              ?>
             <option <?php echo $type_select; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
            <?php } ?>
        </select>
        </div>
    </div>
    <div class="form-group">
        <label class="form-label">{{__('Requirements')}}</label>
        <small class="form-text text-muted mb-2 mt-0">{{__('Below task requirements are assigned in your project.')}}</small>
    </div>
    <div class="col-12 form-group">
 
    <select name="requirement_id[]" class="form-control select2" id="choices-multiple1" data-placeholder="Select Requirement"  multiple>
            @foreach($requirement as $requirement)
            @php
                                $reqq = explode(',',$task->requirement_id);
                            @endphp
                            <?php 
                            if(in_array($requirement->id,$reqq))
                            {
                                $sel='selected=selected';
                            }else{
                                $sel='';
                            }
                          
                            ?>
                <option <?php echo $sel; ?> value='<?php echo $requirement->id;?>' >
                    <p><?php echo 'REQUIREMENT'. sprintf("%05d", $requirement->requirement_id); ?><p> - 
                   <p> {{ $requirement->requirement_details }}</p>
                </option>
              
                @endforeach
                </select>
            </div>
    
    <div class="form-group">
        <label class="form-label">{{__('Task members')}}</label>
        <small class="form-text text-muted mb-2 mt-0">{{__('Below users are assigned in your project.')}}</small>
    </div>
    <div class="list-group list-group-flush mb-4">
        <div class="row">
            @foreach($project->users as $user)
            @if($user->is_enable_login=='1')
                <div class="col-6">
                    <div class="list-group-item px-0">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <a href="#" class="avatar avatar-sm rounded-circle">
                                    <img class="wid-40 rounded-circle ml-3" data-original-title="{{(!empty($user)?$user->name:'')}}" @if($user->avatar) src="{{asset('/storage/uploads/avatar/'.$user->avatar)}}" @else src="{{asset('/storage/uploads/avatar/avatar.png')}}" @endif />
                                </a>
                            </div>
                            <div class="col">
                                <p class="d-block h6 text-sm mb-0">{{ $user->name }}</p>
                                <p class="card-text text-sm text-muted mb-0">{{ $user->email }}</p>
                            </div>
                            @php
                                $usrs = explode(',',$task->assign_to);
                            @endphp
                            <div class="col-auto text-end add_usr {{ (in_array($user->id,$usrs)) ? 'selected':'' }}" data-id="{{ $user->id }}">
                                <button type="button" class="btn mr-3">
                            <span class="btn-inner--visible">
                              <i class="ti ti-{{ (in_array($user->id,$usrs)) ? 'check' : 'plus' }} " id="usr_icon_{{$user->id}}"></i>
                            </span>
                                    <span class="btn-inner--hidden text-white" id="usr_txt_{{$user->id}}">{{ (in_array($user->id,$usrs)) ? __('Added') : __('Add')}}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
        {{ Form::hidden('assign_to', null,['id'=>'assign_to']) }}
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
       var p_end_date=document.getElementById('p_end_date').value;

  document.getElementById("start_date").setAttribute("min", p_start_date);
  document.getElementById("end_date").setAttribute("min", start_date);
  document.getElementById("end_date").setAttribute("max",p_end_date);
   function getdate(start_date)
   {
       document.getElementById("end_date").setAttribute("min", start_date);
   }
  
  
    function get_activity_type(task_activity)
    {  
     
      $.ajax({  
         type:"POST",  
         url: '{{route('task.get_task_activity_type')}}',
         data:{_token: $('meta[name="csrf-token"]').attr('content'),task_activity:task_activity},  
         success: function (data) 
         {
            $("#task_activity_type").empty();
            $("#task_activity_type").html(data);
           
          },
          error: function (data) 
          {
                            
          } 
      }); 
    }
</script>

<script>
  
    $(document).ready(function () {
        $('#edit_task').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission
            var assign_to_value = document.getElementById("assign_to").value;
           
        if (!assign_to_value) {
            alert("Please assign task members.");
            return false; // Prevent form submission
        }
            // Gather form data
            var formData = new FormData(this);
            // Send AJAX request
            $.ajax({
                type: $(this).attr('method'), // Get the HTTP method (POST or GET)
                url: $(this).attr('action'), // Get the form's action attribute value
                data: formData, // Set the form data
                processData: false,  // Important! Don't process the data
                contentType: false,  
                success: function (response) {
                // Handle the success response from the server
                  // console.log(response); // Log the response to the console
                  if(response.success)
                  {
                    show_toastr('Success', response.success, 'success');
                  }else{
                    show_toastr('Error', response.error, 'error');
                  }
                    $('#commonModal').modal('hide');
                     load_data();
                  
                },
                error: function (xhr, status, error) {
                    // Handle errors
                    console.error(error); // Log the error to the console
                }
            });
        });
    });
</script>