<?php echo e(Form::open(['url' => 'projects', 'method' => 'post','id'=>'create_project','enctype' => 'multipart/form-data'])); ?>

<div class="modal-body">
    
    <?php
        $plan= \App\Models\Utility::getChatGPTSettings();
    ?>
    <?php if($plan->chatgpt == 1): ?>
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="<?php echo e(route('generate',['project'])); ?>"
           data-bs-placement="top" data-title="<?php echo e(__('Generate content with AI')); ?>">
            <i class="fas fa-robot"></i> <span><?php echo e(__('Generate with AI')); ?></span>
        </a>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('project_name', __('Project Name'), ['class' => 'form-label'])); ?><span class="text-danger">*</span>
                <?php echo e(Form::text('project_name', null, ['class' => 'form-control','required'=>'required'])); ?>

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

                <?php echo e(Form::date('start_date', null, ['class' => 'form-control','id'=>'start_date','onchange'=>'getdate(this.value);'])); ?>

            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('end_date', __('End Date'), ['class' => 'form-label'])); ?>

                <?php echo e(Form::date('end_date', null, ['class' => 'form-control','id'=>'end_date'])); ?>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-12 col-md-12">
            <?php echo e(Form::label('project_image', __('Project Image'), ['class' => 'form-label'])); ?><span class="text-danger">*</span>
            <div class="form-file mb-3">
                <input type="file" class="form-control" name="project_image" required="">
            </div>

        </div>
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('client', __('Client'),['class'=>'form-label'])); ?><span class="text-danger">* </span>
                <?php echo Form::select('client', $clients, null,array('class' => 'form-control','required'=>'required')); ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create client')): ?>
               
                <div class="text-muted text-xs">
                    <?php echo e(__('Please create new clients')); ?> <a href="<?php echo e(route('clients.index')); ?>"><?php echo e(__('here')); ?></a>.
                </div>
           
            <?php endif; ?>
            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('user', __('Project Leader'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
                <?php echo Form::select('user[]', $users, null,array('class' => 'form-control','required'=>'required')); ?>

            </div>
        </div>
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
        <div class="col-sm-12 col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('lifecycle_model', __('Lifecycle Model'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
                <!-- <?php echo Form::select('user[]', $users, null,array('class' => 'form-control','required'=>'required')); ?> -->
                <select name="lifecycle_model" class="form-control" required>
                <option value="" disabled selected> select Lifecycle Model</option>
            <option value="waterfall_model">Waterfall Model</option>
            <option value="iterative_model">Iterative Model</option>
            <option value="v_model">V Model</option>
            <option value="agile_model">Agile Model</option>
            <option value="bigbang_model">BigBang Model</option>

            <!-- Add more options as needed -->
        </select>
            </div>
        </div>
        <div class="form-group col-sm-12 col-md-12">
            <?php echo e(Form::label('customer_requirement', __('Customer Requirement'), ['class' => 'form-label'])); ?><span class="text-danger">*</span>
            <div class="form-file mb-3">
                <input type="file" class="form-control" name="customer_requirement" required="">
            </div>

        </div>
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

                <?php echo e(Form::text('tag', null, ['class' => 'form-control', 'data-toggle' => 'tags'])); ?>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('status', __('Status'), ['class' => 'form-label'])); ?>

                <select name="status" id="status" class="form-control main-element">
                    <?php $__currentLoopData = \App\Models\Project::$project_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($k); ?>"><?php echo e(__($v)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" id="project_submit_button" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>


<script>
   
     $(document).ready(function () {
        $('#create_project').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = new FormData(this);
            // Send AJAX request
            $("#project_submit_button").attr("disabled", true);
          
            $.ajax({
                type: $(this).attr('method'), // Get the HTTP method (POST or GET)
                url: $(this).attr('action'), // Get the form's action attribute value
                data: formData, // Set the form data
                processData: false,  // Important! Don't process the data
                contentType: false,  
                success: function (response) {
                    // Handle the success response from the server
                 console.log(response.error); // Log the response to the console
                 $("#project_submit_button").attr("disabled", false);
                  if(response.success)
                  {
                    show_toastr('Success', response.success, 'success');
                   // console.log(response.tmp_task)
                   
                         $('#commonModal').modal('hide');
                     load_data();
                   
                  }else{
                  
                    show_toastr('Error', response.error, 'error');
                  }
              
                   
                  
                },
                error: function (xhr, status, error) {
                    // Handle errors
                    console.error(error); // Log the error to the console
                }
            });
        });
    });


    function getdate(start_date)
    {
      
        document.getElementById("end_date").setAttribute("min", start_date);
    }
   
</script>
<?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/projects/create.blade.php ENDPATH**/ ?>