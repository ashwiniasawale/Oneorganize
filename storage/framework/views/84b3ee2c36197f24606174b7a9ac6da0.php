
<?php echo e(Form::open(array('route' => array('project.milestone.store',$project_id)))); ?>

<div class="modal-body overflow-scroll">
    
    <?php
                            $user = \App\Models\User::find(\Auth::user()->creatorId());
                    $plan= \App\Models\Plan::getPlan($user->plan);
    ?>
    <?php if($plan->chatgpt == 1): ?>
    <div class="text-end">
        <a href="#" data-size="lg" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="<?php echo e(route('generate',['project bug'])); ?>"
           data-bs-placement="top" data-title="<?php echo e(__('Generate content with AI')); ?>">
            <i class="fas fa-robot"></i> <span><?php echo e(__('Generate with AI')); ?></span>
        </a>
    </div>
    <?php endif; ?>
    
    <div class="row ">
        <div class="form-group col-md-12 col-sm-12">
            <?php echo e(Form::label('title', __('Title'),['class'=>'form-label'])); ?>

            <?php echo Form::text('title', null, ['class'=>'form-control','rows'=>'2','required'=>'required']); ?>

        </div>
        <div class="form-group col-md-12">
         
        
        <?php echo e(Form::label('start_date', __('Start Date'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
                
        <?php echo Form::date('start_date', null, ['class'=>'form-control','rows'=>'2']); ?>

        
        </div>
        <div class="form-group col-md-12 col-sm-12">
            <?php echo e(Form::label('resources', __('Resources'),['class'=>'form-label'])); ?>

            <?php echo Form::textarea('resources', null, ['class'=>'form-control','rows'=>'2']); ?>

        </div>
        <div class="form-group col-md-12 col-sm-12">
            <?php echo e(Form::label('deliverables', __('Deliverables'),['class'=>'form-label'])); ?>

            <?php echo Form::textarea('deliverables', null, ['class'=>'form-control','rows'=>'2']); ?>

        </div>
        <div class="form-group  col-md-12">
        <?php echo e(Form::label('status', __('Status'),['class' => 'form-label'])); ?>

        <?php echo Form::select('status',\App\Models\Project::$project_status, null,array('class' => 'form-control select','required'=>'required')); ?>

        <?php $__errorArgs = ['client'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <span class="invalid-client" role="alert">
            <strong class="text-danger"><?php echo e($message); ?></strong>
        </span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
        <div class="form-group col-md-12 col-sm-12">
            <?php echo e(Form::label('notes', __('Notes'),['class'=>'form-label'])); ?>

            <?php echo Form::textarea('notes', null, ['class'=>'form-control','rows'=>'2']); ?>

        </div>
       
       
        
       
    </div>
    
    
   
    </div>
   
   
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/projects/milestoneCreate.blade.php ENDPATH**/ ?>