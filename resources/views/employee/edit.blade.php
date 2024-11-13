@extends('layouts.admin')
@section('page-title')
    {{__('Edit Employee')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('employee.index')}}">{{__('Employee')}}</a></li>
    <li class="breadcrumb-item">{{$employeesId}}</li>
@endsection


@section('content')
    <div class="row">
        <div class="col-12">
            {{ Form::model($employee, array('route' => array('employee.update', $employee->id), 'method' => 'PUT' , 'enctype' => 'multipart/form-data')) }}
            @csrf
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 ">
            <div class="card emp_details">
                <div class="card-header"><h6 class="mb-0">{{__('Personal Detail')}}</h6></div>
                <div class="card-body employee-detail-edit-body">

                    <div class="row">
                    <!-- <div class="form-group col-md-3">
                                    {!! Form::label('salutation', __('Salutation'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                    <select class="form-control" name="salutation" required>
                                        <option value="">-Select Salutation-</option>
                                        <option value="Mr.">Mr</option>
                                        <option value="Miss.">Miss</option>
                                        <option value="Mrs.">Mrs.</option>
                                    </select>
                                    @error('salutation')
                                        <div class="text-danger">{{ $message}}</div>
                                    @enderror
                                </div> -->
                        <div class="form-group col-md-5">
                            {!! Form::label('name', __('Name'),['class'=>'form-label']) !!}<span class="text-danger pl-1">*</span>
                            {!! Form::text('name', null, ['class' => 'form-control','required' => 'required']) !!}
                            @error('name')
        <div class="text-danger">{{ $message}}</div>
    @enderror
                        </div>
                        <div class="form-group col-md-4">
                            {!! Form::label('phone', __('Phone'),['class'=>'form-label']) !!}<span class="text-danger pl-1">*</span>
                            {{-- {!! Form::number('phone',null, ['class' => 'form-control']) !!} --}}
                            {{ Form::text('phone',null, ['class' => 'form-control', 'required' => 'required','pattern'=>"\d{10,12}",'placeholder' => __('Enter Employee Phone number'), 'minlength'=>'10','maxlength' => '12','id' => 'phoneInput']) }}
                            @error('phone')
<div class="text-danger">{{ $message}}</div>
@enderror
                        </div>
                        <div class="form-group col-md-6">

                            {!! Form::label('dob', __('Date of Birth'),['class'=>'form-label']) !!}<span class="text-danger pl-1">*</span>
                            {!! Form::date('dob', null, ['class' => 'form-control']) !!}
                            @error('dob')
        <div class="text-danger">{{ $message}}</div>
    @enderror
                        </div>
                        <div class="form-group col-md-6">
                            {!! Form::label('gender', __('Gender'),['class'=>'form-label']) !!}<span class="text-danger pl-1">*</span>
                            <div class="d-flex radio-check mt-2">
                                <div class="form-check form-check-inline form-group">
                                    <input type="radio" id="g_male" value="Male" name="gender" class="form-check-input" {{($employee->gender == 'Male')?'checked':''}}>
                                    <label class="form-check-label" for="g_male">{{__('Male')}}</label>
                                </div>
                                <div class="form-check form-check-inline form-group">
                                    <input type="radio" id="g_female" value="Female" name="gender" class="form-check-input" {{($employee->gender == 'Female')?'checked':''}}>
                                    <label class="form-check-label" for="g_female">{{__('Female')}}</label>
                                </div>

                            </div>
                        </div> 
                        <?php if (Auth::user()->type == 'Employee')
                        { ?>
                        <div class="form-group col-md-6">
                                    {!! Form::label('email', __('Email'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                
                                    {!! Form::email('email', old('email'), ['class' => 'form-control', 'required' => 'required' ,'readonly'=>'readonly','placeholder'=>'Enter employee email']) !!}
                                    @error('email')
                                        <div class="text-danger">{{ $message}}</div>
                                    @enderror
                                </div>
                                <?php }else{ ?>
                                    <div class="form-group col-md-6">
                                    {!! Form::label('email', __('Email'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                
                                    {!! Form::email('email', old('email'), ['class' => 'form-control', 'required' => 'required' ,'placeholder'=>'Enter employee email']) !!}
                                    @error('email')
                                        <div class="text-danger">{{ $message}}</div>
                                    @enderror
                                </div>
                                <?php } ?>
                    </div>
                    <div class="form-group">
                        {!! Form::label('address', __('Address'),['class'=>'form-label']) !!}<span class="text-danger pl-1">*</span>
                        {!! Form::textarea('address',null, ['class' => 'form-control','rows'=>4,'required' => 'required', 'maxlength' => '255']) !!}
                        <span id="address-counter" class="text-danger " >0</span>/255 characters
                        @error('address')
        <div class="text-danger">{{ $message}}</div>
    @enderror
                    </div>
                   
                </div>
            </div>
        </div>
     
            <div class="col-md-6 ">
                <div class="card emp_details">
                    <div class="card-header"><h6 class="mb-0">{{__('Company Detail')}}</h6></div>
                    <div class="card-body employee-detail-edit-body">
                        <div class="row">
                            @csrf
                            <div class="form-group col-md-12">
                                {!! Form::label('employee_id', __('Employee ID'),['class'=>'form-label']) !!}
                                {!! Form::text('employee_id',$employeesId, ['class' => 'form-control','disabled'=>'disabled']) !!}
                                @error('employee_id')
        <div class="text-danger">{{ $message}}</div>
    @enderror
                            </div>

                            <div class="form-group col-md-6">
                                {{ Form::label('branch_id', __('Branch'),['class'=>'form-label']) }}
                                {{ Form::select('branch_id', $branches,null, array('class' => 'form-control select','required'=>'required','id' => 'branch_id')) }}
                                @error('branch_id')
        <div class="text-danger">{{ $message}}</div>
    @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {{ Form::label('department_id', __('Department'),['class'=>'form-label']) }}
                                {{ Form::select('department_id', $departments,null, array('class' => 'form-control select','required'=>'required','id' => 'department_id')) }}
                                @error('department_id')
        <div class="text-danger">{{ $message}}</div>
    @enderror
                                {{-- <select class=" select form-control " id="department_id" name="department_id"  >
                                    @foreach($departmentData as $key=>$val )
                                        <option value="{{$key}}" {{$key==$employee->department_id?'selected':''}}>{{$val}}</option>
                                    @endforeach
                                </select> --}}

                            </div>
                            <div class="form-group col-md-6">
                                {{ Form::label('designation_id', __('Designation'),['class'=>'form-label']) }}
                                <select class="select form-control " id="designation_id" name="designation_id" ></select>

                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('company_doj', 'Company Date Of Joining',['class'=>'form-label']) !!}
                                {!! Form::date('company_doj', null, ['class' => 'form-control ','required' => 'required']) !!}
                                @error('company_doj')
                                    <div class="text-danger">{{ $message}}</div>
                                @enderror
                            </div>
                            <?php if (Auth::user()->type == 'HR' || Auth::user()->type=='company')
                            { ?>
                            <div class="form-group col-md-6">
                            {!! Form::label('annual_salary', 'Salary (e.g. if 3 LPA then 300000)',['class'=>'form-label']) !!}
                            {!! Form::number('annual_salary',null,['class'=>'form-control','required'=>'required'])!!}
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
       
    </div>
   
        <div class="row">
            <div class="col-md-6 ">
                <div class="card emp_details">
                    <div class="card-header"><h6 class="mb-0">{{__('Document')}}</h6></div>
                    <div class="card-body employee-detail-edit-body">
                        @php
                            $employeedoc = $employee->documents()->pluck('document_value',__('document_id'));
                        @endphp

                        @foreach($documents as $key=>$document)
                            <div class="row">
                                <div class="form-group col-12">
                                    <div class="float-left col-4">
                                        <label for="document" class="float-left pt-1 form-label">{{ $document->name }} @if($document->is_required == 1) <span class="text-danger">*</span> @endif</label>
                                    </div>
                                    <div class="float-right col-4">
                                        <input type="hidden" name="emp_doc_id[{{ $document->id}}]" id="" value="{{$document->id}}">
                                        <div class="choose-file form-group">
                                            <label for="document[{{ $document->id }}]">
                                                <input class="form-control @if(!empty($employeedoc[$document->id])) float-left @endif @error('document') is-invalid @enderror border-0" @if($document->is_required == 1 && empty($employeedoc[$document->id]) ) required @endif name="document[{{ $document->id}}]"  onchange="document.getElementById('{{'blah'.$key}}').src = window.URL.createObjectURL(this.files[0])" type="file"  data-filename="{{ $document->id.'_filename'}}">
                                            </label>
                                            <p class="{{ $document->id.'_filename'}}"></p>

                                            @php
                                                $logo=\App\Models\Utility::get_file('uploads/document/');
                                            @endphp

{{--                                            <img id="{{'blah'.$key}}" src=""  width="25%" />--}}
                                                <img target="_blank" id="{{'blah'.$key}}" src="{{ (isset($employeedoc[$document->id]) && !empty($employeedoc[$document->id])?$logo.'/'.$employeedoc[$document->id]:'') }}"  width="25%" />
                                                <a id="openModalBtn" data-size="lg" data-title="{{ __('View Document') }}" data-bs-toggle="modal" data-bs-target="#employeeModal{{'blah'.$key}}" title="{{ __('View') }}" class="btn btn-sm btn-primary">
                                                    <i class="ti ti-eye text-white"></i>
                                                </a>

                                            <!-- Modal -->
                                            <div class="modal fade " id="employeeModal{{'blah'.$key}}" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                    <div class="modal-body" style="height:500px;">
                                                        <iframe id="inlineFrameExample{{'blah'.$key}}"
                                                        frameborder="0"
                                                        allowfullscreen
                                                        src="<?php 
                                                            if (isset($employeedoc[$document->id]) && !empty($employeedoc[$document->id])) {
                                                                echo $logo . '/' . $employeedoc[$document->id];
                                                            } 
                                                        ?>"  width="100%"
                                                        height="400">
                                                        </iframe>
                                                    </div>
                                                    
                                                        <!-- Modal content goes here -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


{{--                                        @if(!empty($employeedoc[$document->id]))--}}
{{--                                            <br> <span class="text-xs"><a href="{{ (!empty($employeedoc[$document->id])?asset(Storage::url('uploads/document')).'/'.$employeedoc[$document->id]:'') }}" target="_blank">{{ (!empty($employeedoc[$document->id])?$employeedoc[$document->id]:'') }}</a>--}}
{{--                                                    </span>--}}
{{--                                        @endif--}}
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card emp_details">
                    <div class="card-header"><h6 class="mb-0">{{__('Bank Account Detail')}}</h6></div>
                    <div class="card-body employee-detail-edit-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                {!! Form::label('account_holder_name', __('Account Holder Name'),['class'=>'form-label']) !!}
                                {!! Form::text('account_holder_name', null, ['class' => 'form-control']) !!}
                                @error('account_holder_name')
                                    <div class="text-danger">{{ $message}}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('account_number', __('Account Number'),['class'=>'form-label']) !!}
                                {{-- {!! Form::number('account_number', null, ['class' => 'form-control']) !!} --}}
                                {{ Form::text('account_number',null, ['class' => 'form-control', 'required' => 'required','pattern'=>"\d{10,12}",'placeholder' => __('Enter account number'), 'minlength'=>'10','maxlength' => '12','id' => 'phoneInput']) }}
                                 
                                @error('account_number')
                                    <div class="text-danger">{{ $message}}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('bank_name', __('Bank Name'),['class'=>'form-label']) !!}
                                {!! Form::text('bank_name', null, ['class' => 'form-control']) !!}
                                @error('bank_name')
                                    <div class="text-danger">{{ $message}}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('bank_identifier_code', __('Bank Identifier Code'),['class'=>'form-label']) !!}
                                {!! Form::text('bank_identifier_code',null, ['class' => 'form-control']) !!}
                                @error('bank_identifier_code')
                                    <div class="text-danger">{{ $message}}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('branch_location', __('Branch Location'),['class'=>'form-label']) !!}
                                {!! Form::text('branch_location',null, ['class' => 'form-control']) !!}
                                @error('branch_location')
                                    <div class="text-danger">{{ $message}}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('tax_payer_id', __('Tax Payer Id (PANCARD)'),['class'=>'form-label']) !!}
                                {!! Form::text('tax_payer_id',null, ['class' => 'form-control']) !!}
                                @error('tax_payer_id')
                                    <div class="text-danger">{{ $message}}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                    {!! Form::label('UAN_NO',__('UAN NO'),['class'=>'form-label'])!!}
                                    {!! Form::text('UAN_NO',old('UAN_NO'),['class'=>'form-control','placeholder'=>'Enter UAN NO'])!!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
  

        <div class="row">
            <div class="col-12">
                <input type="submit" value="{{__('Update')}}" class="btn btn-primary float-end">
            </div>
        </div>
 
    <div class="row">
        <div class="col-12">
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@push('script-page')
<script>
    document.getElementById('phoneInput').addEventListener('input', function (e) {
        // Replace any non-digit characters
        e.target.value = e.target.value.replace(/\D/g, '');
    });
    </script>
    <script type="text/javascript">

        $(document).on('change', '#branch_id', function() {
            var branch_id = $(this).val();
            getDepartment(branch_id);
        });

        function getDepartment(branch_id)
        {
            var data = {
                "branch_id": branch_id,
                "_token": "{{ csrf_token() }}",
            }

            $.ajax({
                url: '{{ route('employee.getdepartment') }}',
                method: 'POST',
                data: data,
                success: function(data) {
                    $('#department_id').empty();
                    $('#department_id').append('<option value="" disabled>{{ __('Select any Department') }}</option>');

                    $.each(data, function(key, value) {
                        $('#department_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                    $('#department_id').val('');
                }
            });
        }
    </script>
    <script type="text/javascript">

        function getDesignation(did) {
            $.ajax({
                url: '{{route('employee.json')}}',
                type: 'POST',
                data: {
                    "department_id": did, "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    $('#designation_id').empty();
                    $('#designation_id').append('<option value="">Select any Designation</option>');
                    $.each(data, function (key, value) {
                        var select = '';
                        if (key == '{{ $employee->designation_id }}') {
                            select = 'selected';
                        }

                        $('#designation_id').append('<option value="' + key + '"  ' + select + '>' + value + '</option>');
                    });
                }
            });
        }

        $(document).ready(function () {
            var d_id = $('#department_id').val();
            var designation_id = '{{ $employee->designation_id }}';
            getDesignation(d_id);
        });

        $(document).on('change', 'select[name=department_id]', function () {
            var department_id = $(this).val();
            getDesignation(department_id);
        });

    </script>
@endpush
<script>
        document.addEventListener("DOMContentLoaded", function() {
            var addressTextarea = document.getElementById('address');
            var addressCounter = document.getElementById('address-counter');

            addressTextarea.addEventListener('input', function() {
                var addressLength = addressTextarea.value.length;
                addressCounter.textContent = addressLength;
            });
        });
    </script>
    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var addressTextarea = document.getElementById('address');
            var addressCounter = document.getElementById('address-counter');

            addressTextarea.addEventListener('input', function() {
                var addressLength = addressTextarea.value.length;
                addressCounter.textContent = addressLength;
            });
        });
    </script>
@endpush