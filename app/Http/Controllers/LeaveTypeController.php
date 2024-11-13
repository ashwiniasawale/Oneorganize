<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('manage leave type'))
        {
            $leavetypes = LeaveType::get();

            return view('leavetype.index', compact('leavetypes'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {

        if(\Auth::user()->can('create leave type'))
        {
            return view('leavetype.create');
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {

        if(\Auth::user()->can('create leave type'))
        {

         
            $validator = \Validator::make(
                $request->all(), [
                'title' => ['required'],
                'days' => 'required|regex:/^\d+$/',
                'leave_year'=>['required']
            ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

               // return redirect()->back()->with('error', $messages->first());
               return response()->json(['error'=>$messages->first()]);
            }
            if($request->monthly_limit>$request->days)
            {
                $messages = 'Monthly Limit should be less than days.';

                // return redirect()->back()->with('error', $messages);
                return response()->json(['error'=>$messages]);
            }
            $check_leave=LeaveType::where('leave_year',$request->leave_year)->get()->count();
           if($check_leave>=1)
           {
            $messages = 'LeaveType already added for the year.';

           // return redirect()->back()->with('error', $messages);
           return response()->json(['error'=>$messages]);
           }

            $leavetype             = new LeaveType();
            $leavetype->title      = $request->title;
            $leavetype->days       = $request->days;
            $leavetype->monthly_limit=$request->monthly_limit;
            $leavetype->leave_paid_status=$request->leave_paid_status;
            $leavetype->leave_year=$request->leave_year;
            $leavetype->created_by = \Auth::user()->id;
            $leavetype->save();

           // return redirect()->route('leavetype.index')->with('success', __('LeaveType  successfully created.'));
           return response()->json(['success'=>'LeaveType successfully created.']);
        }
        else
        {
           // return redirect()->back()->with('error', __('Permission denied.'));
           return response()->json(['error'=>'Permission denied']);
        }
    }

    public function show(LeaveType $leavetype)
    {
        return redirect()->route('leavetype.index');
    }

    public function edit(LeaveType $leavetype)
    {
        if(\Auth::user()->can('edit leave type'))
        {
              return view('leavetype.edit', compact('leavetype'));
          
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, LeaveType $leavetype)
    {
        if(\Auth::user()->can('edit leave type'))
        {
           
              
                $validator = \Validator::make(
                    $request->all(), [
                    'title' => ['required'],
                    'days' => 'required|regex:/^\d+$/',
                    'leave_year'=>['required']
                ]
                );
    
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();
    
                   // return redirect()->back()->with('error', $messages->first());
                   return response()->json(['error'=>$messages->first()]);
                }
                if($request->monthly_limit>$request->days)
                {
                    $messages = 'Monthly Limit should be less than days.';
    
                    // return redirect()->back()->with('error', $messages);
                    return response()->json(['error'=>$messages]);
                }
                $check_leave=LeaveType::where('leave_year',$request->leave_year)->get()->count();
               if($check_leave>1)
               {
                 $messages = 'LeaveType already added for the year.';
    
                 // return redirect()->back()->with('error', $messages);
                 return response()->json(['error'=>$messages]);
               }
                $leavetype->title = $request->title;
                $leavetype->days  = $request->days;
                $leavetype->monthly_limit=$request->monthly_limit;
                $leavetype->leave_paid_status=$request->leave_paid_status;
                $leavetype->leave_year=$request->leave_year;
                $leavetype->save();

               // return redirect()->route('leavetype.index')->with('success', __('LeaveType successfully updated.'));
               return response()->json(['success'=>'LeaveType successfully updated.']);
        }
        else
        {
            //return redirect()->back()->with('error', __('Permission denied.'));
            return response()->json(['error'=>'Permission denied.']);
        }
    }

    public function destroy(LeaveType $leavetype)
    {
        if(\Auth::user()->can('delete leave type'))
        {
          
                $leavetype->delete();

                return redirect()->route('leavetype.index')->with('success', __('LeaveType successfully deleted.'));
           
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
