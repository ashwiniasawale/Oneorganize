{{ Form::model($employee, array('route' => array('employee.salary.update', $employee->id), 'method' => 'POST')) }}
<div class="modal-body">
    <div class="row">
       
        <div class="form-group col-md-12">
            {{ Form::label('salary', __('Monthly Basic Salary'),['class'=>'form-label']) }}
            {{ Form::number('salary',null, array('class' => 'form-control ','maxlength'=>'6','required'=>'required')) }}
        </div>
       
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Save Change')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}
