<?php echo e(Form::model($project, ['route' => ['projects.update', $project->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data'])); ?>

<div class="modal-body">
    
    <?php
    $plan= \App\Models\Utility::getChatGPTSettings();
    ?>
    <?php if($plan->chatgpt == 1): ?>
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="<?php echo e(route('generate',['projects'])); ?>" data-bs-placement="top" data-title="<?php echo e(__('Generate content with AI')); ?>">
            <i class="fas fa-robot"></i> <span><?php echo e(__('Generate with AI')); ?></span>
        </a>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('project_name', __('Project Name'), ['class' => 'form-label'])); ?><span class="text-danger">*</span>
                <?php echo e(Form::text('project_name', null, ['class' => 'form-control'])); ?>

            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('prj_id', __('Project ID'), ['class' => 'form-label'])); ?><span class="text-danger">*</span>
                <?php echo e(Form::text('prj_id', null, ['class' => 'form-control','required'=>'required'])); ?>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('start_date', __('Start Date'), ['class' => 'form-label'])); ?>

                <?php echo e(Form::date('start_date', null, ['class' => 'form-control'])); ?>

            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('end_date', __('End Date'), ['class' => 'form-label'])); ?>

                <?php echo e(Form::date('end_date', null, ['class' => 'form-control'])); ?>

            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('client', __('Client'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
                <?php echo Form::select('client', $clients, $project->client_id,array('class' => 'form-control select2','id'=>'choices-multiple1','required'=>'required')); ?>

            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('user', __('Project Leader'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
                <select name="user" id="user" class="form-control main-element select2">
                    <?php $__currentLoopData = $project->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($user->is_enable_login=='1')
                    { ?>
                    <option value="<?php echo e($user->id); ?>" <?php echo e(($project->manager_id == $user->id) ? 'selected' : ''); ?>><?php echo e($user->name); ?></option>
                  <?php } ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('budget', __('Budget'), ['class' => 'form-label'])); ?>

                <?php echo e(Form::number('budget', null, ['class' => 'form-control'])); ?>

            </div>
        </div>
        <div class="col-6 col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('estimated_hrs', __('Estimated Hours'),['class' => 'form-label'])); ?>

                <?php echo e(Form::number('estimated_hrs', null, ['class' => 'form-control','min'=>'0','maxlength' => '8'])); ?>

            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-12">
        <div class="form-group">
            <?php echo e(Form::label('lifecycle_model', __('Lifecycle Model'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
           
            <select name="lifecycle_model" class="form-control" required>
                <option value="" disabled selected> select Lifecycle Model</option>
                <option <?php if($project->lifecycle_model=='waterfall_model'){ echo 'selected=selected'; } ?> value="waterfall_model">Waterfall Model</option>
                <option <?php if($project->lifecycle_model=='iterative_model'){ echo 'selected=selected'; } ?> value="iterative_model">Iterative Model</option>
                <option <?php if($project->lifecycle_model=='v_model'){ echo 'selected=selected'; } ?> value="v_model">V Model</option>
                <option <?php if($project->lifecycle_model=='agile_model'){ echo 'selected=selected'; } ?> value="agile_model">Agile Model</option>
                <option <?php if($project->lifecycle_model=='bigbang_model'){ echo 'selected=selected'; } ?> value="bigbang_model">BigBang Model</option>

                <!-- Add more options as needed -->
            </select>
        </div>
    </div>
    <div class="form-group col-sm-12 col-md-12">
        <?php echo e(Form::label('customer_requirement', __('Customer Requirement'), ['class' => 'form-label'])); ?><span class="text-danger">*</span>
        <div class="form-file mb-3">
            <input type="file" class="form-control" name="customer_requirement">
        </div>
       
        <a href="<?php echo env('APP_URL'); ?>/storage/<?php echo $project->customer_requirement; ?>" target="_blank">Open file</a>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('description', __('Description'), ['class' => 'form-label'])); ?>

                <?php echo e(Form::textarea('description', null, ['class' => 'form-control', 'rows' => '4', 'cols' => '50'])); ?>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('tag', __('Tag'), ['class' => 'form-label'])); ?>

                <?php echo e(Form::text('tag', isset($project->tags) ? $project->tags: '', ['class' => 'form-control', 'data-toggle' => 'tags'])); ?>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('status', __('Status'), ['class' => 'form-label'])); ?>

                <select name="status" id="status" class="form-control main-element select2">
                    <?php $__currentLoopData = \App\Models\Project::$project_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($k); ?>" <?php echo e(($project->status == $k) ? 'selected' : ''); ?>><?php echo e(__($v)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <?php echo e(Form::label('project_image', __('Project Image'), ['class' => 'form-label'])); ?><span class="text-danger">*</span>
            <div class="form-file mb-3">
                <input type="file" class="form-control" name="project_image">
            </div>
            <img <?php echo e($project->img_image); ?> class="avatar avatar-xl" alt="">
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/projects/edit.blade.php ENDPATH**/ ?>