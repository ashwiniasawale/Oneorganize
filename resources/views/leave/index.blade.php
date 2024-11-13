@extends('layouts.admin')

@section('page-title')
    {{__('Manage Leave')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Manage Leave')}}</li>
@endsection
<style>
.selecttt
{
        display:inline-block !important;
        width:auto !important;
}
    </style>
@section('action-btn')
    <div class="float-end">
    @can('manage leave')
        <select  id="leave_year" name="leave_year" onchange="get_leave_year();" class="form-select selecttt mx-1" style="padding-right:2.5rem;">
            <?php 
            $start_year=date('Y')-10;
            $end_year=date('Y');
            for($i=$start_year;$i<=$end_year;$i++)
            {
                ?>
                <option <?php if($i==$year){ echo 'selected=selected'; } ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php
            }
            ?>
        </select>
        @endcan
        @can('manage leave')
        <a href="{{ route('leave.leave_details') }}"
            data-title="{{ __('Leave Details') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Leave Details') }}">
           Leave Details
        </a>
        @endcan
        @can('create leave')
        <a href="#" data-size="lg" data-url="{{ route('leave.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create Leave')}}" class="btn btn-sm btn-primary">
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
                                @if(\Auth::user()->type!='Employee')
                                    <th>{{__('Employee')}}</th>
                                @endif
                                <th>{{__('Leave Date')}}</th>
                                <th>{{__('Duration')}}</th>
                                <th>{{__('status')}}</th>
                                <th>{{__('Leave Type')}}</th>
                               
                                <th>{{__('Leave Reason')}}</th>
                                    @can('edit leave')
                                        <th width="200px">{{__('Action')}}</th>
                                    @endcan
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($leaves as $leave)
                                <tr>
                                    @if(\Auth::user()->type!='Employee')
                                        <td>{{ !empty($leave->employees) ? $leave->employees->name : '-'}}</td>
                                    @endif
                                    <td>{{ \Auth::user()->dateFormat($leave->leave_date ) }}</td>
                                    <td>{{$leave->duration}}</td>
                                    <td>
                                        <?php if($leave->duration=='multiple')
                                        { ?>
                                         <div class=" ">
                                            <a href="#" data-url="{{ URL::to('leave/'.$leave->id.'/action') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Total Leave')}} ({{$leave->employees->name}})" class="align-items-center" title="{{__('View Leaves')}}" data-original-title="{{__('Total Leave')}} ({{$leave->employees->name}})">
                                                View Status</a>
                                        </div>
                                        <?php }else{ ?>
                                    @if($leave->status=="Pending")<div class=" text-warning">{{ $leave->status }}</div>
                                        @elseif($leave->status=="Approved")
                                            <div class=" text-success">{{ $leave->status }}</div>
                                        @else($leave->status=="Reject")
                                            <div class="text-danger">{{ $leave->status }}</div>
                                        @endif
                                        <?php } ?>
                                    </td>
                                    <td>{{ $leave->leave_type}}</td>
                                   
                                    <td>{{$leave->leave_reason}}</td>
                                      
                                    <td>
                                        @can('manage leave')
                                        <div class="action-btn bg-warning ms-2">
                                            <a href="#" data-url="{{ URL::to('leave/'.$leave->id.'/action') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Total Leave')}} ({{$leave->employees->name}})" class="mx-3 btn btn-sm  align-items-center" title="{{__('View Leaves')}}" data-original-title="{{__('Total Leave')}} ({{$leave->employees->name}})">
                                                <i class="ti ti-eye text-white"></i> </a>
                                        </div>
                                         
                                            <!-- <div class="action-btn bg-primary ms-2">
                                                <a href="#" data-url="{{ URL::to('leave/'.$leave->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Leave')}}" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                <i class="ti ti-pencil text-white"></i></a>
                                            </div> -->
                                        @endcan
                                       
                                        @can('delete leave')
                                        <div class="action-btn bg-danger ms-2">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['leave.destroy', $leave->id],'id'=>'delete-form-'.$leave->id]) !!}
                                            <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$leave->id}}').submit();">
                                            <i class="ti ti-trash text-white"></i></a>
                                            {!! Form::close() !!}
                                        </div>
                                        @endif
                                    </td>
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

    
<script>
    function get_leave_year() {
            var year=$('#leave_year').val();
         
            var url = "{{route('leave.leave_year')}}"; // Replace '/your-url/' with your actual URL
           
                url += '/'+year;
           
           
            // Redirect to the constructed URL
            window.location.href = url;
       
    }
</script>
