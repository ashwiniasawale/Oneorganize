
    <?php echo e(Form::model($designation,array('route' => array('designation.update', $designation->id), 'method' => 'PUT'))); ?>

    <div class="modal-body">

        <div class="row">
            <div class="col-12">
            <div class="form-group">
                    <?php echo e(Form::label('branch_id', __('Branch'),['class'=>'form-label'])); ?>

                    <?php echo e(Form::select('branch_id', $branches,$branches_sel->id, array('onchange'=>'getDepartment(this.value);','class' => 'form-control select2','required'=>'required'))); ?>

                </div>
                <div class="form-group">
                    <?php echo e(Form::label('department_id', __('Department'),['class'=>'form-label'])); ?>

                    <div class="department_div">
                    <?php echo e(Form::select('department_id', $departments,null, array('class' => 'form-control select2 department_id','required'=>'required'))); ?>

</div>
                </div>
                <div class="form-group">
                    <?php echo e(Form::label('name',__('Name'),['class'=>'form-label'])); ?>

                    <?php echo e(Form::text('name',null,array('class'=>'form-control','required'=>'required','placeholder'=>__('Enter Department Name')))); ?>

                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger"><?php echo e($message); ?></strong>
                    </span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn  btn-primary">
    </div>
    <?php echo e(Form::close()); ?>


    <script>


function getDepartment(bid) {

    $.ajax({
        url: '<?php echo e(route('employee.dept_json')); ?>',
        type: 'POST',
        data: {
            "branch_id": bid,
            "_token": "<?php echo e(csrf_token()); ?>",
        },
        success: function(data) {

            $('.department_id').empty();
            var emp_selct = ` <select class="form-control  department_id" name="department_id" id="choices-multiple"
                                    placeholder="Select Designation" >
                                    </select>`;
            $('.department_div').html(emp_selct);
            $('.department_id').append('<option value=""> <?php echo e(__('Select Department')); ?> </option>');
            $.each(data, function(key, value) {
                $('.department_id').append('<option value="' + key + '">' + value +
                    '</option>');
            });
            new Choices('#choices-multiple', {
                removeItemButton: true,
            });


        }
    });
}
</script><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/designation/edit.blade.php ENDPATH**/ ?>