<?php echo e(Form::open(array('url' => 'deals'))); ?>

<div class="modal-body">
    
    <?php
        $plan= \App\Models\Utility::getChatGPTSettings();
    ?>
    <?php if($plan->chatgpt == 1): ?>
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="<?php echo e(route('generate',['deal'])); ?>"
           data-bs-placement="top" data-title="<?php echo e(__('Generate content with AI')); ?>">
            <i class="fas fa-robot"></i> <span><?php echo e(__('Generate with AI')); ?></span>
        </a>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>
        <div class="col-6 form-group">
            <?php echo e(Form::label('name', __('Deal Name'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('name', null, array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        <div class="col-6 form-group">
            <?php echo e(Form::label('phone', __('Phone'),['class'=>'form-label'])); ?>

            <?php echo e(Form::number('phone', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Client Phone number'), 'minlength'=>'10', 'maxlength' => '12','id' => 'phoneInput'])); ?>

        </div>
        <div class="col-6 form-group">
            <?php echo e(Form::label('price', __('Price'),['class'=>'form-label'])); ?>

            <?php echo e(Form::number('price', 0, array('class' => 'form-control','min'=>0,'step'=>'any'))); ?>

        </div>
        <div class="col-6 form-group">
            <?php echo e(Form::label('clients', __('Clients'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('clients[]', $clients,null, array('class' => 'form-control select2','multiple'=>'','id'=>'choices-multiple1','required'=>'required'))); ?>

            <?php if(count($clients) <= 0 && Auth::user()->type == 'Owner'): ?>
                <div class="text-muted text-xs">
                    <?php echo e(__('Please create new clients')); ?> <a href="<?php echo e(route('clients.index')); ?>"><?php echo e(__('here')); ?></a>.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>

<script>
    document.getElementById('phoneInput').addEventListener('input', function (e) {
        // Replace any non-digit characters
        e.target.value = e.target.value.replace(/\D/g, '');
    });
    </script>
    <?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/deals/create.blade.php ENDPATH**/ ?>