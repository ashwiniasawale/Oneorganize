<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\LeaveMapping;
use App\Models\Holiday;
use App\Models\Utility;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LeaveController extends Controller
{
    public function index($year='')
    {

        if(\Auth::user()->can('manage leave'))
        {
            $user     = \Auth::user();
            if($year=='')
            {
                $year=date('Y');
            }
            if(\Auth::user()->type =='company' || \Auth::user()->type =='HR')
            { 
                //->whereMonth('leave_date', $monthh)
               // ->whereYear('leave_date', $yearr)
               
                $leaves = Leave::select('leaves.id', 'leaves.employee_id','leave_mappings.leave_date','leave_mappings.leave_type','leave_mappings.status', 'leaves.applied_on', 'leaves.leave_reason', 'leaves.duration', 'employees.name as employee_name')
                ->join('employees','employees.id', '=','leaves.employee_id' )
                ->join('leave_mappings','leave_mappings.leave_id','=','leaves.id')
                ->whereYear('leave_mappings.leave_date', $year)
                ->orderBy('leave_mappings.leave_date','desc')
               ->groupBy('leave_mappings.leave_id')
                ->get();
                               

            }
            else
            {
              
                $leaves = Leave::select('leaves.id', 'leaves.employee_id','leave_mappings.leave_date','leave_mappings.leave_type','leave_mappings.status', 'leaves.applied_on', 'leaves.leave_reason', 'leaves.duration', 'employees.name as employee_name')
                ->join('employees','employees.id', '=','leaves.employee_id' )
                ->join('leave_mappings','leave_mappings.leave_id','=','leaves.id')
                ->where('employees.user_id', '=', $user->id)
                ->whereYear('leave_mappings.leave_date', $year)
                ->orderBy('leave_mappings.leave_date','desc')
                ->groupBy('leave_mappings.leave_id')
                ->get();
                       
            }
           
            return view('leave.index', compact('leaves','year'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function leave_year($year='')
    {

        if(\Auth::user()->can('manage leave'))
        {
            $user     = \Auth::user();
            if($year=='')
            {
                $year=date('Y');
            }
            if(\Auth::user()->type =='company' || \Auth::user()->type =='HR')
            { 
                //->whereMonth('leave_date', $monthh)
               // ->whereYear('leave_date', $yearr)
               
                $leaves = Leave::select('leaves.id', 'leaves.employee_id','leave_mappings.leave_date','leave_mappings.leave_type','leave_mappings.status', 'leaves.applied_on', 'leaves.leave_reason', 'leaves.duration', 'employees.name as employee_name')
                ->join('employees','employees.id', '=','leaves.employee_id' )
                ->join('leave_mappings','leave_mappings.leave_id','=','leaves.id')
                ->whereYear('leave_mappings.leave_date', $year)
                ->orderBy('leave_mappings.leave_date','desc')
               ->groupBy('leave_mappings.leave_id')
                ->get();
                               

            }
            else
            {
              
                $leaves = Leave::select('leaves.id', 'leaves.employee_id','leave_mappings.leave_date','leave_mappings.leave_type','leave_mappings.status', 'leaves.applied_on', 'leaves.leave_reason', 'leaves.duration', 'employees.name as employee_name')
                ->join('employees','employees.id', '=','leaves.employee_id' )
                ->join('leave_mappings','leave_mappings.leave_id','=','leaves.id')
                ->where('employees.user_id', '=', $user->id)
                ->whereYear('leave_mappings.leave_date', $year)
                ->orderBy('leave_mappings.leave_date','desc')
                ->groupBy('leave_mappings.leave_id')
                ->get();
                       
            }
           
            return view('leave.index', compact('leaves','year'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if(\Auth::user()->can('create leave'))
        {
            if(\Auth::user()->type =='company' || \Auth::user()->type =='HR')
            {
                $employees = Employee::where('is_active','=','1')->get()->pluck('name', 'id');
            }
            else
            {
                $employees = Employee::where('is_active','=','1')->where('user_id', '=', \Auth::user()->id)->first();
            }
          
            $leavetypes      = LeaveType::get();

            return view('leave.create', compact('employees', 'leavetypes'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }
    public function store(Request $request)
    {
      
        if(\Auth::user()->can('create leave'))
        {
         
            $validator = \Validator::make(
                $request->all(), [
                                   'employee_id' => 'required',
                                   'start_date' => 'required',
                                   'leave_reason' => 'required'
                               ]
            );
         
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
               
                return response()->json(['error'=>$messages->first()]);
            }
            $year = Carbon::parse($request->start_date)->year;
            $employee = Employee::where('id', '=',$request->employee_id)->first();
           
           if($employee->company_doj && $request->start_date>$employee->company_doj)
           {
           
            $doj = Carbon::parse($employee->company_doj);
            $current = Carbon::parse($request->start_date);
            // $differenceInyears = $current->diffInYears($doj);
            // $doj_month = date('m', strtotime($employee->company_doj)); 
            $diffInYears = $current->diffInYears($doj);
            $diffInMonths = $current->diffInMonths($doj) % 12; // Get the remaining months after getting the years
            $differenceInyears = $diffInYears . '.' . $diffInMonths;
            $doj_month = date('m', strtotime($employee->company_doj));
            $one_year_doj = date('Y', strtotime($employee->company_doj))+1;

            /*************Logic for previous year avialable leave count and add to current or selected year************** */
            $get_leave_type = LeaveType::where('leave_year',$year-1)->where('leave_paid_status','Paid')->first();
            $previous_year_pending_leave=0;
            if($get_leave_type )
            {
               
                
                 if($doj->year<=$get_leave_type->leave_year)
                 {
                   $select_previous_leave=LeaveMapping::selectRaw('SUM(leave_mappings.leave_count) as leave_count')
                
                   ->whereYear('leave_date', $get_leave_type->leave_year)
                   ->join('leaves','leaves.id', '=','leave_mappings.leave_id' ) // for check monthly leave limit
                   ->where('leaves.employee_id',$request->employee_id)
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
       
         /************End logic for previous pending leave******** */
           
             $leave_type = LeaveType::where('leave_year',$year)->where('leave_paid_status','Paid')->first();
                    if($leave_type)
                    {
                     
                        $startDate =$request->start_date;
                    if($request->duration=='multiple')
                    {
                        $endDate = $request->end_date;
                    }else{
                        $endDate = $request->start_date;
                    }
                     
                    
                        $leave    = new Leave();
                    
                            $leave->employee_id = $request->employee_id;
                    
                        
                        $leave->applied_on       = date('Y-m-d');
                        $leave->total_leave_days ='';
                        $leave->leave_reason     = $request->leave_reason;
                        $leave->duration           = $request->duration;
                        $leave->created_by       = \Auth::user()->id;
                        $leave->save();
                        $lastInsertedId = $leave->id;
                        // if($request->duration=='multiple')
                        // {
                            $startTimestamp = strtotime($startDate);
                            $endTimestamp = strtotime($endDate);
                          
                            // Iterate through each date
                            $leaveDates = [];
                $startTimestamp = strtotime($startDate);
                $endTimestamp = strtotime($endDate);

                for ($timestamp = $startTimestamp; $timestamp <= $endTimestamp; $timestamp += 86400) {
                    $leaveDates[] = date('Y-m-d', $timestamp);
                }

                // Check for weekends and holidays within the leave period and apply the sandwich rule
                $previousLeave = LeaveMapping::where('leave_mappings.leave_type', 'Paid')
                ->where('leave_date', '<', $startDate)
                ->join('leaves', 'leaves.id', '=', 'leave_mappings.leave_id') // Check total leave for the employee
                ->where('leaves.employee_id', $request->employee_id)
                ->where('leave_mappings.status', '!=','Reject')
                ->orderBy('leave_mappings.leave_date', 'desc')
                ->first();
                
               
                $finalLeaveDates = [];

                // Check for possible sandwich scenarios
                foreach ($leaveDates as $key => $leaveDate) {
                    $currentLeaveDate = Carbon::parse($leaveDate);
                    $isHoliday = Holiday::where('date', $leaveDate)->exists();
                    $isSunday = $currentLeaveDate->dayOfWeek == Carbon::SUNDAY;

                    // If it's a public holiday and not a Sunday, skip it
                    if ($isHoliday && !$isSunday) {
                        continue;
                    }

                    // If it's a Sunday, apply the sandwich rule
                    if ($isSunday) {
                        if ($key > 0 && $key < count($leaveDates) - 1) {
                            $previousDay = Carbon::parse($leaveDates[$key - 1]);
                            $nextDay = Carbon::parse($leaveDates[$key + 1]);

                            if (in_array($previousDay->format('Y-m-d'), $leaveDates) && in_array($nextDay->format('Y-m-d'), $leaveDates)) {
                                $finalLeaveDates[] = $leaveDate;  // Include Sunday as leave
                            }
                        }
                    }

                    // Check if this leave creates a sandwich with the previous leave
                    if ($previousLeave) {
                        $previousLeaveDate = Carbon::parse($previousLeave->leave_date);
                        $daysBetween = $previousLeaveDate->diffInDays($currentLeaveDate);

                        // If there's only one day between previous leave and current leave, apply sandwich rule
                        if ($daysBetween == 2) {
                            $sandwichDate = $previousLeaveDate->addDay()->copy(); 
                            if ($sandwichDate->isSunday() && !$isHoliday) {
                                $finalLeaveDates[] = $sandwichDate->format('Y-m-d');  // Include the sandwich Sunday
                            }
                        }
                    }

                    // Include the current leave date
                    $finalLeaveDates[] = $leaveDate;
                }
                if(count($finalLeaveDates)>1)
                {
                    $leave_up    = Leave::find( $lastInsertedId);
                    $leave_up->duration           = 'multiple';
                    $leave_up->save();
                }
                // Store the leave dates considering the sandwich rule
                foreach (array_unique($finalLeaveDates) as $leaveDate) {
                    $monthh = date('m', strtotime($leaveDate));
                    $yearr = date('Y', strtotime($leaveDate));

                   
                        $check_leave_date = LeaveMapping::where('leave_date', $leaveDate)
                            ->join('leaves', 'leaves.id', '=', 'leave_mappings.leave_id') // Check if leave date already exists
                            ->where('leaves.employee_id', $request->employee_id)
                            ->first();

                        if (empty($check_leave_date)) {
                            $get_month_leave_count = LeaveMapping::selectRaw('SUM(leave_mappings.leave_count) as leave_count')
                                ->whereMonth('leave_date', $monthh)
                                ->whereYear('leave_date', $yearr)
                                ->join('leaves', 'leaves.id', '=', 'leave_mappings.leave_id') // Check monthly leave limit
                                ->where('leaves.employee_id', $request->employee_id)
                                ->where('leave_mappings.leave_type', 'Paid')
                                ->first();
                            $get_month_leave = $get_month_leave_count->leave_count;

                            $total_paid_leavee = LeaveMapping::selectRaw('SUM(leave_mappings.leave_count) as leave_count')
                                ->where('leave_mappings.leave_type', 'Paid')
                                ->whereYear('leave_date', $yearr)
                                ->join('leaves', 'leaves.id', '=', 'leave_mappings.leave_id') // Check total leave for the employee
                                ->where('leaves.employee_id', $request->employee_id)
                                ->first();
                            $total_paid_leave = $total_paid_leavee->leave_count;

                            if ($doj->day <= 15) {
                                $available_month = 12 - $doj_month + 1;
                            } else {
                                $available_month = 12 - $doj_month;
                            }
                            if ($request->duration == 'multiple') {
                                $leave_count = '1';
                            }else if($request->duration=='full_day')
                            {
                                $leave_count='1';
                            }else if($request->duration=='two_hours_leave')
                            {
                                $leave_count='0.25';
                            }else{
                                $leave_count='0.5';
                            }
                            if ($differenceInyears >= '1' && $differenceInyears < '2' && $one_year_doj == $year) {
                                $leave_day_year = $available_month * ($leave_type->days / 12);
                                if ($doj->day <= 15) 
                                {
                                    $month_year_leave = ($monthh - $doj_month+1) * ($leave_type->days / 12);
                                }else{
                                    $month_year_leave = ($monthh - $doj_month) * ($leave_type->days / 12);
                                }
                                
                            } else {
                                $month_year_leave = $monthh * ($leave_type->days / 12);  // Employee can take leave
                                $leave_day_year = $leave_type->days;
                            }

                           $year_month_available_leave = $month_year_leave + $previous_year_pending_leave - $total_paid_leave; // Calculate available leave till the current date
                            $total_applicable_leave = $leave_day_year + $previous_year_pending_leave;

                         
                            // Determine the leave type (Paid/Unpaid)
                            if ($total_paid_leave >= $total_applicable_leave) {
                                $leave_types = 'Unpaid';
                            } else if ($differenceInyears < 1) {
                                $leave_types = 'Unpaid';
                            } else if (( $leave_count > $year_month_available_leave)) {
                                $leave_types = 'Unpaid';
                            } else {
                                $leave_types = 'Paid';
                            }

                            // Determine leave count based on duration
                           

                            // Save the leave mapping
                            $leave_map = new LeaveMapping();
                            $leave_map->leave_id = $lastInsertedId;
                            $leave_map->leave_date = $leaveDate;
                            if (\Auth::user()->type != 'company' && \Auth::user()->type != 'HR') {
                                $leave_map->status = 'Pending';
                            } else {
                                $leave_map->status = $request->status;
                            }
                            $leave_map->leave_count = $leave_count;
                            $leave_map->leave_type = $leave_types;
                            $leave_map->save();
                        }
                   
                }
                        
                        // }else{
                           
                        //     $leave_date = $startDate;
                        //     $monthh = date('m', strtotime($leave_date));
                        //     $yearr = date('Y', strtotime($leave_date));
                        //     $check_leave_holiday=Holiday::where('holiday_year',$year)->where('date',$leave_date)->first(); // for check incoming leave in in holiday then skip
                        //       if(empty($check_leave_holiday))
                        //       {
                        //         $check_leave_date = LeaveMapping::where('leave_date', $leave_date)
                        //         ->join('leaves','leaves.id', '=','leave_mappings.leave_id' ) // for leave date already exit
                        //         ->where('leaves.employee_id',$leave->employee_id)
                        //         ->first();
                        //         if(empty($check_leave_date))
                        //         {
                        //             $get_month_leave_count = LeaveMapping::selectRaw('SUM(leave_mappings.leave_count) as leave_count')
                        //             ->whereMonth('leave_date', $monthh)
                        //             ->whereYear('leave_date', $yearr)
                        //             ->join('leaves','leaves.id', '=','leave_mappings.leave_id' ) // for check monthly leave limit
                        //             ->where('leaves.employee_id',$leave->employee_id)
                        //             ->where('leave_mappings.leave_type','Paid')
                        //           //  ->where('leave_mappings.status','Approved')
                        //             ->first();
                                    
                        //             $get_month_leave=$get_month_leave_count->leave_count;
                        //             if( $doj->day <= 15)
                        //             {
                        //                $available_month=12-$doj_month+1;
                        //             }else{
                        //                $available_month=12-$doj_month;
                        //             }
                        //             $total_paid_leavee = LeaveMapping::selectRaw('SUM(leave_mappings.leave_count) as leave_count')
                        //             ->where('leave_mappings.leave_type','Paid')
                        //           //  ->where('leave_mappings.status','Approved')
                        //             ->whereYear('leave_date', $yearr)
                        //             ->join('leaves','leaves.id', '=','leave_mappings.leave_id' ) // for check total leave for particular employee
                        //             ->where('leaves.employee_id',$leave->employee_id)
                        //             ->first();
                        //             $total_paid_leave=$total_paid_leavee->leave_count;
                        //              if($differenceInyears>='1' && $differenceInyears<'2' && $one_year_doj==$year)
                        //             {
                                      
                        //                // $available_month=12-$doj_month;
                        //                 $leave_day_year=$available_month*($leave_type->days/12);
                        //                 $month_year_leave=($monthh-$doj_month)*($leave_type->days/12);
                                       
                        //             }else{
                                       
                        //                 $month_year_leave=$monthh*($leave_type->days/12);  ///employee can take leave
                        //               $leave_day_year=$leave_type->days;
                        //             }
                                    
                        //             $year_month_available_leave=$month_year_leave+$previous_year_pending_leave-$total_paid_leave;//from this year to current leave date available leave
                                  
                                   
                        //                  if($request->duration=='full_day')
                        //                  {
                        //                     $leave_count='1';
                        //                  }else if($request->duration=='two_hours_leave')
                        //                  {
                        //                     $leave_count='0.25';
                        //                  }else{
                        //                     $leave_count='0.5';
                        //                  }
                        //                  $total_applicable_leave=$leave_day_year+$previous_year_pending_leave;
                        //             if($total_paid_leave>=$total_applicable_leave)
                        //             {
                                      
                        //                 $leave_types='Unpaid';
                        //             }else if($differenceInyears<1)
                        //             {
                                       
                        //                 $leave_types='Unpaid';
                        //             }else
                        //             if(($get_month_leave>=$leave_type->monthly_limit &&  $leave_count>$year_month_available_leave) ) // only one year completed employee have paid leave
                        //             {
                                      
                        //                 $leave_types='Unpaid';
                        //             }else{
                                       
                        //                 $leave_types='Paid';
                        //             }
                                 
                        //                 $leave_map=new LeaveMapping();
                                     
                        //                 $leave_map->leave_id=$lastInsertedId;
                        //                 $leave_map->leave_date=$leave_date;
                        //                 if(Auth::user()->type !='company' && Auth::user()->type !='HR')
                        //                  {
                        //                     $leave_map->status='Pending';
                        //                  }else{
                        //                     $leave_map->status=$request->status;
                        //                  }
                                      
                        //                  $leave_map->leave_count=$leave_count;
                        //                 $leave_map->leave_type=$leave_types;
                        //                 $leave_map->save();
                        //         }
                        //     }
                        // }
                        if(\Auth::user()->type !='company')
                        {
                           
                            $employee = Employee::find($request->employee_id);
                            $user = User::select('name','id','email')->where('type','=','HR')->where('is_enable_login','1')->first();
                            $company_details = User::select('name','id','email')->where('type','=','company')->where('is_enable_login','1')->first();

                          
                                    $leaveArr = [
                                        'user_name'=>$user->name,
                                        'start_date' => $request->start_date,
                                        'end_date' => $request->end_date,
                                        'leave_reason' => $leave->leave_reason,
                                        'duration'=>$leave->duration,
                                        'employee_name' => $employee->name,
                                        'employee_email'=>$employee->email,
                                    ];
                                  //  $resp = Utility::sendEmailTemplate('new_leave', [$user->user_id => $user->email], $leaveArr);
                                 // $resp1 = Utility::sendEmailTemplate('new_leave', [$company_details->user_id => $company_details->email], $leaveArr);
                          
                                 
                        }
                          
                           return response()->json(['success'=>'Leave successfully created.']);
                    }else{
                        return response()->json(['error'=>'Leave Type not added for this year.']);
                    }
                }else{
                    return response()->json(['error'=>'Please Add Joining date first. Or your Leave date not match with joining date.']);
                }
       
        } else {
          
            return response()->json(['error'=>'Permission denied.']);
        }
    }

    public function store_no_sandwich(Request $request)
    {
      
        if(\Auth::user()->can('create leave'))
        {
         
            $validator = \Validator::make(
                $request->all(), [
                                   'employee_id' => 'required',
                                   'start_date' => 'required',
                                   'leave_reason' => 'required'
                               ]
            );
         
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
               
                return response()->json(['error'=>$messages->first()]);
            }
            $year = Carbon::parse($request->start_date)->year;
            $employee = Employee::where('id', '=',$request->employee_id)->first();
           
           if($employee->company_doj && $request->start_date>$employee->company_doj)
           {
           
            $doj = Carbon::parse($employee->company_doj);
            $current = Carbon::parse($request->start_date);
            // $differenceInyears = $current->diffInYears($doj);
            // $doj_month = date('m', strtotime($employee->company_doj)); 
            $diffInYears = $current->diffInYears($doj);
            $diffInMonths = $current->diffInMonths($doj) % 12; // Get the remaining months after getting the years
            $differenceInyears = $diffInYears . '.' . $diffInMonths;
            $doj_month = date('m', strtotime($employee->company_doj));
            $one_year_doj = date('Y', strtotime($employee->company_doj))+1;

            /*************Logic for previous year avialable leave count and add to current or selected year************** */
            $get_leave_type = LeaveType::where('leave_year',$year-1)->where('leave_paid_status','Paid')->first();
            $previous_year_pending_leave=0;
            if($get_leave_type )
            {
               
                
                 if($doj->year<=$get_leave_type->leave_year)
                 {
                   $select_previous_leave=LeaveMapping::selectRaw('SUM(leave_mappings.leave_count) as leave_count')
                
                   ->whereYear('leave_date', $get_leave_type->leave_year)
                   ->join('leaves','leaves.id', '=','leave_mappings.leave_id' ) // for check monthly leave limit
                   ->where('leaves.employee_id',$request->employee_id)
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
       
         /************End logic for previous pending leave******** */
           
             $leave_type = LeaveType::where('leave_year',$year)->where('leave_paid_status','Paid')->first();
                    if($leave_type)
                    {
                     
                        $startDate =$request->start_date;
                    
                        $endDate = $request->end_date;
                    
                        $leave    = new Leave();
                    
                            $leave->employee_id = $request->employee_id;
                    
                        
                        $leave->applied_on       = date('Y-m-d');
                        $leave->total_leave_days ='';
                        $leave->leave_reason     = $request->leave_reason;
                        $leave->duration           = $request->duration;
                        $leave->created_by       = \Auth::user()->id;
                        $leave->save();
                        $lastInsertedId = $leave->id;
                        if($request->duration=='multiple')
                        {
                            $startTimestamp = strtotime($startDate);
                            $endTimestamp = strtotime($endDate);
                            
                            // Iterate through each date
                            for ($timestamp = $startTimestamp; $timestamp <= $endTimestamp; $timestamp += 86400) { // 86400 seconds in a day
                                $leave_date = date('Y-m-d', $timestamp);
                                $monthh = date('m', strtotime($leave_date));
                                $yearr = date('Y', strtotime($leave_date));
                               
                                $check_leave_holiday=Holiday::where('holiday_year',$year)->where('date',$leave_date)->first(); // for check incoming leave is in holiday then skip
                              if(empty($check_leave_holiday))
                              {
                                $check_leave_date = LeaveMapping::where('leave_date', $leave_date)
                                ->join('leaves','leaves.id', '=','leave_mappings.leave_id' ) // for leave date already exit
                                ->where('leaves.employee_id',$leave->employee_id)
                                ->first();
                                if(empty($check_leave_date))
                                {

                                   
                                    $get_month_leave_count = LeaveMapping::selectRaw('SUM(leave_mappings.leave_count) as leave_count')
                                    ->whereMonth('leave_date', $monthh)
                                    ->whereYear('leave_date', $yearr)
                                    ->join('leaves','leaves.id', '=','leave_mappings.leave_id' ) // for check monthly leave limit
                                    ->where('leaves.employee_id',$leave->employee_id)
                                    ->where('leave_mappings.leave_type','Paid')
                                   // ->where('leave_mappings.status','Approved')
                                    ->first();
                                    $get_month_leave=$get_month_leave_count->leave_count;
                                   
                                    $total_paid_leavee = LeaveMapping::selectRaw('SUM(leave_mappings.leave_count) as leave_count')
                                    ->where('leave_mappings.leave_type','Paid')
                                   // ->where('leave_mappings.status','Approved')
                                    ->whereYear('leave_date', $yearr)
                                    ->join('leaves','leaves.id', '=','leave_mappings.leave_id' ) // for check total leave for particular employee
                                    ->where('leaves.employee_id',$leave->employee_id)
                                    ->first();
                                    $total_paid_leave=$total_paid_leavee->leave_count;
                                    if( $doj->day <= 15)
                                    {
                                       $available_month=12-$doj_month+1;
                                    }else{
                                       $available_month=12-$doj_month;
                                    }
                                    if($request->duration=='multiple')
                                    {
                                       $leave_count='1';
                                    }
                                        
                                    if($differenceInyears>='1' && $differenceInyears<'2' && $one_year_doj==$year)
                                    {
                                      
                                      
                                        $leave_day_year=$available_month*($leave_type->days/12);
                                        $month_year_leave=($monthh-$doj_month)*($leave_type->days/12);
                                       
                                      
                                    }else{
                                       
                                        $month_year_leave=$monthh*($leave_type->days/12);  ///employee can take leave
                                      $leave_day_year=$leave_type->days;
                                    }
                                    
                                     $year_month_available_leave=$month_year_leave+$previous_year_pending_leave-$total_paid_leave;//from this year to current leave date available leave
                                    $total_applicable_leave=$leave_day_year+$previous_year_pending_leave;
                                    if($total_paid_leave>=$total_applicable_leave)
                                    {
                                        $leave_types='Unpaid';
                                    }else if($differenceInyears<1)
                                    {
                                        $leave_types='Unpaid';
                                    }else
                                    if(($get_month_leave>=$leave_type->monthly_limit &&  $leave_count>$year_month_available_leave) ) // only one year completed employee have paid leave
                                    {
                                        $leave_types='Unpaid';
                                    }else{
                                        $leave_types='Paid';
                                    }
                                        $leave_map=new LeaveMapping();
                                        $leave_map->leave_id=$lastInsertedId;
                                        $leave_map->leave_date=$leave_date;
                                        if(\Auth::user()->type !='company' && \Auth::user()->type !='HR')
                                        {
                                            $leave_map->status='Pending';
                                        }else{
                                           $leave_map->status=$request->status;
                                        }
                                        $leave_map->leave_type=$leave_types;
                                        $leave_map->leave_count='1';
                                        $leave_map->save();
                                }
                            }

                            }
                        
                        }else{
                           
                            $leave_date = $startDate;
                            $monthh = date('m', strtotime($leave_date));
                            $yearr = date('Y', strtotime($leave_date));
                            $check_leave_holiday=Holiday::where('holiday_year',$year)->where('date',$leave_date)->first(); // for check incoming leave in in holiday then skip
                              if(empty($check_leave_holiday))
                              {
                                $check_leave_date = LeaveMapping::where('leave_date', $leave_date)
                                ->join('leaves','leaves.id', '=','leave_mappings.leave_id' ) // for leave date already exit
                                ->where('leaves.employee_id',$leave->employee_id)
                                ->first();
                                if(empty($check_leave_date))
                                {
                                    $get_month_leave_count = LeaveMapping::selectRaw('SUM(leave_mappings.leave_count) as leave_count')
                                    ->whereMonth('leave_date', $monthh)
                                    ->whereYear('leave_date', $yearr)
                                    ->join('leaves','leaves.id', '=','leave_mappings.leave_id' ) // for check monthly leave limit
                                    ->where('leaves.employee_id',$leave->employee_id)
                                    ->where('leave_mappings.leave_type','Paid')
                                  //  ->where('leave_mappings.status','Approved')
                                    ->first();
                                    
                                    $get_month_leave=$get_month_leave_count->leave_count;
                                    if( $doj->day <= 15)
                                    {
                                       $available_month=12-$doj_month+1;
                                    }else{
                                       $available_month=12-$doj_month;
                                    }
                                    $total_paid_leavee = LeaveMapping::selectRaw('SUM(leave_mappings.leave_count) as leave_count')
                                    ->where('leave_mappings.leave_type','Paid')
                                  //  ->where('leave_mappings.status','Approved')
                                    ->whereYear('leave_date', $yearr)
                                    ->join('leaves','leaves.id', '=','leave_mappings.leave_id' ) // for check total leave for particular employee
                                    ->where('leaves.employee_id',$leave->employee_id)
                                    ->first();
                                    $total_paid_leave=$total_paid_leavee->leave_count;
                                     if($differenceInyears>='1' && $differenceInyears<'2' && $one_year_doj==$year)
                                    {
                                      
                                       // $available_month=12-$doj_month;
                                        $leave_day_year=$available_month*($leave_type->days/12);
                                        $month_year_leave=($monthh-$doj_month)*($leave_type->days/12);
                                       
                                    }else{
                                       
                                        $month_year_leave=$monthh*($leave_type->days/12);  ///employee can take leave
                                      $leave_day_year=$leave_type->days;
                                    }
                                    
                                    $year_month_available_leave=$month_year_leave+$previous_year_pending_leave-$total_paid_leave;//from this year to current leave date available leave
                                  
                                   
                                         if($request->duration=='full_day')
                                         {
                                            $leave_count='1';
                                         }else if($request->duration=='two_hours_leave')
                                         {
                                            $leave_count='0.25';
                                         }else{
                                            $leave_count='0.5';
                                         }
                                         $total_applicable_leave=$leave_day_year+$previous_year_pending_leave;
                                    if($total_paid_leave>=$total_applicable_leave)
                                    {
                                      
                                        $leave_types='Unpaid';
                                    }else if($differenceInyears<1)
                                    {
                                       
                                        $leave_types='Unpaid';
                                    }else
                                    if(($get_month_leave>=$leave_type->monthly_limit &&  $leave_count>$year_month_available_leave) ) // only one year completed employee have paid leave
                                    {
                                      
                                        $leave_types='Unpaid';
                                    }else{
                                       
                                        $leave_types='Paid';
                                    }
                                 
                                        $leave_map=new LeaveMapping();
                                     
                                        $leave_map->leave_id=$lastInsertedId;
                                        $leave_map->leave_date=$leave_date;
                                        if(\Auth::user()->type !='company' && \Auth::user()->type !='HR')
                                         {
                                            $leave_map->status='Pending';
                                         }else{
                                            $leave_map->status=$request->status;
                                         }
                                      
                                         $leave_map->leave_count=$leave_count;
                                        $leave_map->leave_type=$leave_types;
                                        $leave_map->save();
                                }
                            }
                        }
                        if(\Auth::user()->type !='company')
                        {
                           
                            $employee = Employee::find($request->employee_id);
                            $user = User::select('name','id','email')->where('type','=','HR')->where('is_enable_login','1')->first();
                            $company_details = User::select('name','id','email')->where('type','=','company')->where('is_enable_login','1')->first();

                          
                                    $leaveArr = [
                                        'user_name'=>$user->name,
                                        'start_date' => $request->start_date,
                                        'end_date' => $request->end_date,
                                        'leave_reason' => $leave->leave_reason,
                                        'duration'=>$leave->duration,
                                        'employee_name' => $employee->name,
                                        'employee_email'=>$employee->email,
                                    ];
                                    $resp = Utility::sendEmailTemplate('new_leave', [$user->user_id => $user->email], $leaveArr);
                                  $resp1 = Utility::sendEmailTemplate('new_leave', [$company_details->user_id => $company_details->email], $leaveArr);
                          
                                 
                        }
                          
                           return response()->json(['success'=>'Leave successfully created.']);
                    }else{
                        return response()->json(['error'=>'Leave Type not added for this year.']);
                    }
                }else{
                    return response()->json(['error'=>'Please Add Joining date first. Or your Leave date not match with joining date.']);
                }
       
        } else {
          
            return response()->json(['error'=>'Permission denied.']);
        }
    }
    public function store_bk(Request $request)
    {
      
        if(\Auth::user()->can('create leave'))
        {
         
            $validator = \Validator::make(
                $request->all(), [
                                   'employee_id' => 'required',
                                   'start_date' => 'required',
                                   'leave_reason' => 'required'
                               ]
            );
         
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
               
                return response()->json(['error'=>$messages->first()]);
            }
            $year = Carbon::parse($request->start_date)->year;
            $employee = Employee::where('id', '=',$request->employee_id)->first();
           
           if($employee->company_doj && $request->start_date>$employee->company_doj)
           {
           
            $doj = Carbon::parse($employee->company_doj);
            $current = Carbon::parse($request->start_date);
            // $differenceInyears = $current->diffInYears($doj);
            // $doj_month = date('m', strtotime($employee->company_doj)); 
            $diffInYears = $current->diffInYears($doj);
            $diffInMonths = $current->diffInMonths($doj) % 12; // Get the remaining months after getting the years
            $differenceInyears = $diffInYears . '.' . $diffInMonths;
            $doj_month = date('m', strtotime($employee->company_doj));
            $one_year_doj = date('Y', strtotime($employee->company_doj))+1;
             $leave_type = LeaveType::where('leave_year',$year)->where('leave_paid_status','Paid')->first();
                    if($leave_type)
                    {
                     
                        $startDate =$request->start_date;
                    
                        $endDate = $request->end_date;
                    
                        $leave    = new Leave();
                    
                            $leave->employee_id = $request->employee_id;
                    
                        
                        $leave->applied_on       = date('Y-m-d');
                        $leave->total_leave_days ='';
                        $leave->leave_reason     = $request->leave_reason;
                        $leave->duration           = $request->duration;
                        $leave->created_by       = \Auth::user()->id;
                        $leave->save();
                        $lastInsertedId = $leave->id;
                        if($request->duration=='multiple')
                        {
                            $startTimestamp = strtotime($startDate);
                            $endTimestamp = strtotime($endDate);
                            
                            // Iterate through each date
                            for ($timestamp = $startTimestamp; $timestamp <= $endTimestamp; $timestamp += 86400) { // 86400 seconds in a day
                                $leave_date = date('Y-m-d', $timestamp);
                                $monthh = date('m', strtotime($leave_date));
                                $yearr = date('Y', strtotime($leave_date));
                               
                                $check_leave_holiday=Holiday::where('holiday_year',$year)->where('date',$leave_date)->first(); // for check incoming leave is in holiday then skip
                              if(empty($check_leave_holiday))
                              {
                                $check_leave_date = LeaveMapping::where('leave_date', $leave_date)
                                ->join('leaves','leaves.id', '=','leave_mappings.leave_id' ) // for leave date already exit
                                ->where('leaves.employee_id',$leave->employee_id)
                                ->first();
                                if(empty($check_leave_date))
                                {

                                   
                                    $get_month_leave_count = LeaveMapping::selectRaw('SUM(leave_mappings.leave_count) as leave_count')
                                    ->whereMonth('leave_date', $monthh)
                                    ->whereYear('leave_date', $yearr)
                                    ->join('leaves','leaves.id', '=','leave_mappings.leave_id' ) // for check monthly leave limit
                                    ->where('leaves.employee_id',$leave->employee_id)
                                    ->where('leave_mappings.leave_type','Paid')
                                   // ->where('leave_mappings.status','Approved')
                                    ->first();
                                    $get_month_leave=$get_month_leave_count->leave_count;
                                   
                                    $total_paid_leavee = LeaveMapping::selectRaw('SUM(leave_mappings.leave_count) as leave_count')
                                    ->where('leave_mappings.leave_type','Paid')
                                   // ->where('leave_mappings.status','Approved')
                                    ->whereYear('leave_date', $yearr)
                                    ->join('leaves','leaves.id', '=','leave_mappings.leave_id' ) // for check total leave for particular employee
                                    ->where('leaves.employee_id',$leave->employee_id)
                                    ->first();
                                    $total_paid_leave=$total_paid_leavee->leave_count;
                                    if($request->duration=='multiple')
                                    {
                                       $leave_count='1';
                                    }
                                        
                                    if($differenceInyears>'1' && $differenceInyears<'2' && $one_year_doj==$year)
                                    {
                                      
                                        $available_month=12-$doj_month;
                                        $leave_day_year=$available_month*($leave_type->days/12);
                                        $month_year_leave=($monthh-$doj_month)*($leave_type->days/12);
                                       
                                    }else{
                                       
                                        $month_year_leave=$monthh*($leave_type->days/12);  ///employee can take leave
                                      $leave_day_year=$leave_type->days;
                                    }
                                    
                                    $year_month_available_leave=$month_year_leave-$total_paid_leave;//from this year to current leave date available leave
                                   
                                    if($total_paid_leave>=$leave_day_year)
                                    {
                                        $leave_types='Unpaid';
                                    }else if($differenceInyears<1)
                                    {
                                        $leave_types='Unpaid';
                                    }else
                                    if(($get_month_leave>=$leave_type->monthly_limit &&  $leave_count>$year_month_available_leave) ) // only one year completed employee have paid leave
                                    {
                                        $leave_types='Unpaid';
                                    }else{
                                        $leave_types='Paid';
                                    }
                                        $leave_map=new LeaveMapping();
                                        $leave_map->leave_id=$lastInsertedId;
                                        $leave_map->leave_date=$leave_date;
                                        if(\Auth::user()->type !='company' && \Auth::user()->type !='HR')
                                        {
                                            $leave_map->status='Pending';
                                        }else{
                                           $leave_map->status=$request->status;
                                        }
                                        $leave_map->leave_type=$leave_types;
                                        $leave_map->leave_count='1';
                                        $leave_map->save();
                                }
                            }

                            }
                        
                        }else{
                           
                            $leave_date = $startDate;
                            $monthh = date('m', strtotime($leave_date));
                            $yearr = date('Y', strtotime($leave_date));
                            $check_leave_holiday=Holiday::where('holiday_year',$year)->where('date',$leave_date)->first(); // for check incoming leave in in holiday then skip
                              if(empty($check_leave_holiday))
                              {
                                $check_leave_date = LeaveMapping::where('leave_date', $leave_date)
                                ->join('leaves','leaves.id', '=','leave_mappings.leave_id' ) // for leave date already exit
                                ->where('leaves.employee_id',$leave->employee_id)
                                ->first();
                                if(empty($check_leave_date))
                                {
                                    $get_month_leave_count = LeaveMapping::selectRaw('SUM(leave_mappings.leave_count) as leave_count')
                                    ->whereMonth('leave_date', $monthh)
                                    ->whereYear('leave_date', $yearr)
                                    ->join('leaves','leaves.id', '=','leave_mappings.leave_id' ) // for check monthly leave limit
                                    ->where('leaves.employee_id',$leave->employee_id)
                                    ->where('leave_mappings.leave_type','Paid')
                                  //  ->where('leave_mappings.status','Approved')
                                    ->first();
                                    
                                    $get_month_leave=$get_month_leave_count->leave_count;
                                   
                                    $total_paid_leavee = LeaveMapping::selectRaw('SUM(leave_mappings.leave_count) as leave_count')
                                    ->where('leave_mappings.leave_type','Paid')
                                  //  ->where('leave_mappings.status','Approved')
                                    ->whereYear('leave_date', $yearr)
                                    ->join('leaves','leaves.id', '=','leave_mappings.leave_id' ) // for check total leave for particular employee
                                    ->where('leaves.employee_id',$leave->employee_id)
                                    ->first();
                                    $total_paid_leave=$total_paid_leavee->leave_count;
                                     if($differenceInyears>'1' && $differenceInyears<'2' && $one_year_doj==$year)
                                    {
                                      
                                        $available_month=12-$doj_month;
                                        $leave_day_year=$available_month*($leave_type->days/12);
                                        $month_year_leave=($monthh-$doj_month)*($leave_type->days/12);
                                       
                                    }else{
                                       
                                        $month_year_leave=$monthh*($leave_type->days/12);  ///employee can take leave
                                      $leave_day_year=$leave_type->days;
                                    }
                                    
                                    $year_month_available_leave=$month_year_leave-$total_paid_leave;//from this year to current leave date available leave
                                  
                                   
                                         if($request->duration=='full_day')
                                         {
                                            $leave_count='1';
                                         }else if($request->duration=='two_hours_leave')
                                         {
                                            $leave_count='0.25';
                                         }else{
                                            $leave_count='0.5';
                                         }
                                    if($total_paid_leave>=$leave_day_year)
                                    {
                                      
                                        $leave_types='Unpaid';
                                    }else if($differenceInyears<1)
                                    {
                                       
                                        $leave_types='Unpaid';
                                    }else
                                    if(($get_month_leave>=$leave_type->monthly_limit &&  $leave_count>$year_month_available_leave) ) // only one year completed employee have paid leave
                                    {
                                      
                                        $leave_types='Unpaid';
                                    }else{
                                       
                                        $leave_types='Paid';
                                    }
                                 
                                        $leave_map=new LeaveMapping();
                                     
                                        $leave_map->leave_id=$lastInsertedId;
                                        $leave_map->leave_date=$leave_date;
                                        if(\Auth::user()->type !='company' && \Auth::user()->type !='HR')
                                         {
                                            $leave_map->status='Pending';
                                         }else{
                                            $leave_map->status=$request->status;
                                         }
                                      
                                         $leave_map->leave_count=$leave_count;
                                        $leave_map->leave_type=$leave_types;
                                        $leave_map->save();
                                }
                            }
                        }
                        if(\Auth::user()->type !='company')
                        {
                           
                            $employee = Employee::find($request->employee_id);
                            $user = User::select('name','id','email')->where('type','=','HR')->where('is_enable_login','1')->first();
                            $company_details = User::select('name','id','email')->where('type','=','company')->where('is_enable_login','1')->first();

                          
                                    $leaveArr = [
                                        'user_name'=>$user->name,
                                        'start_date' => $request->start_date,
                                        'end_date' => $request->end_date,
                                        'leave_reason' => $leave->leave_reason,
                                        'duration'=>$leave->duration,
                                        'employee_name' => $employee->name,
                                        'employee_email'=>$employee->email,
                                    ];
                                    $resp = Utility::sendEmailTemplate('new_leave', [$user->user_id => $user->email], $leaveArr);
                                   $resp1 = Utility::sendEmailTemplate('new_leave', [$company_details->user_id => $company_details->email], $leaveArr);
                          
                                 
                        }
                          
                           return response()->json(['success'=>'Leave successfully created.']);
                    }else{
                        return response()->json(['error'=>'Leave Type not added for this year.']);
                    }
                }else{
                    return response()->json(['error'=>'Please Add Joining date first. Or your Leave date not match with joining date.']);
                }
       
        } else {
          
            return response()->json(['error'=>'Permission denied.']);
        }
    }
   
    public function leave_details($year='')
    {
        if(\Auth::user()->can('manage leave'))
        {
            $user     = \Auth::user();
            if($year=='')
            {
                $year=date('Y');
            }
            $leave_type_year=LeaveType::where('leave_year',$year)->first();
            if(\Auth::user()->type =='company' || \Auth::user()->type =='HR')
            {
                $leaves_details = Employee::select('employees.name as employee_name','employees.id','employees.company_doj')->where('is_active','=','1')
                ->get();
            }
            else
            {
                $leaves_details = Employee::select('employees.name as employee_name','employees.id','employees.company_doj')->where('is_active','=','1')
                ->where('user_id', '=', $user->id)
                ->get();
                       
            }
           
           return view('leave.leave_details', compact('leave_type_year','leaves_details','year'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(Leave $leave)
    {
        return redirect()->route('leave.index');
    }

    public function edit(Leave $leave)
    {
        if(\Auth::user()->can('edit leave'))
        {
            if($leave->created_by == \Auth::user()->creatorId())
            {
                $employees  = Employee::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                $leavetypes = LeaveType::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('title', 'id');

                return view('leave.edit', compact('leave', 'employees', 'leavetypes'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, $leave)
    {

        $leave = Leave::find($leave);
        if(\Auth::user()->can('edit leave'))
        {
            if($leave->created_by == Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'leave_type_id' => 'required',
                                       'start_date' => 'required',
                                       'end_date' => 'required',
                                       'leave_reason' => 'required',
                                       'remark' => 'required',
                                   ]);
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $leave_type = LeaveType::find($request->leave_type_id);

                $startDate = new \DateTime($request->start_date);
                $endDate = new \DateTime($request->end_date);
                $endDate->add(new \DateInterval('P1D'));
                $total_leave_days = !empty($startDate->diff($endDate)) ? $startDate->diff($endDate)->days : 0;
                if ($leave_type->days >= $total_leave_days)
                {

                    $leave->employee_id      = $request->employee_id;
                    $leave->leave_type_id    = $request->leave_type_id;
                    $leave->start_date       = $request->start_date;
                    $leave->end_date         = $request->end_date;
                    $leave->total_leave_days = $total_leave_days;
                    $leave->leave_reason     = $request->leave_reason;
                    $leave->remark           = $request->remark;

                    $leave->save();

                    return redirect()->route('leave.index')->with('success', __('Leave successfully updated.'));
                }
                else
                {
                    return redirect()->back()->with('error', __('Leave type ' . $leave_type->name . ' is provide maximum ' . $leave_type->days . "  days please make sure your selected days is under " . $leave_type->days . ' days.'));
                }
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Leave $leave)
    {
        if(\Auth::user()->can('delete leave'))
        {
          
                $leave->delete();

                LeaveMapping::where('leave_id', $leave->id)->delete();
                return redirect()->route('leave.index')->with('success', __('Leave successfully deleted.'));
           
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function delete_leave(Request $request)
    {
        if(\Auth::user()->can('delete leave'))
        {
                      
                LeaveMapping::where('id', $request->id)->delete();
                return response()->json(['success'=>'Leave successfully deleted']);
           
        }
        else
        {
            return response()->json(['error'=>'Permission denied.']);
        }
    }

    public function action($id)
    {
        if(\Auth::user()->can('manage leave'))
        {
        $leaves = LeaveMapping::where('leave_id', $id)->get();
        return view('leave.action', compact('leaves'));
        }else{
            return redirect()->back()->with('error',__('Permission denied.'));
        }
    }
    public function update_leave_status(Request $request)
    {
        if(\Auth::user()->can('edit leave'))
        {
          
            $leave_map=LeaveMapping::find($request->id);
            $leave_map->status=$request->status;
            $leave_map->leave_type=$request->leave_type;
            $leave_map->approved_by=\Auth::user()->id;
            $leave_map->save();

            $leave=Leave::find($leave_map->leave_id);
            if($request->update_status=='1')
            {
                $setings = Utility::settings();
                    
                        if($setings['leave_status'] == 1)
                        {
            
                            $employee     = Employee::where('id', $leave->employee_id)->first();
                            $leave->name  = !empty($employee->name) ? $employee->name : '';
                            $leave->email = !empty($employee->email) ? $employee->email : '';
            
                            $actionArr = [
            
                                'leave_name'=> !empty($employee->name) ? $employee->name : '',
                                'leave_status' => $request->status,
                                'leave_reason' =>  $leave->leave_reason,
                                'leave_start_date' => $leave_map->leave_date,
                               
                            ];
                            $resp = Utility::sendEmailTemplate('leave_action_sent', [$employee->id => $employee->email], $actionArr);
            
                        }
                        
                      
            }
            return response()->json(['success'=>'Leave status changed.']);
        }else{
            return response()->json(['error'=>'Permission denied.']);
        }
    }
    // public function changeaction(Request $request)
    // {
    //     $leave = Leave::find($request->leave_id);

    //     $leave->status = $request->status;
    //     if($leave->status == 'Approval')
    //     {
    //         $startDate               = new \DateTime($leave->start_date);
    //         $endDate                 = new \DateTime($leave->end_date);
    //         $total_leave_days        = $startDate->diff($endDate)->days;
    //         $leave->total_leave_days = $total_leave_days;
    //         $leave->status           = 'Approved';
    //     }

    //     $leave->save();


    //    //Send Email
    //     $setings = Utility::settings();
    //     if(!empty($employee->id))
    //     {
    //         if($setings['leave_status'] == 1)
    //         {

    //             $employee     = Employee::where('id', $leave->employee_id)->where('created_by', '=', \Auth::user()->creatorId())->first();
    //             $leave->name  = !empty($employee->name) ? $employee->name : '';
    //             $leave->email = !empty($employee->email) ? $employee->email : '';

    //             $actionArr = [

    //                 'leave_name'=> !empty($employee->name) ? $employee->name : '',
    //                 'leave_status' => $leave->status,
    //                 'leave_reason' =>  $leave->leave_reason,
    //                 'leave_start_date' => $leave->start_date,
    //                 'leave_end_date' => $leave->end_date,
    //                 'total_leave_days' => $leave->total_leave_days,
    //             ];
    //             $resp = Utility::sendEmailTemplate('leave_action_sent', [$employee->id => $employee->email], $actionArr);


    //             return redirect()->route('leave.index')->with('success', __('Leave status successfully updated.') .(($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));

    //         }

    //     }

    //     return redirect()->route('leave.index')->with('success', __('Leave status successfully updated.'));
    // }


    public function jsoncount(Request $request)
    {

        // $leave_counts = LeaveType::select(\DB::raw('COALESCE(SUM(leaves.total_leave_days),0) AS total_leave, leave_types.title, leave_types.days,leave_types.id'))
        //                          ->leftjoin('leaves', function ($join) use ($request){
        //     $join->on('leaves.leave_type_id', '=', 'leave_types.id');
        //     $join->where('leaves.employee_id', '=', $request->employee_id);
        // }
        // )->groupBy('leaves.leave_type_id')->get();

        $leave_counts=[];
        $leave_types = LeaveType::where('created_by',\Auth::user()->creatorId())->get();
        foreach ($leave_types as  $type) {
            $counts=Leave::select(\DB::raw('COALESCE(SUM(leaves.total_leave_days),0) AS total_leave'))->where('leave_type_id',$type->id)->groupBy('leaves.leave_type_id')->where('employee_id',$request->employee_id)->first();

            $leave_count['total_leave']=!empty($counts)?$counts['total_leave']:0;
            $leave_count['title']=$type->title;
            $leave_count['days']=$type->days;
            $leave_count['id']=$type->id;
            $leave_counts[]=$leave_count;
        }

        return $leave_counts;

    }
}
