<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Utility;
use App\Models\Timesheet;
use App\Models\ProjectTask;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimesheetController extends Controller
{
    public function timesheetView(Request $request, $project_id)
    {
      
        $authuser = Auth::user();
        if(\Auth::user()->can('manage timesheet'))
        {
            $project_ids = $authuser->projects()->pluck('project_id')->toArray();

            if(in_array($project_id, $project_ids))
            {
                $project = Project::where('id', $project_id)->first();
                if($authuser->type == 'company' || $authuser->type=='Manager'){

                $timesheets  = Timesheet::select('timesheets.*','project_tasks.name','project_tasks.estimated_hrs')->join('projects', 'projects.id', '=', 'timesheets.project_id')->join('project_tasks','project_tasks.id','=','timesheets.task_id')->where('timesheets.project_id',$project_id)->get();
                }else{
                    $timesheets  = Timesheet::select('timesheets.*','project_tasks.name','project_tasks.estimated_hrs')->join('projects', 'projects.id', '=', 'timesheets.project_id')->join('project_tasks','project_tasks.id','=','timesheets.task_id')->where('timesheets.project_id',$project_id)->whereRaw("find_in_set('" . $authuser->id . "',assign_to)")->get();
             
                }
                return view('projects.timesheets.index', compact('project','timesheets'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function appendTimesheetTaskHTML(Request $request)
    {

        $project_id     = $request->has('project_id') ? $request->project_id : null;

        $task_id        = $request->has('task_id') ? $request->task_id : null;
        $selected_dates = $request->has('selected_dates') ? $request->selected_dates : null;

        $returnHTML = '';

        $project = Project::find($project_id);

        if($project)
        {
            $task = ProjectTask::find($task_id);

            if($task && $selected_dates)
            {
                $twoDates = explode(' - ', $selected_dates);

                $first_day   = $twoDates[0];
                $seventh_day = $twoDates[1];

                $period = CarbonPeriod::create($first_day, $seventh_day);

                $returnHTML .= '<tr><td class="task-name">' . $task->name . '</td>';

                foreach($period as $key => $dateobj)
                {
                    $returnHTML .= '<td>
 <input class="form-control border-dark wid-120 task-time day-time1 task-time" data-ajax-timesheet-popup="true" data-type="create" data-task-id="' . $task->id . '" data-date="' . $dateobj->format('Y-m-d') . '" data-url="' . route('timesheet.create', $project_id) . '" value="00:00">';


                }

                $returnHTML .= '<td>
<input class="form-control border-dark wid-120 task-time total-task-time"  type="text" value="00:00" disabled>';
            }
        }

        return response()->json(
            [
                'success' => true,
                'html' => $returnHTML,
            ]
        );
    }

    public function filterTimesheetTableView(Request $request)
    {
        $sectionTaskArray = [];
//        $authuser         = Auth::user();

        $project = Project::find($request->project_id);
        if(Auth::user() != null){
            $authuser         = Auth::user();
        }else{
            $authuser         = User::where('id',$project->created_by)->first();
        }

        $week             = $request->week;
        $project_id       = $request->project_id;
        $timesheet_type   = 'task';

        if($request->has('week') && $request->has('project_id'))
        {
          if($authuser->type == 'client'){

            $project_ids = Project::where('client_id',\Auth::user()->id)->pluck('id','id')->toArray();
          }else{

            $project_ids = $authuser->projects()->pluck('project_id','project_id')->toArray();
          }
            $timesheets  = Timesheet::select('timesheets.*')->join('projects', 'projects.id', '=', 'timesheets.project_id');

            if($timesheet_type == 'task')
            {
                $projects_timesheet = $timesheets->join('project_tasks', 'project_tasks.id', '=', 'timesheets.task_id');
            }
            if($project_id == '0')
            {
                $projects_timesheet = $timesheets->whereIn('projects.id', $project_ids);
            }
            else if(in_array($project_id, $project_ids))
            {
                $projects_timesheet = $timesheets->where('timesheets.project_id', $project_id);

            }

            $days               = Utility::getFirstSeventhWeekDay($week);
            $first_day          = $days['first_day'];
            $seventh_day        = $days['seventh_day'];
            $onewWeekDate       = $first_day->format('M d') . ' - ' . $seventh_day->format('M d, Y');
            $selectedDate       = $first_day->format('Y-m-d') . ' - ' . $seventh_day->format('Y-m-d');
            $projects_timesheet = $projects_timesheet->whereDate('date', '>=', $first_day->format('Y-m-d'))->whereDate('date', '<=', $seventh_day->format('Y-m-d'));

            if($project_id == '0')
            {
                $timesheets = $projects_timesheet->get()->groupBy(
                    [
                        'project_id',
                        'task_id',
                    ]
                )->toArray();
            }
            else if(in_array($project_id, $project_ids))
            {
                $timesheets = $projects_timesheet->get()->groupBy('task_id')->toArray();

            }

            $returnHTML = Project::getProjectAssignedTimesheetHTML($projects_timesheet, $timesheets, $days, $project_id);

            $totalrecords = count($timesheets);
            if($project_id != '0')
            {
                $task_ids = array_keys($timesheets);

                $project  = Project::find($project_id);

                $sections = ProjectTask::getAllSectionedTaskList($request, $project, [], $task_ids);

                foreach($sections as $key => $section)
                {
                    $taskArray                              = [];
                    $sectionTaskArray[$key]['section_id']   = $section['section_id'];
                    $sectionTaskArray[$key]['section_name'] = $section['section_name'];

                    foreach($section['sections'] as $taskkey => $task)
                    {
                        $taskArray[$taskkey]['task_id']   = $task['id'];
                        $taskArray[$taskkey]['task_name'] = $task['taskinfo']['task_name'];
                    }
                    $sectionTaskArray[$key]['tasks'] = $taskArray;
                }
            }

            return response()->json(
                [
                    'success' => true,
                    'totalrecords' => $totalrecords,
                    'selectedDate' => $selectedDate,
                    'sectiontasks' => $sectionTaskArray,
                    'onewWeekDate' => $onewWeekDate,
                    'html' => $returnHTML,
                ]
            );
        }
    }
public function timesheetCreate($project_id)
{
    if(\Auth::user()->can('create timesheet'))
    {
        $parseArray = [];

        $authuser      = Auth::user();
       
        $projects  = Project::find($project_id);
        if($authuser->type != 'company')
        {
            if(\Auth::user()->type == 'client'){
           
              $task = ProjectTask::where('project_id', '=', $project_id)->get();
            }else{
               
                $task = ProjectTask::where('project_id', '=', $project_id)->whereRaw("find_in_set('" . $authuser->id . "',assign_to)")->get();
           
            }
        }

       
        if($authuser->type == 'company' || $authuser->type == 'Manager')
        {
            $task = ProjectTask::where('project_id', '=', $project_id)->get();
        }
        //end
       
                return view('projects.timesheets.create',compact('projects','task'));
           
       
    }

    else
    {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}
    public function estimatehours(Request $request)
    {
        if(\Auth::user()->can('create timesheet'))
        {
            $task = ProjectTask::where('project_id',$request->project_id)->where('id',$request->task_id)->first();
            return response()->json(
                [
                    'success' => true,
                    'estimated_hrs' => $task->estimated_hrs
                ]
            );
        }
    }
    public function timesheetCreatebk(Request $request)
    {
        if(\Auth::user()->can('create timesheet'))
        {
            $parseArray = [];

            $authuser      = Auth::user();
            $project_id    = $request->has('project_id') ? $request->project_id : null;
            $task_id       = $request->has('task_id') ? $request->task_id : null;
            $selected_date = $request->has('date') ? $request->date : null;
            $user_id       = $request->has('date') ? $request->user_id : null;

            $created_by = $user_id != null ? $user_id : $authuser->id;

            $projects = $authuser->projects();

            if($project_id)
            {
                $project = $projects->where('projects.id', '=', $project_id)->pluck('projects.project_name', 'projects.id')->all();

                if(!empty($project) && count($project) > 0)
                {

                    $project_id   = key($project);
                    $project_name = $project[$project_id];

                    $task = ProjectTask::where(
                        [
                            'project_id' => $project_id,
                            'id' => $task_id,
                        ]
                    )->pluck('name', 'id')->all();

                    $task_id   = key($task);
                    $task_name = $task[$task_id];

                    $tasktime = Timesheet::where('task_id', $task_id)->where('created_by', $created_by)->pluck('time')->toArray();

                    $totaltasktime = Utility::calculateTimesheetHours($tasktime);

                    $totalhourstimes = explode(':', $totaltasktime);

                    $totaltaskhour   = $totalhourstimes[0];
                    $totaltaskminute = $totalhourstimes[1];

                    $parseArray = [
                        'project_id' => $project_id,
                        'project_name' => $project_name,
                        'task_id' => $task_id,
                        'task_name' => $task_name,
                        'date' => $selected_date,
                        'totaltaskhour' => $totaltaskhour,
                        'totaltaskminute' => $totaltaskminute,
                    ];

                    return view('projects.timesheets.create', compact('parseArray'));
                }
            }
            else
            {
                $projects = $projects->get();

                return view('projects.timesheets.create', compact('projects', 'project_id', 'selected_date'));
            }
        }

        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function timesheetStore(Request $request)
    {
        if(\Auth::user()->can('create timesheet'))
        {
            $authuser = Auth::user();
            $project  = Project::find($request->project_id);

            if($project)
            {

                $request->validate(
                    [
                        'project_id' => 'required',
                        'task_id' => 'required',
                        'actual_hours' => 'required',
                    ]
                );
                $tasktime_count = Timesheet::where('task_id', $request->task_id)->where('project_id', $request->project_id)->get()->count();
                if($tasktime_count>0)
                {
                    $messages='Task Already Added to this project Timesheet';
                    return redirect()->back()->with('error', $messages);
                }
                
              
                $timesheet              = new Timesheet();
                $timesheet->project_id  = $request->project_id;
                $timesheet->task_id     = $request->task_id;
               
                $timesheet->actual_hours=$request->actual_hours;
                $timesheet->description = $request->description;
                $timesheet->created_by  = $authuser->id;
                $timesheet->save();

                return redirect()->back()->with('success', __('Timesheet Created Successfully!'));
            }
        }

        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function timesheetEdit(Request $request, $project_id, $timesheet_id)
    {
        if(\Auth::user()->can('edit timesheet'))
        {
          
            $authuser = Auth::user();


            $projects = $authuser->projects();

          
            $timesheet  = Timesheet::select('timesheets.*','project_tasks.name','project_tasks.estimated_hrs','projects.project_name')->join('projects', 'projects.id', '=', 'timesheets.project_id')->join('project_tasks','project_tasks.id','=','timesheets.task_id')->where('timesheets.project_id',$project_id)->where('timesheets.id','=',$timesheet_id)->first();
              
            if($timesheet)
            {

              
                    return view('projects.timesheets.edit', compact('timesheet'));
                
            }
        }

        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function timesheetUpdate(Request $request, $timesheet_id)
    {
        if(\Auth::user()->can('edit timesheet'))
        {
            $project = Project::find($request->project_id);

            if($project)
            {

                $request->validate(
                    [
                        'project_id' => 'required',
                        'task_id' => 'required',
                        'actual_hours' => 'required',
                    ]
                );

              
                $timesheet              = Timesheet::find($timesheet_id);
               
                $timesheet->actual_hours        = $request->actual_hours;
                $timesheet->description = $request->description;
                $timesheet->save();

                return redirect()->back()->with('success', __('Timesheet Updated Successfully!'));
            }
        }

        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function timesheetDestroy($timesheet_id)
    {
        if(\Auth::user()->can('delete timesheet'))
        {
            $timesheet = Timesheet::find($timesheet_id);

            if($timesheet)
            {
                $timesheet->delete();
            }

            return redirect()->back()->with('success', __('Timesheet deleted Successfully!'));
        }

        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function timesheetList()
    {
            return view('projects.timesheet_list');
    }

    public function timesheetListGet(Request $request)
    {
        $authuser = Auth::user();
        $week     = $request->week;

        if($request->has('week') && $request->has('project_id'))
        {
            $project_id = $request->project_id;

            $project_ids        = $authuser->projects()->pluck('project_id')->toArray();
            $timesheets         = Timesheet::select('timesheets.*')->join('projects', 'projects.id', '=', 'timesheets.project_id');
            $projects_timesheet = $timesheets->join('project_tasks', 'project_tasks.id', '=', 'timesheets.task_id');

            if($project_id == '0')
            {
                $projects_timesheet = $timesheets->whereIn('projects.id', $project_ids);
            }
            else if(in_array($project_id, $project_ids))
            {
                $projects_timesheet = $timesheets->where('timesheets.project_id', $project_id);
            }

            $days        = Utility::getFirstSeventhWeekDay($week);
            $first_day   = $days['first_day'];
            $seventh_day = $days['seventh_day'];

            $onewWeekDate = $first_day->format('M d') . ' - ' . $seventh_day->format('M d, Y');
            $selectedDate = $first_day->format('Y-m-d') . ' - ' . $seventh_day->format('Y-m-d');

            $projects_timesheet = $projects_timesheet->whereDate('date', '>=', $first_day->format('Y-m-d'))->whereDate('date', '<=', $seventh_day->format('Y-m-d'));

            if($project_id == '0')
            {
                $timesheets = $projects_timesheet->get()->groupBy(
                    [
                        'project_id',
                        'task_id',
                    ]
                )->toArray();
            }
            else if(in_array($project_id, $project_ids))
            {
                $timesheets = $projects_timesheet->get()->groupBy('task_id')->toArray();
            }

            $returnHTML = Project::getProjectAssignedTimesheetHTML($projects_timesheet, $timesheets, $days, $project_id);

            $totalrecords = count($timesheets);

            if($project_id != '0')
            {
                $task_ids = array_keys($timesheets);
                $project  = Project::find($project_id);
                $sections = ProjectTask::getAllSectionedTaskList($request, $project, [], $task_ids);

                foreach($sections as $key => $section)
                {
                    $taskArray = [];

                    $sectionTaskArray[$key]['section_id']   = $section['section_id'];
                    $sectionTaskArray[$key]['section_name'] = $section['section_name'];

                    foreach($section['sections'] as $taskkey => $task)
                    {
                        $taskArray[$taskkey]['task_id']   = $task['id'];
                        $taskArray[$taskkey]['task_name'] = $task['taskinfo']['task_name'];
                    }
                    $sectionTaskArray[$key]['tasks'] = $taskArray;
                }
            }

            return response()->json(
                [
                    'success' => true,
                    'totalrecords' => $totalrecords,
                    'selectedDate' => $selectedDate,
                    'sectiontasks' => $sectionTaskArray,
                    'onewWeekDate' => $onewWeekDate,
                    'html' => $returnHTML,
                ]
            );
        }
    }


}
