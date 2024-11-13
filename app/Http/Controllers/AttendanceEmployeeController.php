<?php

namespace App\Http\Controllers;

use App\Imports\AttendanceImport;
use App\Models\AttendanceEmployee;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\IpRestrict;
use App\Models\User;
use App\Models\Utility;
use App\Models\Holiday;
use App\Models\LeaveMapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceEmployeeController extends Controller
{
    public function index(Request $request)
    {
     
        if (\Auth::user()->can('manage attendance')) {

            $branch = Branch::get()->pluck('name', 'id');
            $branch->prepend('Select Branch', '');

            $department = Department::get()->pluck('name', 'id');
            $department->prepend('Select Department', '');


            if (\Auth::user()->type != 'client' && \Auth::user()->type != 'company' && \Auth::user()->type != 'HR') {
               
                $emp = !empty(\Auth::user()->employee)?\Auth::user()->employee->id : 0;
                $employee = Employee::select('id','name')->where('is_active','=','1')->where('id',$emp)->get();

                $attendanceEmployee = AttendanceEmployee::where('employee_id', $emp);

                if ($request->type == 'monthly' && !empty($request->month)) {
                    $month = date('m', strtotime($request->month));
                    $year = date('Y', strtotime($request->month));
                    $date = Carbon::createFromFormat('Y-m',$request->month);
                    // Get the number of days in the month
                    $daysInMonth = $date->daysInMonth;
                    $start_date = date($year . '-' . $month . '-01');
                   // $end_date = date($year . '-' . $month . '-t');
                   $end_date = $date->endOfMonth()->toDateString();
                    $attendanceEmployee->whereBetween(
                        'date', [
                            $start_date,
                            $end_date,
                        ]
                    );
                } elseif ($request->type == 'daily' && !empty($request->date)) {
                    $attendanceEmployee->where('date', $request->date);
                } else {
                    $month = date('m');
                    $year = date('Y');
                    $start_date = date($year . '-' . $month . '-01');
                   // $end_date = date($year . '-' . $month . '-t');
                    $date = Carbon::createFromFormat('Y-m', $year.'-'.$month);
                    $end_date = $date->endOfMonth()->toDateString();
                    // Get the number of days in the month
                    $daysInMonth = $date->daysInMonth;
                    $attendanceEmployee->whereBetween(
                        'date', [
                            $start_date,
                            $end_date,
                        ]
                    );
                }
                $attendanceEmployee = $attendanceEmployee->get();
             
            } else {

                $employee = Employee::select('id','name')->where('is_active','=','1');

                if (!empty($request->branch)) {
                    $employee->where('branch_id', $request->branch);
                }

                if (!empty($request->department)) {
                    $employee->where('department_id', $request->department);
                }
                $employee = $employee->get();
                   
                if (!empty($request->employee)) {
                   $emp_id=$request->employee;
                  
                }else{
                   
                    $emp_id= $employee[0]->id;
                }

                $attendanceEmployee = AttendanceEmployee::where('employee_id', $emp_id);
               // echo $request->month;
                if ($request->type == 'monthly' && !empty($request->month)) {
                   
                    $date = Carbon::createFromFormat('Y-m',$request->month);
                    // Get the number of days in the month
                    $daysInMonth = $date->daysInMonth;

                    $month = date('m', strtotime($request->month));
                    $year = date('Y', strtotime($request->month));

                    $start_date = date($year . '-' . $month . '-01');
                // echo   $end_date = date($year . '-' . $month . '-t');
                 $end_date = $date->endOfMonth()->toDateString();
                    $attendanceEmployee->whereBetween(
                        'date', [
                            $start_date,
                            $end_date,
                        ]
                    );

            

                } elseif ($request->type == 'daily' && !empty($request->date)) {
                    $attendanceEmployee->where('date', $request->date);
                } else {
                    $month = date('m');
                    $year = date('Y');
                    $start_date = date($year . '-' . $month . '-01');
                  //  $end_date = date($year . '-' . $month . '-t');
                
                    $date = Carbon::createFromFormat('Y-m', $year.'-'.$month);
                    // Get the number of days in the month
                    $daysInMonth = $date->daysInMonth;
                    $end_date = $date->endOfMonth()->toDateString();
                    $attendanceEmployee->whereBetween(
                        'date', [
                            $start_date,
                            $end_date,
                        ]
                    );
                }
                
//                dd($attendanceEmployee->toSql(), $attendanceEmployee->getBindings());
                $attendanceEmployee = $attendanceEmployee->get();

            }
          
            return view('attendance.index', compact('attendanceEmployee', 'branch', 'department','daysInMonth','employee'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('create attendance')) {
            $employees = Employee::where('is_active','1')->get();
           // $employees->prepend('Select Employee', '');
            $company_start_time = Utility::getValByName('company_start_time');
            $company_end_time = Utility::getValByName('company_end_time');
            return view('attendance.create', compact('employees','company_start_time','company_end_time'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function store(Request $request)
    {
      
        if (\Auth::user()->can('create attendance')) {
            $validator = \Validator::make(
                $request->all(), [
                    'employee_id' => 'required',
                    'date' => 'required',
                    'clock_in' => 'required',
                    'clock_out' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

               // return redirect()->back()->with('error', $messages->first());
               return response()->json(['error' =>$messages->first()]);
            }

            $startTime = Utility::getValByName('company_start_time');
            $endTime = Utility::getValByName('company_end_time');
         
            $diff=strtotime($request->clock_out) - strtotime($request->clock_in);
          ///  $work_hours = floor($diff / 3600);
            $work_hours =number_format(($diff / 3600),2);
                     
            if($work_hours>='8.50')
            {
                $day_count=1;
            }else if($work_hours>='6' && $work_hours<'8.50')
            {
                $day_count=0.75;
            }else if($work_hours>='4' && $work_hours<'6')
            {
                $day_count=0.5;
            }else if($work_hours<'4' && $work_hours>='2')
            {
                $day_count=0.25;
            }else{
                $day_count=0;
            }
            
           
                if($request->type=='daily')
                {
                    $check_leave_holiday=Holiday::where('date',$request->date)->first();
                    if(empty($check_leave_holiday))
                    {
                        if($request->employee_id=='all')
            {
                $user=User::select('employees.id','employees.name')->where('is_enable_login','1')
                ->Join('employees','employees.user_id','users.id')->get();
              
                foreach($user as $user)
                {

                    $attendance = AttendanceEmployee::where('employee_id', '=', $user->id)->where('date', '=', $request->date)->get()->count();
                    $attendanceIds = AttendanceEmployee::where('employee_id', '=', $user->id)
                    ->where('date', '=', $request->date)
                    ->first();
                    $check_month_leave = LeaveMapping::where('leave_date', $request->date)
                           
                    ->join('leaves','leaves.id', '=','leave_mappings.leave_id' ) // for check monthly leave limit
                    ->where('leaves.employee_id',$request->employee_id)
                    //->where('leave_mappings.leave_type','Paid')
                    ->first();
                    if(empty($check_month_leave))
                    {
                    $date = date("Y-m-d");

                    $totalLateSeconds = strtotime($request->clock_in) - strtotime($date . $startTime);

                    $hours = floor($totalLateSeconds / 3600);
                    $mins = floor($totalLateSeconds / 60 % 60);
                    $secs = floor($totalLateSeconds % 60);

                    $late = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                    //early Leaving
                    $totalEarlyLeavingSeconds = strtotime($date . $endTime) - strtotime($request->clock_out);
                    $hours = floor($totalEarlyLeavingSeconds / 3600);
                    $mins = floor($totalEarlyLeavingSeconds / 60 % 60);
                    $secs = floor($totalEarlyLeavingSeconds % 60);
                    $earlyLeaving = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                    if (strtotime($request->clock_out) > strtotime($date . $endTime)) {
                        //Overtime
                        $totalOvertimeSeconds = strtotime($request->clock_out) - strtotime($date . $endTime);
                        $hours = floor($totalOvertimeSeconds / 3600);
                        $mins = floor($totalOvertimeSeconds / 60 % 60);
                        $secs = floor($totalOvertimeSeconds % 60);
                        $overtime = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                    } else {
                        $overtime = '00:00:00';
                    }
                    if ($attendance==0)
                    {
                     
                      $employeeAttendance = new AttendanceEmployee();
                        $employeeAttendance->employee_id = $user->id;
                        $employeeAttendance->date = $request->date;
                        $employeeAttendance->status = 'Present';
                        $employeeAttendance->clock_in = $request->clock_in . ':00';
                        $employeeAttendance->clock_out = $request->clock_out . ':00';
                        $employeeAttendance->late = $late;
                        $employeeAttendance->early_leaving = $earlyLeaving;
                        $employeeAttendance->overtime = $overtime;
                        $employeeAttendance->total_rest = '00:00:00';
                        $employeeAttendance->day_count=$day_count;
                        $employeeAttendance->late_mark=$request->late_mark;
                        $employeeAttendance->half_day=$request->half_day;
                        $employeeAttendance->created_by = \Auth::user()->id;
                    
                        $employeeAttendance->save();
                    }else{
                        $employeeAttendance = AttendanceEmployee::find($attendanceIds->id);
                        $employeeAttendance->employee_id = $user->id;
                        $employeeAttendance->date = $request->date;
                        $employeeAttendance->status = 'Present';
                        $employeeAttendance->clock_in = $request->clock_in . ':00';
                        $employeeAttendance->clock_out = $request->clock_out . ':00';
                        $employeeAttendance->late = $late;
                        $employeeAttendance->early_leaving = $earlyLeaving;
                        $employeeAttendance->overtime = $overtime;
                        $employeeAttendance->total_rest = '00:00:00';
                        $employeeAttendance->day_count=$day_count;
                        $employeeAttendance->late_mark=$request->late_mark;
                        $employeeAttendance->half_day=$request->half_day;
                        $employeeAttendance->created_by = \Auth::user()->id;
                    
                        $employeeAttendance->save();  
                    }
                }
                }
            }else{
                     $attendance = AttendanceEmployee::where('employee_id', '=', $request->employee_id)->where('date', '=', $request->date)->get()->count();
                     $attendanceIds = AttendanceEmployee::where('employee_id', '=', $request->employee_id)
                     ->where('date', '=', $request->date)
                     ->first();
                     $check_month_leave = LeaveMapping::where('leave_date', $request->date)
                           
                     ->join('leaves','leaves.id', '=','leave_mappings.leave_id' ) // for check monthly leave limit
                     ->where('leaves.employee_id',$request->employee_id)
                    // ->where('leave_mappings.leave_type','Paid')
                     ->first();
                   if(empty($check_month_leave))
                   {
                     $date = date("Y-m-d");

                     $totalLateSeconds = strtotime($request->clock_in) - strtotime($date . $startTime);

                     $hours = floor($totalLateSeconds / 3600);
                     $mins = floor($totalLateSeconds / 60 % 60);
                     $secs = floor($totalLateSeconds % 60);

                     $late = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                     //early Leaving
                     $totalEarlyLeavingSeconds = strtotime($date . $endTime) - strtotime($request->clock_out);
                     $hours = floor($totalEarlyLeavingSeconds / 3600);
                     $mins = floor($totalEarlyLeavingSeconds / 60 % 60);
                     $secs = floor($totalEarlyLeavingSeconds % 60);
                     $earlyLeaving = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                     if (strtotime($request->clock_out) > strtotime($date . $endTime)) {
                         //Overtime
                         $totalOvertimeSeconds = strtotime($request->clock_out) - strtotime($date . $endTime);
                         $hours = floor($totalOvertimeSeconds / 3600);
                         $mins = floor($totalOvertimeSeconds / 60 % 60);
                         $secs = floor($totalOvertimeSeconds % 60);
                         $overtime = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                     } else {
                         $overtime = '00:00:00';
                     }
                    if ($attendance==0)
                    {
                     
                        $employeeAttendance = new AttendanceEmployee();
                        $employeeAttendance->employee_id = $request->employee_id;
                        $employeeAttendance->date = $request->date;
                        $employeeAttendance->status = 'Present';
                        $employeeAttendance->clock_in = $request->clock_in . ':00';
                        $employeeAttendance->clock_out = $request->clock_out . ':00';
                        $employeeAttendance->late = $late;
                        $employeeAttendance->early_leaving = $earlyLeaving;
                        $employeeAttendance->overtime = $overtime;
                        $employeeAttendance->total_rest = '00:00:00';
                        $employeeAttendance->day_count=$day_count;
                        $employeeAttendance->late_mark=$request->late_mark;
                        $employeeAttendance->half_day=$request->half_day;
                        $employeeAttendance->created_by = \Auth::user()->id;
                    
                        $employeeAttendance->save();
                    }else{
                        $employeeAttendance = AttendanceEmployee::find($attendanceIds->id);
                        $employeeAttendance->employee_id = $request->employee_id;
                        $employeeAttendance->date = $request->date;
                        $employeeAttendance->status = 'Present';
                        $employeeAttendance->clock_in = $request->clock_in . ':00';
                        $employeeAttendance->clock_out = $request->clock_out . ':00';
                        $employeeAttendance->late = $late;
                        $employeeAttendance->early_leaving = $earlyLeaving;
                        $employeeAttendance->overtime = $overtime;
                        $employeeAttendance->total_rest = '00:00:00';
                        $employeeAttendance->day_count=$day_count;
                        $employeeAttendance->late_mark=$request->late_mark;
                        $employeeAttendance->half_day=$request->half_day;
                        $employeeAttendance->created_by = \Auth::user()->id;
                    
                        $employeeAttendance->save();
                    }
                }
            }
                }
                }else if($request->type=='month' && $request->employee_id!='all'){
                   
                    list($year, $month) = explode('-', $request->month_date);

                    // Get the number of days in the given month
                    $daysInMonth = Carbon::createFromDate(trim($year), trim($month))->daysInMonth;
                    
                    // Initialize an array to store the dates
                    $dates = [];
                    
                    // Loop through each day of the month and add it to the dates array
                    for ($day = 1; $day <= $daysInMonth; $day++) {
                       
                        // Create a Carbon instance for the current date
                       $date1 = Carbon::create(trim($year),trim($month),trim($day));
                     
                        // Add the date to the dates array
                        $dates[] = $date1->toDateString(); // Adds date in "Y-m-d" format to $dates array
                    }

                    $date = date("Y-m-d");

                    // Example: Check condition for each date in $dates array
                    foreach ($dates as $dates) {
                        if ($dates <= $date) {
                     
                            $check_month_leave = LeaveMapping::where('leave_date', $dates)
                           
                            ->join('leaves','leaves.id', '=','leave_mappings.leave_id' ) // for check monthly leave limit
                            ->where('leaves.employee_id',$request->employee_id)
                           // ->where('leave_mappings.leave_type','Paid')
                            ->count();

                          
                        $check_leave_holiday=Holiday::where('holiday_year',$year)->where('date',$dates)->first();
                        if(empty($check_leave_holiday))
                            {
                                if(empty($check_month_leave))
                                {
                        $attendance = AttendanceEmployee::where('employee_id', '=', $request->employee_id)->where('date', '=',$dates)->get()->count();
                        $attendanceIds =AttendanceEmployee::where('employee_id', '=', $request->employee_id)
                        ->where('date', '=', $dates)
                        ->first();
                       
                        $totalLateSeconds = strtotime($request->clock_in) - strtotime($date . $startTime);
    
                        $hours = floor($totalLateSeconds / 3600);
                        $mins = floor($totalLateSeconds / 60 % 60);
                        $secs = floor($totalLateSeconds % 60);

                        $late = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                        //early Leaving
                        $totalEarlyLeavingSeconds = strtotime($date . $endTime) - strtotime($request->clock_out);
                        $hours = floor($totalEarlyLeavingSeconds / 3600);
                        $mins = floor($totalEarlyLeavingSeconds / 60 % 60);
                        $secs = floor($totalEarlyLeavingSeconds % 60);
                        $earlyLeaving = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                        if (strtotime($request->clock_out) > strtotime($date . $endTime)) {
                            //Overtime
                            $totalOvertimeSeconds = strtotime($request->clock_out) - strtotime($date . $endTime);
                            $hours = floor($totalOvertimeSeconds / 3600);
                            $mins = floor($totalOvertimeSeconds / 60 % 60);
                            $secs = floor($totalOvertimeSeconds % 60);
                            $overtime = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                        } else {
                            $overtime = '00:00:00';
                        }
                        if ($attendance==0)
                        {
    
    
                            $employeeAttendance = new AttendanceEmployee();
                            $employeeAttendance->employee_id = $request->employee_id;
                            $employeeAttendance->date = $dates;
                            $employeeAttendance->status = 'Present';
                            $employeeAttendance->clock_in = $request->clock_in . ':00';
                            $employeeAttendance->clock_out = $request->clock_out . ':00';
                            $employeeAttendance->late = $late;
                            $employeeAttendance->early_leaving = $earlyLeaving;
                            $employeeAttendance->overtime = $overtime;
                            $employeeAttendance->total_rest = '00:00:00';
                            $employeeAttendance->day_count=$day_count;
                            $employeeAttendance->late_mark=$request->late_mark;
                            $employeeAttendance->half_day=$request->half_day;
                            $employeeAttendance->created_by = \Auth::user()->id;
                        
                            $employeeAttendance->save();
                        }else{
                            
                            $employeeAttendance = AttendanceEmployee::find($attendanceIds->id);
                            $employeeAttendance->employee_id = $request->employee_id;
                            $employeeAttendance->date = $dates;
                            $employeeAttendance->status = 'Present';
                            $employeeAttendance->clock_in = $request->clock_in . ':00';
                            $employeeAttendance->clock_out = $request->clock_out . ':00';
                            $employeeAttendance->late = $late;
                            $employeeAttendance->early_leaving = $earlyLeaving;
                            $employeeAttendance->overtime = $overtime;
                            $employeeAttendance->total_rest = '00:00:00';
                            $employeeAttendance->day_count=$day_count;
                            $employeeAttendance->late_mark=$request->late_mark;
                            $employeeAttendance->half_day=$request->half_day;
                            $employeeAttendance->created_by = \Auth::user()->id;
                        
                            $employeeAttendance->save(); 
                        }
                    }
                   }
                }
                    }
                }else{
                    return response()->json(['success' =>'Please select particular employee.']);
   
                }
                return response()->json(['success' =>'Employee attendance successfully created.']);


               // return redirect()->route('attendanceemployee.index')->with('success', __('Employee attendance successfully created.'));
          
        } else {
          //  return redirect()->back()->with('error', __('Permission denied.'));
          return response()->json(['error'=>'Permission denied']);
        }
    }

    public function show()
    {
        return redirect()->route('attendanceemployee.index');
    }

    public function edit($id)
    {
      
        if (\Auth::user()->can('edit attendance')) {
            $attendanceEmployee = AttendanceEmployee::where('id', $id)->first();
            $employees = Employee::where('is_active','=','1')->get()->pluck('name', 'id');

            return view('attendance.edit', compact('attendanceEmployee', 'employees'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function attendance_update(Request $request,$id)
    {
       
        $employeeId = !empty(\Auth::user()->employee)?\Auth::user()->employee->id : 0;

        $todayAttendance = AttendanceEmployee::where('employee_id', '=', $employeeId)->where('date', date('Y-m-d'))->orderBy('id', 'desc')->first();
       
        $startTime = Utility::getValByName('company_start_time');
        $endTime = Utility::getValByName('company_end_time');

        $attendance = AttendanceEmployee::orderBy('id', 'desc')->where('employee_id', '=', $employeeId)->where('clock_out', '=', '00:00:00')->first();

      

        $date = date("Y-m-d");
        $time = date("H:i:s ");
      
        $clockIn = $attendance->clock_in;
            $clockOut = date('H:i:00');
            $diff=strtotime($clockOut) - strtotime($clockIn);
        
            $work_hours =number_format(($diff / 3600),2);
         
            
            if($work_hours>='8.50')
            {
                $day_count=1;
            }else if($work_hours>='6' && $work_hours<'8.50')
            {
                $day_count=0.75;
            }else if($work_hours>='4' && $work_hours<'6')
            {
                $day_count=0.5;
            }else if($work_hours<'4' && $work_hours>='2')
            {
                $day_count=0.25;
            }else{
                $day_count=0;
            }
            if ($clockIn) {
                $status = "present";
            } else {
                $status = "leave";
            }
            if ($attendance != null) {
                $attendance = AttendanceEmployee::find($attendance->id);
                $attendance->clock_out = date('H:i:00');
                $attendance->day_count=$day_count;
                $attendance->save();
                return response()->json(['success'=>'Employee Successfully  Clock Out.']);
            }else{
                return response()->json(['success'=>'Employee already Clock Out.']);
            }
     
           

    }
    public function update(Request $request, $id)
    {
        //        dd($request->all());
       
        if (\Auth::user()->can('edit attendance')){
        
            $employeeId = AttendanceEmployee::where('employee_id', $request->employee_id)->first();
            $check = AttendanceEmployee::where('id',$id)->where('employee_id', '=', $request->employee_id)->where('date', $request->date)->first();
            // dd($check->date);

            $startTime = Utility::getValByName('company_start_time');
            $endTime = Utility::getValByName('company_end_time');

            $clockIn = $request->clock_in;
            $clockOut = $request->clock_out;
            $diff=strtotime($clockOut) - strtotime($clockIn);
            $work_hours =number_format(($diff / 3600),2);
         
           
            
            if($work_hours>='8.50')
            {
                $day_count=1;
            }else if($work_hours>='6' && $work_hours<'8.50')
            {
                $day_count=0.75;
            }else if($work_hours>='4' && $work_hours<'6')
            {
                $day_count=0.5;
            }else if($work_hours<'4' && $work_hours>='2')
            {
                $day_count=0.25;
            }else{
                $day_count=0;
            }
            if ($clockIn) {
                $status = "present";
            } else {
                $status = "leave";
            }
           
            $totalLateSeconds = strtotime($clockIn) - strtotime($startTime);

            $hours = floor($totalLateSeconds / 3600);
            $mins = floor($totalLateSeconds / 60 % 60);
            $secs = floor($totalLateSeconds % 60);
            $late = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

            $totalEarlyLeavingSeconds = strtotime($endTime) - strtotime($clockOut);
            $hours = floor($totalEarlyLeavingSeconds / 3600);
            $mins = floor($totalEarlyLeavingSeconds / 60 % 60);
            $secs = floor($totalEarlyLeavingSeconds % 60);
            $earlyLeaving = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
        
            if (strtotime($clockOut) > strtotime($endTime)) {
                //Overtime
                $totalOvertimeSeconds = strtotime($clockOut) - strtotime($endTime);
                $hours = floor($totalOvertimeSeconds / 3600);
                $mins = floor($totalOvertimeSeconds / 60 % 60);
                $secs = floor($totalOvertimeSeconds % 60);
                $overtime = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
            } else {
                $overtime = '00:00:00';
            }
          //   dd($check->date == date('Y-m-d'));
            //echo $check->date;
            
            // if ($check->date == date('Y-m-d')) {
                $check->update([
                    'late' => $late,
                    'early_leaving' => ($earlyLeaving > 0) ? $earlyLeaving : '00:00:00',
                    'overtime' => $overtime,
                    'clock_in' => $clockIn,
                    'clock_out' => $clockOut,
                    'biometric_clock_in' => $clockIn,
                    'biometric_clock_out' => $clockOut,
                    'day_count'=>$day_count,
                    'half_day'=>$request->half_day,
                    'late_mark'=>$request->late_mark,
                ]);
                return response()->json(['success'=>'Employee attendance successfully updated.']);
                //return redirect()->route('attendanceemployee.index')->with('success', __('Employee attendance successfully updated.'));
            // } else {
            //     return redirect()->route('attendanceemployee.index')->with('error', __('you can only update current day attendance.'));
            // }
        }

    }

    public function destroy($id)
    {
        if (\Auth::user()->can('delete attendance')) {
            $attendance = AttendanceEmployee::where('id', $id)->first();

            $attendance->delete();

            return redirect()->route('attendanceemployee.index')->with('success', __('Attendance successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function attendance(Request $request)
    {
        $settings = Utility::settings();

        if ($settings['ip_restrict'] == 'on') {
            $userIp = request()->ip();
            $ip = IpRestrict::where('created_by', \Auth::user()->id)->whereIn('ip', [$userIp])->first();
            if (!empty($ip)) {
                return redirect()->back()->with('error', __('This ip is not allowed to clock in & clock out.'));
            }
        }
        $employeeId = !empty(\Auth::user()->employee)?\Auth::user()->employee->id : 0;

        $todayAttendance = AttendanceEmployee::where('employee_id', '=', $employeeId)->where('date', date('Y-m-d'))->orderBy('id', 'desc')->first();
        //        if(empty($todayAttendance))
        //        {

        $startTime = Utility::getValByName('company_start_time');
        $endTime = Utility::getValByName('company_end_time');

       
        $attendance = AttendanceEmployee::orderBy('id', 'desc')->where('employee_id', '=', $employeeId)->where('clock_out', '=', '00:00:00')->first();

        if ($attendance != null) {

            
            $attendance = AttendanceEmployee::find($attendance->id);
            $attendance->clock_out = '00:00:00';
            $attendance->save();
        }

        $date = date("Y-m-d");
        $time = date("H:i:s ");
      

        if (!empty($todayAttendance)) {
            $startTime = $todayAttendance->clock_out;
        }
      
        $totalLateSeconds = time() - strtotime($date . $startTime);

        $hours = abs(floor($totalLateSeconds / 3600));
        $mins = abs(floor($totalLateSeconds / 60 % 60));
        $secs = abs(floor($totalLateSeconds % 60));

        $late = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

        $checkDb = AttendanceEmployee::where('employee_id', '=',$employeeId)->where('date','=',$date)->count();

        if ($checkDb==0) {
            $employeeAttendance = new AttendanceEmployee();
            $employeeAttendance->employee_id = $employeeId;
            $employeeAttendance->date = $date;
            $employeeAttendance->status = 'Present';
            $employeeAttendance->clock_in = $time;
            $employeeAttendance->clock_out = '00:00:00';
            $employeeAttendance->late = $late;
            $employeeAttendance->early_leaving = '00:00:00';
            $employeeAttendance->overtime = '00:00:00';
            $employeeAttendance->total_rest = '00:00:00';
            $employeeAttendance->created_by = \Auth::user()->id;
            $employeeAttendance->day_count='0';
            $employeeAttendance->save();

         
            return response()->json(['success'=>'Employee Successfully Clock In.']);
        }
        
        //        }
        //        else
        //        {
        //            return redirect()->back()->with('error', __('Employee are not allow multiple time clock in & clock for every day.'));
        //        }
    }

    public function bulkAttendance(Request $request)
    {
        if (\Auth::user()->can('create attendance')) {

            $branch = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $branch->prepend('Select Branch', '');

            $department = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $department->prepend('Select Department', '');

            $employees = [];
            if (!empty($request->branch) && !empty($request->department)) {
                $employees = Employee::where('created_by', \Auth::user()->creatorId())->where('branch_id', $request->branch)->where('department_id', $request->department)->get();

            } else {
                $employees = Employee::where('created_by', \Auth::user()->creatorId())->where('branch_id', 1)->where('department_id', 1)->get();
            }

            return view('attendance.bulk', compact('employees', 'branch', 'department'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function bulkAttendanceData(Request $request)
    {

        if (\Auth::user()->can('create attendance')) {
            if (!empty($request->branch) && !empty($request->department)) {
                $startTime = Utility::getValByName('company_start_time');
                $endTime = Utility::getValByName('company_end_time');
                $date = $request->date;

                $employees = $request->employee_id;
                $atte = [];

                if (!empty($employees)) {
                    foreach ($employees as $employee) {
                        $present = 'present-' . $employee;
                        $in = 'in-' . $employee;
                        $out = 'out-' . $employee;
                        $atte[] = $present;
                        if ($request->$present == 'on') {

                            $in = date("H:i:s", strtotime($request->$in));
                            $out = date("H:i:s", strtotime($request->$out));

                            $totalLateSeconds = strtotime($in) - strtotime($startTime);

                            $hours = floor($totalLateSeconds / 3600);
                            $mins = floor($totalLateSeconds / 60 % 60);
                            $secs = floor($totalLateSeconds % 60);
                            $late = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                            //early Leaving
                            $totalEarlyLeavingSeconds = strtotime($endTime) - strtotime($out);
                            $hours = floor($totalEarlyLeavingSeconds / 3600);
                            $mins = floor($totalEarlyLeavingSeconds / 60 % 60);
                            $secs = floor($totalEarlyLeavingSeconds % 60);
                            $earlyLeaving = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                            if (strtotime($out) > strtotime($endTime)) {
                                //Overtime
                                $totalOvertimeSeconds = strtotime($out) - strtotime($endTime);
                                $hours = floor($totalOvertimeSeconds / 3600);
                                $mins = floor($totalOvertimeSeconds / 60 % 60);
                                $secs = floor($totalOvertimeSeconds % 60);
                                $overtime = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                            } else {
                                $overtime = '00:00:00';
                            }
                            $attendance = AttendanceEmployee::where('employee_id', '=', $employee)->where('date', '=', $request->date)->first();

                            if (!empty($attendance)) {
                                $employeeAttendance = $attendance;
                            } else {
                                $employeeAttendance = new AttendanceEmployee();
                                $employeeAttendance->employee_id = $employee;
                                $employeeAttendance->created_by = \Auth::user()->creatorId();
                            }
                            $employeeAttendance->date = $request->date;
                            $employeeAttendance->status = 'Present';
                            $employeeAttendance->clock_in = $in;
                            $employeeAttendance->clock_out = $out;
                            $employeeAttendance->late = $late;
                            $employeeAttendance->early_leaving = ($earlyLeaving > 0) ? $earlyLeaving : '00:00:00';
                            $employeeAttendance->overtime = $overtime;
                            $employeeAttendance->total_rest = '00:00:00';
                            $employeeAttendance->save();

                        } else {
                            $attendance = AttendanceEmployee::where('employee_id', '=', $employee)->where('date', '=', $request->date)->first();

                            if (!empty($attendance)) {
                                $employeeAttendance = $attendance;
                            } else {
                                $employeeAttendance = new AttendanceEmployee();
                                $employeeAttendance->employee_id = $employee;
                                $employeeAttendance->created_by = \Auth::user()->creatorId();
                            }

                            $employeeAttendance->status = 'Leave';
                            $employeeAttendance->date = $request->date;
                            $employeeAttendance->clock_in = '00:00:00';
                            $employeeAttendance->clock_out = '00:00:00';
                            $employeeAttendance->late = '00:00:00';
                            $employeeAttendance->early_leaving = '00:00:00';
                            $employeeAttendance->overtime = '00:00:00';
                            $employeeAttendance->total_rest = '00:00:00';
                            $employeeAttendance->save();
                        }
                    }
                } else {
                    return redirect()->back()->with('error', __('Employee not found.'));
                }

                return redirect()->back()->with('success', __('Employee attendance successfully created.'));
            } else {
                return redirect()->back()->with('error', __('Branch & department field required.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    //for attendance employee report
    public function importFile()
    {
        return view('attendance.import');
    }

    public function import_bk(Request $request)
    {
        $settings = Utility::settings();
        if (\Auth::user()->can('create attendance')) { 
        $rules = [
            'file' => 'required|mimes:csv,xlsx',
        ];
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $attendance = (new AttendanceImport())->toArray(request()->file('file'))[0];
      
        $minDateTime = [];
        $maxDateTime = [];
     
        // Loop through the Excel data
      
foreach ($attendance as $row) {
    $id = $row[0]; // Extract ID
    $dateTimeString = $row[1]; // The date-time string in 'YYYY-MM-DD HH:MM:SS'

    // Create a DateTime object from the string
    $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $dateTimeString);

    // Extract date and time separately
    $date = $dateTime->format('Y-m-d');
    $time = $dateTime->format('H:i');

    // Update min and max time for each date and ID
    if (!isset($minDateTime[$date][$id]) || $time < $minDateTime[$date][$id]) {
        $minDateTime[$date][$id] = $time;
    }
    if (!isset($maxDateTime[$date][$id]) || $time > $maxDateTime[$date][$id]) {
        $maxDateTime[$date][$id] = $time;
    }
}

    
        $result = [];
        foreach ($minDateTime as $date => $idClock) {
            
            foreach ($idClock as $id => $minTime) {
               
                $result[] = [
                    'employee_code' => $id,
                    'date'=>$date,
                    'clock_in' =>  $minTime,
                    'clock_out' =>$maxDateTime[$date][$id]
                ];
            }
           
        }
           
        // Output the result
      
        $email_data = [];
        foreach ($result as $key => $employee) {
            //if ($key != 0) {
              //  echo "<pre>";
             
              $excelPrefix = $settings['employee_prefix'];

              
                // Excel employee ID
                $excelEmployeeId = $employee['employee_code'];

                // Remove the prefix from the Excel employee ID
               $excelEmployeeIdWithoutPrefix = str_replace($excelPrefix, '', $excelEmployeeId);

                if ($employee != null && Employee::where('employee_id', $excelEmployeeIdWithoutPrefix)->exists()) {
                     $email = $employee['employee_code'];
                } else {
                     $email_data[] = $employee['employee_code'];
                }
           // }
        }
       // $totalattendance = count($attendance) - 1;
       //echo $totalattendance = count($attendance);
       
        $errorArray = [];

        $startTime = Utility::getValByName('company_start_time');
        $endTime = Utility::getValByName('company_end_time');

       
        if (!empty($attendanceData)) {
            $errorArray[] = $attendanceData;
        } else {
            
            foreach ($result as $key => $employee) {
              //  if ($key != 0) {
              
                $check_leave_holiday=Holiday::where('date',$employee['date'])->first();
              
                if(empty($check_leave_holiday))
                {
              $excelPrefix = $settings['employee_prefix'];
              // Excel employee ID
              $excelEmployeeId = $employee['employee_code'];

                // Remove the prefix from the Excel employee ID
               $excelEmployeeIdWithoutPrefix = str_replace($excelPrefix, '', $excelEmployeeId);
               $employeeData = Employee::where('employee_id', $excelEmployeeIdWithoutPrefix)->first();
                    // $employeeId = 0;
                  
                    if (!empty($employeeData)) {
                        $employeeId = $employeeData->id;
                       
                        $clockIn = $employee['clock_in'];
                        $clockOut = $employee['clock_out'];
                         $diff=strtotime($clockOut) - strtotime($clockIn);
                        $work_hours =number_format(($diff / 3600),2);
                     
                        if ($clockIn) {
                            $status = "present";
                        } else {
                            $status = "leave";
                        }

                        $totalLateSeconds = strtotime($clockIn) - strtotime($startTime);

                        $hours = floor($totalLateSeconds / 3600);
                        $mins = floor($totalLateSeconds / 60 % 60);
                        $secs = floor($totalLateSeconds % 60);
                        $late = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                        $totalEarlyLeavingSeconds = strtotime($endTime) - strtotime($clockOut);
                        $hours = floor($totalEarlyLeavingSeconds / 3600);
                        $mins = floor($totalEarlyLeavingSeconds / 60 % 60);
                        $secs = floor($totalEarlyLeavingSeconds % 60);
                        $earlyLeaving = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                        if (strtotime($clockOut) > strtotime($endTime)) {
                            //Overtime
                            $totalOvertimeSeconds = strtotime($clockOut) - strtotime($endTime);
                            $hours = floor($totalOvertimeSeconds / 3600);
                            $mins = floor($totalOvertimeSeconds / 60 % 60);
                            $secs = floor($totalOvertimeSeconds % 60);
                            $overtime = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                        } else {
                            $overtime = '00:00:00';
                        }
                      
                        if($work_hours>='8.50')
                        {
                            $day_count=1;
                        }else if($work_hours>='6' && $work_hours<'8.50')
                        {
                            $day_count=0.75;
                        }else if($work_hours>='4' && $work_hours<'6')
                        {
                            $day_count=0.5;
                        }else if($work_hours<'4' && $work_hours>='2')
                        {
                            $day_count=0.25;
                        }else{
                            $day_count=0;
                        }
                    
                        $check = AttendanceEmployee::where('employee_id', $employeeId)->where('date', $employee['date'])->first();
                      
                        if ($check) {
                            $check->update([
                                'late' => $late,
                                'early_leaving' => ($earlyLeaving > 0) ? $earlyLeaving : '00:00:00',
                                'overtime' => $overtime,
                                'clock_in' => $employee['clock_in'],
                                'clock_out' => $employee['clock_out'],
                                'day_count'=>$day_count,
                                
                            ]);
                        } else {
                            $time_sheet = AttendanceEmployee::create([
                                'employee_id' => $employeeId,
                                'date' => $employee['date'],
                                'status' => $status,
                                'late' => $late,
                                'early_leaving' => ($earlyLeaving > 0) ? $earlyLeaving : '00:00:00',
                                'overtime' => $overtime,
                                'clock_in' => $employee['clock_in'],
                                'clock_out' => $employee['clock_out'],
                                'day_count'=>$day_count,
                                'created_by' => \Auth::user()->id,
                            ]);
                        }
                    }
                }
            }
          
                if (empty($errorArray)) {
                    $data['status'] = 'success';
                    $data['msg'] = __('Record successfully imported');
                } else {

                    $data['status'] = 'error';
                    //$data['msg'] = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalattendance . ' ' . 'record');

                    foreach ($errorArray as $errorData) {
                        $errorRecord[] = implode(',', $errorData->toArray());
                    }

                    \Session::put('errorArray', $errorRecord);
                }

               return redirect()->back()->with($data['status'], $data['msg']);
          
        }
    }else{
        return redirect()->back()->with('error', __('Permission denied.'));
    }
    }

    public function import(Request $request)
    {
        $settings = Utility::settings();
        if (\Auth::user()->can('create attendance')) { 
        $rules = [
            'file' => 'required|mimes:csv,xlsx',
        ];
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $attendance = (new AttendanceImport())->toArray(request()->file('file'))[0];
      
        $minDateTime = [];
        $maxDateTime = [];
     
        // Loop through the Excel data
      
foreach ($attendance as $row) {
  
    $id = $row[0]; // Extract ID
    $dateTimeString = $row[1]; // The date-time string in 'YYYY-MM-DD HH:MM:SS'
    $dateTime = null;

    // Check if the date is in Excel's serial date format
    // if (is_numeric($dateTimeString)) {
    //     // Convert Excel serial date to Unix timestamp
    //     // Excel's base date is January 1, 1900, which is timestamp 25569 days since Unix epoch (January 1, 1970)
    //     // Multiply by 86400 to convert days to seconds
    //     $timestamp = ($dateTimeString - 25569) * 86400;

    //     // Create DateTime object from timestamp
    //     $dateTime = new \DateTime();
    //     $dateTime->setTimestamp($timestamp);
    // }
    // // Other date formats can be checked here...
    // else {
    //     // Check for 'Y-m-d H:i' or other formats as previously discussed
    //     if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}(:\d{2})?$/', $dateTimeString)) {
    //         $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $dateTimeString);
    //         if ($dateTime === false) {
    //             $dateTime = \DateTime::createFromFormat('Y-m-d H:i', $dateTimeString);
    //         }
    //     } elseif (preg_match('/^\d{2}-\d{2}-\d{4} \d{2}:\d{2}(:\d{2})?$/', $dateTimeString)) {
    //         $dateTime = \DateTime::createFromFormat('d-m-Y H:i:s', $dateTimeString);
    //         if ($dateTime === false) {
    //             $dateTime = \DateTime::createFromFormat('d-m-Y H:i', $dateTimeString);
    //         }
    //     } else {
    //         error_log("Invalid date-time format for ID $id: $dateTimeString");
    //         continue; // Skip to the next iteration
    //     }
    // }
    // Create a DateTime object from the string
    $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $dateTimeString);

    // Extract date and time separately
    $date = $dateTime->format('Y-m-d');
    $time = $dateTime->format('H:i');

    // Update min and max time for each date and ID
    if (!isset($minDateTime[$date][$id]) || $time < $minDateTime[$date][$id]) {
        $minDateTime[$date][$id] = $time;
    }
    if (!isset($maxDateTime[$date][$id]) || $time > $maxDateTime[$date][$id]) {
        $maxDateTime[$date][$id] = $time;
    }
}

    
        $result = [];
        foreach ($minDateTime as $date => $idClock) {
            
            foreach ($idClock as $id => $minTime) {
               
                $result[] = [
                    'employee_code' => $id,
                    'date'=>$date,
                    'clock_in' =>  $minTime,
                    'clock_out' =>$maxDateTime[$date][$id]
                ];
            }
           
        }
           
        // Output the result
      
        $email_data = [];
        foreach ($result as $key => $employee) {
            //if ($key != 0) {
              //  echo "<pre>";
             
              $excelPrefix = $settings['employee_prefix'];

              
                // Excel employee ID
                $excelEmployeeId = $employee['employee_code'];

                // Remove the prefix from the Excel employee ID
               $excelEmployeeIdWithoutPrefix = str_replace($excelPrefix, '', $excelEmployeeId);

                if ($employee != null && Employee::where('employee_id', $excelEmployeeIdWithoutPrefix)->exists()) {
                     $email = $employee['employee_code'];
                } else {
                     $email_data[] = $employee['employee_code'];
                }
           // }
        }
       // $totalattendance = count($attendance) - 1;
       //echo $totalattendance = count($attendance);
       
        $errorArray = [];

        $startTime = Utility::getValByName('company_start_time');
        $endTime = Utility::getValByName('company_end_time');

       
        if (!empty($attendanceData)) {
            $errorArray[] = $attendanceData;
        } else {
            
            foreach ($result as $key => $employee) {
              //  if ($key != 0) {
              
                $check_leave_holiday=Holiday::where('date',$employee['date'])->first();
              
                if(empty($check_leave_holiday))
                {
              $excelPrefix = $settings['employee_prefix'];
              // Excel employee ID
              $excelEmployeeId = $employee['employee_code'];

                // Remove the prefix from the Excel employee ID
               $excelEmployeeIdWithoutPrefix = str_replace($excelPrefix, '', $excelEmployeeId);
               $employeeData = Employee::where('employee_id', $excelEmployeeIdWithoutPrefix)->first();
                    // $employeeId = 0;
                  
                    if (!empty($employeeData)) {
                        $employeeId = $employeeData->id;
                       
                        $clockIn = $employee['clock_in'];
                        $clockOut = $employee['clock_out'];
                         $diff=strtotime($clockOut) - strtotime($clockIn);
                        $work_hours =number_format(($diff / 3600),2);
                     
                        if ($clockIn) {
                            $status = "present";
                        } else {
                            $status = "leave";
                        }

                        $totalLateSeconds = strtotime($clockIn) - strtotime($startTime);

                        $hours = floor($totalLateSeconds / 3600);
                        $mins = floor($totalLateSeconds / 60 % 60);
                        $secs = floor($totalLateSeconds % 60);
                        $late = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                        $totalEarlyLeavingSeconds = strtotime($endTime) - strtotime($clockOut);
                        $hours = floor($totalEarlyLeavingSeconds / 3600);
                        $mins = floor($totalEarlyLeavingSeconds / 60 % 60);
                        $secs = floor($totalEarlyLeavingSeconds % 60);
                        $earlyLeaving = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                        if (strtotime($clockOut) > strtotime($endTime)) {
                            //Overtime
                            $totalOvertimeSeconds = strtotime($clockOut) - strtotime($endTime);
                            $hours = floor($totalOvertimeSeconds / 3600);
                            $mins = floor($totalOvertimeSeconds / 60 % 60);
                            $secs = floor($totalOvertimeSeconds % 60);
                            $overtime = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
                        } else {
                            $overtime = '00:00:00';
                        }
                      
                        if($work_hours>='8.50')
                        {
                            $day_count=1;
                        }else if($work_hours>='6' && $work_hours<'8.50')
                        {
                            $day_count=0.75;
                        }else if($work_hours>='4' && $work_hours<'6')
                        {
                            $day_count=0.5;
                        }else if($work_hours<'4' && $work_hours>='2')
                        {
                            $day_count=0.25;
                        }else{
                            $day_count=0;
                        }
                    
                        $check = AttendanceEmployee::where('employee_id', $employeeId)->where('date', $employee['date'])->first();
                    
                        if ($check) {
                          
                          $check->update([
                                'late' => $late,
                                'early_leaving' => ($earlyLeaving > 0) ? $earlyLeaving : '00:00:00',
                                'overtime' => $overtime,
                                'biometric_clock_in' => $employee['clock_in'],
                                'biometric_clock_out' => $employee['clock_out'],
                                'clock_in' => $employee['clock_in'],
                                'clock_out' => $employee['clock_out'],
                                'day_count'=>$day_count,
                                
                            ]);
                           
                        } else {
                           
                            $time_sheet = AttendanceEmployee::create([
                                'employee_id' => $employeeId,
                                'date' => $employee['date'],
                                'status' => $status,
                                'late' => $late,
                                'early_leaving' => ($earlyLeaving > 0) ? $earlyLeaving : '00:00:00',
                                'overtime' => $overtime,
                                'biometric_clock_in' => $employee['clock_in'],
                                'biometric_clock_out' => $employee['clock_out'],
                                'clock_in' => $employee['clock_in'],
                                'clock_out' => $employee['clock_out'],
                                'day_count'=>$day_count,
                                'created_by' => \Auth::user()->id,
                            ]);
                        }
                       
                    }
                }
            }
          
                if (empty($errorArray)) {
                    $data['status'] = 'success';
                    $data['msg'] = __('Record successfully imported');
                } else {

                    $data['status'] = 'error';
                    //$data['msg'] = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalattendance . ' ' . 'record');

                    foreach ($errorArray as $errorData) {
                        $errorRecord[] = implode(',', $errorData->toArray());
                    }

                    \Session::put('errorArray', $errorRecord);
                }

               return redirect()->back()->with($data['status'], $data['msg']);
          
        }
    }else{
        return redirect()->back()->with('error', __('Permission denied.'));
    }
    }

}
