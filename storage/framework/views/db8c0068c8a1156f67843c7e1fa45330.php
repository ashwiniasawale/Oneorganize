<?php echo e(Form::open(array('url' => 'bank-account'))); ?>

<div class="modal-body">
   
    <div class="row">
        <div class="form-group col-md-6">
            <?php echo e(Form::label('chart_account_id', __('Account'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            
            <select name="chart_account_id" class="form-control" required="required">
                <?php $__currentLoopData = $chartAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $chartAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>" class="subAccount"><?php echo e($chartAccount); ?></option>
                    <?php $__currentLoopData = $subAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($key == $subAccount['account']): ?>
                            <option value="<?php echo e($subAccount['id']); ?>" class="ms-5"> &nbsp; &nbsp;&nbsp; <?php echo e($subAccount['code_name']); ?></option>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['chart_account_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="error invalid-feedback"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('holder_name', __('Bank Holder Name'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::text('holder_name', '', array('class' => 'form-control','required'=>'required' , 'placeholder'=>__('Enter Bank Holder Name')))); ?>

            <?php $__errorArgs = ['holder_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="error invalid-feedback"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('bank_name', __('Bank Name'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::text('bank_name', '', array('class' => 'form-control','required'=>'required' , 'placeholder' => __('Enter Bank Name')))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('account_number', __('Account Number'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::text('account_number', null, ['class' => 'form-control', 'required' => 'required','minlength' => '16', 'maxlength' => '18','id' => 'phoneInput'])); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('opening_balance', __('Opening Balance'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::number('opening_balance', '', array('class' => 'form-control','step'=>'0.01' , 'placeholder'=>__('Enter Opening Balance')))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('contact_number', __('Contact Number'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            
            
            <?php echo e(Form::number('contact_number',null, ['class' => 'form-control', 'required' => 'required','pattern'=>"\d{10,12}",'placeholder' => __('Enter Contact Number'), 'minlength'=>'10','maxlength' => '12','id' => 'contactNumberInput'])); ?>


            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="error invalid-email text-danger" role="alert">
                <strong><?php echo e($message); ?></strong>
            </span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="form-group col-md-12">
            <?php echo e(Form::label('bank_address', __('Bank Address'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::textarea('bank_address', '', array('class' => 'form-control','rows'=>3 , 'placeholder' => __('Enter Bank Address')))); ?>

        </div>
        <?php if(!$customFields->isEmpty()): ?>
            <div class="col-md-12">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    <?php echo $__env->make('customFields.formBuilder', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
    document.getElementById('contactNumberInput').addEventListener('input', function (e) {
        // Replace any non-digit characters and enforce the max length
        e.target.value = e.target.value.replace(/\D/g, '').slice(0, 12);
    });
</script>
<script>
    document.getElementById('phoneInput').addEventListener('input', function (e) {
        // Replace any non-digit characters and enforce the max length
        e.target.value = e.target.value.replace(/\D/g, '').slice(0, 18);
    });
</script><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/bankAccount/create.blade.php ENDPATH**/ ?>