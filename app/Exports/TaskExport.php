<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use App\Models\ProjectSubtask;
use App\Models\User;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TaskExport implements FromCollection, WithHeadings
{
    protected $tasks;
    protected $view;
    protected $task_stage_id;

    public function __construct(Collection $tasks, $view,$task_stage_id)
    {
        $this->tasks = $tasks;
        $this->view = $view;
        $this->view=$task_stage_id;
    }

    public function collection()
    {
        
        $exportData = [];
        $groupedTasks = [];

        // Group tasks by project_id
        foreach ($this->tasks as $task) {
            $projectId = $task->project_id;
            if (!isset($groupedTasks[$projectId])) {
                $groupedTasks[$projectId] = [
                    'project' => $task->project,
                    'tasks' => []
                ];
            }
            $groupedTasks[$projectId]['tasks'][] = $task;
        }

       if (!empty($groupedTasks))
       {
       
            $currentProject = null; // Initialize a variable to track the current project
       
            foreach ($groupedTasks as $group)
               
             {
                foreach ($group['tasks'] as $task)
                {
                
                    
                     if ($task->project->project_name !== $currentProject)
                     {
                         $project_name=$task->project->project_name;
                         $currentProject = $task->project->project_name;
                     }else{
                         $project_name='';
                     }
                    
                           $data='';                        
                     if (!empty($task->assign_to)) {
                         foreach (explode(',', $task->assign_to) as $key_user) {
                            
                             $getUsers = User::select('id','name')->where('id','=',$key_user)->first();
                         
                            $data .=$getUsers->name.',';
                           
                           
                         }
                         
                     } 
                     $exportData[] = [
                       
                        $project_name,
                        $task->task_seq,
                        $task->name,
                        $task->description,
                       $task->stage->name,
                        $task->progress,
                        $task->start_date,
                        $task->end_date,
                        $task->comment,
                        $data,
                    ];
                     $subtask=ProjectSubtask::where('project_id',$task->project_id)->where('stage_id',$task->stage_id)->where('task_id',$task->id)->orderBy('subtask_seq', 'asc')->get(); 
                                   
                    if (!$subtask->isEmpty())
                     {
                        foreach ($subtask as $subtask)
                        {
                           
                           $data1='';
                            if (!empty($subtask->assign_to)) {
                                foreach (explode(',', $subtask->assign_to) as $key_users1) {
                                    $getUserss = User::select('id','name')->where('id','=',$key_users1)->first();
                         
                            $data1 .=$getUserss->name.',';
                                }
                               
                            }
                       
                            
                            $exportData[] = [
                                '',
                                $task->task_seq.'.'.$subtask->subtask_seq,
                                $subtask->subtask_name,
                                $subtask->description,
                                $subtask->stage->name,
                                $subtask->progress,
                                $subtask->start_date,
                                $subtask->end_date,
                                $subtask->comment,
                                $data1,
                            ];
                        }
                     }
                }
             }
       }
       

        return collect($exportData);
    }

    public function headings(): array
    {
        return [
            "Project Name",
            "ID",
            "Task Name",
            "Description",
            "Status",
            "Progress",
            "Start Date",
            "End Date",
            "Comment",
            "Assigned To",
        ];
    }
    public function styles(Worksheet $sheet)
    {
        // Define styles for headings (e.g., bold)
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);
    }
}
