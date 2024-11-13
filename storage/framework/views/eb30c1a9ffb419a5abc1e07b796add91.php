<?php echo e(Form::model($timesheet, ['route' => ['timesheet.update',$timesheet->id], 'method' => 'PUT'])); ?>

<div class="modal-body">

<div class="row">
        <div class="col-md-6">
            <div class="form-group mb-0">
                <label for="project_name"><?php echo e(__('Project Name')); ?></label>
                <select class="form-control select " name="project_id" id="project_id" required="" readonly>
                  
                    <option value="<?php echo $timesheet->project_id; ?>"><?php echo $timesheet->project_name; ?></option>
                   
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
            <label for="project_name"><?php echo e(__('Task Name')); ?></label>
           
                <select class="form-control select" name="task_id" id="task_id" required="" readonly>

                <option value="<?php echo $timesheet->task_id; ?>"><?php echo $timesheet->name; ?></option>
                   

                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
            <label for="project_name"><?php echo e(__('Task Estimate Hours')); ?></label>
                <input class="form-control" name="estimated_hrs" id="estimated_hrs"  value="<?php echo $timesheet->estimated_hrs; ?>" readonly required="">
                
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
            <label for="project_name"><?php echo e(__('Task Actual Hours')); ?></label>
            <input type="number" class="form-control" name="actual_hours" id="actual_hours" value="<?php echo $timesheet->actual_hours; ?>" min='0' maxlength = '8'  required="">
                 
            </div>
        </div>
       
    </div>

    <div class="form-group">
        <label for="description"><?php echo e(__('Description')); ?></label>
        <textarea class="form-control form-control-light" id="description" rows="3" name="description" required><?php echo $timesheet->description; ?></textarea>
    </div>


</div>


<div class="modal-footer">
    <input type="submit" value="<?php echo e(__('Save')); ?>" class="btn btn-primary">
</div>
<?php echo e(Form::close()); ?>

<?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/projects/timesheets/edit.blade.php ENDPATH**/ ?>