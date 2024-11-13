<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Utility;
use App\Models\TaskFile;
use App\Models\Bug;
use App\Models\BugStatus;
use App\Models\TaskStage;
use App\Models\ActivityLog;
use App\Models\RequirementMatrix;
use App\Models\ProjectTask;
use App\Models\ProjectSubtask;
use App\Models\TaskComment;
use App\Models\TaskChecklist;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TaskExport;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskEmail;
class ProjectTaskController extends Controller
{

    public function index($project_id)
    {

        $usr = \Auth::user();
        if (\Auth::user()->can('manage project task')) {
            $project = Project::where('id', $project_id)->where('created_by', \Auth::user()->creatorId())->first();

            if ($project != null) {

                $stages = TaskStage::orderBy('order')->where('created_by', \Auth::user()->creatorId())->get();
            
                foreach ($stages as $status) {
                    $stageClass[] = 'task-list-' . $status->id;
                    
                    
                    // check project is shared or owner
                    if($usr->type != 'company')
                    {
                        if(\Auth::user()->type == 'client'){
                       
                          $task = ProjectTask::where('project_id', '=', $project_id);
                        }else{
                           
                            $task = ProjectTask::where('project_id', '=', $project_id)->whereRaw("find_in_set('" . $usr->id . "',assign_to)");
                       
                        }
                    }
    
                   
                    if($usr->type == 'company' || $usr->type == 'Manager')
                    {
                        $task = ProjectTask::where('project_id', '=', $project_id);
                    }
                    //end
                    $task->orderBy('order');
                    $status['tasks'] = $task->where('stage_id', '=', $status->id)->get();
                }
              
                return view('project_task.index', compact('stages', 'stageClass', 'project'));
            } else {
                return redirect()->route('projects.index')->with('error', __('Projeat not found'));
            }

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create($project_id, $stage_id,$position,$task_seq)
    {
       
        if (\Auth::user()->can('create project task')) {
            $project = Project::find($project_id);
            $hrs = Project::projectHrs($project_id);
            $settings = Utility::settings();
            $stages = TaskStage::orderBy('order')->where('created_by', \Auth::user()->creatorId())->get();
            $requirement= RequirementMatrix::where('project_id', $project_id)->get();
        
            return view('project_task.create', compact('project_id', 'stage_id', 'project', 'hrs', 'settings','stages','requirement','position','task_seq'));
       
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    public function createsubtask($project_id,$task_id)
    {
        if (\Auth::user()->can('create project task'))
        {
            $task = ProjectTask::find($task_id);
            $project = Project::find($project_id);
            $requirement= RequirementMatrix::where('project_id', $project_id)->get();
        
            $stages = TaskStage::orderBy('order')->where('created_by', \Auth::user()->creatorId())->get();
            $stage_id=$stages[0]->id;
         
            return view('project_task.createSubtask',compact('task','project_id','task_id','stage_id','project','requirement'));
        }else{
            return redirect()->back()->with('error',__('Permission Denied.'));
        }

    } 

    public function store(Request $request, $project_id, $stage_id)
    {
       
        if($request->requirement_id)
        {
           $requirement_id= implode(',', $request->requirement_id);
        }else{
           $requirement_id=$request->requirement_id;
        }
      
        if (\Auth::user()->can('create project task')) {
            $validator = Validator::make(
                $request->all(), [
                    'name' => 'required',
                    'estimated_hrs' => 'required',
                    'priority' => 'required',
                ]
            );

            if ($validator->fails()) {
                return response()->json(['error'=>Utility::errorFormat($validator->getMessageBag())]);
              //  return redirect()->back()->with('error', Utility::errorFormat($validator->getMessageBag()));
            }
            $tmp_task                 = [];
            $last_task_seq = ProjectTask::where('project_id', '=', $project_id)
                ->orderBy('task_seq', 'desc') // Order by sequence_id in descending order
                ->first(); // Get the first result
                if ($last_task_seq && $request->position=='end') {
                   
                   $last_sequence_id = $last_task_seq->task_seq+1;
                    // $last_sequence_id now contains the last sequence ID for the specified project_id
                    // You can use $last_sequence_id as needed
                } elseif($request->position=='below' ) {
                    // Handle case where no tasks are found for the specified project_id
                    $last_sequence_id=$request->task_seq+1;
                    $get_seq = ProjectTask::select('id', 'project_id', 'task_seq')
                        ->where('project_id', '=', $project_id)
                        ->where('task_seq', '>', $request->task_seq)
                        ->orderBy('task_seq', 'asc') // Order by task_seq in ascending order
                        ->get();
                        foreach ($get_seq as $get_seq) {
                            $tmp                = [];
                            $update_seq=$get_seq->task_seq+1;
                           
                            $tmp['id']           = $get_seq->id;
                            $tmp['project_id']         = $project_id;
                            $tmp['task_seq']     =$update_seq;
                            $tmp_task[]             = $tmp;
                  
                 
                        }
                     
                }else{
                    
                    $last_sequence_id=1;
                }
               
            $usr = Auth::user();
            $project = Project::find($project_id);
            $last_stage = $project->first()->id;
            $post = $request->all();
          
            $post['project_id'] = $project->id;
            $post['task_seq']=$last_sequence_id;
            $post['stage_id'] = $request->stage_id;
            $post['assign_to'] = $request->assign_to;
            $post['requirement_id']= $requirement_id;
            $post['task_activity']=$request->task_activity;
            $post['task_activity_type']=$request->task_activity_type;
            $post['comment']=$request->comment;
            $post['remark']=$request->remark;
            $post['created_by'] = \Auth::user()->id;
            $post['start_date'] = date("Y-m-d H:i:s", strtotime($request->start_date));
            $post['end_date'] = date("Y-m-d H:i:s", strtotime($request->end_date));
            if ($stage_id == $last_stage) {
                $post['marked_at'] = date('Y-m-d');
            }
           
            $task = ProjectTask::create($post);

            //Make entry in activity log
            ActivityLog::create(
                [
                    'user_id' => $usr->id,
                    'project_id' => $project_id,
                    'task_id' => $task->id,
                    'log_type' => 'Create Task',
                    'remark' => json_encode(['title' => $task->name]),
                ]
            );



            //For Notification
            $setting = Utility::settings(\Auth::user()->id);
            $project_name = Project::find($project_id);
            $project = Project::where('id', $project_name->id)->first();

            $users = explode(',',$task->assign_to);

            // if(isset($setting['new_task']) && $setting['new_task'] ==1)
            // {
            //     foreach ($users as $key => $user) {
            //         $user = User::find($user);
            //         $taskArr = [
            //             'task_user' => $user->name,
            //             'task_name' => $task->name,
            //             'project_name' => $project->project_name,
            //             'task_start_date' => $task->start_date,
            //             'task_end_date' => $task->end_date,
            //             'hours' => $task->estimated_hrs,
            //         ];
            //         $resp = Utility::sendEmailTemplate('new_task', [$user->id => $user->email], $taskArr);
            //     }
            // }



            $taskNotificationArr = [
                'task_name' => $task->name,
                'project_name' => $project->project_name,
                'user_name' => \Auth::user()->name,
            ];
            //Slack Notification
            if (isset($setting['task_notification']) && $setting['task_notification'] == 1) {
                Utility::send_slack_msg('new_task', $taskNotificationArr);
            }
            //Telegram Notification
            if (isset($setting['telegram_task_notification']) && $setting['telegram_task_notification'] == 1) {
                Utility::send_telegram_msg('new_task', $taskNotificationArr);
            }


            //For Google Calendar
            if ($request->get('synchronize_type') == 'google_calender') {
                $type = 'task';
                $request1 = new ProjectTask();
                $request1->title = $request->name;
                $request1->start_date = $request->start_date;
                $request1->end_date = $request->end_date;
                Utility::addCalendarData($request1, $type);
            }

            //webhook
            $module = 'New Task';
            $webhook = Utility::webhookSetting($module);
            if ($webhook) {
                $parameter = json_encode($task);
                $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);
                if ($status == true) {
                   // return redirect()->back()->with('success', __('Task added successfully.'));
                   return response()->json(['success'=>'Task added successfully.','tmp_task'=>$tmp_task]);
                } else {
                   // return redirect()->back()->with('error', __('Webhook call failed.'));
                    return response()->json(['error'=>'Webhook call failed.']);
                }
            }

           // return redirect()->back()->with('success', __('Task added successfully.'));
           return response()->json(['success'=>'Task added successfully.','tmp_task'=>$tmp_task]);
        } else {
           // return redirect()->back()->with('error', __('Permission Denied.'));
           return response()->json(['error'=>'Permission Denied.']);
        }
    }
    public function task_seq_update(Request $request)
    {
        $tmp_task = $request->tmp_task;
        if(!empty($tmp_task))
        {
        for ($i = 0; $i < count($tmp_task); $i++) {
            // Access each element using $tmp_task[$i]
           
            $post = $request->all();
            $task = ProjectTask::find($tmp_task[$i]['id']);
           
            $post['task_seq']=$tmp_task[$i]['task_seq'];
            
            $task->update($post);
            }
            
        }
    }
    public function task_seq_change(Request $request)
    {
        $tmp_task                 = [];
        if($request->position=='down')
        {
        $get_seq = ProjectTask::select('id', 'project_id', 'task_seq')
        ->where('project_id', '=', $request->project_id)
        ->where('task_seq', '=', ($request->task_seq)+1)
        ->orderBy('task_seq', 'asc') // Order by task_seq in ascending order
        ->first();
       
            if(!empty($get_seq))
            {

                    /***move down */
                    $post= $request->all();
                    $move_down = ProjectTask::find($request->task_id);
                    $post['task_seq']=$get_seq->task_seq;
                    
                    $move_down->update($post);

                    /****move up */
                    $postt = $request->all();
                    $request->task_seq;
                    $move_up = ProjectTask::find($get_seq->id);
                    $postt['task_seq']=$request->task_seq;
                    
                    $move_up->update($postt);
                    /**********Prdece ******* */
                   
                  
                    return response()->json(['success'=>'Moved Down']);
        
            }else{
                return response()->json(['error'=>'No Task Found']);
            }
        
        }else if($request->position=='up')
        {
            $get_seq = ProjectTask::select('id', 'project_id', 'task_seq')
            ->where('project_id', '=', $request->project_id)
            ->where('task_seq', '=', ($request->task_seq)-1)
            ->orderBy('task_seq', 'asc') // Order by task_seq in ascending order
            ->first();
           
                if(!empty($get_seq))
                {
                        /***move up */
                        $post= $request->all();
                        $move_up = ProjectTask::find($request->task_id);
                        $post['task_seq']=$get_seq->task_seq;
                        $move_up->update($post);
    
                        /****move down */
                        $postt = $request->all();
    
                        $move_down = ProjectTask::find($get_seq->id);
                        $postt['task_seq']=$request->task_seq;
                        $move_down->update($postt);
                      
                        return response()->json(['success'=>'Moved UP']);
                }else{
                    return response()->json(['error'=>'No Task Found']);
                }
        }
       
       
    }
    public function storesubtask(Request $request, $project_id,$task_id)
    {
       
        if (\Auth::user()->can('create project task')) {

            if($request->requirement_id)
            {
            $requirement_id= implode(',', $request->requirement_id);
            }else{
            $requirement_id=$request->requirement_id;
            }
            $validator = Validator::make(
                $request->all(), [
                    'subtask_name' => 'required',
                    'start_date' => 'required',
                    'end_date' => 'required',
                ]
            );

            if ($validator->fails()) {
                return response()->json(['error'=>Utility::errorFormat($validator->getMessageBag())]);
                //return redirect()->back()->with('error', Utility::errorFormat($validator->getMessageBag()));
            }
            $usr = Auth::user();
            $project = Project::find($project_id);
            $last_stage = $project->first()->id;

            $last_sub_task_seq = ProjectSubtask::where('project_id', '=', $project_id)
            ->where('task_id','=',$task_id)
            ->orderBy('subtask_seq', 'desc') // Order by sequence_id in descending order
            ->first(); // Get the first result
            if ($last_sub_task_seq) {
               
               $last_sequence_id = $last_sub_task_seq->subtask_seq+1;
                // $last_sequence_id now contains the last sequence ID for the specified project_id
                // You can use $last_sequence_id as needed
            }else{
                $last_sequence_id=1;
            }
            $post = $request->all();
           
            $post['project_id'] = $project->id;
            $post['task_id']=$task_id;
            $post['subtask_seq']=$last_sequence_id;
            $post['stage_id'] = $request->stage_id;
            $post['assign_to'] = $request->assign_to;
            $post['requirement_id']= $requirement_id;
            $post['task_activity']=$request->task_activity;
            $post['task_activity_type']=$request->task_activity_type;
            $post['comment']=$request->comment;
            $post['remark']=$request->remark;
            $post['created_by'] = \Auth::user()->id;
            $post['start_date'] = date("Y-m-d H:i:s", strtotime($request->start_date));
            $post['end_date'] = date("Y-m-d H:i:s", strtotime($request->end_date));
            
            $subtask = ProjectSubtask::create($post);

            $setting = Utility::settings(\Auth::user()->id);
            $users = explode(',',$subtask->assign_to);

            // if(isset($setting['new_task']) && $setting['new_task'] ==1)
            // {
            //     foreach ($users as $key => $user) {
            //         $user = User::find($user);
            //         $taskArr = [
            //             'task_user' => $user->name,
            //             'task_name' => $subtask->subtask_name,
            //             'project_name' => $project->project_name,
            //             'task_start_date' => $subtask->start_date,
            //             'task_end_date' => $subtask->end_date,
                       
            //         ];
            //        // $resp = Utility::sendEmailTemplate('new_task', [$user->id => $user->email], $taskArr);
            //     }
            // }
            return response()->json(['success'=>'SubTask added successfully.']);
            //return redirect()->back()->with('success', __('SubTask added successfully.'));
        }else{
            return response()->json(['error'=>'Permission Denied.']);
           // return redirect()->back()->with('error', __('Permission Denied.'));  
        }
    }

    // For Taskboard View
    public function taskBoard($view)
    {
       
        $usr = Auth::user();
        $projects = $usr->projects()->pluck('project_name','project_id')->toArray();
        $stages = TaskStage::orderBy('order')->where('created_by', \Auth::user()->creatorId())->get();
       
          return view('project_task.taskboard', compact('view','projects','stages'));

    }


    // For Load Task using ajax
    public function taskboardView(Request $request)
    {
      
        $usr = Auth::user();
             
        if (\Auth::user()->type == 'client') {
            $user_projects = Project::where('client_id', \Auth::user()->id)->pluck('id', 'id')->toArray();
        } elseif (\Auth::user()->type != 'client') {
            if($request->project_id=='all')
            {
                $user_projects = $usr->projects()->pluck('project_id', 'project_id')->toArray();
     
            }else{
                $user_projects = $usr->projects()->where('project_id', $request->project_id)->pluck('project_id', 'project_id')->toArray();
     
            }
              }
     
        if ($request->ajax() && $request->has('view') ) {
          
            $tasks = ProjectTask::whereIn('project_id', $user_projects)->orderBy('task_seq','asc');
           
            if (\Auth::user()->type != 'company' && \Auth::user()->type!='Manager') {
               
                if (\Auth::user()->type == 'client') {
                    $tasks->where('created_by', \Auth::user()->creatorId());

                } else {
                    $tasks->whereRaw("find_in_set('" . $usr->id . "',assign_to)");
                }
            }
          
            if (!empty($request->task_stage_id)) {
                $tasks->where('stage_id','=',$request->task_stage_id);
            }
            
            $tasks = $tasks->with(['project'])->orderBy('project_id','asc')->get();
            $view=$request->view;
            $returnHTML = view('project_task.' . $request->view, compact('tasks','view'))->render();

            return response()->json(
                [
                    'success' => true,
                    'html' => $returnHTML,
                ]
            );
        }
    }
    public function task_export(Request $request)
    {
        $usr = Auth::user();
             
        if (\Auth::user()->type == 'client') {
            $user_projects = Project::where('client_id', \Auth::user()->id)->pluck('id', 'id')->toArray();
        } elseif (\Auth::user()->type != 'client') {
            if($request->project_id=='all')
            {
                $user_projects = $usr->projects()->pluck('project_id', 'project_id')->toArray();
     
            }else{
                $user_projects = $usr->projects()->where('project_id', $request->project_id)->pluck('project_id', 'project_id')->toArray();
     
            }
              }
     
        if ($request->ajax() && $request->has('view') ) {
          
            $tasks = ProjectTask::whereIn('project_id', $user_projects)->orderBy('task_seq','asc');
           
            if (\Auth::user()->type != 'company' && \Auth::user()->type!='Manager') {
               
                if (\Auth::user()->type == 'client') {
                    $tasks->where('created_by', \Auth::user()->creatorId());

                } else {
                    $tasks->whereRaw("find_in_set('" . $usr->id . "',assign_to)");
                }
            }
          
            if (!empty($request->task_stage_id)) {
                $tasks->where('stage_id','=',$request->task_stage_id);
            }
            
            $tasks = $tasks->with(['project'])->orderBy('project_id','asc')->get();
            $view=$request->view;
           
            $export = new TaskExport($tasks, $view, $request->task_stage_id);
            $storagePath = 'uploads/attachments/tasks_export.csv';
           
        try {
            // Define storage path for the export file
          
            // Store the export data into the defined storage path using the 'local' disk
            Excel::store($export, $storagePath, 'local');
            $get_company_email=User::where('type','company')->where('is_enable_login','=','1')->first();
          
            Mail::to($get_company_email->email)->send(new TaskEmail($storagePath));
        
        } catch (\Exception $e) {
            // Handle any storage errors
            return response()->json(['error' => 'Failed to export tasks. Please try again.'], 500);
        }

        // Delete the temporary file after sending
        return Excel::download(new TaskExport($tasks,$view,$request->task_stage_id), 'tasks_export.csv');
                
        }
    }
    public function get_task_activity_type(Request $request)
    {
        $usr        = \Auth::user();
        $task_activity= $request->task_activity;
        if($task_activity=='hardware')
        {
            $activity_type=ProjectTask::$hardware_activity_type;
        }else if($task_activity=='software')
        {
            $activity_type=ProjectTask::$software_activity_type;
        }else if($task_activity=='general')
        {
            $activity_type=ProjectTask::$general_activity_type;
        }
       
        $data='';
        $data .='<option value="" disabled>Select Activity Type</option>';
        foreach($activity_type as $key=>$value)
        {
            $data .='<option value="'.$key.'">'.$value.'</option>';
        }
        echo $data;

    }
    public function allBugList($view)
    {
        $usr        = \Auth::user();
      
        $projects = $usr->projects()->pluck('project_name','project_id')->toArray();
       
            return view('projects.bugList', compact( 'view','projects'));
      
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
    // For Taskboard View
    public function bugreportView(Request $request)
    {
             $usr        = \Auth::user();
        $bugStatus = BugStatus::where('created_by', \Auth::user()->creatorId())->get();
       
        if (Auth::user()->type == 'company' || Auth::user()->type == 'Manager') {
            $bugs = Bug::where('project_id',$request->project_id)->with(['project','createdBy' , 'projectBUg'])->get();
        } elseif (Auth::user()->type != 'company') {
            if (\Auth::user()->type == 'client') {
                $user_projects = Project::where('client_id', \Auth::user()->id)->where('project_id',$request->project_id)->pluck('id', 'id')->toArray();
                $bugs = Bug::whereIn('project_id', $user_projects)->where('created_by', \Auth::user()->creatorId())->with(['project','createdBy'])->get();
            } else {
                $bugs = Bug::where('created_by', \Auth::user()->creatorId())->where('project_id',$request->project_id)->whereRaw("find_in_set('" . \Auth::user()->id . "',assign_to)")->with(['project','createdBy'])->get();
            }
        }
        $view=$request->view;
        if ($request->view == 'list') {
            $returnHTML= view('projects.allBugListView', compact('bugs', 'bugStatus', 'view'))->render();
            return response()->json(
                [
                    'success' => true,
                    'html' => $returnHTML,
                ]);
        } else {
            $returnHTML=view('projects.allBugGridView', compact('bugs', 'bugStatus', 'view'))->render();
            return response()->json(
                [
                    'success' => true,
                    'html' => $returnHTML,
                ]);
        }
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
    // public function bugreportView(Request $request)
    // {
    //     $usr        = \Auth::user();
    //     $bugStatus = BugStatus::where('created_by', \Auth::user()->creatorId())->get();
    //     $projects = $usr->projects()->pluck('project_name','project_id')->toArray();
    //     if (Auth::user()->type == 'company') {
    //         $bugs = Bug::where('created_by', \Auth::user()->creatorId())->with(['project','createdBy' , 'projectBUg'])->get();
    //     } elseif (Auth::user()->type != 'company') {
    //         if (\Auth::user()->type == 'client') {
    //             $user_projects = Project::where('client_id', \Auth::user()->id)->pluck('id', 'id')->toArray();
    //             $bugs = Bug::whereIn('project_id', $user_projects)->where('created_by', \Auth::user()->creatorId())->with(['project','createdBy'])->get();
    //         } else {
    //             $bugs = Bug::where('created_by', \Auth::user()->creatorId())->whereRaw("find_in_set('" . \Auth::user()->id . "',assign_to)")->with(['project','createdBy'])->get();
    //         }
    //     }
    //     if ($view == 'list') {
    //         return view('projects.allBugListView', compact('bugs', 'bugStatus', 'view','projects'));
    //     } else {
    //         return view('projects.allBugGridView', compact('bugs', 'bugStatus', 'view','projects'));
    //     }
    //     return redirect()->back()->with('error', __('Permission Denied.'));
    // }

    public function show($project_id, $task_id)
    {

        if (\Auth::user()->can('view project task')) {
            $allow_progress = Project::find($project_id)->task_progress;
            $task = ProjectTask::find($task_id);

            return view('project_task.view', compact('task', 'allow_progress'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function edit($project_id, $task_id)
    {
        if (\Auth::user()->can('edit project task')) {
            $project = Project::find($project_id);
            $task = ProjectTask::find($task_id);
            $hrs = Project::projectHrs($project_id);
            $stages = TaskStage::orderBy('order')->where('created_by', \Auth::user()->creatorId())->get();
            $requirement= RequirementMatrix::where('project_id', $project_id)->get();
        
            if($task->task_activity=='hardware')
            {
                $activity_type=ProjectTask::$hardware_activity_type;
            }else if($task->task_activity=='software')
            {
                $activity_type=ProjectTask::$software_activity_type;
            }else if($task->task_activity=='general')
            {
                $activity_type=ProjectTask::$general_activity_type;
            }
            return view('project_task.edit', compact('project', 'task', 'hrs','stages','activity_type','requirement'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function editsubtask($project_id,$task_id,$subtask_id)
    {
      
        if (\Auth::user()->can('edit project task')) {
            $project = Project::find($project_id);
            $subtask = ProjectSubtask::find($subtask_id);
            $task = ProjectTask::find($task_id);
            $stages = TaskStage::orderBy('order')->where('created_by', \Auth::user()->creatorId())->get();
            $requirement= RequirementMatrix::where('project_id', $project_id)->get();
        
            if($subtask->task_activity=='hardware')
            {
                $activity_type=ProjectTask::$hardware_activity_type;
            }else if($subtask->task_activity=='software')
            {
                $activity_type=ProjectTask::$software_activity_type;
            }else if($subtask->task_activity=='general')
            {
                $activity_type=ProjectTask::$general_activity_type;
            }
           
            return view('project_task.editSubtask', compact('project', 'subtask','stages','task','activity_type','requirement'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    
public function req($project_id,$task_id)
{
    $project = Project::find($project_id);
    $task = ProjectTask::find($task_id);

    return view('project_task.req', compact('project', 'task'));
}
    public function update(Request $request, $project_id, $task_id)
    {

        if (\Auth::user()->can('edit project task')) {
            if($request->requirement_id)
        {
            $requirement_id= implode(',', $request->requirement_id);
        }else{
            $requirement_id=$request->requirement_id;
          
        }
            $validator = Validator::make(
                $request->all(), [
                    'name' => 'required',
                    'estimated_hrs' => 'required',
                    'priority' => 'required',
                ]
            );

            if ($validator->fails()) {
               // return redirect()->back()->with('error', Utility::errorFormat($validator->getMessageBag()));
               return response()->json(['error'=>Utility::errorFormat($validator->getMessageBag())]);
            }

            $post = $request->all();
            $task = ProjectTask::find($task_id);
           
            $post['requirement_id']= $requirement_id;
            
            $task->update($post);
         
           // return redirect()->back()->with('success', __('Task Updated successfully.'));
           return response()->json(['success'=>'Task Updated successfully.']);
        } else {
           // return redirect()->back()->with('error', __('Permission Denied.'));
           return response()->json(['error'=>'Permission Denied.']);
        }
    }

    public function updatesubtask(Request $request, $project_id, $task_id,$subtask_id)
    {
       
        if (\Auth::user()->can('edit project task')) {
            if($request->requirement_id)
        {
            $requirement_id= implode(',', $request->requirement_id);
        }else{
            $requirement_id=$request->requirement_id;
          
        }
                $validator = Validator::make(
                    $request->all(), [
                        'subtask_name' => 'required',
                        'start_date' => 'required',
                        'end_date' => 'required',
                    ]
                );

            if ($validator->fails()) {
               
                return response()->json(['error'=>Utility::errorFormat($validator->getMessageBag())]);
            }


            $post = $request->all();
            $subtask = ProjectSubtask::find($subtask_id);
            $post['task_activity_type']= $request->task_activity_type;
            $post['requirement_id']= $requirement_id;
            
            $subtask->update($post);
                    
            return response()->json(['success'=>'Subtask Updated successfully.']);
           
        } else {
            //return redirect()->back()->with('error', __('Permission Denied.'));
            return response()->json(['error'=>'Permission Denied.']);
        }

    }
    public function wbsupdate(Request $request)
    {
        if (\Auth::user()->can('edit project task')) {
          
            $usr = \Auth::user();
            $project = Project::find($request->project_id);
            $precede_arr=[];
           
            
           if($request->task_predece)
            {
               
           if ((int) $request->task_predece ==$request->task_predece)
            {
                $check_task_date=ProjectTask::where('project_id',$request->project_id)->where('id', $request->task_id)->first();
              
                $task_data=ProjectTask::where('project_id',$request->project_id)->where('task_seq', $request->task_predece)->first(); 
              
               
               
               if(empty($task_data))
                {
                  
                    $precede_arr['status']='error';
                    $precede_arr['message']="ID not found.";
                }else{
                
                     $new_task_start_date = Carbon::parse($task_data->end_date)->addDays(1)->format('Y-m-d');
                if($request->task_id && empty($request->subtask_id))
                {
                  
                    $get_old_task=ProjectTask::where('project_id',$request->project_id)->where('id',$request->task_id)->first();
                   
                    $start = Carbon::parse($get_old_task->start_date);
                    $end = Carbon::parse($get_old_task->end_date);
                    $differenceInDays = $end->diffInDays($start);
                    $new_task_end_date = Carbon::parse($new_task_start_date)->addDays($differenceInDays)->format('Y-m-d');
                    if($project->end_date<$new_task_end_date)
                    {
                        $precede_arr['status']='error';
                        $precede_arr['message']="Project end date should not be grater";
                    }else if($check_task_date->task_seq!=$request->task_predece)
                    { 
                     
                         $task = ProjectTask::find($request->task_id);
                       
                         $task->start_date=$new_task_start_date;
                         $task->end_date=$new_task_end_date;
                         $task->predece=$request->task_predece;
                         $task->save();
                         $precede_arr['status']='success';
                         $precede_arr['new_task_start_date']=$new_task_start_date;
                         $precede_arr['new_task_end_date']=$new_task_end_date;
                        
                    }else{
                        $precede_arr['status']='error';
                        $precede_arr['message']="You type same ID.";
                    }
                }else if($request->task_id && $request->subtask_id){
                 
                     $get_old_subtask=ProjectSubtask::where('project_id',$request->project_id)->where('task_id',$request->task_id)->where('id',$request->subtask_id)->first();
                  
                    $start = Carbon::parse($get_old_subtask->start_date);
                    $end = Carbon::parse($get_old_subtask->end_date);
                    $differenceInDays = $end->diffInDays($start);
                    $new_task_end_date = Carbon::parse($new_task_start_date)->addDays($differenceInDays)->format('Y-m-d');
                 
                    if($project->end_date<$new_task_end_date)
                    {
                        $precede_arr['status']='error';
                        $precede_arr['message']="Project end date should not be grater";
                    }else if($new_task_start_date>=$check_task_date->start_date && $new_task_start_date<=$check_task_date->end_date && $new_task_end_date<=$check_task_date->end_date)
                   {
                        $subtaskk = ProjectSubtask::find($request->subtask_id);
                        $subtaskk->start_date=$new_task_start_date;
                        $subtaskk->end_date=$new_task_end_date;
                        $subtaskk->predece=$request->task_predece;
                        $subtaskk->save();
                        $precede_arr['status']='success';
                        $precede_arr['new_task_start_date']=$new_task_start_date;
                        $precede_arr['new_task_end_date']=$new_task_end_date;
                    }else{
                        $precede_arr['status']='error';
                        $precede_arr['message']="Task Start Date and End date should not be grater";
                    }
                }
            }
          
            } else
            {
                
                $check_task_date=ProjectTask::where('project_id',$request->project_id)->where('id', $request->task_id)->first();
               
                $sub_data=explode('.',$request->task_predece);
                $task_data=ProjectTask::where('project_id',$request->project_id)->where('task_seq',$sub_data[0])->first(); 
             
                $subtask_data=ProjectSubtask::where('project_id',$request->project_id)->where('task_id',$task_data->id)->where('subtask_seq',$sub_data[1])->first(); 
                
                if(empty($subtask_data))
                {
                 
                    $precede_arr['status']='error';
                    $precede_arr['message']="ID not found.";
                }else{
                    $new_task_start_date = Carbon::parse($subtask_data->end_date)->addDays(1)->format('Y-m-d');
                    if($request->task_id && empty($request->subtask_id))
                  {
                   
                    $get_old_task=ProjectTask::where('project_id',$request->project_id)->where('id',$request->task_id)->first();
                    
                    $start = Carbon::parse($get_old_task->start_date);
                    $end = Carbon::parse($get_old_task->end_date);
                    $differenceInDays = $end->diffInDays($start);
                    $new_task_end_date = Carbon::parse($new_task_start_date)->addDays($differenceInDays)->format('Y-m-d');
                    if($project->end_date<$new_task_end_date)
                    {
                        $precede_arr['status']='error';
                        $precede_arr['message']="Project end date should not be grater";
                    }else {
                       
                         $task = ProjectTask::find($request->task_id);
                         $task->predece=$request->task_predece;
                         $task->start_date=$new_task_start_date;
                         $task->end_date=$new_task_end_date;
                         $task->save();
                         $precede_arr['status']='success';
                         $precede_arr['new_task_start_date']=$new_task_start_date;
                         $precede_arr['new_task_end_date']=$new_task_end_date;
                        
                    }
                  }else{
                    $get_old_subtask=ProjectSubtask::where('project_id',$request->project_id)->where('task_id',$request->task_id)->where('id',$request->subtask_id)->first();
                   
                    $start = Carbon::parse($get_old_subtask->start_date);
                    $end = Carbon::parse($get_old_subtask->end_date);
                    $differenceInDays = $end->diffInDays($start);
                  
                    $new_task_end_date = Carbon::parse($new_task_start_date)->addDays($differenceInDays)->format('Y-m-d');
                   
                    if($project->end_date<$new_task_end_date)
                    {
                        $precede_arr['status']='error';
                        $precede_arr['message']="Project end date should not be grater";
                    }else if($new_task_start_date>=$check_task_date->start_date && $new_task_start_date<=$check_task_date->end_date && $new_task_end_date<=$check_task_date->end_date)
                    {
                        $subtaskk = ProjectSubtask::find($request->subtask_id);
                        $subtaskk->start_date=$new_task_start_date;
                        $subtaskk->end_date=$new_task_end_date;
                        $subtaskk->predece=$request->task_predece;
                        $subtaskk->save();
                        $precede_arr['status']='success';
                        $precede_arr['new_task_start_date']=$new_task_start_date;
                        $precede_arr['new_task_end_date']=$new_task_end_date;
                    }else{
                        $precede_arr['status']='error';
                        $precede_arr['message']="Task Start Date and End date should not be grater";
                    }
                  }
                }
            
            }
        }
        else{
            
            if($request->task_id && empty($request->subtask_id))
            {
                $check_end_date=ProjectTask::where('project_id',$request->project_id)->where('id',$request->task_id)->first();
             
              $task_data=ProjectTask::where('project_id',$request->project_id)->where('predece', $check_end_date->task_seq)->get();
              $new_task_start_date = Carbon::parse($request->end_date)->addDays(1)->format('Y-m-d');
             }else{
             
                $ttask=ProjectTask::where('project_id',$request->project_id)->where('id',$request->task_id)->first();
              
                $check_end_date=ProjectSubtask::where('project_id',$request->project_id)->where('task_id',$request->task_id)->where('id',$request->subtask_id)->first();
                $subtask_seq=$ttask->task_seq.'.'.$check_end_date->subtask_seq;
                $task_data=ProjectTask::where('project_id',$request->project_id)->where('predece', $subtask_seq)->get(); 
                $new_task_start_date = Carbon::parse($request->subtask_end_date)->addDays(1)->format('Y-m-d');
             }
                if($task_data)
                {
                    foreach($task_data as $task_data)
                    {
                   
                      
                        $start = Carbon::parse($task_data->start_date);
                        $end = Carbon::parse($task_data->end_date);
                        $differenceInDays = $end->diffInDays($start);
                         $new_task_end_date = Carbon::parse($new_task_start_date)->addDays($differenceInDays)->format('Y-m-d');
                        if($project->end_date<$new_task_end_date)
                        {
                          //  $precede_arr['status']='error';
                           // $precede_arr['message']="Project end date should not be grater";
                        }else
                        { 
                        
                             $task = ProjectTask::find($task_data->id);
                           
                             $task->start_date=$new_task_start_date;
                             $task->end_date=$new_task_end_date;
                             $task->save();
                             $subtask_data=ProjectSubtask::where('project_id',$request->project_id)->where('task_id', $task_data->id)->get(); 
                          
                             if($subtask_data)
                            {
                                foreach($subtask_data as $subtask_data)
                                {
                                    if($subtask_data->start_date<$new_task_start_date || $subtask_data->start_date>$new_task_end_date)
                                    {
                                        $subtask = ProjectSubtask::find($subtask_data->id);
                           
                                        $subtask->start_date=$new_task_start_date;
                                        $subtask->end_date=$new_task_end_date;
                                        $subtask->save(); 
                                    }
                                }
                            }
                        }
                    }
                }
            
        }

       
            $task = ProjectTask::find($request->task_id);
            $task->name = $request->task_name;
            if(empty($precede_arr['new_task_start_date']) && empty($request->subtask_id))
            {
                $task->start_date=$request->start_date;
                $task->end_date=$request->end_date;
              $task->predece='';
            }
           
            $task->progress=$request->progress;
            if($request->progress=='100')
            {
              
                $task->stage_id='9';
            }else{
                $task->stage_id=$request->stage_id;
            }
           
            $task->save();

            if($request->subtask_id)
            {
                $subtaskk = ProjectSubtask::find($request->subtask_id);
                $subtaskk->subtask_name = $request->subtask_name;
                if(empty($precede_arr['new_task_start_date']))
                {
                    $subtaskk->start_date=$request->subtask_start_date;
                    $subtaskk->end_date=$request->subtask_end_date;
                    $subtaskk->predece='';
                }
            
                $subtaskk->progress=$request->subtask_progress;
                if($request->subtask_progress=='100')
                {
                    $subtaskk->stage_id='9';
                }else{
                    $subtaskk->stage_id=$request->subtask_stage_id;
                }
               
                $subtaskk->save();
            }
           
          
            $tasks   = [];

            if ($project) {
               
                $tasksobj= ProjectTask::where('project_id', '=',$request->project_id);
                if(!empty($request->task_user_id))
                {
                 
                    $tasksobj->whereRaw("find_in_set('" .$request->task_user_id. "', assign_to)");
                }else if(\Auth::user()->type != 'company' && \Auth::user()->type != 'Manager')
                {
                    $tasksobj->whereRaw("find_in_set('" .$usr->id. "', assign_to)");
                }
                if(!empty($request->task_stage_id))
                {
                  
                    $tasksobj->where('stage_id','=',$request->task_stage_id);
                }
                $tasksobj->orderBy('task_seq', 'asc');
                $tasksobj=$tasksobj->get();
              //  $tasksobj = $project->tasks;

                foreach ($tasksobj as $task) {
                    
                    $assign_to = explode(",", $task->assign_to);
                    if ((in_array( $usr->id, $assign_to) && \Auth::user()->type != 'company') || \Auth::user()->type == 'company' || \Auth::user()->type=='Manager')
                    {
                    $tmp                 = [];
                    $tmp['id']           = 'task_' . $task->id;
                    $tmp['name']         = $task->name;
                    $tmp['stage_id']     =$task->stage_id;
                    $tmp['start']        = $task->start_date;
                    $tmp['end']          = $task->end_date;
                    $tmp['custom_class'] = (empty($task->priority_color) ? '#ecf0f1' : $task->priority_color);
                    
                    $tmp['progress']     = $task->progress;
                    $tmp['extra']        = [
                        'priority' => ucfirst(__($task->priority)),
                        'comments' => count($task->comments),
                        'duration' => Utility::getDateFormated($task->start_date) . ' - ' . Utility::getDateFormated($task->end_date),
                    ];

                    $subtasks=ProjectSubtask::where('project_id',$task->project_id);
                    $subtasks->where('task_id',$task->id);
                    if(!empty($task_user_id))
                    {
                     
                        $subtasks->whereRaw("find_in_set('" . $request->task_user_id . "', assign_to)");
                    }else if(\Auth::user()->type != 'company' && \Auth::user()->type != 'Manager'){
                        $subtasks->whereRaw("find_in_set('" .$usr->id. "', assign_to)");
                    }
                    if(!empty($task_stage_id))
                    {
                   
                        $subtasks->where('stage_id','=',$request->task_stage_id);
                    }
                    $subtasks->orderBy('subtask_seq', 'asc');
                    $subtasks=$subtasks->get();
                   
                    $tasks[]             = $tmp;
                  
                    foreach ($subtasks as $subtask) {
                        $subtasksData = [];
                        $subtasksData['id']           = 'subtask_' . $subtask->id;
                        $subtasksData['name']         = $subtask->subtask_name;
                       
                        $subtasksData['start']        = $subtask->start_date;
                        $subtasksData['end']          = $subtask->end_date;
                        $subtasksData['custom_class'] = (empty($subtask->priority_color) ? '#ecf0f1' : $subtask->priority_color);
                        $subtasksData['progress']     =$subtask->progress;
                       
    
                        $subtasksData['extra']        = [
                            'priority' => ucfirst(__($subtask->priority)),
                            'comments' => count($task->comments),
                            'duration' => Utility::getDateFormated($subtask->start_date) . ' - ' . Utility::getDateFormated($subtask->end_date),
                        ];
                        $subtasksData['dependencies'] = ['task_' . $task->id]; // Assign subtasks as children
                        $tasks[]             = $subtasksData;
                    }
                   
            
                }
                }
            }
            return json_encode( [
                'status' => true,
                'task' => $tasks,
                'precede_arr'=>$precede_arr,
               
            ]);
        } else {
            return json_encode(['error' => 'Permission Denied.']);
           
        }
    }
    public function wbsupdate_bk(Request $request)
    {
        if (\Auth::user()->can('edit project task')) {
          
            $usr = \Auth::user();
            $project = Project::find($request->project_id);
            $precede_arr=[];
           
           if($request->task_predece)
            {
           if ((int) $request->task_predece ==$request->task_predece)
            {
                $check_task_date=ProjectTask::where('project_id',$request->project_id)->where('id', $request->task_id)->first();
              
                $task_data=ProjectTask::where('project_id',$request->project_id)->where('task_seq', $request->task_predece)->first(); 
              
               
               
               if(empty($task_data))
                {
                  
                    $precede_arr['status']='error';
                    $precede_arr['message']="ID not found.";
                }else{
                
                     $new_task_start_date = Carbon::parse($task_data->end_date)->addDays(1)->format('Y-m-d');
                if($request->task_id && empty($request->subtask_id))
                {
                  
                    $get_old_task=ProjectTask::where('project_id',$request->project_id)->where('id',$request->task_id)->first();
                   
                    $start = Carbon::parse($get_old_task->start_date);
                    $end = Carbon::parse($get_old_task->end_date);
                    $differenceInDays = $end->diffInDays($start);
                    $new_task_end_date = Carbon::parse($new_task_start_date)->addDays($differenceInDays)->format('Y-m-d');
                    if($project->end_date<$new_task_end_date)
                    {
                        $precede_arr['status']='error';
                        $precede_arr['message']="Project end date should not be grater";
                    }else if($check_task_date->task_seq!=$request->task_predece)
                    { 
                     
                         $task = ProjectTask::find($request->task_id);
                       
                         $task->start_date=$new_task_start_date;
                         $task->end_date=$new_task_end_date;
                         $task->predece=$request->task_predece;
                         $task->save();
                         $precede_arr['status']='success';
                         $precede_arr['new_task_start_date']=$new_task_start_date;
                         $precede_arr['new_task_end_date']=$new_task_end_date;
                        
                    }else{
                        $precede_arr['status']='error';
                        $precede_arr['message']="You type same ID.";
                    }
                }else if($request->task_id && $request->subtask_id){
                 
                     $get_old_subtask=ProjectSubtask::where('project_id',$request->project_id)->where('task_id',$request->task_id)->where('id',$request->subtask_id)->first();
                  
                    $start = Carbon::parse($get_old_subtask->start_date);
                    $end = Carbon::parse($get_old_subtask->end_date);
                    $differenceInDays = $end->diffInDays($start);
                    $new_task_end_date = Carbon::parse($new_task_start_date)->addDays($differenceInDays)->format('Y-m-d');
                 
                    if($project->end_date<$new_task_end_date)
                    {
                        $precede_arr['status']='error';
                        $precede_arr['message']="Project end date should not be grater";
                    }else if($new_task_start_date>=$check_task_date->start_date && $new_task_start_date<=$check_task_date->end_date && $new_task_end_date<=$check_task_date->end_date)
                   {
                        $subtaskk = ProjectSubtask::find($request->subtask_id);
                        $subtaskk->start_date=$new_task_start_date;
                        $subtaskk->end_date=$new_task_end_date;
                        $subtaskk->predece=$request->task_predece;
                        $subtaskk->save();
                        $precede_arr['status']='success';
                        $precede_arr['new_task_start_date']=$new_task_start_date;
                        $precede_arr['new_task_end_date']=$new_task_end_date;
                    }else{
                        $precede_arr['status']='error';
                        $precede_arr['message']="Task Start Date and End date should not be grater";
                    }
                }
            }
          
            } else
            {
                
                $check_task_date=ProjectTask::where('project_id',$request->project_id)->where('id', $request->task_id)->first();
               
                $sub_data=explode('.',$request->task_predece);
                $task_data=ProjectTask::where('project_id',$request->project_id)->where('task_seq',$sub_data[0])->first(); 
             
                $subtask_data=ProjectSubtask::where('project_id',$request->project_id)->where('task_id',$task_data->id)->where('subtask_seq',$sub_data[1])->first(); 
                
                if(empty($subtask_data))
                {
                 
                    $precede_arr['status']='error';
                    $precede_arr['message']="ID not found.";
                }else{
                    $new_task_start_date = Carbon::parse($subtask_data->end_date)->addDays(1)->format('Y-m-d');
                    if($request->task_id && empty($request->subtask_id))
                  {
                   
                    $get_old_task=ProjectTask::where('project_id',$request->project_id)->where('id',$request->task_id)->first();
                    
                    $start = Carbon::parse($get_old_task->start_date);
                    $end = Carbon::parse($get_old_task->end_date);
                    $differenceInDays = $end->diffInDays($start);
                    $new_task_end_date = Carbon::parse($new_task_start_date)->addDays($differenceInDays)->format('Y-m-d');
                    if($project->end_date<$new_task_end_date)
                    {
                        $precede_arr['status']='error';
                        $precede_arr['message']="Project end date should not be grater";
                    }else {
                       
                         $task = ProjectTask::find($request->task_id);
                         $task->predece=$request->task_predece;
                         $task->start_date=$new_task_start_date;
                         $task->end_date=$new_task_end_date;
                         $task->save();
                         $precede_arr['status']='success';
                         $precede_arr['new_task_start_date']=$new_task_start_date;
                         $precede_arr['new_task_end_date']=$new_task_end_date;
                        
                    }
                  }else{
                    $get_old_subtask=ProjectSubtask::where('project_id',$request->project_id)->where('task_id',$request->task_id)->where('id',$request->subtask_id)->first();
                   
                    $start = Carbon::parse($get_old_subtask->start_date);
                    $end = Carbon::parse($get_old_subtask->end_date);
                    $differenceInDays = $end->diffInDays($start);
                  
                    $new_task_end_date = Carbon::parse($new_task_start_date)->addDays($differenceInDays)->format('Y-m-d');
                   
                    if($project->end_date<$new_task_end_date)
                    {
                        $precede_arr['status']='error';
                        $precede_arr['message']="Project end date should not be grater";
                    }else if($new_task_start_date>=$check_task_date->start_date && $new_task_start_date<=$check_task_date->end_date && $new_task_end_date<=$check_task_date->end_date)
                    {
                        $subtaskk = ProjectSubtask::find($request->subtask_id);
                        $subtaskk->start_date=$new_task_start_date;
                        $subtaskk->end_date=$new_task_end_date;
                        $subtaskk->predece=$request->task_predece;
                        $subtaskk->save();
                        $precede_arr['status']='success';
                        $precede_arr['new_task_start_date']=$new_task_start_date;
                        $precede_arr['new_task_end_date']=$new_task_end_date;
                    }else{
                        $precede_arr['status']='error';
                        $precede_arr['message']="Task Start Date and End date should not be grater";
                    }
                  }
                }
            
            }
        }

            $task = ProjectTask::find($request->task_id);
            $task->name = $request->task_name;
            if(empty($precede_arr['new_task_start_date']) && empty($request->subtask_id))
            {
                $task->start_date=$request->start_date;
                $task->end_date=$request->end_date;
                $task->predece='';
            }
           
            $task->progress=$request->progress;
            $task->stage_id=$request->stage_id;
            $task->save();

            if($request->subtask_id)
            {
                $subtaskk = ProjectSubtask::find($request->subtask_id);
                $subtaskk->subtask_name = $request->subtask_name;
                if(empty($precede_arr['new_task_start_date']))
                {
                    $subtaskk->start_date=$request->subtask_start_date;
                    $subtaskk->end_date=$request->subtask_end_date;
                    $subtaskk->predece='';
                }
            
                $subtaskk->progress=$request->subtask_progress;
                $subtaskk->stage_id=$request->subtask_stage_id;
                $subtaskk->save();
            }
           
          
            $tasks   = [];

            if ($project) {
                $tasksobj = $project->tasks;

                foreach ($tasksobj as $task) {
                    
                    $assign_to = explode(",", $task->assign_to);
                    if ((in_array( $usr->id, $assign_to) && \Auth::user()->type != 'company') || \Auth::user()->type == 'company' || \Auth::user()->type=='Manager')
                    {
                    $tmp                 = [];
                    $tmp['id']           = 'task_' . $task->id;
                    $tmp['name']         = $task->name;
                    $tmp['stage_id']     =$task->stage_id;
                    $tmp['start']        = $task->start_date;
                    $tmp['end']          = $task->end_date;
                    $tmp['custom_class'] = (empty($task->priority_color) ? '#ecf0f1' : $task->priority_color);
                    
                    $tmp['progress']     = $task->progress;
                    $tmp['extra']        = [
                        'priority' => ucfirst(__($task->priority)),
                        'comments' => count($task->comments),
                        'duration' => Utility::getDateFormated($task->start_date) . ' - ' . Utility::getDateFormated($task->end_date),
                    ];

                    $subtasks=ProjectSubtask::where('project_id',$task->project_id)->where('task_id',$task->id)->orderBy('subtask_seq', 'asc')->get(); 
                   
                  
                   
                    $tasks[]             = $tmp;
                  
                    foreach ($subtasks as $subtask) {
                        $subtasksData = [];
                        $subtasksData['id']           = 'subtask_' . $subtask->id;
                        $subtasksData['name']         = $subtask->subtask_name;
                       
                        $subtasksData['start']        = $subtask->start_date;
                        $subtasksData['end']          = $subtask->end_date;
                        $subtasksData['custom_class'] = (empty($subtask->priority_color) ? '#ecf0f1' : $subtask->priority_color);
                        $subtasksData['progress']     =$subtask->progress;
                       
    
                        $subtasksData['extra']        = [
                            'priority' => ucfirst(__($subtask->priority)),
                            'comments' => count($task->comments),
                            'duration' => Utility::getDateFormated($subtask->start_date) . ' - ' . Utility::getDateFormated($subtask->end_date),
                        ];
                        $subtasksData['dependencies'] = ['task_' . $task->id]; // Assign subtasks as children
                        $tasks[]             = $subtasksData;
                    }
                   
            
                }
                }
            }
            return json_encode( [
                'status' => true,
                'task' => $tasks,
                'precede_arr'=>$precede_arr,
               
            ]);
        } else {
            return json_encode(['error' => 'Permission Denied.']);
           
        }
    }

    public function get_updated_gantt(Request $request)
    {
        $usr = \Auth::user();
        if (\Auth::user()->can('view project task')) {
            $projectID=$request->project_id;
            $project = Project::find($projectID);
          
        
            if(\Auth::user()->type != 'company')
            {
                if(\Auth::user()->type == 'client'){
               
                  $task_list = ProjectTask::where('project_id', '=', $projectID)->orderBy('task_seq', 'asc')->get();
                }else{
                   
                    $task_list = ProjectTask::where('project_id', '=', $projectID)->whereRaw("find_in_set('" . $usr->id . "',assign_to)")->orderBy('task_seq', 'asc')->get();
               
                }
            }

           
            if(\Auth::user()->type == 'company' || \Auth::user()->type == 'Manager')
            {
                $task_list = ProjectTask::where('project_id', '=', $projectID)->orderBy('task_seq', 'asc')->get();
            }
            $stages = TaskStage::orderBy('order')->where('created_by', \Auth::user()->creatorId())->get();
              
            $tasks   = [];

            if ($project) {
                $tasksobj = $project->tasks;
                
                foreach ($tasksobj as $task) {
                    
                    $assign_to = explode(",", $task->assign_to);
                    if ((in_array( $usr->id, $assign_to) && \Auth::user()->type != 'company') || \Auth::user()->type == 'company' || \Auth::user()->type=='Manager')
                    {
                    $tmp                 = [];
                    $tmp['id']           = 'task_' . $task->id;
                    $tmp['name']         = $task->name;
                    $tmp['stage_id']     =$task->stage_id;
                    $tmp['start']        = $task->start_date;
                    $tmp['end']          = $task->end_date;
                    $tmp['custom_class'] = (empty($task->priority_color) ? '#ecf0f1' : $task->priority_color);
                    $tmp['progress']     =$task->progress;
                   

                    $tmp['extra']        = [
                        'priority' => ucfirst(__($task->priority)),
                        'comments' => count($task->comments),
                        'duration' => Utility::getDateFormated($task->start_date) . ' - ' . Utility::getDateFormated($task->end_date),
                    ];
                   
                    $subtasks=ProjectSubtask::where('project_id',$task->project_id)->where('task_id',$task->id)->orderBy('subtask_seq', 'asc')->get(); 
                   
                  
                   
                    $tasks[]             = $tmp;
                  
                    foreach ($subtasks as $subtask) {
                        $subtasksData = [];
                        $subtasksData['id']           = 'subtask_' . $subtask->id;
                        $subtasksData['name']         = $subtask->subtask_name;
                       
                        $subtasksData['start']        = $subtask->start_date;
                        $subtasksData['end']          = $subtask->end_date;
                        $subtasksData['custom_class'] = (empty($subtask->priority_color) ? '#ecf0f1' : $subtask->priority_color);
                        $subtasksData['progress']     =$subtask->progress;
                       
    
                        $subtasksData['extra']        = [
                            'priority' => ucfirst(__($subtask->priority)),
                            'comments' => count($task->comments),
                            'duration' => Utility::getDateFormated($subtask->start_date) . ' - ' . Utility::getDateFormated($subtask->end_date),
                        ];
                        $subtasksData['dependencies'] = ['task_' . $task->id]; // Assign subtasks as children
                        $tasks[]             = $subtasksData;
                    }
                }
                }
            }
          
            return json_encode( [
                'status' => true,
                'task' => $tasks,
               
            ]);
        } else {
            return json_encode(['error' => 'Permission Denied.']);
        }
    }

    public function destroy(Request $request)
    {

        if (\Auth::user()->can('delete project task')) {

            $tmp_task= [];
           
                    // Handle case where no tasks are found for the specified project_id
                 
                    $get_seq = ProjectTask::select('id', 'project_id', 'task_seq')
                        ->where('project_id', '=', $request->project_id)
                        ->where('task_seq', '>', $request->task_seq)
                        
                        ->orderBy('task_seq', 'asc') // Order by task_seq in ascending order
                        ->get();
                       
                        foreach ($get_seq as $get_seq) {
                            $tmp                = [];
                            $update_seq=$get_seq->task_seq-1;
                           
                            $tmp['id']           = $get_seq->id;
                            $tmp['project_id']         = $request->project_id;
                            $tmp['task_seq']     =$update_seq;
                            $tmp_task[]             = $tmp;
                  
                 
                        }
                      
            ProjectTask::deleteTask([$request->task_id]);

          return response()->json(['success'=>'Task Deleted successfully.','tmp_task'=>$tmp_task]);

            //return json_encode(['task_id' => $task_id]);
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function getStageTasks(Request $request, $stage_id)
    {

        if (\Auth::user()->can('view project task')) {
            $count = ProjectTask::where('stage_id', $stage_id)->count();
            echo json_encode($count);
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function changeCom($projectID, $taskId)
    {

        if (\Auth::user()->can('view project task')) {
            $project = Project::find($projectID);
            $task = ProjectTask::find($taskId);

            if ($task->is_complete == 0) {
                $last_stage = TaskStage::orderBy('order', 'DESC')->where('created_by', \Auth::user()->creatorId())->first();
                $task->is_complete = 1;
                $task->marked_at = date('Y-m-d');
                $task->stage_id = $last_stage->id;
            } else {
                $first_stage = TaskStage::orderBy('order', 'ASC')->where('created_by', \Auth::user()->creatorId())->first();
                $task->is_complete = 0;
                $task->marked_at = NULL;
                $task->stage_id = $first_stage->id;
            }

            $task->save();

            return [
                'com' => $task->is_complete,
                'task' => $task->id,
                'stage' => $task->stage_id,
            ];
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function changeFav($projectID, $taskId)
    {
        if (\Auth::user()->can('view project task')) {
            $task = ProjectTask::find($taskId);
            if ($task->is_favourite == 0) {
                $task->is_favourite = 1;
            } else {
                $task->is_favourite = 0;
            }

            $task->save();

            return [
                'fav' => $task->is_favourite,
            ];
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function changeProg(Request $request, $projectID, $taskId)
    {
        if (\Auth::user()->can('view project task')) {
            $task = ProjectTask::find($taskId);
            $task->progress = $request->progress;
            $task->save();

            return ['task_id' => $taskId];
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function checklistStore(Request $request, $projectID, $taskID)
    {

        if (\Auth::user()->can('view project task')) {
            $request->validate(
                ['name' => 'required']
            );

            $post = [];
            $post['name'] = $request->name;
            $post['task_id'] = $taskID;
            $post['user_type'] = 'User';
            $post['created_by'] = \Auth::user()->id;
            $post['status'] = 0;

            $checkList = TaskChecklist::create($post);
            $user = $checkList->user;
            $checkList->updateUrl = route(
                'checklist.update', [
                    $projectID,
                    $checkList->id,
                ]
            );
            $checkList->deleteUrl = route(
                'checklist.destroy', [
                    $projectID,
                    $checkList->id,
                ]
            );

            return $checkList->toJson();
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function checklistUpdate($projectID, $checklistID)
    {

        if (\Auth::user()->can('view project task')) {
            $checkList = TaskChecklist::find($checklistID);
            if ($checkList->status == 0) {
                $checkList->status = 1;
            } else {
                $checkList->status = 0;
            }
            $checkList->save();

            return $checkList->toJson();
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function checklistDestroy($projectID, $checklistID)
    {
        if (\Auth::user()->can('view project task')) {
            $checkList = TaskChecklist::find($checklistID);
            $checkList->delete();

            return "true";
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function commentStoreFile(Request $request, $projectID, $taskID)
    {

        if (\Auth::user()->can('view project task')) {
            $request->validate(
                ['file' => 'required']
            );
            if ($request->hasFile('file')) {
                $filenameWithExt = $request->file('file')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('file')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
    
                $settings = Utility::getStorageSetting();
                if ($settings['storage_setting'] == 'local') {
                    $dir = 'uploads/tasks/';
                } else {
                    $dir = 'uploads/tasks';
                }
    
    
                $url = '';
                $path = Utility::upload_file($request, 'file', $fileNameToStore, $dir, []);
                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->route('file', \Auth::user()->id)->with('error', __($path['msg']));
                }
            }

            $post['task_id'] = $taskID;
            $post['file'] = $request->hasFile('file') ? $fileNameToStore : '';
            $post['name'] = $request->file->getClientOriginalName();
            $post['extension'] = $request->file->getClientOriginalExtension();
            $post['file_size'] = round(($request->file->getSize() / 1024) / 1024, 2) . ' MB';
            $post['created_by'] = \Auth::user()->id;
            $post['user_type'] = 'User';
            $TaskFile = TaskFile::create($post);
            $user = $TaskFile->user;
            $TaskFile->deleteUrl = '';
            $TaskFile->deleteUrl = route(
                'comment.destroy.file', [
                    $projectID,
                    $taskID,
                    $TaskFile->id,
                ]
            );

            return $TaskFile->toJson();
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function commentDestroyFile(Request $request, $projectID, $taskID, $fileID)
    {
        if (\Auth::user()->can('view project task')) {
            $commentFile = TaskFile::find($fileID);
            $path = storage_path('tasks/' . $commentFile->file);
            if (file_exists($path)) {
                \File::delete($path);
            }
            $commentFile->delete();

            return "true";
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function commentDestroy(Request $request, $projectID, $taskID, $commentID)
    {

        if (\Auth::user()->can('view project task')) {
            $comment = TaskComment::find($commentID);
            $comment->delete();

            return "true";
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function commentStore(Request $request, $projectID, $taskID)
    {

        if (\Auth::user()->can('view project task')) {
            $post = [];
            $post['task_id'] = $taskID;
            $post['user_id'] = \Auth::user()->id;
            $post['comment'] = $request->comment;
            $post['created_by'] = \Auth::user()->creatorId();
            $post['user_type'] = \Auth::user()->type;

            $comment = TaskComment::create($post);
            $user = $comment->user;
            $user_detail = $comment->userdetail;

            $comment->deleteUrl = route(
                'comment.destroy', [
                    $projectID,
                    $taskID,
                    $comment->id,
                ]
            );

            //For Notification
            $setting = Utility::settings(\Auth::user()->creatorId());
            $commentOfTask = ProjectTask::find($taskID);
            $project = Project::find($projectID);
            $CommentNotificationArr = [
                'task_name' => $commentOfTask->name,
                'project_name' => $project->project_name,
                'user_name' => \Auth::user()->name,
            ];
            //Slack Notification
            if (isset($setting['taskcomment_notification']) && $setting['taskcomment_notification'] == 1) {
                Utility::send_slack_msg('new_task_comment', $CommentNotificationArr);
            }

            //Telegram Notification
            if (isset($setting['telegram_taskcomment_notification']) && $setting['telegram_taskcomment_notification'] == 1) {
                Utility::send_telegram_msg('new_task_comment', $CommentNotificationArr);

            }


            $comment->current_time = $comment->created_at->diffForHumans();
            $comment->default_img = asset(\Storage::url("uploads/avatar/avatar.png"));

            //webhook
            $module = 'New Task Comment';
            $webhook = Utility::webhookSetting($module);
            if ($webhook) {
                $parameter = json_encode($comment);
                $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);

                if ($status == true) {
                    return redirect()->back()->with('success', __('Comment added successfully.'));
                } else {
                    return redirect()->back()->with('error', __('Webhook call failed.'));
                }
            }
            return $comment->toJson();
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function updateTaskPriorityColor(Request $request)
    {
        if (\Auth::user()->can('view project task')) {
            $task_id = $request->input('task_id');
            $color = $request->input('color');

            $task = ProjectTask::find($task_id);

            if ($task && $color) {
                $task->priority_color = $color;
                $task->save();
            }
            echo json_encode(true);
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function taskOrderUpdate(Request $request, $project_id)
    {

        if (\Auth::user()->can('view project task')) {

            $user = \Auth::user();
            $project = Project::find($project_id);
            // Save data as per order

            if (isset($request->sort)) {
                foreach ($request->sort as $index => $taskID) {
                    if (!empty($taskID)) {
                        echo $index . "-" . $taskID;
                        $task = ProjectTask::find($taskID);

                        $task->order = $index;
                        $task->save();

                    }
                }
            }

            // Update Task Stage
            if ($request->new_stage != $request->old_stage) {

                $new_stage = TaskStage::find($request->new_stage);
                $old_stage = TaskStage::find($request->old_stage);
                $last_stage = TaskStage::where('created_by', \Auth::user()->creatorId())->orderBy('order', 'DESC')->first();
                $last_stage = $last_stage->id;

                $task = ProjectTask::find($request->id);

                $task->stage_id = $request->new_stage;

                if ($request->new_stage == $last_stage) {
                    $task->is_complete = 1;
                    $task->marked_at = date('Y-m-d');
                } else {
                    $task->is_complete = 0;
                    $task->marked_at = NULL;
                }
                $task->save();

                //For Notification
                $setting = Utility::settings(\Auth::user()->creatorId());
                $old_stage = TaskStage::find($request->old_stage);
                $new_stage = TaskStage::find($request->new_stage);
                $task = ProjectTask::find($request->id);
                $users = explode(',',$task->assign_to);

                // if(isset($setting['task_status_updated']) && $setting['task_status_updated'] ==1)
                // {
                //     foreach ($users as $key => $user) {
                //         $user = User::find($user);
                //         $projectArr = [
                //             'task_user' => $user->name,
                //             'task_name' => $task->name,
                //             'old_stage_name' => $old_stage->name,
                //             'new_stage_name' => $new_stage->name,
                //         ];
                //       //  $resp = Utility::sendEmailTemplate('task_status_updated', [$user->id => $user->email], $projectArr);
                //     }
                // }                

                $projectTaskNotificationArr = [
                    'task_name' => $task->name,
                    'old_stage_name' => $old_stage->name,
                    'new_stage_name' => $new_stage->name,
                ];
                //Slack Notification
                if (isset($setting['taskmove_notification']) && $setting['taskmove_notification'] == 1) {
                    Utility::send_slack_msg('task_stage_updated', $projectTaskNotificationArr);
                }
                //Telegram Notification
                if (isset($setting['telegram_taskmove_notification']) && $setting['telegram_taskmove_notification'] == 1) {
                    Utility::send_telegram_msg('task_stage_updated', $projectTaskNotificationArr);
                }

                //webhook
                $module = 'Task Stage Updated';
                $webhook = Utility::webhookSetting($module);
                if ($webhook) {
                    $parameter = json_encode($task);
                    $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);
                    if ($status == true) {
                        return redirect()->back()->with('success', __('Task successfully updated!'));
                    } else {
                        return redirect()->back()->with('error', __('Webhook call failed.'));
                    }
                }


                // Make Entry in activity log
                ActivityLog::create(
                    [
                        'user_id' => $user->id,
                        'project_id' => $project_id,
                        'task_id' => $request->id,
                        'log_type' => 'Move Task',
                        'remark' => json_encode(
                            [
                                'title' => $task->name,
                                'old_stage' => $old_stage->name,
                                'new_stage' => $new_stage->name,
                            ]
                        ),
                    ]

                );

                return $task->toJson();
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function taskGet($task_id)
    {
        if (\Auth::user()->can('view project task')) {
            $task = ProjectTask::find($task_id);
//            dd($task->taskProgress()['color']);

            $html = '';
            $html .= '<div class="card-body"><div class="row align-items-center mb-2">';
            $html .= '<div class="col-6">';
            $html .= '<span class="badge badge-pill badge-xs badge-' . ProjectTask::$priority_color[$task->priority] . '">' . ProjectTask::$priority[$task->priority] . '</span>';
            $html .= '</div>';
            $html .= '<div class="col-6 text-end">';
//            if(str_replace('%', '', $task->taskProgress()['percentage']) > 0)
//            {
//                $html .= '<span class="text-sm">' . $task->taskProgress()['percentage'] . '</span> <div class="progress">
//                                                    <div class="progress-bar bg-{{ $task->taskProgress()['color'] }}" role="progressbar"
//                                                         style="width: {{ $task->taskProgress()['percentage'] }};"></div>
//                                                </div>';
//            }
            if (\Auth::user()->can('view project task') || \Auth::user()->can('edit project task') || \Auth::user()->can('delete project task')) {
                $html .= '<div class="dropdown action-item">
                                                            <a href="#" class="action-item" data-toggle="dropdown"><i class="ti ti-ellipsis-h"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-right">';
                if (\Auth::user()->can('view project task')) {
                    $html .= '<a href="#" data-url="' . route(
                            'projects.tasks.show', [
                                $task->project_id,
                                $task->id,
                            ]
                        ) . '" data-ajax-popup="true" class="dropdown-item">' . __('View') . '</a>';
                }
                if (\Auth::user()->can('edit project task')) {
                    $html .= '<a href="#" data-url="' . route(
                            "projects.tasks.edit", [
                                $task->project_id,
                                $task->id,
                            ]
                        ) . '" data-ajax-popup="true" data-size="lg" data-title="' . __("Edit ") . $task->name . '" class="dropdown-item">' . __('Edit') . '</a>';
                }
                if (\Auth::user()->can('delete project task')) {
                    $html .= '<a href="#" class="dropdown-item del_task" data-url="' . route(
                            'projects.tasks.destroy', [
                                $task->project_id,
                                $task->id,
                            ]
                        ) . '">' . __('Delete') . '</a>';
                }
                $html .= '                                 </div>
                                                        </div>
                                                    </div>';
                $html .= '</div>';
            }
            $html .= '<a class="h6" href="#" data-url="' . route(
                    "projects.tasks.show", [
                        $task->project_id,
                        $task->id,
                    ]
                ) . '" data-ajax-popup="true">' . $task->name . '</a>';
            $html .= '<div class="row align-items-center">';
            $html .= '<div class="col-12">';
            $html .= '<div class="actions d-inline-block">';
            if (count($task->taskFiles) > 0) {
                $html .= '<div class="action-item mr-2"><i class="ti ti-file text-primary mr-2"></i>' . count($task->taskFiles) . '</div>';
            }
            if (count($task->comments) > 0) {
                $html .= '<div class="action-item mr-2"><i class="ti ti-message text-primary mr-2"></i>' . count($task->comments) . '</div>';
            }
            if ($task->checklist->count() > 0) {
                $html .= '<div class="action-item mr-2"><i class="ti ti-list text-primary mr-2"></i>' . $task->countTaskChecklist() . '</div>';
            }
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="col-5">';
            if (!empty($task->end_date) && $task->end_date != '0000-00-00') {
                $clr = (strtotime($task->end_date) < time()) ? 'text-danger' : '';
                $html .= '<small class="' . $clr . '">' . date("d M Y", strtotime($task->end_date)) . '</small>';
            }
            $html .= '</div>';
            $html .= '<div class="col-7 text-end">';

            if ($users = $task->users()) {
                $html .= '<div class="avatar-group">';
                foreach ($users as $key => $user) {
                    if ($key < 3) {
                        $html .= ' <a href="#" class="avatar rounded-circle avatar-sm">';
                        $html .= '<img class="hweb" src="' . $user->getImgImageAttribute() . '" title="' . $user->name . '">';
                        $html .= '</a>';
                    }
                }

                if (count($users) > 3) {
                    $html .= '<a href="#" class="avatar rounded-circle avatar-sm"><img avatar="';
                    $html .= count($users) - 3;
                    $html .= '"></a>';
                }
                $html .= '</div>';
            }
            $html .= '</div></div></div>';

            print_r($html);
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function getDefaultTaskInfo(Request $request, $task_id)
    {

        if (\Auth::check()) {
            if (\Auth::user()->can('view project task')) {
                $response = [];
                $task = ProjectTask::find($task_id);
                if ($task) {
                    $response['task_name'] = $task->name;
                    $response['task_due_date'] = $task->due_date;
                }

                return json_encode($response);
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            $response = [];
            $task = ProjectTask::find($task_id);
            if ($task) {
                $response['task_name'] = $task->name;
                $response['task_due_date'] = $task->due_date;
            }

            return json_encode($response);
        }


    }

    // Calendar View
    public function calendarView($task_by, $project_id = NULL)
    {
        $usr = Auth::user();
        $transdate = date('Y-m-d', time());

        if ($usr->type != 'admin') {
            if (\Auth::user()->type == 'client') {
                $user_projects = Project::where('client_id', \Auth::user()->id)->pluck('id', 'id')->toArray();
            } else {
                $user_projects = $usr->projects()->pluck('project_id', 'project_id')->toArray();
            }
            $user_projects = (!empty($project_id) && $project_id > 0) ? [$project_id] : $user_projects;

            if (\Auth::user()->type == 'company') {
                $tasks = ProjectTask::whereIn('project_id', $user_projects);
            } elseif (\Auth::user()->type != 'company') {
                if (\Auth::user()->type == 'client') {

                    $tasks = ProjectTask::whereIn('project_id', $user_projects);
                } else {
                    $tasks = ProjectTask::whereIn('project_id', $user_projects)->whereRaw("find_in_set('" . \Auth::user()->id . "',assign_to)");
                }
            }
            if (\Auth::user()->type == 'client') {
                if ($task_by == 'all') {
                    $tasks->where('created_by', \Auth::user()->creatorId());
                }
            } else {
                if ($task_by == 'my') {
                    $tasks->whereRaw("find_in_set('" . $usr->id . "',assign_to)");
                }
            }
            $tasks = $tasks->get();
            $arrTasks = [];

            foreach ($tasks as $task) {
                $arTasks = [];
                if ((!empty($task->start_date) && $task->start_date != '0000-00-00') || !empty($task->end_date) && $task->end_date != '0000-00-00') {
                    $arTasks['id'] = $task->id;
                    $arTasks['title'] = $task->name;

                    if (!empty($task->start_date) && $task->start_date != '0000-00-00') {
                        $arTasks['start'] = $task->start_date;
                    } elseif (!empty($task->end_date) && $task->end_date != '0000-00-00') {
                        $arTasks['start'] = $task->end_date;
                    }
                    if (!empty($task->end_date) && $task->end_date != '0000-00-00') {
                        $arTasks['end'] = $task->end_date;
                    } elseif (!empty($task->start_date) && $task->start_date != '0000-00-00') {
                        $arTasks['end'] = $task->start_date;
                    }
                    $arTasks['allDay'] = !0;
                    $arTasks['className'] = 'event-' . ProjectTask::$priority_color[$task->priority];
                    $arTasks['description'] = $task->description;
                    $arTasks['url'] = route('task.calendar.show', $task->id);
                    $arTasks['resize_url'] = route('task.calendar.drag', $task->id);
                    $arrTasks[] = $arTasks;


                }
            }

            return view('tasks.calendar', compact('arrTasks', 'project_id', 'task_by', 'transdate'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    // Calendar Show
    public function calendarShow($id)
    {
        $task = ProjectTask::find($id);

        return view('tasks.calendar_show', compact('task'));
    }

    // Calendar Drag
    public function calendarDrag(Request $request, $id)
    {
        $task = ProjectTask::find($id);
        $task->start_date = $request->start;
        $task->end_date = $request->end;
        $task->save();
    }

    //for Google Calendar
    public function get_task_data(Request $request)
    {
        if ($request->get('calender_type') == 'goggle_calender') {
            $type = 'task';
            $arrayJson = Utility::getCalendarData($type);
        } else {
            if (Auth::user()->type == 'client') {
                $user_projects = Project::where('client_id', \Auth::user()->id)->pluck('id', 'id')->toArray();
                $data = ProjectTask::whereIn('project_id', $user_projects)->get();
            } else {
                if (Auth::user()->type == 'company') {
                    $data = ProjectTask::where('created_by', \Auth::user()->creatorId())->get();
                } else {
                    $usr = Auth::user();
                    $user_projects = $usr->projects()->pluck('project_id', 'project_id')->toArray();
                    $data = ProjectTask::whereIn('project_id', $user_projects)
                        ->where('created_by', \Auth::user()->creatorId())
                        ->whereRaw("find_in_set('" . \Auth::user()->id . "',assign_to)")->get();
                }

            }

//            $data = ProjectTask::where('created_by', \Auth::user()->creatorId())->get();
            $arrayJson = [];
            foreach ($data as $val) {
                $end_date = date_create($val->end_date);
                date_add($end_date, date_interval_create_from_date_string("1 days"));
                $arrayJson[] = [
                    "id" => $val->id,
                    "title" => $val->name,
                    "start" => $val->start_date,
                    "end" => date_format($end_date, "Y-m-d H:i:s"),
                    "className" => 'event-primary',
                    "textColor" => '#51459d',
                    "allDay" => true,
                    'url' => route('task.calendar.show', $val->id),
                    'resize_url' => route('task.calendar.drag', $val->id),
                ];
            }
        }

        return $arrayJson;
    }
}
