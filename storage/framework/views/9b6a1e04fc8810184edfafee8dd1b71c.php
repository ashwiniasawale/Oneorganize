<?php echo e(Form::model($saturationdeduction,array('route' => array('saturationdeduction.update', $saturationdeduction->id), 'method' => 'PUT'))); ?>

    <div class="modal-body">

    <div class="card-body p-0">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo e(Form::label('deduction_option', __('Deduction Options'))); ?><span class="text-danger">*</span>
                    <?php echo e(Form::select('deduction_option',$deduction_options,null, array('class' => 'form-control select','readonly'=>'readonly','required'=>'required'))); ?>

                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo e(Form::label('type', __('Type'), ['class' => 'form-label'])); ?>

                    <?php echo e(Form::select('type', $saturationdeduc, null, ['class' => 'form-control select amount_type', 'required' => 'required'])); ?>

                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo e(Form::label('amount', __('Amount'),['class'=>'form-label amount_label'])); ?>

                    <?php echo e(Form::number('amount',null, array('class' => 'form-control ','required'=>'required','step'=>'0.01'))); ?>

                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn  btn-primary">
    </div>
<?php echo e(Form::close()); ?>

<?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/saturationdeduction/edit.blade.php ENDPATH**/ ?>