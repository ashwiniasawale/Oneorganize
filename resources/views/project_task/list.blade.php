<style>
    .borderless-input {
        border: none;
        outline: none; /* Removes the default focus outline */
    }
    .highlight {
        background-color: #b7b4d1 !important;
    }
    .hiddenRow {
    padding: 0 !important;
}
.task-main{
        background: #cfe3f1;
    }
.toggle-button {
    cursor: pointer;
}

.toggle-icon {
    width: 20px;
    height: 20px;
    font-size: 20px;
    line-height: 1;
    color: white;
    display: inline-block;
    background-color: #c00009;
    border-radius: 50%;
    text-align: center;
    font-weight: bold;
}

/* Style for button in expanded state */
.toggle-button.expanded .toggle-icon {
    color: #fff; /* Change color when button is expanded */
    background-color: #c00009; /* Change background color when button is expanded */
    border-radius: 50%;
    padding: 5px 8px;
}

.scrol{
    height:800px !important;
    overflow-y: auto;
}

.scrol::-webkit-scrollbar 
{
        width: 8px; /* Thin scrollbar width for Chrome, Safari, Edge */
}

.scrol::-webkit-scrollbar-track 
{
        background-color: #ffffff; /* Scrollbar track color */
}

.scrol::-webkit-scrollbar-thumb 
{
        background-color: #dddddd; /* Scrollbar thumb color */
        border-radius: 6px; /* Rounded corners for scrollbar thumb */
}
.text-container 
{
            min-width: 250px;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important; /* Also include overflow-wrap for better compatibility */
            white-space: normal !important;
 }
.selecttt
{
        display:inline-block !important;
        width:auto !important;
}
 .width-data
 {
        width:90px;
  }
</style>
@php
   
    use App\Models\ProjectSubtask;
   @endphp
