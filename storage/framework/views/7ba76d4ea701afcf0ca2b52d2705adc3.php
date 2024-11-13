<?php echo e(Form::model($lead, array('route' => array('leads.update', $lead->id), 'method' => 'PUT'))); ?>

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
            <?php echo e(Form::label('subject', __('Subject'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::text('subject', null, array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        <div class="col-6 form-group">
            <?php echo e(Form::label('user_id', __('Created By'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::select('user_id', $users,null, array('class' => 'form-control select','required'=>'required'))); ?>

        </div>
        <div class="col-6 form-group">
            <?php echo e(Form::label('name', __('Client Name'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::text('name', null, array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        <div class="col-6 form-group">
            <?php echo e(Form::label('email', __('Client Email'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::email('email', null, array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        <div class="col-6 form-group">
            <?php echo e(Form::label('phone', __('Client Phone Number'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::text('phone',null, ['class' => 'form-control', 'required' => 'required','pattern'=>"\d{10,12}",'placeholder' => __('Enter Employee Phone number'), 'minlength'=>'10','maxlength' => '12','id' => 'phoneInput'])); ?>

        </div>
          <div class="col-6 form-group">
            <?php echo e(Form::label('Location', __('Client Location'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('location', null, array('class' => 'form-control','required'=>'required' , 'placeholder' => __(' Enter Location')))); ?>

        </div>
        <div class="col-4 form-group">
           
            <?php echo e(Form::label('country', __('Country'), ['class'=>'form-label'])); ?>

           
            <select name="country" id="country" class="form-control select" onchange="get_state();" required>
            <option value="">Select Country</option>
            <?php foreach($country as $country)
            {
                if($country->country_id==$selected_country)
                {
                    $country_select='selected=selected';
                }else{
                    $country_select='';
                }
              ?>
             <option <?php echo $country_select; ?> value="<?php echo $country->country_id; ?>"><?php echo $country->country_name; ?></option>
            <?php } ?>
        </select>
        </div>
      <div class="col-4 form-group">
            <?php echo e(Form::label('state', __('State'), ['class'=>'form-label'])); ?>

            <select name="state" id="state" class="form-control select"  required>
            <?php foreach($state as $state)
            {
                if($state->state_id==$selected_state)
                {
                    $state_select='selected=selected';
                }else{
                    $state_select='';
                }
              ?>
             <option <?php echo $state_select; ?> value="<?php echo $state->state_id; ?>"><?php echo $state->state_name; ?></option>
            <?php } ?>
        </select>
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

         
            <?php echo e(Form::select('responsible_person', $users,null, array('class' => 'form-control select','required'=>'required'))); ?>

        </div> 
        <div class="col-6 form-group">
            <?php echo e(Form::label('pipeline_id', __('Pipeline'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::select('pipeline_id', $pipelines,null, array('class' => 'form-control select','required'=>'required'))); ?>

        </div>
        <div class="col-6 form-group">
            <?php echo e(Form::label('stage_id', __('Stage'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::select('stage_id', [''=>__('Select Stage')],null, array('class' => 'form-control select','required'=>'required'))); ?>

        </div>
        <div class="col-12 form-group">
            <?php echo e(Form::label('sources', __('Sources'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::select('sources[]', $sources,null, array('class' => 'form-control select2','id'=>'choices-multiple2','multiple'=>''))); ?>

        </div>
        <div class="col-12 form-group">
            <?php echo e(Form::label('products', __('Products'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo e(Form::select('products[]', $products,null, array('class' => 'form-control select2','id'=>'choices-multiple1','multiple'=>''))); ?>

        </div>
        <div class="col-12 form-group">
            <?php echo e(Form::label('notes', __('Notes'),['class'=>'form-label'])); ?>

            <?php echo e(Form::textarea('notes',null, array('class' => 'summernote-simple'))); ?>

        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn  btn-primary">
</div>

<?php echo e(Form::close()); ?>

<script>
    document.getElementById('phoneInput').addEventListener('input', function (e) {
        // Replace any non-digit characters
        e.target.value = e.target.value.replace(/\D/g, '');
    });
    </script>

<script>
    function get_state()
    {
        var country_id = $("#country").val();  
     
      $.ajax({  
         type:"POST",  
         url: '<?php echo e(route('leads.get_state')); ?>',
         data:{_token: $('meta[name="csrf-token"]').attr('content'),country_id:country_id},  
         success: function (data) 
         {
           
            $("#state").empty();
            $("#state").html(data);
           // alert(data)
          },
          error: function (data) 
          {
                            
          } 
      }); 
    }
</script>

<script>
    var stage_id = '<?php echo e($lead->stage_id); ?>';

    $(document).ready(function () {
        var pipeline_id = $('[name=pipeline_id]').val();
        getStages(pipeline_id);
    });

    $(document).on("change", "#commonModal select[name=pipeline_id]", function () {
        var currVal = $(this).val();
        console.log('current val ', currVal);
        getStages(currVal);
    });

    function getStages(id) {
        $.ajax({
            url: '<?php echo e(route('leads.json')); ?>',
            data: {pipeline_id: id, _token: $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            success: function (data) {
                var stage_cnt = Object.keys(data).length;
                $("#stage_id").empty();
                if (stage_cnt > 0) {
                    $.each(data, function (key, data1) {
                        var select = '';
                        if (key == '<?php echo e($lead->stage_id); ?>') {
                            select = 'selected';
                        }
                        $("#stage_id").append('<option value="' + key + '" ' + select + '>' + data1 + '</option>');
                    });
                }
                $("#stage_id").val(stage_id);
                $('#stage_id').select2({
                    placeholder: "<?php echo e(__('Select Stage')); ?>"
                });
            }
        })
    }
</script>
<?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/leads/edit.blade.php ENDPATH**/ ?>