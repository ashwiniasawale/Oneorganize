<?php echo e(Form::open(array('route' => array('project.review.store',$project_id),'enctype' => 'multipart/form-data'
    
    
    
    
    
    
    
    
    ))); ?>

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
    
    <div class="row">
      
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('review_date', __('Review Date'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::date('review_date', '', array('class' => 'form-control','required'=>'required'))); ?>

        </div>
       
        <div class="form-group col-md-6">
            <?php echo e(Form::label('attended_by', __('attended By'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('attended_by', $users, null,array('class' => 'form-control select','required'=>'required'))); ?>

        </div>
        <div class="form-group  col-md-12">
            <?php echo e(Form::label('artifacts_of_review', __('Artifacts of Review'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::text('artifacts_of_review', '', array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('checklist',__('Checklist'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <div class="form-file mb-3">
                <input type="file" class="form-control" name="checklist" required="">
            </div>
        </div>
        <!-- <div class="form-group  col-md-6">
            <?php echo e(Form::label('review_criteria', __('Review Criteria'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::text('review_criteria', '', array('class' => 'form-control','required'=>'required'))); ?>

        </div> -->
        <div class="form-group  col-md-12">
            <?php echo e(Form::label('requirement', __('Requirement'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::text('requirement', '', array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        <div class="form-group  col-md-12">
            <?php echo e(Form::label('non_conf_list', __('Non-Confirm List'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::text('non_conf_list', '', array('class' => 'form-control','required'=>'required'))); ?>

        </div>

        <div class="form-group  col-md-12">
            <?php echo e(Form::label('improvement_suggestions', __('Improvement Suggestions'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::text('improvement_suggestions', '', array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('risk_identified', __('Risk Identified'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('risk_identified', $users, null,array('class' => 'form-control select','required'=>'required'))); ?>

        </div>
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('problem_discover', __('Problem Discover'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::text('problem_discover', '', array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('deviation_taken', __('Deviation Taken'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::text('deviation_taken', '', array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('is_updated', __('Is Requirement Sheet Updated'),['class'=>'form-label'])); ?>

           <select class="form-control select" name="is_updated" required>
                <option value="">Select</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
           </select>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/project_review/reviewCreate.blade.php ENDPATH**/ ?>