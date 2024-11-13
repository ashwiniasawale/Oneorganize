
{{ Form::open(['url' => route('timesheet.store'), 'id' => 'project_form']) }}
<div class="modal-body">


    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-0">
                <label for="project_name">{{ __('Project Name')}}</label>
                <select class="form-control select " name="project_id" id="project_id" required="">
                  
                    <option value="<?php echo $projects->id; ?>"><?php echo $projects->project_name; ?></option>
                   
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
            <label for="project_name">{{ __('Task Name')}}</label>
           
                <select class="form-control select" name="task_id" id="task_id" required="" onchange="get_task_hours(this.value);">

                    <option value="">{{ __('Select Task') }}</option>
                    <?php foreach($task as $task)
                    { ?>
                    <option value="<?php echo $task->id; ?>"><?php echo $task->name; ?></option>
                    <?php } ?>

                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
            <label for="project_name">{{ __('Task Estimate Hours')}}</label>
                <input class="form-control" name="estimated_hrs" id="estimated_hrs" readonly required="">
                
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
            <label for="project_name">{{ __('Task Actual Hours')}}</label>
            <input type="number" class="form-control" name="actual_hours" id="actual_hours" min='0' maxlength = '8'  required="">
                 
            </div>
        </div>
       
    </div>

    <div class="form-group">
        <label for="description">{{ __('Description')}}</label>
        <textarea class="form-control form-control-light" id="description" rows="3" name="description" required></textarea>
    </div>


</div>


<div class="modal-footer">
    <input type="submit" value="{{ __('Save') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
<script>
    function get_task_hours(task_id)
    {
        var project_id=$('#project_id').val();
       
        $.ajax({  
         type:"POST",  
         url: '{{route('task.estimatehours')}}',
         data:{_token: $('meta[name="csrf-token"]').attr('content'),project_id:project_id,task_id:task_id},  
         success: function (data) 
         {
           if(data.success==true)
           {
           
             $("#estimated_hrs").empty();
             document.getElementById('estimated_hrs').value=data.estimated_hrs;
         
           }
          
          },
          error: function (data) 
          {
                            
          } 
      }); 
    }
</script>