<div class="col-md-12">
    <div class="card">
        <div class="col-12">
            <div class="card-body table-border-style">
                <div class="table-responsive scrol">
                    <table class="table table-bordered table-dark-border" id="att_table">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Project Name') }}</th>
                                <th scope="col">{{__('ID')}}</th>
                               
                                <th scope="col">{{ __('Task Name') }}</th>
                                <th scope="col">{{__('Description')}}</th>
                                <th scope="col">{{ __('Status') }}</th>
                               
                                <th scope="col">{{__('Progress')}} %</th>
                                <th scope="col">{{__('Start Date')}}</th>
                                <th scope="col">{{ __('End Date') }}</th>
                                <th scope="col">{{__('Comment')}}</th>
                                <th scope="col">{{ __('Assigned To') }}</th>
                               
                                
                            </tr>
                        </thead>
                            <?php
                                                    $groupedTasks = [];

                            // Group tasks by project_id
                            foreach ($tasks as $task) {
                                $projectId = $task->project_id;
                                if (!isset($groupedTasks[$projectId])) {
                                    $groupedTasks[$projectId] = [
                                        'project' => $task->project,
                                        'tasks' => []
                                    ];
                                }
                                $groupedTasks[$projectId]['tasks'][] = $task;
                            }
                            ?>

                            <tbody>
                            @if (!empty($groupedTasks))
                            @php
                                $currentProject = null; // Initialize a variable to track the current project
                            @endphp
                                @foreach ($groupedTasks as $group)
                                   
                                    {{-- Display tasks for the current project --}}
                                    @foreach ($group['tasks'] as $task)
                                    <tr class="task-row task-main" data-task-id="{{ $task->id }}" id="high{{$task->id}}" onclick="highlight({{$task->project_id}},{{$task->id}},{{$task->task_seq}});">
                                        @php
                                            $checkProject = \Auth::user()->checkProject($task->project_id);
                                        @endphp
                                        @if ($task->project->project_name !== $currentProject)
                                           
                                                <td ><strong>{{ $task->project->project_name }}</strong></td>
                                            
                                            @php
                                                $currentProject = $task->project->project_name;
                                            @endphp
                                        @else
                                        <td ></td>
                                         @endif   
                                        <td><strong>{{$task->task_seq}}</strong></td>
                                        <!-- <td>
                                            <span class="d-flex text-sm text-muted justify-content-between">
                                                <p class="m-0">{{ $task->project->project_name }}</p>
                                                
                                            </span>
                                        </td> -->
                                        <td>{{ $task->name }}
                                        </td>
                                        <td>{{$task->description}}</td>
                                        <td>{{ $task->stage->name }}</td>
                                        
                                        <td>{{$task->progress}} %</td>
                                        <td>{{Utility::getDateFormated($task->start_date)}}</td>
                                        <td class="{{ strtotime($task->end_date) < time() ? 'text-danger' : '' }}">
                                            {{ Utility::getDateFormated($task->end_date) }}</td>
                                            <td class="text-container">{{$task->comment}}</td>
                                        <td>

                                            <div class="avatar-group">
                                                
                                            <?php
                                                    $users = [];
                                                   
                                                    if (!empty($task->assign_to)) {
                                                        foreach (explode(',', $task->assign_to) as $key_user) {
                                                           
                                                            $getUsers = App\Models\User::select('id','name')->where('id','=',$key_user)->first();
                                                          ?>
                                                           <small><strong>{{ $getUsers->name }}</strong></small><br><br>
                                                           <?php
                                                          
                                                        }
                                                        
                                                    } 
                                                    ?>

                                            </div> 
                                        </td>
                                        <!-- <td>
                                        <div class="action-btn bg-info ms-2">
                                                <a href="#" data-size="lg" class="mx-3 btn btn-sm  align-items-center" data-url="{{ route('task.req',[$task->project_id,$task->id]) }}" data-ajax-popup="true" data-size="xl" data-bs-toggle="tooltip" title="{{__('View')}}" data-title="{{__('View Requirement')}}">
                                                    <i class="ti ti-eye text-white"></i>
                                                </a>
                                            </div>
                                        </td> -->
                                       
                                       
                                    </tr>
                                    <?php  $subtask=ProjectSubtask::where('project_id',$task->project_id)->where('stage_id',$task->stage_id)->where('task_id',$task->id)->orderBy('subtask_seq', 'asc')->get(); 
                                   
                                   if (!$subtask->isEmpty())
                                    {
                                    
                                   ?>
                                 
                                                           @forelse ($subtask as $subtask)
                                                               <tr class="task-row" data-task-id="{{$task->id}}{{$subtask->id}}" id="high{{$task->id}}{{$subtask->id}}" onclick="highlight({{$task->project_id}},{{$task->id}}{{$subtask->id}},{{$task->task_seq}});">
                                                               <td></td>
                                                               <td>{{$task->task_seq}}.{{$subtask->subtask_seq}}</td>
                                                                   <td> {{$subtask->subtask_name}}</td>
                                                                  <td>{{$subtask->description}}</td>
                                                                   <td>{{ $subtask->stage->name }}</td>
                                                                   
                                                                  <td>{{$subtask->progress}} %
                                                                   </td>
                                                                   <td>{{Utility::getDateFormated($subtask->start_date)}}</td>
                                                                   <td>{{Utility::getDateFormated($subtask->end_date)}}</td>
                                                                   <td class="text-container">{{$subtask->comment}}</td>
                                                                  <td>

                                                                   <div class="avatar-group">
                                                                   @php
                                                                           $userss = [];
                                                                           $getUserss = App\Models\ProjectSubtask::getusers();
                                                                         
                                                                           if (!empty($subtask->assign_to)) {
                                                                               foreach (explode(',', $subtask->assign_to) as $key_users) {
                                                                                   $users['name'] = $getUserss[$key_users]['name'];
                                                                                   $users['avatar'] = $getUserss[$key_users]['avatar'];

                                                                                   $userss[] = $users;
                                                                               }
                                                                               $taskusers = $userss;
                                                                           } else {
                                                                               $taskusers = [];
                                                                           }
                                                                       @endphp

                                                                       @if (count($taskusers) > 0)
                                                                      
                                                                           @foreach ($taskusers as $key => $users)
                                                                             
                                                                               <small><strong>{{ $users['name'] }}</strong></small><br><br>
                                                                           @endforeach
                                                                       @else
                                                                           {{ __('-') }}
                                                                       @endif
                                                                   </div>
                                                                   {{-- <div class="avatar-group">
                                                                       @php
                                                                           $userss = $subtask->users();
                                                                       @endphp
                                                                       @if ($userss->count() > 0)
                                                                           @if ($userss)
                                                                               @foreach ($userss as $key => $users)
                                                                                   @if ($key < 3)
                                                                                     
                                                                                       <small><strong>{{ $users['name'] }}</strong></small><br><br>
                                                                                   @else
                                                                                   @break
                                                                               @endif
                                                                           @endforeach
                                                                       @endif
                                                                       @if (count($users) > 3)
                                                                          
                                                                           <small><strong>{{ $users['name'] }}</strong></small><br><br>
                                                                       @endif
                                                                       @else
                                                                           {{ __('-') }}
                                                                       @endif
                                                                   </div> --}}
                                                                   </td>
                                                                   
                                                               </tr>
                                                           @empty
                                                               <tr>
                                                                   <td colspan="3">No subtasks found</td>
                                                               </tr>
                                                           @endforelse
                                                       
                                  
                                       <?php
                                   } ?>
                                    @endforeach
                                @endforeach
                            @endif
                            </tbody>
                       
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
   
   function highlight(project_id,task_id,task_seq)
          {
            $('tr').removeClass('highlight');
                // Highlight the clicked row
                $('#high'+task_id).addClass('highlight');
               
          }

    function display_subtask(task_id)
        {
            const subtaskRow = document.getElementById('subtask-row-' + task_id);
            subtaskRow.style.display = subtaskRow.style.display === 'none' ? 'table-row' : 'none';
            var $icon = $('#toggle-icon'+task_id);
            if ( subtaskRow.style.display==='none') {
            
            $('#toggle-icon'+task_id).html('+');
            } else {
            
            $('#toggle-icon'+task_id).html('-');
            }
        }
</script>
