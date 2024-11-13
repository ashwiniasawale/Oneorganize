@extends('layouts.admin')

@section('page-title')
    {{__('Manage Attendance Request')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{url('attendanceemployee')}}">{{__('Attendance')}}</a></li>
    <li class="breadcrumb-item">{{__('Attendance Request')}}</li>
@endsection

@section('action-btn')
    <div class="float-end">
     
        @can('create leave')
        <a href="#" data-size="lg" data-url="{{ route('attendance_request.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create Attendance Request')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
        </a>
        @endcan
    </div>
@endsection

@section('content')
    <div class="row">
    <div class="col-xl-12">
            <div class="card">
            <div class="card-body table-border-style">
                    <div class="table-responsive">
                    <table class="table datatable" id="att_table">
                            <thead>
                            <tr>
                            <th>{{__('Employee')}}</th>
                               
                               <th>{{__('Date')}}</th>
                              
                               <th>{{__('Clock In')}}</th>
                               <th>{{__('Clock Out')}}</th>
                               <th>{{__('Status')}}</th>
                               <th>{{__('Attendance Reason')}}</th>
                               <th>{{__('Created At')}}</th>
                               <th>{{__('Updated At')}}</th>
                               @if(Gate::check('edit attendance') || Gate::check('delete attendance'))
                                   <th>{{__('Action')}}</th>
                               @endif
                            </tr>
                            </thead>
                            <tbody>
                           
                            @foreach ($attendance_request as $attendance)
                          
                                <tr>
                                   
                                    <td>{{!empty($attendance->employee)?$attendance->employee->name:'' }}</td>
                                   
                                    <td>{{ \Auth::user()->dateFormat($attendance->date) }}</td>
                                   
                                    <td>{{ ($attendance->clock_in !='00:00:00') ?\Auth::user()->timeFormat( $attendance->clock_in):'00:00' }} </td>
                                    <td>{{ ($attendance->clock_out !='00:00:00') ?\Auth::user()->timeFormat( $attendance->clock_out):'00:00' }}</td>
                                    <td>{{$attendance->status}}</td>
                                    <td>{{$attendance->attendance_reason}}</td>
                                    <td>{{$attendance->created_at}}</td>
                                    <td>{{$attendance->updated_at}}</td>
                                    @if(Gate::check('edit attendance') || Gate::check('delete attendance'))
                                        <td>
                                            @can('edit attendance')
                                                <div class="action-btn bg-primary ms-2">
                                                    <a href="#" data-url="{{ route('attendance_request.edit', ['id' => $attendance->id]) }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Attendance Request')}}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                        <i class="ti ti-pencil text-white"></i></a>
                                                        
                                                </div>
                                            @endcan
                                            @can('delete attendance')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['attendance_request.destroy', $attendance->id],'id'=>'delete-form-'.$attendance->id]) !!}

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
                            
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


