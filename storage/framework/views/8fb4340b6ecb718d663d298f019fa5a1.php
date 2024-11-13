
<?php echo e(Form::model($communication, array('route' => array('project.communication.update', $project_id,$communication->id ), 'method' => 'POST', 'enctype' => 'multipart/form-data'))); ?>

<div class="modal-body">
    
    <?php
                            $user = \App\Models\User::find(\Auth::user()->creatorId());
                    $plan= \App\Models\Plan::getPlan($user->plan);
    ?>
    <?php if($plan->chatgpt == 1): ?>
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="<?php echo e(route('generate',['project Communication Record'])); ?>"
           data-bs-placement="top" data-title="<?php echo e(__('Generate content with AI')); ?>">
            <i class="fas fa-robot"></i> <span><?php echo e(__('Generate with AI')); ?></span>
        </a>
    </div>
    <?php endif; ?>
    
    <div class="row">
      
        
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('title', __('Title'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('title', null, array('class' => 'form-control','required'=>'required'))); ?>

        </div>
       
        <div class="form-group col-md-6">
            <?php echo e(Form::label('date', __('Date'),['class'=>'form-label'])); ?>

            <?php echo e(Form::date('date', null, array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        <div class="form-group  col-md-12">
            <?php echo e(Form::label('description', __('Description'),['class'=>'form-label'])); ?>

            <?php echo e(Form::textarea('description', null, array('class' => 'form-control','required'=>'required'))); ?>

        </div>
       
        <div class="form-group col-md-12">
            <?php echo e(Form::label('attachment',__('Attachment'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <div class="form-file mb-3">
            <input type="file" class="form-control" name="attachment" >
            </div>
            <a target="_blank" href="<?php echo env('APP_URL'); ?>/storage/<?php echo $communication->attachment; ?>">Open File</a>
        </div>
        
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/project_communication/communiEdit.blade.php ENDPATH**/ ?>