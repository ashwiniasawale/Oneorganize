@extends('layouts.admin')

@section('page-title')
    {{__('Manage Leave')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Leave Details')}}</li>
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
       
        <a href="{{ route('leave.index') }}"
            data-title="{{ __('Leave Details') }}" data-bs-toggle="tooltip" title="Back" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Leave Details') }}">
            <span class="btn-inner--icon"><i class="ti ti-arrow-left"></i></span>
        </a>
       
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
                                <th>{{__('Leave Type')}}</th>
                                <th>{{__('No. of Leaves')}}<br>
                                    <small>Current Year+previous Year</small>
                                </th>
                                <th>{{__('Applicable Leaves')}}
                                <br>
                                <small>Current Year+previous Year</small>
                                </th>
                                <th>{{__('Monthly Limit')}}</th>
                                <th>{{__('Total Paid Leave Taken')}}</th>
                               
                                <th>{{__('Available Paid Leaves')}}</th>
                                 
                            </tr>
                            </thead>
                            <tbody>
                                <?php if($leave_type_year)
                                { ?>
                            @foreach ($leaves_details as $leaves_details)
                            <?php 
                                $total_paid_leave = App\Models\LeaveMapping::selectRaw('SUM(leave_mappings.leave_count) as leave_count')
                                ->where('leave_mappings.leave_type','Paid')
                               // ->where('leave_mappings.status','Approved')
                                ->whereYear('leave_date', $year)
                                ->join('leaves','leaves.id', '=','leave_mappings.leave_id' ) // for check total leave for particular employee
                                ->where('leaves.employee_id',$leaves_details->id)
                                ->first();
                               // $available_paid_leaves=$leave_type_year->days-$total_paid_leave->leave_count;
                               $dojj =  $leaves_details->company_doj ;
                           
                               if(!empty($dojj)  )
                               {
                              
                              $doj =  $leaves_details->company_doj 
                               ? \Carbon\Carbon::parse($leaves_details->company_doj) 
                               : null;
                               if($year==date('Y'))
                               {
                               
                                 $current = \Carbon\Carbon::parse(date(''.$year.'-m-d'));
                               }else{
                              
                               $current = \Carbon\Carbon::createFromDate($year, 12, 31);
                               }
                           
                                 $diffInYears = $current->diffInYears($doj);
                               $diffInMonths = $current->diffInMonths($doj) % 12; // Get the remaining months after getting the years
                           
                               $differenceInyears = $diffInYears . '.' . $diffInMonths;
                                $doj_month = date('m', strtotime($leaves_details->company_doj)); 
                               $one_year_doj = date('Y', strtotime($leaves_details->company_doj))+1; 
                             // echo $doj->day; 


                             if( $doj->day <= 15)
                             {
                                $available_month=12-$doj_month+1;
                             }else{
                                $available_month=12-$doj_month;
                             }
                             
                               
                                if($differenceInyears<'1')
                                {
                                    $available=0;
                                    $available_paid_leaves=0;
                                }else
                                if($differenceInyears>='1' && $differenceInyears<'2' && $one_year_doj==$year)
                                {
                                   
                                    $available=$available_month*($leave_type_year->days/12);
                                    $available_paid_leaves=$available-$total_paid_leave->leave_count;
                                }else{
                                    $available=$leave_type_year->days;
                                    $available_paid_leaves=$leave_type_year->days-$total_paid_leave->leave_count;
                                }
                            }else{
                              
                                $available=0;
                                $available_paid_leaves=0;
                            }


                             /*************Logic for previous year avialable leave count and add to current or selected year************** */
                                $get_leave_type =  App\Models\LeaveType::where('leave_year',$year-1)->where('leave_paid_status','Paid')->first();
                                $previous_year_pending_leave=0;
                                if($get_leave_type )
                                {
                                
                                    if($doj->year<=$get_leave_type->leave_year)
                                    {
                                    $select_previous_leave= App\Models\LeaveMapping::selectRaw('SUM(leave_mappings.leave_count) as leave_count')
                                    
                                    ->whereYear('leave_date', $get_leave_type->leave_year)
                                    ->join('leaves','leaves.id', '=','leave_mappings.leave_id' ) // for check monthly leave limit
                                    ->where('leaves.employee_id',$leaves_details->id)
                                    ->where('leave_mappings.leave_type','Paid')
                                    // ->where('leave_mappings.status','Approved')
                                    ->first();
                                     $previous_year_paid_leave =$select_previous_leave->leave_count;

                                    if( $doj->day <= 15)
                                    {
                                        $available_month1=12-$doj_month+1;
                                    }else{
                                        $available_month1=12-$doj_month;
                                    }
                                     $current1 = \Carbon\Carbon::createFromDate( $get_leave_type->leave_year, 12, 31);
                                    $diffInYears1 = $current1->diffInYears($doj);
                                    $diffInMonths1 = $current1->diffInMonths($doj) % 12; // Get the remaining months after getting the years
                                    $differenceInyears1 = $diffInYears1 . '.' . $diffInMonths1;
                                   
                                    if($differenceInyears1<'1')
                                {
                                     $leave_day_year1=0;
                                 
                                }else
                                    if($differenceInyears1>='1' && $differenceInyears1<'2')
                                    {
                                        
                                     $leave_day_year1=$available_month1*($get_leave_type->days/12);
                                        
                                        
                                    }else{
                                        
                                        $leave_day_year1=$get_leave_type->days;
                                    }

                                    $previous_year_pending_leave +=$leave_day_year1-$previous_year_paid_leave;
                                    
                                    }
                              
                                }
                               $previous_year_pending_leave;
                                /*******end previous year logic */
                            ?>
                                <tr>
                                   <td>{{ $leaves_details->employee_name; }}</td>
                                    <td>{{ $leave_type_year->leave_paid_status }}</td>
                                    <td>{{$leave_type_year->days}}+{{ $previous_year_pending_leave}}={{$leave_type_year->days+$previous_year_pending_leave}}</td>
                                    <td>{{$available}}+{{ $previous_year_pending_leave}}={{$available+$previous_year_pending_leave}}</td>
                                    <td>{{ $leave_type_year->monthly_limit}}</td>
                                   
                                    <td>{{$total_paid_leave->leave_count}}</td>
                                    <td>{{$available_paid_leaves+$previous_year_pending_leave}}</td>
                                    
                                </tr>
                            @endforeach
                            <?php } ?>
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
         
            var url = "{{route('leave.leave_details')}}"; // Replace '/your-url/' with your actual URL
           
                url += '/'+year;
           
           
            // Redirect to the constructed URL
            window.location.href = url;
       
    }
</script>
