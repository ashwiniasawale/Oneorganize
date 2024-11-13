<?php echo e(Form::open(array('route' => array('project.risk.store',$project_id)))); ?>

<div class="modal-body overflow-scroll">
    
    <?php
    $user = \App\Models\User::find(\Auth::user()->creatorId());
    $plan= \App\Models\Plan::getPlan($user->plan);
    ?>

    
    <div class="row ">
        <div class="form-group col-md-6">
            <?php echo e(Form::label('risk_details', __('Risk Details'),['class'=>'form-label'])); ?>

            <?php echo Form::textarea('risk_details', null, ['class'=>'form-control','rows'=>'2','required'=>'required']); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('priority', __('Priority'),['class'=>'form-label'])); ?><span class="text-danger">*</span>

            <select name="priority" class="form-control" required>
                <option value="" disabled selected> select</option>
                <option value="low">Low</option>
                <option value="high">High</option>
                <option value="medium">Medium</option>


                <!-- Add more options as needed -->
            </select>

        </div>
        <div class="form-group col-md-6 col-sm-6">
            <?php echo e(Form::label('identified_on', __('Identified On'),['class'=>'form-label'])); ?>

            <?php echo Form::date('identified_on', null, ['class'=>'form-control','rows'=>'2']); ?>

        </div>

        <div class="form-group col-md-6 col-sm-6">
            <?php echo e(Form::label('mitigation_target_date', __('Mitigation Target Date'),['class'=>'form-label'])); ?>

            <?php echo Form::date('mitigation_target_date', null, ['class'=>'form-control','rows'=>'2']); ?>

        </div>

        <div class="form-group col-md-6">
            <?php echo e(Form::label('responsible_person', __('Responsible Person'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo Form::select('responsible_person', $users, null,array('class' => 'form-control','required'=>'required')); ?>


        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label(' risk_classification', __(' Risk classification '),['class'=>'form-label'])); ?>

            <select name="risk_classification" class="form-control" required>
                <option value="" disabled selected> select</option>
                <option value="external_risks">External Risks</option>
                <option value="technological_risks">Technological Risks</option>
                <option value="stakeholder_risks">Stakeholder Risks</option>
                <option value="regulatory_risks">Regulatory Risks</option>
                <option value="project_execution_risks">Project Execution Risks</option>
                <option value="legal_risks">Legal Risks</option>
                <option value="release_risks">Release Risks</option>
                <option value="reputation_risks">Reputation Risks</option>


                <!-- Add more options as needed -->
            </select>
        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('risk_description', __('Risk Description'),['class'=>'form-label'])); ?>

            <?php echo Form::textarea('risk_description', null, ['class'=>'form-control','rows'=>'2']); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('risk_impact', __('Risk Impact'),['class'=>'form-label'])); ?>


            <select name="risk_impact" id="risk_impact" class="form-control select" onchange="calculate_score();" required>
                <option value="" disabled selected> select</option>
                <?php $i = 1;
                foreach ($risk_impact as $option) :
                ?>
                    <option value="<?php echo $i; ?>"> <?php echo $option; ?></option>
                <?php $i++;
                endforeach; ?>
            </select>
        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('risk_severity', __('Risk severity'),['class'=>'form-label'])); ?>

            <select name="risk_severity" id="risk_severity" class="form-control" required onchange="calculate_score();">
                <option value="" disabled selected> select</option>
                <?php $j = 1;
                foreach ($risk_severity as $option1) :
                ?>
                    <option value="<?php echo $j; ?>"> <?php echo $option1; ?></option>
                <?php $j++;
                endforeach; ?>
                <!-- Add more options as needed -->
            </select>
        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('risk_probability', __('Risk Probability'),['class'=>'form-label'])); ?>

            <select name="risk_probability" id="risk_probability" class="form-control" required onchange="calculate_score();">
                <option value="" disabled selected> select</option>
                <?php
                foreach ($risk_probability as $key => $value) :
                ?>
                    <option value="<?php echo $key; ?>"> <?php echo $value; ?></option>
                <?php endforeach; ?>

                <!-- Add more options as needed -->
            </select>
        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('status', __('Status'),['class'=>'form-label'])); ?>

            <select name="status" class="form-control" required>
                <option value="" disabled selected> select</option>
                <option value="accept">Accept</option>
                <option value="reject">Reject</option>
                <option value="modifed">Modified</option>
                <option value="resolved">Resolved</option>
                <option value="inprocess">Inprocess</option>

                <!-- Add more options as needed -->
            </select>
        </div>
        <div class="form-group col-md-6 col-sm-6">
            <?php echo e(Form::label('risk_score', __('Risk Score'),['class'=>'form-label'])); ?>

            <?php echo Form::text('risk_score', null, ['class'=>'form-control','rows'=>'2','readonly'=>'readonly','id'=>'risk_score']); ?>

        </div>
        <div class="form-group col-md-6 col-sm-6">
            <?php echo e(Form::label('risk_consequence', __('Risk Consequence'),['class'=>'form-label'])); ?>

            <?php echo Form::textarea('risk_consequence', null, ['class'=>'form-control','rows'=>'2']); ?>

        </div>
        
    </div>
    <hr />
    <h4>Mitigation /Recovery Plan</h4>
    <div class="row">
        <div class="form-group col-md-6">


            <?php echo e(Form::label('mitigation_person', __('Mitigation Person'),['class'=>'form-label'])); ?><span class="text-danger">*</span>
            <?php echo Form::select('mitigation_person', $users, null,array('class' => 'form-control','required'=>'required')); ?>



        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('critical_dependency ', __('Critical Dependency '),['class'=>'form-label'])); ?>

            <?php echo Form::textarea('critical_dependency', null, ['class'=>'form-control','rows'=>'2','required'=>'required']); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('require_resource_for_mitigation', __('Require Resource for Mitigation'),['class'=>'form-label'])); ?>

            <?php echo Form::textarea('mitigation_resource', null, ['class'=>'form-control','rows'=>'2','required'=>'required']); ?>

        </div>
        <div class="form-group col-md-6 col-sm-6">
            <?php echo e(Form::label('financial_impact', __('Financial Impact'),['class'=>'form-label'])); ?>

            <?php echo Form::textarea('financial_impact', null, ['class'=>'form-control','rows'=>'2','required'=>'required']); ?>

        </div>
        <div class="form-group col-md-6 col-sm-6">
            <?php echo e(Form::label('timeline_impact', __('Timeline Impact'),['class'=>'form-label'])); ?>

            <?php echo Form::textarea('timeline_impact', null, ['class'=>'form-control','rows'=>'2','required'=>'required']); ?>

        </div>
        <div class="form-group col-md-6 col-sm-6">
            <?php echo e(Form::label('action_item', __('Action Item'),['class'=>'form-label'])); ?>

            <?php echo Form::textarea('action_item', null, ['class'=>'form-control','rows'=>'2','required'=>'required']); ?>

        </div>
        <div class="form-group col-md-6 col-sm-6">
            <?php echo e(Form::label('action_taken', __('Action Taken'),['class'=>'form-label'])); ?>

            <?php echo Form::textarea('action_taken', null, ['class'=>'form-control','rows'=>'2','required'=>'required']); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('changes_in_project_plan', __('Changes in Project plan'),['class'=>'form-label'])); ?>

            <?php echo Form::textarea('changes_in_project_plan', null, ['class'=>'form-control','rows'=>'2']); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('assumptions_made', __('Assumptions made'),['class'=>'form-label'])); ?>

            <?php echo Form::textarea('assumptions_made', null, ['class'=>'form-control','rows'=>'2','required'=>'required']); ?>

        </div>
    </div>


</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>


<script>
    function calculate_score() {
        var risk_impact = $('#risk_impact').val() ?? 0;
        var risk_severity = $('#risk_severity').val() ?? 0;
        var risk_probability = $('#risk_probability').val() ?? 0;
        var risk_score=risk_impact*risk_severity*risk_probability;
        $('#risk_score').val(risk_score);
    }
</script><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/projects/riskCreate.blade.php ENDPATH**/ ?>