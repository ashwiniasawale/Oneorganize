<?php

namespace App\Http\Controllers;

use App\Models\DeductionOption;
use App\Models\Employee;
use App\Models\SaturationDeduction;
use Illuminate\Http\Request;

class SaturationDeductionController extends Controller
{
    public function saturationdeductionCreate($id)
    {
        $employee          = Employee::find($id);
        $deduction_options = DeductionOption::get()->pluck('name', 'id');
        $saturationdeduc = SaturationDeduction::$saturationDeductiontype;
        return view('saturationdeduction.create', compact('employee', 'deduction_options','saturationdeduc'));
    }

    public function store(Request $request)
    {
        if(\Auth::user()->can('create saturation deduction'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'employee_id' => 'required',
                                   'deduction_option' => 'required',
                                   
                                   'amount' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $check_deduction=SaturationDeduction::where('deduction_option',$request->deduction_option)->where('employee_id',$request->employee_id)->count();
            if($check_deduction>0)
            {
                return redirect()->back()->with('error','This Deduction Option already added.');
            }else{
            
            $saturationdeduction                   = new SaturationDeduction;
            $saturationdeduction->employee_id      = $request->employee_id;
            $saturationdeduction->deduction_option = $request->deduction_option;
            
            $saturationdeduction->type            = $request->type;
            $saturationdeduction->amount           = $request->amount;
            $saturationdeduction->created_by       = \Auth::user()->id;
            $saturationdeduction->save();

            return redirect()->back()->with('success', __('SaturationDeduction  successfully created.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(SaturationDeduction $saturationdeduction)
    {
        return redirect()->route('commision.index');
    }

    public function edit($saturationdeduction)
    {
        $saturationdeduction = SaturationDeduction::find($saturationdeduction);
        if(\Auth::user()->can('edit saturation deduction'))
        {
           
                $deduction_options = DeductionOption::get()->pluck('name', 'id');
                $saturationdeduc = SaturationDeduction::$saturationDeductiontype;
                return view('saturationdeduction.edit', compact('saturationdeduction', 'deduction_options','saturationdeduc'));
           
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, SaturationDeduction $saturationdeduction)
    {
        if(\Auth::user()->can('edit saturation deduction'))
        {
           
                $validator = \Validator::make(
                    $request->all(), [

                                       'deduction_option' => 'required',
                                       
                                       'amount' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $saturationdeduction->deduction_option = $request->deduction_option;
                
                $saturationdeduction->type            = $request->type;
                $saturationdeduction->amount           = $request->amount;
                $saturationdeduction->save();

                return redirect()->back()->with('success', __('SaturationDeduction successfully updated.'));
          
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(SaturationDeduction $saturationdeduction)
    {
        if(\Auth::user()->can('delete saturation deduction'))
        {
           
                $saturationdeduction->delete();

                return redirect()->back()->with('success', __('SaturationDeduction successfully deleted.'));
           
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
