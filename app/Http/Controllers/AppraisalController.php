<?php

namespace App\Http\Controllers;

use App\Models\Appraisal;
use App\Models\Branch;
use App\Models\Competencies;
use App\Models\Employee;
use App\Models\Indicator;
use App\Models\User;
use App\Models\Performance_Type;
use App\Models\PerformanceType;
use Illuminate\Http\Request;

class AppraisalController extends Controller
{

    public function index()
    {
        if(\Auth::user()->can('manage appraisal'))
        {
          
            $user = \Auth::user();
            if($user->type == 'Employee')
            {
                $employee   = Employee::where('user_id', $user->id)->first();
           
                $appraisals = Appraisal::where('employee', $employee->id)->get();
            }
            else
            {
              
                $appraisals = Appraisal::get();
            }

            return view('appraisal.index', compact('appraisals'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if(\Auth::user()->can('create appraisal'))
        {
            $employee=User::select('employees.id','employees.name')->where('is_enable_login','1')
            ->Join('employees','employees.user_id','users.id')->get();
         
            return view('appraisal.create', compact( 'employee'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function store(Request $request)
    {

        if(\Auth::user()->can('create appraisal'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                    'appraisal_date'=>'required',
                                   'employee' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return response()->json(['error'=>$messages->first()]);
            }
            $check_data=Appraisal::where('appraisal_date',$request->appraisal_date)->where('employee',$request->employee)->where('type',$request->type)->count();
            if($check_data>0)
            {
                return response()->json(['error'=>'Appraisal already added for this month year.']);
            }else{
            $appraisal                 = new Appraisal();
            $appraisal->employee       = $request->employee;
            $appraisal->appraisal_date = $request->appraisal_date;
            $appraisal->appraisal_salary= $request->appraisal_salary;
            $appraisal->type=$request->type;
            $appraisal->created_by     = \Auth::user()->id;
            $appraisal->save();

            $employee_record=Employee::find($request->employee);
            $employee_record->annual_salary=$request->appraisal_salary;
            $employee_record->save();
            }

            return response()->json(['success'=>'Appraisal successfully created.']);
        }
    }

    public function show(Appraisal $appraisal)
    {
        $rating = json_decode($appraisal->rating, true);
        $performance_types     = PerformanceType::where('created_by', '=', \Auth::user()->creatorId())->get();
        $employee = Employee::find($appraisal->employee);

        $indicator = Indicator::where('branch',$employee->branch_id)->where('department',$employee->department_id)->where('designation',$employee->designation_id)->first();
        if ($indicator != null) {
            $ratings = json_decode($indicator->rating, true);
        }else {
            $ratings = null;
        }

        return view('appraisal.show', compact('appraisal', 'performance_types', 'ratings','rating'));
    }

    public function edit(Appraisal $appraisal)
    {
        if(\Auth::user()->can('edit appraisal'))
        {

            
            $employee = Employee::find($appraisal->employee);

            return view('appraisal.edit', compact( 'employee', 'appraisal'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function update(Request $request, Appraisal $appraisal)
    {
        if(\Auth::user()->can('edit appraisal'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'branch' => 'required',
                                   'employee' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $appraisal->branch         = $request->branch;
            $appraisal->employee       = $request->employee;
            $appraisal->appraisal_date = $request->appraisal_date;
            $appraisal->rating         = json_encode($request->rating, true);
            $appraisal->remark         = $request->remark;
            $appraisal->save();

            return redirect()->route('appraisal.index')->with('success', __('Appraisal successfully updated.'));
        }
    }

    public function destroy(Appraisal $appraisal)
    {
        if(\Auth::user()->can('delete appraisal'))
        {
          
                $appraisal->delete();

                return redirect()->route('appraisal.index')->with('success', __('Appraisal successfully deleted.'));
           
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function empByStar(Request $request)
    {
        $employee = Employee::find($request->employee);

        $indicator = Indicator::where('branch',$employee->branch_id)->where('department',$employee->department_id)->where('designation',$employee->designation_id)->first();

        $ratings = !empty($indicator)? json_decode($indicator->rating, true):[];

        $performance_types = PerformanceType::where('created_by', '=', \Auth::user()->creatorId())->get();

        $viewRender = view('appraisal.star', compact('ratings','performance_types'))->render();
        // dd($viewRender);
        return response()->json(array('success' => true, 'html'=>$viewRender));

    }

    public function empByStar1(Request $request)
    {
        $employee = Employee::find($request->employee);

        $appraisal = Appraisal::find($request->appraisal);

        $indicator = Indicator::where('branch',$employee->branch_id)->where('department',$employee->department_id)->where('designation',$employee->designation_id)->first();

        if ($indicator != null) {
            $ratings = json_decode($indicator->rating, true);
        }else {
            $ratings = null;
        }
        $rating = json_decode($appraisal->rating,true);
        $performance_types = PerformanceType::where('created_by', '=', \Auth::user()->creatorId())->get();
        $viewRender = view('appraisal.staredit', compact('ratings','rating','performance_types'))->render();
        // dd($viewRender);
        return response()->json(array('success' => true, 'html'=>$viewRender));

    }

    public function getemployee(Request $request)
    {
        $data['employee'] = Employee::where('branch_id',$request->branch_id)->get();
        return response()->json($data);
    }
}
