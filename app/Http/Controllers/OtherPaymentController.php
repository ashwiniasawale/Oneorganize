<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Models\OtherPayment;
use Illuminate\Http\Request;

class OtherPaymentController extends Controller
{
    public function other_payment($year_month='')
    {
        if(\Auth::user()->can('create other payment'))
        {
            if($year_month=='')
            {
                $year_month=date('Y-m');
            }
            $other_payment=OtherPayment::select('employees.name','other_payments.id','other_payments.title','other_payments.amount','other_payments.year_month','other_payments.payment_option')
            ->join('employees','employees.id','=','other_payments.employee_id')
            ->where('other_payments.year_month',$year_month)->get();
            return view('otherpayment.index',compact('other_payment','year_month'));
        } else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function otherpaymentCreate()
    {
     
        $employee=User::select('employees.id','employees.name')->where('is_enable_login','1')
        ->Join('employees','employees.user_id','users.id')->get();
        return view('otherpayment.create', compact('employee'));
    }

    public function store(Request $request)
    {
        if(\Auth::user()->can('create other payment'))
        {
          
            $validator = \Validator::make(
                $request->all(), [
                                   'employee_id' => 'required',
                                   'title' => 'required',
                                   'amount' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return response()->json(['error'=>$messages->first()]);
            }

           
            if($request->employee_id=='all')
            {
              
                $user=User::select('employees.id','employees.name')->where('is_enable_login','1')
                ->Join('employees','employees.user_id','users.id')->get();
              
                foreach($user as $user)
                {
                    $check_exit=OtherPayment::where('employee_id',$user->id)->where('payment_option',$request->payment_option)->where('year_month',$request->year_month)->count();
                   if($check_exit=='0')
                   {
                    $otherpayment              = new OtherPayment();
                    $otherpayment->employee_id = $user->id;
                    $otherpayment->title       = $request->title;
                    $otherpayment->type       = 'fixed';
                    $otherpayment->amount      = $request->amount;
                    $otherpayment->payment_option=$request->payment_option;
                    $otherpayment->year_month=$request->year_month;
                    $otherpayment->created_by  = \Auth::user()->id;
                    $otherpayment->save();
                   }
                }
            }else{
                $check_exit=OtherPayment::where('employee_id',$request->employee_id)->where('payment_option',$request->payment_option)->where('year_month',$request->year_month)->count();
                   if($check_exit=='0')
                   {
                    $otherpayment              = new OtherPayment();
                    $otherpayment->employee_id = $request->employee_id;
                    $otherpayment->title       = $request->title;
                    $otherpayment->type       = 'fixed';
                    $otherpayment->amount      = $request->amount;
                    $otherpayment->payment_option=$request->payment_option;
                    $otherpayment->year_month=$request->year_month;
                    $otherpayment->created_by  = \Auth::user()->id;
                    $otherpayment->save();
                   }
            }

          
            return response()->json(['success'=>'OtherPayment successfully created.']);
        }
        else
        {
           
            return response()->json(['error','Permission denied.']);
        }
    }

    public function show(OtherPayment $otherpayment)
    {
        return redirect()->route('commision.index');
    }

    public function edit($otherpayment)
    {
        $otherpayment = OtherPayment::find($otherpayment);
        if(\Auth::user()->can('edit other payment'))
        {
            if($otherpayment->created_by == \Auth::user()->creatorId())
            {
                $otherpaytypes=OtherPayment::$otherPaymenttype;

                return view('otherpayment.edit', compact('otherpayment','otherpaytypes'));
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

    public function update(Request $request, OtherPayment $otherpayment)
    {
        if(\Auth::user()->can('edit other payment'))
        {
            if($otherpayment->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [

                                       'title' => 'required',
                                       'amount' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $otherpayment->title  = $request->title;
                $otherpayment->type  = $request->type;
                $otherpayment->amount = $request->amount;
                $otherpayment->save();

                return redirect()->back()->with('success', __('OtherPayment successfully updated.'));
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

    public function destroy($id)
    {
        if(\Auth::user()->can('delete other payment'))
        {
           
              
                $otherpayment=OtherPayment::find($id);
                $otherpayment->delete();

                return redirect()->back()->with('success', __('OtherPayment successfully deleted.'));
           
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
