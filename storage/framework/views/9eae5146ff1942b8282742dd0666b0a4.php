<?php echo e(Form::model($employee, array('route' => array('employee.salary.update', $employee->id), 'method' => 'POST'))); ?>

<div class="modal-body">
    <div class="row">
       
        <div class="form-group col-md-12">
            <?php echo e(Form::label('salary', __('Monthly Basic Salary'),['class'=>'form-label'])); ?>

            <?php echo e(Form::number('salary',null, array('class' => 'form-control ','maxlength'=>'6','required'=>'required'))); ?>

        </div>
       
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Save Change')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>

<?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/setsalary/basic_salary.blade.php ENDPATH**/ ?>