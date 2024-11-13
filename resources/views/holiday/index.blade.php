@extends('layouts.admin')

@section('page-title')
    {{__('Manage Holiday')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Holiday')}}</li>
@endsection

@section('action-btn')
    @can('create holiday')
        <div class="float-end">
            <!-- <a href="{{ route('holiday.calender') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{__('Calender View')}}" data-original-title="{{__('Calender View')}}">
                <i class="ti ti-calendar"></i>
            </a> -->
            <a href="#" data-size="lg" data-url="{{ route('holiday.create_default_holiday') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Mark Default Holidays')}}" data-title="{{__('Create Default Holiday')}}" class="btn btn-sm btn-primary">
            Mark Default Holidays
            </a>
          
            <a href="#" data-size="lg" data-url="{{ route('holiday.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create New Holiday')}}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endcan
@endsection


@section('content')
 
        <div class="row">
            <div class="col-sm-12">
                <div class=" mt-2 " id="multiCollapseExample1">
                    <div class="card">
                        <div class="card-body">
                            {{ Form::open(array('route' => array('holiday.index'),'method'=>'get','id'=>'holiday_filter')) }}
                            <div class="row align-items-center justify-content-end">
                                <div class="col-xl-10">
                                    <div class="row">
                                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                            <div class="btn-box">
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                            <div class="btn-box">
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                            <div class="btn-box">
                                                {{Form::label('Year',__('Year'),['class'=>'form-label'])}}
                                            </div>
                                            @php
                                                $years = range(date('Y') - 10, date('Y') + 10); // Adjust the range as needed
                                            @endphp

                                            {{ Form::select('holiday_year', array_combine($years, $years), isset($_GET['holiday_year']) ? $_GET['holiday_year'] : date('Y'), ['class' => 'form-control','id'=>'holiday_year', 'required' => 'required']) }}
         
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                            <div class="btn-box">
                                                {{ Form::label('month', __('Month'), ['class' => 'form-label']) }}
                                            </div>
                                            
                                            @php
                                                $months = [
                                                    1 => 'January',
                                                    2 => 'February',
                                                    3 => 'March',
                                                    4 => 'April',
                                                    5 => 'May',
                                                    6 => 'June',
                                                    7 => 'July',
                                                    8 => 'August',
                                                    9 => 'September',
                                                    10 => 'October',
                                                    11 => 'November',
                                                    12 => 'December',
                                                ];
                                            @endphp

                                            {{ Form::select('holiday_month', $months, isset($_GET['holiday_month']) ? $_GET['holiday_month'] : date('n'), ['class' => 'form-control', 'id' => 'holiday_month', 'required' => 'required']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="row">
                                        <div class="col-auto mt-4">
                                            <a href="#" class="btn btn-sm btn-primary" onclick="document.getElementById('holiday_filter').submit(); return false;" data-bs-toggle="tooltip" title="{{__('Apply')}}" data-original-title="{{__('apply')}}">
                                                <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                            </a>
                                            <a href="{{route('holiday.calender')}}" class="btn btn-sm btn-danger " data-bs-toggle="tooltip"  title="{{ __('Reset') }}" data-original-title="{{__('Reset')}}">
                                                <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    

    <div class="row mt-1">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('Occasion')}}</th>
                                <th>{{__('Start Date')}}</th>
                                <th>{{__('Holiday Year')}}</th>
                                @if(Gate::check('edit holiday') || Gate::check('delete holiday'))
                                    <th>{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody class="font-style">
                            @foreach ($holidays as $holiday)
                                <tr id="holiday-row-{{ $holiday->id }}">
                                    <td>{{ $holiday->occasion }}</td>
                                    <td>{{ \Auth::user()->dateFormat($holiday->date) }}</td>
                                    <td>{{$holiday->holiday_year}}</td>
                                    @if(Gate::check('edit holiday') || Gate::check('delete holiday'))
                                        <td class="Action">
                                            <span>
                                                @can('edit holiday')
                                                    <div class="action-btn bg-primary ms-2">
                                                        <a href="#" data-size="lg" class="mx-3 btn btn-sm align-items-center" data-url="{{ route('holiday.edit',$holiday->id) }}" data-ajax-popup="true" data-title="{{__('Edit Holiday')}}" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                <!-- @can('delete holiday')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['holiday.destroy', $holiday->id],'id'=>'delete-form-'.$holiday->id]) !!}
                                                            <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip"  title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$holiday->id}}').submit();">
                                                                <i class="ti ti-trash text-white"></i>
                                                            </a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endcan -->
                                                @can('delete holiday')
    <div class="action-btn bg-danger ms-2">
        <a href="#" class="mx-3 btn btn-sm align-items-center delete-btn" data-id="{{ $holiday->id }}" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}">
            <i class="ti ti-trash text-white"></i>
        </a>
    </div>
@endcan
                                            </span>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script-page')
<script>
    $(document).ready(function() {
        // Bind click event for delete buttons
        $('.delete-btn').on('click', function(e) {
            e.preventDefault();
            var holidayId = $(this).data('id');
            var confirmMessage = $(this).data('confirm').split('|');
            var title = confirmMessage[0];
            var message = confirmMessage[1];

            // Show confirmation dialog
            if (confirm(`${title}\n${message}`)) {
                // Send AJAX request to delete the holiday
                $.ajax({
                   
                    url: "{{ url('holidays') }}/" + holidayId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.success) {
                            // Remove the row from the table
                            $('#holiday-row-' + holidayId).remove();
                            show_toastr('Success',response.success,'success');
                         //   alert(response.message);  // Show success message
                        } else {
                          //  alert(response.message);  // Show error message
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred. Please try again.');
                    }
                });
            }
        });
    });
</script>
@endpush