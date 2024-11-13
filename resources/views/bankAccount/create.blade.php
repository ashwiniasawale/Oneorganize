{{ Form::open(array('url' => 'bank-account')) }}
<div class="modal-body">
   
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('chart_account_id', __('Account'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{-- {{ Form::select('chart_account_id', $chart_accounts,'', array('class' => 'form-control select','required'=>'required')) }} --}}
            <select name="chart_account_id" class="form-control" required="required">
                @foreach ($chartAccounts as $key => $chartAccount)
                    <option value="{{ $key }}" class="subAccount">{{ $chartAccount }}</option>
                    @foreach ($subAccounts as $subAccount)
                        @if ($key == $subAccount['account'])
                            <option value="{{ $subAccount['id'] }}" class="ms-5"> &nbsp; &nbsp;&nbsp; {{ $subAccount['code_name'] }}</option>
                        @endif
                    @endforeach
                @endforeach
            </select>
            @error('chart_account_id')
            <span class="error invalid-feedback">{{ $message }}</span>
        @enderror
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('holder_name', __('Bank Holder Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::text('holder_name', '', array('class' => 'form-control','required'=>'required' , 'placeholder'=>__('Enter Bank Holder Name'))) }}
            @error('holder_name')
            <span class="error invalid-feedback">{{ $message }}</span>
        @enderror
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('bank_name', __('Bank Name'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::text('bank_name', '', array('class' => 'form-control','required'=>'required' , 'placeholder' => __('Enter Bank Name'))) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('account_number', __('Account Number'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::text('account_number', null, ['class' => 'form-control', 'required' => 'required','minlength' => '16', 'maxlength' => '18','id' => 'phoneInput']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('opening_balance', __('Opening Balance'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::number('opening_balance', '', array('class' => 'form-control','step'=>'0.01' , 'placeholder'=>__('Enter Opening Balance'))) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('contact_number', __('Contact Number'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{-- {{ Form::text('contact_number', '', array('class' => 'form-control' , 'required' => 'required', 'placeholder'=>__('Enter Contact Number'),'minlength'=>'10','maxlength' => '12','id' => 'phoneInput')) }} --}}
            {{-- {{ Form::number('contact_number', null, ['class' => 'form-control', 'placeholder' => __('Enter Client Phone number'), 'minlength'=>'10','maxlength' => '12','id' => 'phoneInput']) }} --}}
            {{ Form::number('contact_number',null, ['class' => 'form-control', 'required' => 'required','pattern'=>"\d{10,12}",'placeholder' => __('Enter Contact Number'), 'minlength'=>'10','maxlength' => '12','id' => 'contactNumberInput']) }}

            @error('phone')
            <span class="error invalid-email text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('bank_address', __('Bank Address'),['class'=>'form-label']) }}<span class="text-danger">*</span>
            {{ Form::textarea('bank_address', '', array('class' => 'form-control','rows'=>3 , 'placeholder' => __('Enter Bank Address'))) }}
        </div>
        @if(!$customFields->isEmpty())
            <div class="col-md-12">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    @include('customFields.formBuilder')
                </div>
            </div>
        @endif

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}
<script>
    document.getElementById('contactNumberInput').addEventListener('input', function (e) {
        // Replace any non-digit characters and enforce the max length
        e.target.value = e.target.value.replace(/\D/g, '').slice(0, 12);
    });
</script>
<script>
    document.getElementById('phoneInput').addEventListener('input', function (e) {
        // Replace any non-digit characters and enforce the max length
        e.target.value = e.target.value.replace(/\D/g, '').slice(0, 18);
    });
</script>