{{ Form::open(['route' => ['projects.tasks.storesubtask',$project_id,$task_id],'id' => 'create_subtask']) }}
<style>
    .choices__inner{
        height: 130px; overflow-y: auto;
    }
    
</style>
<div class="modal-body">
    {{-- start for ai module--}}
    @php
    $plan= \App\Models\Utility::getChatGPTSettings();
   
    use App\Models\ProjectSubtask;
   @endphp

    {{-- end for ai module--}}
    <input type="hidden" id="t_end_date" value="{{$task->end_date}}">
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('name', __('Subtask Name'),['class' => 'form-label']) }}<span class="text-danger">*</span>
                {{ Form::text('subtask_name', null, ['class' => 'form-control','required'=>'required']) }}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('milestone_id', __('Milestone'),['class' => 'form-label']) }}
                <select class="form-control select" name="milestone_id" id="milestone_id">
                    <option value="0" class="text-muted">{{__('Select Milestone')}}</option>
                    @foreach($project->milestones as $m_val)
                    <option value="{{ $m_val->id }}">{{ $m_val->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'),['class' => 'form-label']) }}
                <small class="form-text text-muted mb-2 mt-0">{{__('This textarea will autosize while you type')}}</small>
                {{ Form::textarea('description', null, ['class' => 'form-control','rows'=>'2','data-toggle' => 'autosize']) }}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('comment', __('Comment'),['class' => 'form-label']) }} <small>(Optional)</small>
                
                {{ Form::textarea('comment', null, ['class' => 'form-control','rows'=>'2']) }}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
             {{ Form::label('remark', __('Remark'),['class' => 'form-label']) }} <small>(Optional)</small>
                
             {{ Form::textarea('remark', null, ['class' => 'form-control','rows'=>'2']) }}
            </div>
        </div>
       
        
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('priority', __('Priority'),['class' => 'form-label']) }}<span class="text-danger">*</span>
                <small class="form-text text-muted mb-2 mt-0">{{__('Set Priority of your task')}}</small>
                <select class="form-control select" name="priority" id="priority" required>
                    <option value=''>Select Priority</option>
                    @foreach(\App\Models\ProjectTask::$priority as $key => $val)
                    <option value="{{ $key }}">{{ __($val) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
       
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('start_date', __('Start Date'),['class' => 'form-label']) }}<span class="text-danger">*</span>
                {{ Form::date('start_date', $task->start_date, ['class' => 'form-control','id'=>'start_date','required'=>'required','onchange'=>'getdate(this.value);']) }}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('end_date', __('End Date'),['class' => 'form-label']) }}<span class="text-danger">*</span>
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
             
                <select name="stage_id" id="stage_id" class="form-control select" required>
                    <option value="<?php echo $stage_id ?>">To Do</option>
                   
                </select>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">

                {{ Form::label('task_activity', __('Select Activity'),['class' => 'form-label']) }}<span class="text-danger">*</span></br>
                <input type="radio" id="task_activity1" name="task_activity" value="hardware" onclick="get_activity_type(this.value);" required>
                <label for="task_activity1">Hardware</label>

                <input type="radio" id="task_activity2" name="task_activity" value="software" onclick="get_activity_type(this.value);" required>
                <label for="task_activity2">Software</label>
                <input type="radio" id="task_activity3" name="task_activity"  value="general" onclick="get_activity_type(this.value);" required>
                <label for="task_activity3">General</label>
            </div>
        </div>
        <div class="col-6 form-group">
            {{ Form::label('task_activity_type', __('Activity Type'), ['class'=>'form-label']) }}
            <select name="task_activity_type" id="task_activity_type" class="form-control select" required>

            </select>
        </div>
        <div class="form-group">
        <label class="form-label">{{__('Requirements')}}</label>
        <small class="form-text text-muted mb-2 mt-0">{{__('Below task requirements are assigned in your project.')}}</small>
    </div>
    <div class="col-12 form-group">
    <select name="requirement_id[]" class="form-control select2" id="choices-multiple1" data-placeholder="Select Requirement" multiple>
            @foreach($requirement as $requirement)
                <option value='<?php echo $requirement->id;?>'>
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
       
        <input type="hidden" name="assign_to" value="{{$task->assign_to}}" id="assign_to" required>
    </div>
    </div>
  
   
  
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" id="create_subtask_id" value="{{__('Create')}}" class="btn btn-primary">
</div>
{{Form::close()}}

<script>
     $(document).ready(function() {
        $("#create_subtask").submit(function(e) {
           
            e.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = new FormData(this);
            var assign_to_value = document.getElementById("assign_to").value;
        if (!assign_to_value) {
            alert("Please assign task members.");
            return false; // Prevent form submission
        }
            // Send AJAX request
            $("#create_subtask_id").attr("disabled", true);
            $("#loader").css("display", "flex");

            $.ajax({
                type: $(this).attr('method'), // Get the HTTP method (POST or GET)
                url: $(this).attr('action'), // Get the form's action attribute value
                data: formData, // Set the form data
                processData: false,  // Important! Don't process the data
                contentType: false,  
                success: function (response) {
                    // Handle the success response from the server
                // console.log(response); // Log the response to the console
                $("#loader").css("display", "none");
            $("#create_subtask_id").attr("disabled", false);
                if(response.success)
                {
                    show_toastr('Success', response.success, 'success');
                // console.log(response.tmp_task)
                
                       $('#commonModal').modal('hide');
                       load_data();
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
    
   var p_start_date=document.getElementById('start_date').value;
   var t_end_date=document.getElementById('t_end_date').value;
   document.getElementById("start_date").setAttribute("min", p_start_date);
   document.getElementById("start_date").setAttribute("max", t_end_date);
   document.getElementById("end_date").setAttribute("min", p_start_date);
   document.getElementById("end_date").setAttribute("max", t_end_date);
        
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

