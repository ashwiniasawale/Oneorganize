{{ Form::model($project, ['route' => ['projects.update', $project->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    {{-- start for ai module--}}
    @php
    $plan= \App\Models\Utility::getChatGPTSettings();
    @endphp
    @if($plan->chatgpt == 1)
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="{{ route('generate',['projects']) }}" data-bs-placement="top" data-title="{{ __('Generate content with AI') }}">
            <i class="fas fa-robot"></i> <span>{{__('Generate with AI')}}</span>
        </a>
    </div>
    @endif
    {{-- end for ai module--}}
    <div class="row">
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                {{ Form::label('project_name', __('Project Name'), ['class' => 'form-label']) }}<span class="text-danger">*</span>
                {{ Form::text('project_name', null, ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                {{ Form::label('prj_id', __('Project ID'), ['class' => 'form-label']) }}<span class="text-danger">*</span>
                {{ Form::text('prj_id', null, ['class' => 'form-control','required'=>'required']) }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
                {{ Form::date('start_date', null, ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
                {{ Form::date('end_date', null, ['class' => 'form-control']) }}
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                {{ Form::label('client', __('Client'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                {!! Form::select('client', $clients, $project->client_id,array('class' => 'form-control select2','id'=>'choices-multiple1','required'=>'required')) !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                {{ Form::label('user', __('Project Leader'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                <select name="user" id="user" class="form-control main-element select2">
                    @foreach($project->users as $user)
                    <?php if($user->is_enable_login=='1')
                    { ?>
                    <option value="{{$user->id}}" {{ ($project->manager_id == $user->id) ? 'selected' : ''}}>{{$user->name}}</option>
                  <?php } ?>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                {{ Form::label('budget', __('Budget'), ['class' => 'form-label']) }}
                {{ Form::number('budget', null, ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-6 col-md-6">
            <div class="form-group">
                {{ Form::label('estimated_hrs', __('Estimated Hours'),['class' => 'form-label']) }}
                {{ Form::number('estimated_hrs', null, ['class' => 'form-control','min'=>'0','maxlength' => '8']) }}
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-12">
        <div class="form-group">
            {{ Form::label('lifecycle_model', __('Lifecycle Model'),['class'=>'form-label']) }}<span class="text-danger">*</span>
           
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
        {{ Form::label('customer_requirement', __('Customer Requirement'), ['class' => 'form-label']) }}<span class="text-danger">*</span>
        <div class="form-file mb-3">
            <input type="file" class="form-control" name="customer_requirement">
        </div>
       
        <a href="<?php echo env('APP_URL'); ?>/storage/<?php echo $project->customer_requirement; ?>" target="_blank">Open file</a>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
                {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => '4', 'cols' => '50']) }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="form-group">
                {{ Form::label('tag', __('Tag'), ['class' => 'form-label']) }}
                {{ Form::text('tag', isset($project->tags) ? $project->tags: '', ['class' => 'form-control', 'data-toggle' => 'tags']) }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="form-group">
                {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
                <select name="status" id="status" class="form-control main-element select2">
                    @foreach(\App\Models\Project::$project_status as $k => $v)
                    <option value="{{$k}}" {{ ($project->status == $k) ? 'selected' : ''}}>{{__($v)}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            {{ Form::label('project_image', __('Project Image'), ['class' => 'form-label']) }}<span class="text-danger">*</span>
            <div class="form-file mb-3">
                <input type="file" class="form-control" name="project_image">
            </div>
            <img {{$project->img_image}} class="avatar avatar-xl" alt="">
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>
{{Form::close()}}