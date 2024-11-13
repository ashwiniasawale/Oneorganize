
<?php echo e(Form::open(['route' => ['project.test.store',$project_id,$stage_id],'id' => 'create_test','method'=>'POST','enctype'=>'multipart/form-data'])); ?>

<style>
    .choices__inner{
        height: 130px; overflow-y: auto;
    }
</style>
<div class="modal-body">
    
    <?php
                            $user = \App\Models\User::find(\Auth::user()->creatorId());
                    $plan= \App\Models\Plan::getPlan($user->plan);
    ?>
    <?php if($plan->chatgpt == 1): ?>
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="<?php echo e(route('generate',['project bug'])); ?>"
           data-bs-placement="top" data-title="<?php echo e(__('Generate content with AI')); ?>">
            <i class="fas fa-robot"></i> <span><?php echo e(__('Generate with AI')); ?></span>
        </a>
    </div>
    <?php endif; ?>
    
   <input type="hidden" id="p_end_date" value="<?php echo $project->end_date; ?>">
    <div class="row">
        <div class="form-group col-md-6">
            <?php echo e(Form::label('test_name', __('Test Name'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('test_name', '', array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        <div class="col-6">
            <div class="form-group">
                <?php echo e(Form::label('milestone_id', __('Milestone'),['class' => 'form-label'])); ?>

                <select class="form-control select" name="milestone_id" id="milestone_id">
                    <option value="0" class="text-muted"><?php echo e(__('Select Milestone')); ?></option>
                    <?php $__currentLoopData = $project->milestones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($m_val->id); ?>"><?php echo e($m_val->title); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        <div class="col-12">
        <div class="form-group">
                <?php echo e(Form::label('test_description', __('Test Description'),['class' => 'form-label'])); ?>

                <small class="form-text text-muted mb-2 mt-0"><?php echo e(__('This textarea will autosize while you type')); ?></small>
                <?php echo e(Form::textarea('test_description', null, ['class' => 'form-control','rows'=>'2','data-toggle' => 'autosize'])); ?>

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
                <?php echo e(Form::label('priority', __('Priority'),['class' => 'form-label'])); ?><span class="text-danger">*</span>
                <small class="form-text text-muted mb-2 mt-0"><?php echo e(__('Set Priority of your task')); ?></small>
                <select class="form-control select" name="priority" id="priority" required>
                    <?php $__currentLoopData = \App\Models\ProjectTask::$priority; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>"><?php echo e(__($val)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
</div>
<div class="col-6">
<div class="form-group">
                <?php echo e(Form::label('start_date', __('Start Date'),['class' => 'form-label'])); ?><span class="text-danger">*</span>
                <?php echo e(Form::date('start_date', $project->start_date, ['class' => 'form-control','required'=>'required','id'=>'start_date','onchange'=>'getdate(this.value);'])); ?>

            </div>
</div>
<div class="col-6">
<div class="form-group">
                <?php echo e(Form::label('end_date', __('End Date'),['class' => 'form-label'])); ?><span class="text-danger">*</span>
                <?php echo e(Form::date('end_date', null, ['class' => 'form-control','required'=>'required','id'=>'end_date'])); ?>

            </div>  
</div>
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('deliverables', __('Deliverables'),['class'=>'form-label'])); ?>

            <?php echo Form::textarea('deliverables', null, ['class'=>'form-control','rows'=>'2']); ?>

        </div>
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('test_procedures', __('Test Procedures'),['class'=>'form-label'])); ?>

            <?php echo Form::textarea('test_procedures', null, ['class'=>'form-control','rows'=>'2']); ?>

        </div>
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('test_input', __('Test Input'),['class'=>'form-label'])); ?>

            <?php echo Form::textarea('test_input', null, ['class'=>'form-control','rows'=>'2']); ?>

        </div>
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('test_accepted_output', __('Test  Accepted Output'),['class'=>'form-label'])); ?>

            <?php echo Form::textarea('test_accepted_output', null, ['class'=>'form-control','rows'=>'2']); ?>

        </div>
       

        <div class="form-group  col-md-12">
            <?php echo e(Form::label('test_note', __('Test Note'),['class'=>'form-label'])); ?>

            <?php echo Form::textarea('test_note', null, ['class'=>'form-control','rows'=>'2']); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('test_plan',__('Test Plan'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <div class="form-file mb-3">
                <input type="file" class="form-control" name="test_plan" required="">
            </div>
        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('test_result',__('Test Result'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <div class="form-file mb-3">
                <input type="file" class="form-control" name="test_result" required="">
            </div>
        </div>

        <div class="form-group col-md-6">
        <?php echo e(Form::label('stage_id', __('Status'),['class' => 'form-label'])); ?><span class="text-danger">*</span>
        <select name="stage_id" id="stage_id" class="form-control select" required>
        <option value="">Select Status</option>
            <?php foreach($stages as $stage)
            {
              if($stage->id)
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
        <div class="form-group col-md-6">
            <?php echo e(Form::label('test_type', __('Test Type'),['class'=>'form-label'])); ?>

           
            <select name="test_type" id="test_type" class="form-control select" required>
                <option value="">Select Test Type</option>
                    <?php foreach($test_type as $key=>$value)
                    {
                    
                    ?>
                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                    <?php } ?>
                </select>
        </div>
    </div>
    <div class="row">
    <div class="col-6">
    <div class="form-group">

<?php echo e(Form::label('task_activity', __('Select Activity'),['class' => 'form-label'])); ?><span class="text-danger">*</span></br>
<input type="radio" id="task_activity1" name="task_activity" value="hardware" onclick="get_activity_type(this.value);" required>
<label for="task_activity1">Hardware</label>

<input type="radio" id="task_activity2" name="task_activity" value="software" onclick="get_activity_type(this.value);" required>
<label for="task_activity2">Software</label>
</div>
           
         </div>


<div class="form-group col-6">
<?php echo e(Form::label('task_activity_type', __('Activity Type'), ['class'=>'form-label'])); ?>

            <select name="task_activity_type" id="task_activity_type" class="form-control select" required>

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
                <option value='<?php echo $requirement->id;?>'>
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
                                <img class="wid-40 rounded-circle ml-3" data-original-title="<?php echo e((!empty($user)?$user->name:'')); ?>" <?php if($user->avatar): ?> src="<?php echo e(asset('/storage/uploads/avatar/'.$user->avatar)); ?>" <?php else: ?> src="<?php echo e(asset('/storage/uploads/avatar/avatar.png')); ?>" <?php endif; ?>/>
                            </a>
                        </div>
                        <div class="col">
                            <p class="d-block h6 text-sm mb-0"><?php echo e($user->name); ?></p>
                            <p class="card-text text-sm text-muted mb-0"><?php echo e($user->email); ?></p>
                        </div>
                        <div class="col-auto text-end add_usr" data-id="<?php echo e($user->id); ?>">
                            <button type="button" class="btn btn-xs btn-animated btn-blue rounded-pill btn-animated-y mr-3">
                                <span class="btn-inner--visible">
                                    <i class="ti ti-plus" id="usr_icon_<?php echo e($user->id); ?>"></i>
                                </span>
                                <span class="btn-inner--hidden text-white" id="usr_txt_<?php echo e($user->id); ?>"><?php echo e(__('Add')); ?></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <input type="hidden" name="assign_to" id="assign_to" required>
    </div>

</div>
<?php if(isset($settings['google_calendar_enable']) && $settings['google_calendar_enable'] == 'on'): ?>
    <div class="form-group col-md-6">
        <?php echo e(Form::label('synchronize_type',__('Synchronize in Google Calendar ?'),array('class'=>'form-label'))); ?>

        <div class="form-switch">
            <input type="checkbox" class="form-check-input mt-2" name="synchronize_type" id="switch-shadow" value="google_calender">
            <label class="form-check-label" for="switch-shadow"></label>
        </div>
    </div>
    <?php endif; ?>
</div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>



<script>
   var p_start_date=document.getElementById('start_date').value;
   var p_end_date=document.getElementById('p_end_date').value;
   
   document.getElementById("start_date").setAttribute("min", p_start_date);
   document.getElementById("end_date").setAttribute("min", p_start_date);
   document.getElementById("end_date").setAttribute("max", p_end_date);

    function getdate(start_date)
    {
        document.getElementById("end_date").setAttribute("min", start_date);
    }
   
</script>
<script>
    document.getElementById("create_test").onsubmit = function() {
        var assign_to_value = document.getElementById("assign_to").value;
        if (!assign_to_value) {
            alert("Please assign task members.");
            return false; // Prevent form submission
        }
    };
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
        $('#create_test').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

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
                   //console.log(response); // Log the response to the console
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
<script>
$(document).ready(function () {
            /*Set requirement_id Value */
            
            /*Set assign_to Value*/
            $(document).on('click', '.add_usr', function () {
                var ids = [];
                $(this).toggleClass('selected');
                var crr_id = $(this).attr('data-id');
                $('#usr_txt_' + crr_id).html($('#usr_txt_' + crr_id).html() == 'Add' ? '<?php echo e(__('Added')); ?>' : '<?php echo e(__('Add')); ?>');
                if ($('#usr_icon_' + crr_id).hasClass('ti-plus')) {
                    $('#usr_icon_' + crr_id).removeClass('ti-plus');
                    $('#usr_icon_' + crr_id).addClass('ti-check');
                } else {
                    $('#usr_icon_' + crr_id).removeClass('ti-check');
                    $('#usr_icon_' + crr_id).addClass('ti-plus');
                }
              
                $('.add_usr.selected').each(function () {
                    ids.push($(this).attr('data-id'));
                });
               
                $('input[name="assign_to"]').val(ids);
            });
        })
</script><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/projects/testCreate.blade.php ENDPATH**/ ?>