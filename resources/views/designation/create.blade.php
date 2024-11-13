{{Form::open(array('url'=>'designation','method'=>'post'))}}
<div class="modal-body">

    <div class="row">
        <div class="col-12">

            <div class="form-group ">
                {{ Form::label('branch_id', __('Select Branch*'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::select('branch_id', $branches, null, ['onchange'=>'getDepartment(this.value);','required'=>'required','class' => 'form-control select2', 'placeholder' => 'Select Branch']) }}
                </div>

            </div>

            <div class="form-group ">
                {{ Form::label('department_id', __('Select Department*'), ['class' => 'form-label']) }}
             
                <div class="department_div">
                                            <select class="form-control  department_id" name="department_id"
                                                id="choices-multiple" placeholder="Select Designation">
                                            </select>
                                        </div> 

            </div>
            <div class="form-group">
                {{Form::label('name',__('Name'),['class'=>'form-label'])}}
                {{Form::text('name',null,array('class'=>'form-control','required'=>'required','placeholder'=>__('Enter Designation Name')))}}
                @error('name')
                <span class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>
{{Form::close()}}
<script>


        function getDepartment(bid) {

            $.ajax({
                url: '{{ route('employee.dept_json') }}',
                type: 'POST',
                data: {
                    "branch_id": bid,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {

                    $('.department_id').empty();
                    var emp_selct = ` <select class="form-control  department_id" name="department_id" id="choices-multiple"
                                            placeholder="Select Designation" >
                                            </select>`;
                    $('.department_div').html(emp_selct);
                    $('.department_id').append('<option value=""> {{ __('Select Department') }} </option>');
                    $.each(data, function(key, value) {
                        $('.department_id').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                    new Choices('#choices-multiple', {
                        removeItemButton: true,
                    });


                }
            });
        }
    </script>