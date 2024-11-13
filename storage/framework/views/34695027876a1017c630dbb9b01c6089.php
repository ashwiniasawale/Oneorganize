<?php echo e(Form::model($task, ['route' => ['projects.tasks.update',[$project->id, $task->id]], 'id' => 'edit_task', 'method' => 'POST'])); ?>

<style>
    .choices__inner{
        height: 130px; overflow-y: auto;
    }
</style>
<div class="modal-body">
   
    
    <?php
        $plan= \App\Models\Utility::getChatGPTSettings();
    ?>
    <?php if($plan->chatgpt == 1): ?>
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="<?php echo e(route('generate',['project task'])); ?>"
           data-bs-placement="top" data-title="<?php echo e(__('Generate content with AI')); ?>">
            <i class="fas fa-robot"></i> <span><?php echo e(__('Generate with AI')); ?></span>
        </a>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <input type="hidden" id="p_start_date" value="<?php echo $project->start_date; ?>">
        <input type="hidden" id="p_end_date" value="<?php echo $project->end_date; ?>">
        <div class="col-6">
            <div class="form-group">
                <?php echo e(Form::label('name', __('Task name'),['class' => 'form-label'])); ?><span class="text-danger">*</span>
                <?php echo e(Form::text('name', null, ['class' => 'form-control','required'=>'required'])); ?>

            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <?php echo e(Form::label('milestone_id', __('Milestone'),['class' => 'form-label'])); ?>

                <select class="form-control select" name="milestone_id" id="milestone_id">
                    <option value="0" class="text-muted"><?php echo e(__('Select Milestone')); ?></option>
                    <?php $__currentLoopData = $project->milestones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($m_val->id); ?>" <?php echo e(($task->milestone_id == $m_val->id) ? 'selected':''); ?>><?php echo e($m_val->title); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <?php echo e(Form::label('description', __('Description'),['class' => 'form-label'])); ?>

                <small class="form-text text-muted mb-2 mt-0"><?php echo e(__('This textarea will autosize while you type')); ?></small>
                <?php echo e(Form::textarea('description', null, ['class' => 'form-control','rows'=>'1','data-toggle' => 'autosize'])); ?>

            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <?php echo e(Form::label('comment',__('Comment'),['class'=>'form-label'])); ?> <small>(Optional)</small>
                <?php echo e(Form::textarea('comment',$task->comment,['class'=>'form-control','rows'=>'1'])); ?>

            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <?php echo e(Form::label('remark',__('Remark'),['class'=>'form-label'])); ?> <small>(Optional)</small>
                <?php echo e(Form::textarea('remark',$task->remark,['class'=>'form-control','rows'=>'1'])); ?>

            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <?php echo e(Form::label('estimated_hrs', __('Estimated Hours'),['class' => 'form-label'])); ?><span class="text-danger">*</span>
                <small class="form-text text-muted mb-2 mt-0"><?php echo e(__('allocated total ').$hrs['allocated'].__(' hrs in other tasks')); ?></small>
                <?php echo e(Form::number('estimated_hrs', null, ['class' => 'form-control','required' => 'required','min'=>'0','maxlength' => '8'])); ?>

            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <?php echo e(Form::label('priority', __('Priority'),['class' => 'form-label'])); ?>

                <small class="form-text text-muted mb-2 mt-0"><?php echo e(__('Set Priority of your task')); ?></small>
                <select class="form-control select" name="priority" id="priority" required>
                    <?php $__currentLoopData = \App\Models\ProjectTask::$priority; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e(($key == $task->priority) ? 'selected' : ''); ?> ><?php echo e(__($val)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <?php echo e(Form::label('start_date', __('Start Date'),['class' => 'form-label'])); ?>

                <?php echo e(Form::date('start_date', null, ['class' => 'form-control','id'=>'start_date','required'=>'required','onchange'=>'getdate(this.value);'])); ?>

            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <?php echo e(Form::label('end_date', __('End Date'),['class' => 'form-label'])); ?>

                <?php echo e(Form::date('end_date', null, ['class' => 'form-control','id'=>'end_date','required'=>'required'])); ?>

            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
            <?php echo e(Form::label('deliverables', __('Deliverables'),['class' => 'form-label'])); ?>

            <?php echo e(Form::text('deliverables', null, ['class' => 'form-control'])); ?>

            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
          
            <?php echo e(Form::label('stage_id', __('Status'),['class' => 'form-label'])); ?><span class="text-danger">*</span>
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

                <?php echo e(Form::label('task_activity', __('Select Activity'),['class' => 'form-label'])); ?><span class="text-danger">*</span></br>
                <input type="radio" id="task_activity1" name="task_activity" <?php if($task->task_activity=='hardware'){ echo 'checked=checked'; } ?> value="hardware" onclick="get_activity_type(this.value);" required>
                <label for="task_activity1">Hardware</label>

                <input type="radio" id="task_activity2" name="task_activity" <?php if($task->task_activity=='software'){ echo 'checked=checked'; } ?> value="software" onclick="get_activity_type(this.value);" required>
                <label for="task_activity2">Software</label>
                <input type="radio" id="task_activity3" name="task_activity" <?php if($task->task_activity=='general'){ echo 'checked=checked'; } ?> value="general" onclick="get_activity_type(this.value);" required>
                <label for="task_activity3">General</label>
            </div>
        </div>
        <div class="col-6 form-group">
            <?php echo e(Form::label('task_activity_type', __('Activity Type'), ['class'=>'form-label'])); ?>

           
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
        <label class="form-label"><?php echo e(__('Requirements')); ?></label>
        <small class="form-text text-muted mb-2 mt-0"><?php echo e(__('Below task requirements are assigned in your project.')); ?></small>
    </div>
    <div class="col-12 form-group">
 
    <select name="requirement_id[]" class="form-control select2" id="choices-multiple1" data-placeholder="Select Requirement"  multiple>
            <?php $__currentLoopData = $requirement; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $requirement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                                $reqq = explode(',',$task->requirement_id);
                            ?>
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
                   <p> <?php echo e($requirement->requirement_details); ?></p>
                </option>
              
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
    
    <div class="form-group">
        <label class="form-label"><?php echo e(__('Task members')); ?></label>
        <small class="form-text text-muted mb-2 mt-0"><?php echo e(__('Below users are assigned in your project.')); ?></small>
    </div>
    <div class="list-group list-group-flush mb-4">
        <div class="row">
            <?php $__currentLoopData = $project->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-6">
                    <div class="list-group-item px-0">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <a href="#" class="avatar avatar-sm rounded-circle">
                                    <img class="wid-40 rounded-circle ml-3" data-original-title="<?php echo e((!empty($user)?$user->name:'')); ?>" <?php if($user->avatar): ?> src="<?php echo e(asset('/storage/uploads/avatar/'.$user->avatar)); ?>" <?php else: ?> src="<?php echo e(asset('/storage/uploads/avatar/avatar.png')); ?>" <?php endif; ?> />
                                </a>
                            </div>
                            <div class="col">
                                <p class="d-block h6 text-sm mb-0"><?php echo e($user->name); ?></p>
                                <p class="card-text text-sm text-muted mb-0"><?php echo e($user->email); ?></p>
                            </div>
                            <?php
                                $usrs = explode(',',$task->assign_to);
                            ?>
                            <div class="col-auto text-end add_usr <?php echo e((in_array($user->id,$usrs)) ? 'selected':''); ?>" data-id="<?php echo e($user->id); ?>">
                                <button type="button" class="btn mr-3">
                            <span class="btn-inner--visible">
                              <i class="ti ti-<?php echo e((in_array($user->id,$usrs)) ? 'check' : 'plus'); ?> " id="usr_icon_<?php echo e($user->id); ?>"></i>
                            </span>
                                    <span class="btn-inner--hidden text-white" id="usr_txt_<?php echo e($user->id); ?>"><?php echo e((in_array($user->id,$usrs)) ? __('Added') : __('Add')); ?></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php echo e(Form::hidden('assign_to', null,['id'=>'assign_to'])); ?>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>



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
         url: '<?php echo e(route('task.get_task_activity_type')); ?>',
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
</script><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/project_task/edit.blade.php ENDPATH**/ ?>