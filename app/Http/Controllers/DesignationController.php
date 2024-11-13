<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Branch;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index()
    {

        if(\Auth::user()->can('manage designation'))
        {
            $designations = Designation::get();
          
            return view('designation.index', compact('designations'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if(\Auth::user()->can('create designation'))
        {
           
            $branches         = Branch::get()->pluck('name', 'id');
            $departments      = [];
            return view('designation.create', compact('departments','branches'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {

        if(\Auth::user()->can('create designation'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                    'branch_id'=>'required',
                                   'department_id' => 'required',
                                   'name' => ['required'],
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $check_desig=Designation::where('department_id','=',$request->department_id)->where('name','=',$request->name)->count();
            if($check_desig>0)
            {
                return redirect()->back()->with('error', __('Designation Already Added to this branch department.'));
            }else{
            $designation                = new Designation();
            $designation->department_id = $request->department_id;
            $designation->name          = $request->name;
            $designation->created_by    = \Auth::user()->id;

            $designation->save();

            return redirect()->route('designation.index')->with('success', __('Designation  successfully created.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(Designation $designation)
    {
        return redirect()->route('designation.index');
    }

    public function edit(Designation $designation)
    {

        if(\Auth::user()->can('edit designation'))
        {
           
            $check_desig=Designation::where('department_id','=',$designation->department_id)->where('name','=',$designation->name)->count();
            if($check_desig>1)
            {
                return redirect()->back()->with('error', __('Designation Already Added to this branch department.'));
            }else{
                $departments_sel = Department::where('id', $designation->department_id)->first();
               
                $branches_sel =Branch::where('id', $departments_sel->branch_id)->first();

                $branches         = Branch::pluck('name', 'id');
                $departments =Department::where('branch_id', $branches_sel->id)->pluck('name', 'id');
                return view('designation.edit', compact('branches_sel','branches','designation', 'departments'));
            }
              
            
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Designation $designation)
    {
        if(\Auth::user()->can('edit designation'))
        {
          
                $validator = \Validator::make(
                    $request->all(), [
                                       'department_id' => 'required',
                                       'name' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
                $designation->name          = $request->name;
                $designation->department_id = $request->department_id;
                $designation->save();

                return redirect()->route('designation.index')->with('success', __('Designation  successfully updated.'));
          
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Designation $designation)
    {
        if(\Auth::user()->can('delete designation'))
        {
           
                $designation->delete();

                return redirect()->route('designation.index')->with('success', __('Designation successfully deleted.'));
           
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
