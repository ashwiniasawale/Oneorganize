<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRequest;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Utility;
use App\Models\User;
use App\Exports\TaskExport;
use Illuminate\Support\Facades\Mail;
use App\Mail\AttRequestEmail;
use App\Models\AttendanceEmployee;

class AttendanceRequestController extends Controller
{
    //
    public function index(Request $request)
    {
        if(\Auth::user()->can('manage attendance'))
        {
            $user     = \Auth::user();
            if(\Auth::user()->type =='company' || \Auth::user()->type =='HR')
            {
               
                $attendance_request = AttendanceRequest::select('attendance_requests.id','attendance_requests.status', 'attendance_requests.employee_id','attendance_requests.date','attendance_requests.clock_in','attendance_requests.clock_out', 'attendance_requests.attendance_reason', 'attendance_requests.created_at', 'attendance_requests.updated_at', 'employees.name as employee_name')
                ->join('employees','employees.id', '=','attendance_requests.employee_id' )
                ->orderBy('attendance_requests.date','desc')
                ->get();            
            }
            else
            {
                $attendance_request = AttendanceRequest::select('attendance_requests.id','attendance_requests.status', 'attendance_requests.employee_id','attendance_requests.date','attendance_requests.clock_in','attendance_requests.clock_out', 'attendance_requests.attendance_reason', 'attendance_requests.created_at', 'attendance_requests.updated_at', 'employees.name as employee_name')
                ->join('employees','employees.id', '=','attendance_requests.employee_id' )
                ->where('employees.user_id', '=', $user->id)
                ->orderBy('attendance_requests.date','desc')
                ->get();

                       
            }
           
            return view('attendanceRequest.index', compact('attendance_request'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if(\Auth::user()->type =='company' || \Auth::user()->type =='HR')
            {
                $employees = Employee::where('is_active','=','1')->get()->pluck('name', 'id');
            }
            else
            {
                $employees = Employee::where('is_active','=','1')->where('user_id', '=', \Auth::user()->id)->first();
            }
        // $employees->prepend('Select Employee', '');
         $company_start_time = Utility::getValByName('company_start_time');
         $company_end_time = Utility::getValByName('company_end_time');
         return view('attendanceRequest.create', compact('employees','company_start_time','company_end_time'));
    }

    public function store(Request $request)
    {
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

        $check=AttendanceRequest::where('employee_id','=',$request->employee_id)->where('date','=',$request->date)->count();
        if($check>0)
        {
            return response()->json(['success'=>'Request already added for the date.']);
        }else{
        $employeeAttendance = new AttendanceRequest();
        $employeeAttendance->employee_id = $request->employee_id;
        $employeeAttendance->date = $request->date;
        $employeeAttendance->status='Pending';
        $employeeAttendance->clock_in = $request->clock_in . ':00';
        $employeeAttendance->clock_out = $request->clock_out . ':00';
        $employeeAttendance->attendance_reason = $request->attendance_reason;
     
        $employeeAttendance->created_by = \Auth::user()->id;
    
        $employeeAttendance->save();
        $user = User::select('name','id','email')->where('type','=','HR')->where('is_enable_login','1')->first();
        Mail::to($user->email)->send(new AttRequestEmail($request->employee_id,$request->date));
        return response()->json(['success' =>'Employee Attendance Request successfully created.']);
        }
    }

    public function edit($id)
    {
        if (\Auth::user()->can('edit attendance')) {
          
            $attendanceRequest = AttendanceRequest::where('id', $id)->first();
            $employees = Employee::where('is_active','=','1')->get()->pluck('name', 'id');

             return view('attendanceRequest.edit', compact('attendanceRequest', 'employees'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        } 
    }
    public function update(Request $request, $id)
    {
   
        if (\Auth::user()->can('edit attendance'))
        {
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
            
            $employeeAttendance = AttendanceRequest::find($id);
            $employeeAttendance->employee_id = $request->employee_id;
            $employeeAttendance->date = $request->date;
            $employeeAttendance->status=$request->status;
            $employeeAttendance->clock_in = $request->clock_in;
            $employeeAttendance->clock_out = $request->clock_out;
            $employeeAttendance->attendance_reason = $request->attendance_reason;
            $employeeAttendance->updated_at=date('Y-m-d H:i:s');
            $employeeAttendance->created_by = \Auth::user()->id;
        
            $employeeAttendance->save();

            if($request->status=='Approved')
            {
                $attendance = AttendanceEmployee::where('employee_id', '=',$request->employee_id)->where('date', '=', $request->date)->get()->count();
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
                if ($attendance==0)
                {
                 
                  $employeeAttendance = new AttendanceEmployee();
                    $employeeAttendance->employee_id = $request->employee_id;
                    $employeeAttendance->date = $request->date;
                    $employeeAttendance->status = 'Present';
                    $employeeAttendance->clock_in = $request->clock_in . ':00';
                    $employeeAttendance->clock_out = $request->clock_out . ':00';
                    $employeeAttendance->biometric_clock_in = $request->clock_in . ':00';
                    $employeeAttendance->biometric_clock_out = $request->clock_out . ':00';
                    $employeeAttendance->late ='';
                    $employeeAttendance->early_leaving = '';
                    $employeeAttendance->overtime ='';
                    $employeeAttendance->total_rest = '00:00:00';
                    $employeeAttendance->day_count=$day_count;
                    $employeeAttendance->late_mark=$request->late_mark;
                    $employeeAttendance->half_day=$request->half_day;
                    $employeeAttendance->created_by = \Auth::user()->id;
                
                    $employeeAttendance->save();
                }else{
                    AttendanceEmployee::where('employee_id', $request->employee_id)->where('date',$request->date)->update([
                        'clock_in' =>  $request->clock_in . ':00',
                        'clock_out' => $request->clock_out . ':00',
                        'biometric_clock_in'=>$request->clock_in . ':00',
                        'biometric_clock_out'=>$request->clock_out . ':00',

                    ]);
                   
                }
          
            }
            return response()->json(['success' =>'Employee Attendance Request successfully updated.']);
        }else{
            return response()->json(['error'=>'Permission denied.']);
        }
    }
    public function destroy($id)
    {
        if (\Auth::user()->can('delete attendance')) {
            $attendance = AttendanceRequest::where('id', $id)->first();

            $attendance->delete();

            return redirect()->back()->with('success', __('Attendance Request successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
