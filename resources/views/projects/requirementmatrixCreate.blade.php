{{ Form::open(array('route' => array('project.requirementmatrix.store',$project_id))) }}
<div class="modal-body">
    {{-- start for ai module--}}
    @php
                            $user = \App\Models\User::find(\Auth::user()->creatorId());
                    $plan= \App\Models\Plan::getPlan($user->plan);
    @endphp
    @if($plan->chatgpt == 1)
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="{{ route('generate',['project bug']) }}"
           data-bs-placement="top" data-title="{{ __('Generate content with AI') }}">
            <i class="fas fa-robot"></i> <span>{{__('Generate with AI')}}</span>
        </a>
    </div>
    @endif
    {{-- end for ai module--}}
    <div class="row">
       
        <div class="form-group col-md-12 col-sm-12">
            {{ Form::label('requirement_details', __('Requirement Details'),['class'=>'form-label']) }}
            {!! Form::textarea('requirement_details', null, ['class'=>'form-control','rows'=>'2']) !!}
        </div>
        
        <div class="form-group col-md-6">
         
        
        {{ Form::label('categories', __('Categories'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                
                <select name="categories" class="form-control" required>
                <option value="" disabled selected> select</option>
            <option value="Hardware">Hardware</option>
            <option value="Software">Software</option>
            <option value="Firmware">Firmware</option>
            <option value="Mechanical">Mechanical</option>
            <option value="Testing">Testing</option>
            <option value="Compliance">Compliance</option>
            <option value="Documention">Documention</option>

            <!-- Add more options as needed -->
        </select>
        
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('implementable', __('Implementable'),['class'=>'form-label']) }}
            <select name="implementable" class="form-control" required>
                <option value="" > select</option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
           

            <!-- Add more options as needed -->
        </select>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('testable', __('Testable'),['class'=>'form-label']) }}
            <select name="testable" class="form-control" required>
                <option value="" disabled selected> select</option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
          
            <!-- Add more options as needed -->
        </select>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('implementation_status', __('Implimentation Status'),['class'=>'form-label']) }}
            <select name="implementation_status" class="form-control" required>
                <option value="" disabled selected> select</option>
            <option value="To Do">To Do</option>
            <option value="In progress">In progress</option>
            <option value="Completed">Completed</option>
            

            <!-- Add more options as needed -->
        </select>
        </div>
    </div>
    
    <div class="row">
        <div class="form-group  col-md-12">
            {{ Form::label('testing_status', __('Testing Status'),['class'=>'form-label']) }}
            <select name="testing_status" class="form-control" required>
                <option value="" disabled selected> select</option>
            <option value="To Do">To Do</option>
            <option value="In progress">In progress</option>
            <option value="Completed">Completed</option>
            

            <!-- Add more options as needed -->
        </select>
        </div>
    </div>
   
   
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>
{{Form::close()}}