<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\Test;
use App\Models\User;
use App\Models\Utility;
use App\Models\TaskFile;
use App\Models\Bug;
use App\Models\BugStatus;
use App\Models\TaskStage;
use App\Models\ProjectUser;
use App\Models\ActivityLog;
use App\Models\RequirementMatrix;
use App\Models\ProjectTask;
use App\Models\TaskComment;
use App\Models\TaskChecklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
class ProjectTestController extends Controller
{
    public function testIndex ($project_id){
      

        $user = Auth::user();
        if ($user->can('manage project test')) {
            $project = Project::find($project_id);

            if (!empty($project) && $project->created_by == Auth::user()->creatorId()) {
                $stages = TaskStage::orderBy('order')->where('created_by', \Auth::user()->creatorId())->get();
            
                
                if ($user->type != 'company') {
                    if (\Auth::user()->type == 'client') {
                        $tests = Test::where('project_id', $project->id)->get();
                    } else {
                        $tests = Test::where('project_id', $project->id)->whereRaw("find_in_set('" . $user->id . "',assign_to)")->get();
                    }
                }

                if ($user->type == 'company' || $user->type == 'Manager') {
                    $tests = Test::where('project_id', '=', $project_id)->get();
                }
              
                return view('projects.testIndex', compact('project', 'tests','stages'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function testKanban($project_id){
      

        $user = Auth::user();
        if ($user->can('manage project test')) {
            $project = Project::find($project_id);

            if (!empty($project) && $project->created_by == Auth::user()->creatorId()) {
                $stages = TaskStage::orderBy('order')->where('created_by', \Auth::user()->creatorId())->get();
               
               
              
                return view('projects.testKanban', compact('project','stages'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function testCreate ($project_id,$stage_id){
       
        if (\Auth::user()->can('create project test')) {

            
            $hrs = Project::projectHrs($project_id);
           
            $project_user = ProjectUser::where('project_id', $project_id)->get();
            $project = Project::find($project_id);
            $settings = Utility::settings();
            $stages = TaskStage::orderBy('order')->where('created_by', \Auth::user()->creatorId())->get();
           
            $requirement= RequirementMatrix::where('project_id', $project_id)->get();
            $test_type=Test::$test_type;
           
            $users        = [];
            foreach ($project_user as $key => $user) {

                $user_data = User::find($user->user_id);
                $key = $user->user_id;
                $user_name = !empty($user_data) ? $user_data->name : '';
                $users[$key] = $user_name;
            }
          
            return view('projects.testCreate', compact( 'project_id', 'users','project','requirement','hrs','stages','stage_id','test_type'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function testStore(Request $request, $project_id,$stage_id)
    {
        if($request->requirement_id)
        {
            $requirement_id= implode(',', $request->requirement_id);
        }else{
            $requirement_id=$request->requirement_id;
          
        }
       
        if (\Auth::user()->can('create project test')) {
            $validator = \Validator::make(
                $request->all(),
                [

                    'test_name' => 'required',
                    'estimated_hrs' => 'required',
                    'priority' => 'required',
                    'test_procedures'=>'required',
                    'test_plan'=>'required|file',

                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                //return redirect()->route('project.testindex', $project_id)->with('error', $messages->first());
                return response()->json(['error' =>$messages->first()]);
            }
          
            $usr         = \Auth::user();
            $project     = Project::where('id', '=', $project_id)->first();

            $test              = new Test();
            $test->project_id  = $project_id;
            $test->test_name       = $request->test_name;
            $test->test_description    = $request->test_description;
            $test->priority    = $request->priority;
            $test->estimated_hrs    = $request->estimated_hrs;
            $test->test_procedures    = $request->test_procedures;
            $test->test_input    = $request->test_input;
            $test->test_accepted_output    = $request->test_accepted_output;
           
            if($request->hasFile('test_plan'))
            {
              
                //storage limit
                $file_size = $request->file('test_plan')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $file_size);
                if($result==1)
                {
                   
                    $fileName = time() . '.' . $request->test_plan->extension();
                    $request->file('test_plan')->storeAs('project_test_plan', $fileName);
                    $test->test_plan      = 'project_test_plan/'.$fileName;
                }
            }
         
            $test->test_note    = $request->test_note;
           
            if($request->hasFile('test_result'))
            {
              
                //storage limit
                $file_size1 = $request->file('test_result')->getSize();
                $result1 = Utility::updateStorageLimit(\Auth::user()->creatorId(), $file_size1);
                if($result1==1)
                {
                    $fileName1 = time() . '.' . $request->test_result->extension();
                    $request->file('test_result')->storeAs('project_test_result', $fileName1);
                    $test->test_result      = 'project_test_result/'.$fileName1;
                }
            }
            $test->test_type    = $request->test_type;
            $test->assign_to = $request->assign_to;
            $test->milestone_id    = $request->milestone_id;
            $test->deliverables    = $request->deliverables;
            $test->stage_id = $request->stage_id;
            $test->requirement_id=$requirement_id;
            $test->task_activity=$request->task_activity;
            $test->task_activity_type=$request->task_activity_type;
            $test->start_date = date("Y-m-d H:i:s", strtotime($request->start_date));
            $test->end_date = date("Y-m-d H:i:s", strtotime($request->end_date));
            $test->created_by  = \Auth::user()->id;
            $test->save();


            ActivityLog::create(
                [
                    'user_id' => $usr->id,
                    'project_id' => $project_id,
                    'log_type' => 'Create Bug',
                    'remark' => json_encode(['title' => $test->test_name]),
                ]
            );

            $projectArr = [
                'project_id' => $project_id,
                'name' => $project->name,
                'updated_by' => $usr->id,
            ];
            return response()->json(['success' =>'Test successfully created.']);


           // return redirect()->route('project.testindex', $project_id)->with('success', __('Test successfully created.'));
        } else {
          //  return redirect()->back()->with('error', __('Permission denied.'));
          return response()->json(['error'=>'Permission denied']);
        }
    }
    public function testupdate(Request $request, $project_id,$test_id)
    {
        if (\Auth::user()->can('edit project test')) {
            if($request->requirement_id)
            {
                $requirement_id= implode(',', $request->requirement_id);
            }else{
                $requirement_id=$request->requirement_id;
              
            }
            $validator = \Validator::make(
                $request->all(),
                [
                    'test_name' => 'required',
                    'estimated_hrs' => 'required',
                    'priority' => 'required',
                    'test_procedures'=>'required',
                  ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return response()->json(['error'=>$messages->first()]);
               // return redirect()->route('project.testindex', $project_id)->with('error', $messages->first());
            }

            $usr         = \Auth::user();
            $project     = Project::where('id', '=', $project_id)->first();

          
            $test              = Test::find($test_id);
            $test->project_id  = $project_id;
            $test->test_name       = $request->test_name;
            $test->test_description    = $request->test_description;
            $test->priority    = $request->priority;
            $test->estimated_hrs    = $request->estimated_hrs;
            $test->test_procedures    = $request->test_procedures;
            $test->test_input    = $request->test_input;
            $test->test_accepted_output    = $request->test_accepted_output;
           
          
            if($request->hasFile('test_plan'))
            {
              
                $file_path = $test->test_plan;
                $file_size = $request->file('test_plan')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $file_size);

                if($result==1) {
                   
                    Utility::changeStorageLimit(\Auth::user()->creatorId(), $file_path);
                    $fileName = time() . '.' . $request->test_plan->extension();
                    $request->file('test_plan')->storeAs('project_test_plan', $fileName);
                    $test->test_plan = 'project_test_plan/' . $fileName;
                }
            }
            $test->test_note    = $request->test_note;
           
            
            if($request->hasFile('test_result'))
            {
              
                $file_path1 = $test->test_result;
                $file_size1 = $request->file('test_result')->getSize();
                $result1 = Utility::updateStorageLimit(\Auth::user()->creatorId(), $file_size1);

                if($result1==1) {
                  
                    Utility::changeStorageLimit(\Auth::user()->creatorId(), $file_path1);
                    $fileName1 = time() . '.' . $request->test_result->extension();
                    $request->file('test_result')->storeAs('project_test_result', $fileName1);
                    $test->test_result = 'project_test_result/' . $fileName1;
                }
            }
            $test->test_type    = $request->test_type;
            $test->assign_to = $request->assign_to;
            $test->milestone_id    = $request->milestone_id;
            $test->deliverables    = $request->deliverables;
            $test->stage_id = $request->stage_id;
            
            $test->requirement_id=$requirement_id;
            $test->task_activity=$request->task_activity;
            $test->task_activity_type=$request->task_activity_type;
            $test->start_date = date("Y-m-d H:i:s", strtotime($request->start_date));
            $test->end_date = date("Y-m-d H:i:s", strtotime($request->end_date));
            $test->created_by  = \Auth::user()->creatorId();
            $test->save();


            ActivityLog::create(
                [
                    'user_id' => $usr->id,
                    'project_id' => $project_id,
                    'log_type' => 'Create Bug',
                    'remark' => json_encode(['title' => $test->test_name]),
                ]
            );

            $projectArr = [
                'project_id' => $project_id,
                'name' => $project->name,
                'updated_by' => $usr->id,
            ];
            return response()->json(['success'=>'Test successfully updated.']);
           // return redirect()->route('project.testindex', $project_id)->with('success', __('Test successfully updated.'));
        } else {
            //return redirect()->back()->with('error', __('Permission denied.'));
            return response()->json(['error'=>'Permission denied']);
        }
    }
    public function testEdit($project_id, $test_id)
    {
        
        if (\Auth::user()->can('edit project test')) {
            $project = Project::find($project_id);
            $test = Test::find($test_id);
            $hrs = Project::projectHrs($project_id);
            $stages = TaskStage::orderBy('order')->where('created_by', \Auth::user()->creatorId())->get();
            $requirement= RequirementMatrix::where('project_id', $project_id)->get();
            $test_type=Test::$test_type;
            if($test->task_activity=='hardware')
            {
                $activity_type=Test::$hardware_activity_type;
            }else if($test->task_activity=='software')
            {
                $activity_type=Test::$software_activity_type;
            }else if($task->task_activity=='general')
            {
                $activity_type=ProjectTask::$general_activity_type;
            }
            return view('projects.testEdit', compact('project', 'test', 'hrs','stages','activity_type','requirement','test_type'));

        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function testDestroy($project_id, $id)
    {
             $test = Test::find($id);
            if(\Auth::user()->can('delete project test'))
            {
                Test::where('project_id', '=', $test->project_id)->where('id', '=', $id)->delete();
                $path        = storage_path($test->test_plan);
                $test_path        = storage_path($test->test_result);
                if(file_exists($path))
                {
                    \File::delete($path);
                }
                if(file_exists($test_path))
                {
                    \File::delete($test_path);
                }
                return redirect()->back()->with('success', __('Project Review Record successfully deleted!'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }

    }

    

}
