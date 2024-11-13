<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Employee;
use App\Models\EmployeeAsset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('manage assets'))
        {
            $assets = Asset::get();

            return view('assets.index', compact('assets'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function employee_assets()
    {
        if(\Auth::user()->can('manage assets'))
        {
            $assets = EmployeeAsset::select('employees.name as employee_name','employee_assets.id','employee_assets.asset_id')->join('employees','employees.id','=','employee_assets.employee_id')->get();

            return view('employee_assets.index', compact('assets'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        if(\Auth::user()->can('create assets'))
        {
          
            return view('assets.create');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create_employee_assets()
    {
        if(\Auth::user()->can('create assets'))
        {
            $employee      = Employee::where('is_active','1')->get()->pluck('name', 'id');
            $asset=Asset::where('total_count','>','0')->get();
            return view('employee_assets.create',compact('employee','asset'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function store(Request $request)
    {
//        dd($request->all());
        if(\Auth::user()->can('create assets'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'serial_number' => 'required',
                                   'total_count' => 'required',
                                   'status' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return response()->json(['error'=>$messages->first()]);
            }

            $assets                 = new Asset();
           
            $assets->name           = $request->name;
            $assets->serial_number  = $request->serial_number;
            $assets->total_count = $request->total_count;
            $assets->status         = $request->status;
            $assets->description    = $request->description;
            $assets->created_by     = \Auth::user()->id;
            $assets->save();

            return response()->json(['success'=>'Assets successfully created.']);
        }
        else
        {
          
            return response()->json(['error'=>'Permission denied.']);
        }
    }

    public function employee_assets_store(Request $request)
    {
        if(\Auth::user()->can('create assets'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'employee_id' => 'required',
                                   
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return response()->json(['error'=>$messages->first()]);
            }
          
            $check_emp_asset=EmployeeAsset::where('employee_id',$request->employee_id)->count();
            if($check_emp_asset>0)
            {
                return response()->json(['error'=>'Employee Assets Already added, please update the assets.']);
            }else{

                $asset_ids = array_unique($request->asset_id);
           
               $asset_id= implode(',',  $asset_ids);
          
            $assets                 = new EmployeeAsset();
           
            $assets->employee_id           = $request->employee_id;
            $assets->asset_id  = $asset_id;
            
            $assets->created_by     = \Auth::user()->id;
            $assets->save();



            return response()->json(['success'=>'Assets successfully created.']);
        }
        }
        else
        {
          
            return response()->json(['error'=>'Permission denied.']);
        }
    }
    public function show(Asset $asset)
    {
        //
    }

    public function employee_assets_edit($id)
    {
        if(\Auth::user()->can('edit assets'))
        {
          
            $asset = Asset::get();
            $emp_asset=EmployeeAsset::find($id);
            $employee      = Employee::where('id',$emp_asset->employee_id)->first();
           
            return view('employee_assets.edit', compact('asset','employee','emp_asset'));
        }else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function edit($id)
    {

        if(\Auth::user()->can('edit assets'))
        {
            $asset = Asset::find($id);
          
            return view('assets.edit', compact('asset'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function employee_assets_update(Request $request)
    {
        if(\Auth::user()->can('edit assets'))
        {

          
            $asset_ids = array_unique($request->asset_id);
          
            $asset_id= implode(',',  $asset_ids);
          
         $assets                 = EmployeeAsset::find($request->ids);
        
         $assets->asset_id  = $asset_id;
         
         $assets->created_by     = \Auth::user()->id;
         $assets->save();
                return response()->json(['success'=>'Employee Assets successfully updated.']);
        }
        else
        {
            
            return response()->json(['error'=>'Permission denied.']);
        }
    }

    public function update(Request $request, $id)
    {
        if(\Auth::user()->can('edit assets'))
        {
            $asset = Asset::find($id);
           
                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required',
                                       'serial_number' => 'required',
                                       'total_count' => 'required',
                                       'status' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return response()->json(['error'=>$messages->first()]);
                }

                $asset->name           = $request->name;
               
                $asset->serial_number  = $request->serial_number;
                $asset->total_count = $request->total_count;
                $asset->status         = $request->status;
                $asset->description    = $request->description;
                $asset->save();

                return response()->json(['success'=>'Assets successfully updated.']);
        }
        else
        {
            
            return response()->json(['error'=>'Permission denied.']);
        }
    }

    public function employee_asset_destroy($id)
    {
        if(\Auth::user()->can('delete assets'))
        {
            $asset = EmployeeAsset::find($id);
           
                $asset->delete();

                return redirect()->route('employee-assets')->with('success', __('Employee Assets successfully deleted.'));
           
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function destroy($id)
    {
        if(\Auth::user()->can('delete assets'))
        {
            $asset = Asset::find($id);
           
                $asset->delete();

                return redirect()->route('account-assets.index')->with('success', __('Assets successfully deleted.'));
           
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
