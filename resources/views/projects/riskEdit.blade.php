{{ Form::open(array('route' => array('project.risk.update',$project_id,$risk->id),'method'=>'POST')) }}
<div class="modal-body overflow-scroll">
    {{-- start for ai module--}}
    @php
    $user = \App\Models\User::find(\Auth::user()->creatorId());
    $plan= \App\Models\Plan::getPlan($user->plan);
    @endphp

    {{-- end for ai module--}}
    <div class="row ">
        <div class="form-group col-md-6">
            {{ Form::label('risk_details', __('Risk Details'),['class'=>'form-label']) }}
            {!! Form::textarea('risk_details', $risk->risk_details, ['class'=>'form-control','rows'=>'2','required'=>'required']) !!}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('priority', __('Priority'),['class'=>'form-label']) }}<span class="text-danger">*</span>

            <select name="priority" class="form-control" required>
                <option value="" disabled selected> select</option>
                <option <?php if ($risk->priority == 'low') {
                            echo 'selected=selected';
                        } ?> value="low">Low</option>
                <option <?php if ($risk->priority == 'high') {
                            echo 'selected=selected';
                        } ?> value="high">High</option>
                <option <?php if ($risk->priority == 'medium') {
                            echo 'selected=selected';
                        } ?> value="medium">Medium</option>


                <!-- Add more options as needed -->
            </select>

        </div>
        <div class="form-group col-md-6 col-sm-6">
            {{ Form::label('identified_on', __('Identified On'),['class'=>'form-label']) }}
            {!! Form::date('identified_on', $risk->identified_on, ['class'=>'form-control','rows'=>'2']) !!}
        </div>

        <div class="form-group col-md-6 col-sm-6">
            {{ Form::label('mitigation_target_date', __('Mitigation Target Date'),['class'=>'form-label']) }}
            {!! Form::date('mitigation_target_date', $risk->mitigation_target_date, ['class'=>'form-control','rows'=>'2']) !!}
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('responsible_person', __('Responsible Person'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {!! Form::select('responsible_person', $users, null,array('class' => 'form-control','required'=>'required')) !!}

        </div>
        <div class="form-group col-md-6">
            {{ Form::label(' risk_classification', __(' Risk classification '),['class'=>'form-label']) }}
            <select name="risk_classification" class="form-control" required>
                <option value="" disabled selected> select</option>
                <option <?php if ($risk->risk_classification == 'external_risks') {
                            echo 'selected=selected';
                        } ?> value="external_risks">External Risks</option>
                <option <?php if ($risk->risk_classification == 'technological_risks') {
                            echo 'selected=selected';
                        } ?> value="technological_risks">Technological Risks</option>
                <option <?php if ($risk->risk_classification == 'stakeholder_risks') {
                            echo 'selected=selected';
                        } ?> value="stakeholder_risks">Stakeholder Risks</option>
                <option <?php if ($risk->risk_classification == 'regulatory_risks') {
                            echo 'selected=selected';
                        } ?>value="regulatory_risks">Regulatory Risks</option>
                <option <?php if ($risk->risk_classification == 'project_execution_risks') {
                            echo 'selected=selected';
                        } ?> value="project_execution_risks">Project Execution Risks</option>
                <option <?php if ($risk->risk_classification == 'legal_risks') {
                            echo 'selected=selected';
                        } ?> value="legal_risks">Legal Risks</option>
                <option <?php if ($risk->risk_classification == 'release_risks') {
                            echo 'selected=selected';
                        } ?> value="release_risks">Release Risks</option>
                <option <?php if ($risk->risk_classification == 'reputation_risks') {
                            echo 'selected=selected';
                        } ?> value="reputation_risks">Reputation Risks</option>


                <!-- Add more options as needed -->
            </select>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('risk_description', __('Risk Description'),['class'=>'form-label']) }}
            {!! Form::textarea('risk_description',$risk->risk_description, ['class'=>'form-control','rows'=>'2']) !!}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('risk_impact', __('Risk Impact'),['class'=>'form-label']) }}
            <select name="risk_impact" id="risk_impact" class="form-control select" onchange="calculate_score();" required>
                <option value="" disabled selected> select</option>
                <?php $i = 1;
                foreach ($risk_impact as $option) :
                    if($i==$risk->risk_impact)
                    {
                        $select_imapact='selected=selected';
                    }else{
                        $select_imapact='';
                    }
                ?>
                    <option <?php echo $select_imapact; ?> value="<?php echo $i; ?>"> <?php echo $option; ?></option>
                <?php $i++;
                endforeach; ?>
            </select>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('risk_severity', __('Risk severity'),['class'=>'form-label']) }}
            <select name="risk_severity" id="risk_severity" class="form-control" required onchange="calculate_score();">
                <option value="" disabled selected> select</option>
                <?php $j = 1;
                foreach ($risk_severity as $option1) :
                    if($j==$risk->risk_severity)
                    {
                        $select_severity='selected=selected';
                    }else{
                        $select_severity='';
                    }
                ?>
                    <option <?php echo $select_severity; ?> value="<?php echo $j; ?>"> <?php echo $option1; ?></option>
                <?php $j++;
                endforeach; ?>
                <!-- Add more options as needed -->
            </select>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('risk_probability', __('Risk Probability'),['class'=>'form-label']) }}
            <select name="risk_probability" id="risk_probability" class="form-control" required onchange="calculate_score();">
                <option value="" disabled selected> select</option>
                <?php
                foreach ($risk_probability as $key => $value) :
                    if($key==$risk->risk_probability)
                    {
                        $select_probability='selected=selected';
                    }else{
                        $select_probability='';
                    }
                ?>
                    <option <?php echo $select_probability; ?> value="<?php echo $key; ?>"> <?php echo $value; ?></option>
                <?php endforeach; ?>

                <!-- Add more options as needed -->
            </select>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('status', __('Status'),['class'=>'form-label']) }}
            <select name="status" class="form-control" required>
                <option value="" disabled selected> select</option>
                <option <?php if ($risk->status == 'accept') {
                            echo 'selected=selected';
                        } ?> value="accept">Accept</option>
                <option <?php if ($risk->status == 'reject') {
                            echo 'selected=selected';
                        } ?> value="reject">Reject</option>
                <option <?php if ($risk->status == 'modifed') {
                            echo 'selected=selected';
                        } ?> value="modifed">Modified</option>
                <option <?php if ($risk->status == 'resolved') {
                            echo 'selected=selected';
                        } ?> value="resolved">Resolved</option>
                <option <?php if ($risk->status == 'inprocess') {
                            echo 'selected=selected';
                        } ?> value="inprocess">Inprocess</option>

                <!-- Add more options as needed -->
            </select>
        </div>
        <div class="form-group col-md-6 col-sm-6">
            {{ Form::label('risk_score', __('Risk Score'),['class'=>'form-label']) }}
            {!! Form::text('risk_score', $risk->risk_score, ['class'=>'form-control','rows'=>'2','readonly'=>'readonly','id'=>'risk_score']) !!}
        </div>
        <div class="form-group col-md-6 col-sm-6">
            {{ Form::label('risk_consequence', __('Risk Consequence'),['class'=>'form-label']) }}
            {!! Form::textarea('risk_consequence', $risk->risk_consequence, ['class'=>'form-control','rows'=>'2']) !!}
        </div>
        
    </div>
    <hr />
    <h4>Mitigation /Recovery Plan</h4>
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('mitigation_person', __('Mitigation Person'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {!! Form::select('mitigation_person', $users, null,array('class' => 'form-control','required'=>'required')) !!}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('critical_dependency ', __('Critical Dependency '),['class'=>'form-label']) }}
            {!! Form::textarea('critical_dependency', $risk->critical_dependency, ['class'=>'form-control','rows'=>'2','required'=>'required']) !!}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('require_resource_for_mitigation', __('Require Resource for Mitigation'),['class'=>'form-label']) }}
            {!! Form::textarea('mitigation_resource', $risk->mitigation_resource, ['class'=>'form-control','rows'=>'2','required'=>'required']) !!}
        </div>
        <div class="form-group col-md-6 col-sm-6">
            {{ Form::label('financial_impact', __('Financial Impact'),['class'=>'form-label']) }}
            {!! Form::textarea('financial_impact', $risk->financial_impact, ['class'=>'form-control','rows'=>'2','required'=>'required']) !!}
        </div>
        <div class="form-group col-md-6 col-sm-6">
            {{ Form::label('timeline_impact', __('Timeline Impact'),['class'=>'form-label']) }}
            {!! Form::textarea('timeline_impact', $risk->timeline_impact, ['class'=>'form-control','rows'=>'2','required'=>'required']) !!}
        </div>
        <div class="form-group col-md-6 col-sm-6">
            {{ Form::label('action_item', __('Action Item'),['class'=>'form-label']) }}
            {!! Form::textarea('action_item', $risk->action_item, ['class'=>'form-control','rows'=>'2','required'=>'required']) !!}
        </div>
        <div class="form-group col-md-6 col-sm-6">
            {{ Form::label('action_taken', __('Action Taken'),['class'=>'form-label']) }}
            {!! Form::textarea('action_taken', $risk->action_taken, ['class'=>'form-control','rows'=>'2','required'=>'required']) !!}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('changes_in_project_plan', __('Changes in Project plan'),['class'=>'form-label']) }}
            {!! Form::textarea('changes_in_project_plan', $risk->changes_in_project_plan, ['class'=>'form-control','rows'=>'2']) !!}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('assumptions_made', __('Assumptions made'),['class'=>'form-label']) }}
            {!! Form::textarea('assumptions_made', $risk->assumptions_made, ['class'=>'form-control','rows'=>'2','required'=>'required']) !!}
        </div>
    </div>


</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>
{{Form::close()}}
<script>
    function calculate_score() {
        var risk_impact = $('#risk_impact').val() ?? 0;
        var risk_severity = $('#risk_severity').val() ?? 0;
        var risk_probability = $('#risk_probability').val() ?? 0;
        var risk_score=risk_impact*risk_severity*risk_probability;
        $('#risk_score').val(risk_score);
    }
</script>