
<?php echo e(Form::model($review, array('route' => array('project.review.update', $project_id,$review->id ), 'method' => 'POST','enctype' => 'multipart/form-data'))); ?>

<div class="modal-body">
    
    <?php
                            $user = \App\Models\User::find(\Auth::user()->creatorId());
                    $plan= \App\Models\Plan::getPlan($user->plan);
    ?>
    <?php if($plan->chatgpt == 1): ?>
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="<?php echo e(route('generate',['project Review'])); ?>"
           data-bs-placement="top" data-title="<?php echo e(__('Generate content with AI')); ?>">
            <i class="fas fa-robot"></i> <span><?php echo e(__('Generate with AI')); ?></span>
        </a>
    </div>
    <?php endif; ?>
    
    <div class="row">
      
        
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('review_date', __('Review Date'),['class'=>'form-label'])); ?>

            <?php echo e(Form::date('review_date', null, array('class' => 'form-control','required'=>'required'))); ?>

        </div>
       
        <div class="form-group col-md-6">
            <?php echo e(Form::label('attended_by', __('attended By'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('attended_by', $users, null,array('class' => 'form-control select','required'=>'required'))); ?>

        </div>
        <div class="form-group  col-md-12">
            <?php echo e(Form::label('artifacts_of_review', __('Artifacts of Review'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('artifacts_of_review', null, array('class' => 'form-control','required'=>'required'))); ?>

        </div>
       
        <div class="form-group col-md-6">
            <?php echo e(Form::label('checklist',__('Checklist'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <div class="form-file mb-3">
                <input type="file" class="form-control" name="checklist" >
            </div>
            <img src="<?php echo env('APP_URL'); ?>/storage/<?php echo $review->checklist; ?>" alt="<?php echo $review->checklist; ?>"/>
            <a target="_blank" href="<?php echo env('APP_URL'); ?>/storage/<?php echo $review->checklist; ?>">View File</a>
        </div>
        <!-- <div class="form-group  col-md-6">
            <?php echo e(Form::label('review_criteria', __('Review Criteria'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('review_criteria', null, array('class' => 'form-control','required'=>'required'))); ?>

        </div> -->
        <div class="form-group  col-md-12">
            <?php echo e(Form::label('requirement', __('Requirement'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('requirement', null, array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        <div class="form-group  col-md-12">
            <?php echo e(Form::label('non_conf_list', __('Non-Confirm List'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('non_conf_list', null, array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        <div class="form-group  col-md-12">
            <?php echo e(Form::label('improvement_suggestions', __('Improvement Suggestions'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('improvement_suggestions', null, array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        <div class="form-group  col-md-12">
            <?php echo e(Form::label('risk_identified', __('Risk Identified'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('risk_identified', $users,null, array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('problem_discover', __('Problem Discover'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('problem_discover', null, array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('deviation_taken', __('Deviation Taken'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('deviation_taken', null, array('class' => 'form-control','required'=>'required'))); ?>

        </div>
       
        <div class="form-group col-md-6">
            <?php echo e(Form::label('is_updated', __('Is Requirement Sheet Updated'),['class'=>'form-label'])); ?>

           <select class="form-control select" name="is_updated" required>
                <option value="">Select</option>
                <option <?php if($review->is_updated=='Yes'){ echo 'selected=selected';} ?> value="Yes">Yes</option>
                <option <?php if($review->is_updated=='No'){ echo 'selected=selected';} ?> value="No">No</option>
           </select>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/project_review/reviewEdit.blade.php ENDPATH**/ ?>