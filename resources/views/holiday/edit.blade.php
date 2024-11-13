{{ Form::model($holiday, array('route' => array('holiday.update', $holiday->id), 'method' => 'PUT')) }}
<div class="modal-body">
    {{-- start for ai module--}}
    @php
        $plan= \App\Models\Utility::getChatGPTSettings();
    @endphp
    @if($plan->chatgpt == 1)
    <div class="text-end">
        <!-- <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="{{ route('generate',['holiday']) }}"
           data-bs-placement="top" data-title="{{ __('Generate content with AI') }}">
            <i class="fas fa-robot"></i> <span>{{__('Generate with AI')}}</span>
        </a> -->
    </div>
    @endif
    {{-- end for ai module--}}
  

    <div >
        <div class="row">
            <div class="form-group col-md-6">
                {{ Form::label('occasion', __('Occasion'), ['class' => 'form-label']) }}
                {{ Form::textarea('occasion', $holiday->occasion, ['class' => 'form-control','required'=>'required', 'placeholder' => __('Enter Occasion'), 'rows' => 6]) }}
            </div>
            <div class="col-md-6">
                <div class="form-group pb-2">
                    {{ Form::label('date', __('Start Date'), ['class' => 'form-label']) }}
                    {{ Form::date('date', $holiday->date, ['class' => 'form-control','required'=>'required']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('holiday_year', __('Holiday Year'), ['class' => 'form-label']) }}
                    @php
                        $years = range(date('Y') - 10, date('Y') + 10); // Adjust the range as needed
                    @endphp

                    {{ Form::select('holiday_year', array_combine($years, $years), $holiday->holiday_year, ['class' => 'form-control', 'required' => 'required']) }}
                </div>
            </div>
            
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>
{{Form::close()}}

<script>
    if ($(".datepicker").length) {
        $('.datepicker').daterangepicker({
            singleDatePicker: true,
            format: 'yyyy-mm-dd',
            locale: date_picker_locale,
        });
    }
</script>

