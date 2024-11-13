<?php echo e(Form::open(array('url' => 'leads'))); ?>

<div class="modal-body">
    
    <?php
        $plan= \App\Models\Utility::getChatGPTSettings();
    ?>
    <?php if($plan->chatgpt == 1): ?>
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="<?php echo e(route('generate',['lead'])); ?>"
           data-bs-placement="top" data-title="<?php echo e(__('Generate content with AI')); ?>">
            <i class="fas fa-robot"></i> <span><?php echo e(__('Generate with AI')); ?></span>
        </a>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-6 form-group">
            <?php echo e(Form::label('subject', __('Subject'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('subject', null, array('class' => 'form-control','required'=>'required' , 'placeholder'=>__('Enter Subject')))); ?>

        </div>
        <div class="col-6 form-group">
            <?php echo e(Form::label('user_id', __('Created By'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('user_id', $users,null, array('class' => 'form-control select','required'=>'required'))); ?>

            <?php if(count($users) == 1): ?>
                <div class="text-muted text-xs">
                    <?php echo e(_('Please create new users')); ?> <a href="<?php echo e(route('users.index')); ?>"><?php echo e(_('here')); ?></a>.
                </div>
            <?php endif; ?>
        </div>
        <div class="col-6 form-group">
            <?php echo e(Form::label('name', __(' Client Name'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('name', null, array('class' => 'form-control','required'=>'required' , 'placeholder' => __('Enter client Name')))); ?>

        </div>
        <div class="col-6 form-group">
            <?php echo e(Form::label('email', __(' Client Email'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('email', null, array('class' => 'form-control','required'=>'required' , 'placeholder' => __('Enter client email')))); ?>

        </div>
        <div class="col-6 form-group">
            <?php echo e(Form::label('phone', __('Client Phone Number'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('phone', null, array('class' => 'form-control','required'=>'required' , 'placeholder' => __('Enter Client Phone number')))); ?>

        </div>
        <div class="col-6 form-group">
            <?php echo e(Form::label('Location', __('Client Location'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('location', null, array('class' => 'form-control','required'=>'required' , 'placeholder' => __(' Enter Location')))); ?>

        </div>
        
    <div class="col-4 form-group">
        <?php echo e(Form::label('country', __('Country'), ['class'=>'form-label'])); ?>

        <?php echo e(Form::text('country', null, ['class' => 'form-control', 'required'=>'required', 'placeholder' => __('Select')])); ?>

    </div>
    <div class="col-4 form-group">
        <?php echo e(Form::label('state', __('State'), ['class'=>'form-label'])); ?>

        <?php echo e(Form::text('state', null, ['class' => 'form-control', 'required'=>'required', 'placeholder' => __('Select')])); ?>

    </div>
    <div class="col-4 form-group">
        <?php echo e(Form::label('city', __('City'), ['class'=>'form-label'])); ?>

        <?php echo e(Form::text('city', null, ['class' => 'form-control', 'required'=>'required', 'placeholder' => __('Select')])); ?>

    </div>
    <div class="col-12 form-group">
        <?php echo e(Form::label('requirements', __('Client Requirements'), ['class'=>'form-label'])); ?>

        <?php echo e(Form::textarea('requirements', null, ['class' => 'form-control', 'required'=>'required', 'placeholder' => __('Enter Client Requirements'), 'style' => 'height: 100px;'])); ?>

    </div>
    <div class="col- form-group">
        <?php echo e(Form::label('responsible_person', __('Responsible Person'), ['class'=>'form-label'])); ?>

        <?php echo e(Form::text('responsible_person', null, ['class' => 'form-control', 'placeholder' => __('Enter Responsible Person')])); ?>

    </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>

<?php echo e(Form::close()); ?><?php /**PATH D:\xampp\htdocs\ERP_APP\resources\views/leads/create.blade.php ENDPATH**/ ?>