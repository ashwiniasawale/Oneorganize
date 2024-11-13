<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Models\ProjectStage;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\TaskFile;
use App\Models\TaskStage;
use App\Models\TimeTracker;
use App\Models\User;
use App\Models\Project;
use App\Models\Utility;
use App\Models\Review;
use App\Models\Communication;
use App\Models\ProjectUser;
use Carbon\Carbon;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class CommunicationController extends Controller
{
    public function communication_record($project_id)
    {

         $user = Auth::user();
         if($user->can('manage communication record'))
         {
             $project = Project::find($project_id);

            if(!empty($project) && $project->created_by == Auth::user()->creatorId())
            {

                if($user->type != 'company')
                {
                    if(\Auth::user()->type == 'client'){
                      $communication = Communication::where('project_id',$project->id)->get();
                    }else{
                    //  $communication = Communication::where('project_id',$project->id)->whereRaw("find_in_set('" . $user->id . "',created_by)")->get();
                    $communication = Communication::where('project_id',$project->id)->get();
                }
                }

               
                if($user->type == 'company')
                {
                    $communication = Communication::where('project_id', '=', $project_id)->get();
                }
                

                return view('project_communication.communi_record', compact('project','communication'));
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

    public function communiCreate($project_id)
    {
        if(\Auth::user()->can('create communication record'))
        {

            return view('project_communication.communiCreate', compact( 'project_id'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function communiStore(Request $request, $project_id)
    {
        if(\Auth::user()->can('create communication record'))
        {
            $validator = \Validator::make(
                $request->all(), [

                                   'date' => 'required',
                                   'title' => 'required',
                                   'description' => 'required',
                                  
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('project.communication.index', $project_id)->with('error', $messages->first());
            }

            $usr         = \Auth::user();
          
            $communication              = new Communication();
         
            $communication->project_id  = $project_id;
            $communication->title       = $request->title;
            $communication->date=$request->date;
            if($request->hasFile('attachment'))
            {
              
                //storage limit
                $file_size = $request->file('attachment')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $file_size);
                if($result==1)
                {
                    $fileName = time() . '.' . $request->attachment->extension();
                    $request->file('attachment')->storeAs('communication_record', $fileName);
                    $communication->attachment      = 'communication_record/'.$fileName;
                }
            }
         
            $communication->description    = $request->description;
            $communication->created_by  = \Auth::user()->id;

            $communication->save();

           
            ActivityLog::create(
                [
                    'user_id' => $usr->id,
                    'project_id' => $project_id,
                    'log_type' => 'Create Communication Record',
                    'remark' => json_encode(['title' => $communication->title]),
                ]
            );

           

            return redirect()->route('project.communication.index', $project_id)->with('success', __('Communication Record successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function communiEdit($project_id, $crecord_id)
    {
        if(\Auth::user()->can('edit communication record'))
        {
            $communication  = Communication::find($crecord_id);
            return view('project_communication.communiEdit', compact('project_id', 'communication'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }


    }

    public function communiUpdate(Request $request, $project_id, $cid)
    {


        if(\Auth::user()->can('edit communication record'))
        {
            $validator = \Validator::make(
                $request->all(), [

                    'title' => 'required',
                    'date' => 'required',
                    'description' => 'required',
                   
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('project.communication.index', $project_id)->with('error', $messages->first());
            }
            $communication              = Communication::find($cid);
            $communication->project_id  = $project_id;
            $communication->title       = $request->title;
            $communication->date=$request->date;
          
            
            if($request->hasFile('attachment'))
            {
              
                $file_path = $communication->attachment;
                $file_size = $request->file('attachment')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $file_size);

                if($result==1) {
                Utility::checkFileExistsnDelete([$communication->attachment]);
                    Utility::changeStorageLimit(\Auth::user()->creatorId(), $file_path);
                    $fileName = time() . '.' . $request->attachment->extension();
                    $request->file('attachment')->storeAs('communication_record', $fileName);
                    $communication->attachment = 'communication_record/' . $fileName;
                }
            }
           
            $communication->description    = $request->description;
            $communication->created_by  = \Auth::user()->creatorId();
            $communication->save();
          

            return redirect()->route('project.communication.index', $project_id)->with('success', __('Communication Record successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function communiDestroy($project_id, $id)
    {
        $communication = Communication::find($id);
            if(\Auth::user()->can('delete communication record'))
            {
                Communication::where('project_id', '=', $communication->project_id)->where('id', '=', $id)->delete();
                $path        = storage_path($communication->attachment);
                if(file_exists($path))
                {
                    \File::delete($path);
                }
                return redirect()->back()->with('success', __('Project Communication Record successfully deleted!'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }

    }

}
