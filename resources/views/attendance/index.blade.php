@extends('layouts.admin')
@section('page-title')
    {{__('Manage Attendance List')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Attendance')}}</li>
@endsection

@section('action-btn')
   <div class="float-end">
       @can('manage attendance')
        <a href="{{ route('attendance_request') }}"
            data-title="{{ __('Attendance Request') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Attendance Request') }}">
           Attendance Request
        </a>
        @endcan
   </div>
@endsection
@section('content')


    <div class="row">
        <div class="col-sm-12">
                    @if (session('status'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {!! session('status') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
                    @endif
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(array('route' => array('attendanceemployee.index'),'method'=>'get','id'=>'attendanceemployee_filter')) }}
                        <div class="row align-items-center justify-content-end">
                            <div class="col-xl-10">
                                <div class="row">
                                    <div class="col-2">
                                        <label class="form-label">{{__('Type')}}</label> <br>

                                        <div class="form-check form-check-inline form-group">
                                            <input type="radio" id="monthly" value="monthly" name="type" class="form-check-input" {{isset($_GET['type']) && $_GET['type']=='monthly' ?'checked':'checked'}}>
                                            <label class="form-check-label" for="monthly">{{__('Monthly')}}</label>
                                        </div>
                                        <!-- <div class="form-check form-check-inline form-group">
                                            <input type="radio" id="daily" value="daily" name="type" class="form-check-input" {{isset($_GET['type']) && $_GET['type']=='daily' ?'checked':''}}>
                                            <label class="form-check-label" for="daily">{{__('Daily')}}</label>
                                        </div> -->

                                    </div>
                                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 month">
                                        <div class="btn-box">
                                            {{Form::label('month',__('Month'),['class'=>'form-label'])}}
                                            {{Form::month('month',isset($_GET['month'])?$_GET['month']:date('Y-m'),array('class'=>'month-btn form-control month-btn'))}}
                                        </div>
                                    </div>
                                    <!-- <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 date">
                                        <div class="btn-box">
                                            {{ Form::label('date', __('Date'),['class'=>'form-label'])}}
                                            {{ Form::date('date',isset($_GET['date'])?$_GET['date']:'', array('class' => 'form-control month-btn')) }}
                                        </div>
                                    </div> -->
                                    @if(\Auth::user()->type != 'Employee')
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                            <div class="btn-box">
                                                {{ Form::label('employee', __('Employee'),['class'=>'form-label'])}}
                                                
                                           <select name="employee" id="employee" class="form-control select2">
                                              <?php foreach($employee as $employee)
                                              { 
                                                if(isset($_GET['employee']))
                                                {
                                                    if($_GET['employee']==$employee->id)
                                                    {
                                                        $sel='selected=selected';
                                                    }else{
                                                        $sel='';
                                                    }
                                                }else{
                                                    $sel='';
                                                }
                                            
                                                ?>
                                              <option <?php echo $sel; ?> value="<?php echo $employee->id; ?>"><?php echo $employee->name; ?></option>
                                              <?php } ?>
                                           </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12">
                                            <div class="btn-box">
                                                {{ Form::label('branch', __('Branch'),['class'=>'form-label'])}}
                                                {{ Form::select('branch', $branch,isset($_GET['branch'])?$_GET['branch']:'', array('class' => 'form-control select2')) }}
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                            <div class="btn-box">
                                                {{ Form::label('department', __('Department'),['class'=>'form-label'])}}
                                                {{ Form::select('department', $department,isset($_GET['department'])?$_GET['department']:'', array('class' => 'form-control select2')) }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-auto mt-4">
                                <div class="row">
                                    <div class="col-auto">
                                        <a href="#" class="btn btn-sm btn-primary" onclick="document.getElementById('attendanceemployee_filter').submit(); return false;" data-bs-toggle="tooltip" title="{{__('Apply')}}" data-original-title="{{__('apply')}}">
                                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                        </a>
                                        <a href="{{route('attendanceemployee.index')}}" class="btn btn-sm btn-danger " data-bs-toggle="tooltip"  title="{{ __('Reset') }}" data-original-title="{{__('Reset')}}">
                                            <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                                        </a>
                                        @can('create attendance')
                                        <a href="#" data-size="md"  data-bs-toggle="tooltip" title="{{__('Import')}}" data-url="{{ route('attendance.file.import') }}" data-ajax-popup="true" data-title="{{__('Import employee CSV file')}}" class="btn btn-sm btn-primary">
                                            <i class="ti ti-file-import"></i>
                                        </a>
                                       
                                        <a data-url="{{route('attendanceemployee.create')}}" data-size="lg" class="btn btn-sm btn-danger " data-bs-toggle="tooltip"  title="{{ __('Mark Attendance') }}" data-ajax-popup="true" data-original-title="{{__('Mark Attendance')}}">
                                            <span class="btn-inner--icon"><i class="ti ti-plus text-white"></i></span>
                                        </a>
                                        @endcan
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


    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable" id="att_table">
                            <thead>
                            <tr>
                                    <th>{{__('Employee')}}</th>
                               
                                <th>{{__('Date')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Dashboard Clock In')}}</th>
                                <th>{{__('Dashboard Clock Out')}}</th>
                                <th>{{__('Biometric Clock In')}}</th>
                                <th>{{__('Biometric Clock Out')}}</th>
                                <th>{{__('Day Count')}}</th>
                               
                                @if(Gate::check('edit attendance') || Gate::check('delete attendance'))
                                    <th>{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            <?php $total_days=0; ?>
                            @foreach ($attendanceEmployee as $attendance)
                            <?php 
                          $total_days +=$attendance->day_count;
                            ?>
                                <tr>
                                   
                                    <td>{{!empty($attendance->employee)?$attendance->employee->name:'' }}</td>
                                   
                                    <td>{{ \Auth::user()->dateFormat($attendance->date) }}</td>
                                    <td>{{ $attendance->status }}</td>
                                    <td>{{ ($attendance->clock_in !='00:00:00') ?\Auth::user()->timeFormat( $attendance->clock_in):'00:00' }} </td>
                                    <td>{{ ($attendance->clock_out !='00:00:00') ?\Auth::user()->timeFormat( $attendance->clock_out):'00:00' }}</td>
                                    <td>{{($attendance->biometric_clock_in !='00:00:00') ?\Auth::user()->timeFormat($attendance->biometric_clock_in):'00:00'}}</td>
                                    <td>{{($attendance->biometric_clock_out !='00:00:00') ?\Auth::user()->timeFormat($attendance->biometric_clock_out):'00:00'}}</td>
                                    <td>{{$attendance->day_count}}</td>
                                   
                                    @if(Gate::check('edit attendance') || Gate::check('delete attendance'))
                                        <td>
                                            @can('edit attendance')
                                                <div class="action-btn bg-primary ms-2">
                                                    <a href="#" data-url="{{ URL::to('attendanceemployee/'.$attendance->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Attendance')}}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                        <i class="ti ti-pencil text-white"></i></a>
                                                </div>
                                            @endcan
                                            @can('delete attendance')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['attendanceemployee.destroy', $attendance->id],'id'=>'delete-form-'.$attendance->id]) !!}

                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}"
                                                       data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$attendance->id}}').submit();">
                                                        <i class="ti ti-trash text-white"></i></a>
                                                    {!! Form::close() !!}
                                                </div>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="7"></td>
                                <td >Total : <?php echo $total_days; ?>/<?php echo $daysInMonth; ?></td>
                            </tr>
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
        function load_data()
        {
           
            $("#att_table").load(" #att_table");
        }
  
    </script>
@endpush
