@extends('layouts.admin')

@section('page-title') {{__('Gantt Chart')}} @endsection
<style>
    .borderless-input {
        border: none;
        outline: none; /* Removes the default focus outline */
        background: transparent;
    }
    .highlight {
        background-color: #b7b4d1 !important;
    }
    .task-main{
        background: #cfe3f1 !important;
    }
    .hiddenRow {
    padding: 0 !important;
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
    background-color:#c00009;
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
/* .card:not(.table-card) .dataTable-bottom, .card:not(.table-card) .dataTable-top {
    padding: 0px !important;
} */

.loader-wrapper {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(255, 255, 255, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  display:none;
}

.loader {
  border: 4px solid #f3f3f3;
  border-top: 4px solid #3498db;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
.btn-rad{
    border-radius: 50% !important;
}
.scrol{
    height:800px !important;
    overflow-y: auto !important;
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
.act-dur
{
        background-color: #bf964f !important;
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
   <link
    href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css"
    rel="stylesheet"
/>
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('projects.index')}}">{{__('Project')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('projects.show',$project->id)}}">    {{ucwords($project->project_name)}}</a></li>
    <li class="breadcrumb-item">{{__('WBS- Gantt Chart')}}</li>
@endsection

@section('action-btn')
    <div class="float-end">
    <select  id="task_stage_id" name="task_stage_id" onchange="get_emp_task();" class="form-select selecttt mx-1" style="padding-right:2.5rem;">
        <option value="0">--Select Status--</option>
        <?php
          foreach($stages as $stage)
            { 
            ?>
           <option <?php if($task_stage_id==$stage->id){ echo 'selected=selected'; } ?> value="{{$stage->id}}">{{$stage->name}}</option>
        <?php } ?>
    </select>
    <select name="task_user_id" id="task_user_id" onchange="get_emp_task();" class="form-select selecttt mx-1" style="padding-right: 2.5rem;" >
        <option value="0">--Select User--</option>
        @foreach($project->users as $user)
        <?php if($user->id==\Auth::user()->id  && \Auth::user()->type =='Employee')
        { ?>
       <option <?php if($task_user_id==$user->id){ echo 'selected=selected'; } ?> value="<?php echo $user->id; ?>"><?php echo $user->name; ?></option>
      
      <?php }else if(\Auth::user()->type !='Employee'){ ?>
        <option <?php if($task_user_id==$user->id){ echo 'selected=selected'; } ?> value="<?php echo $user->id; ?>"><?php echo $user->name; ?></option>
      <?php } ?>
        @endforeach
    </select>
    
    @can('create project task')
          <a href="#" data-size="lg" data-url="{{ route('projects.tasks.create',[$project->id,$stages[0]->id,'end','0']) }}" data-ajax-popup="true"   title="Create Task" data-title="{{__('Add Task in ').$stages[0]->name}}" class="btn btn-sm btn-primary p-2">
                                            <i class="ti ti-plus"></i></a>
      @endif
                                    <a href="{{route('projects.show',$project->id)}}" class="btn btn-primary btn-sm p-2" data-ajax-popup="true" title="Back" data-title="Back" >
                                            <span class="btn-inner--icon"><i class="ti ti-arrow-left"></i></span>
                                        </a>
                                       
                             
    </div>

@endsection


@section('content')
<div class="loader-wrapper" id="loader"  >
  <div class="loader"></div>
</div>
<br>
<div class="col-md-12">
    <div class="card ">
        <div class="col-12">
           
       

            <div class="card-body" >
            <input type="hidden" id="p_start_date" value="<?php echo $project->start_date; ?>">
            <input type="hidden" id="p_end_date" value="<?php echo $project->end_date; ?>">
                <div class="table-responsive scrol horizontal-scroll-cards" >
              
                    <table class="table table-bordered table-dark-border " id="att_table">
                        <thead>
                            <tr>
                              
                               
                                <th></th>
                                <th scope="col">ID</th>
                                <th scope="col">{{ __('Task Name') }}</th>
                                <th  scope="col">{{ __('Discription') }}</th>
                                <th scope="col">{{ __('Status') }}</th>
                                
                                <th scope="col">{{__('Progress')}} (%)</th>
                                <th scope="col">{{__('Start Date')}}</th>
                                <th scope="col">{{ __('End Date') }}</th>
                                <th scope="col">{{ __('Predecessor') }}</th>
                                <th scope="col">{{ __('Assigned To') }}</th>
                                <th scope="col">{{ __('Comment') }}</th>
                                <th scope="col">{{ __('Remark') }}</th>
                                <th scope="col">{{__('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody class="list" >

                        @if ($task_list)
                                @foreach ($task_list as $task)
                               
                                    <tr  class="task-row task-main" data-task-id="{{ $task->id }}" id="high{{$task->id}}" onclick="highlight({{$project->id}},{{$task->id}},{{$task->task_seq}});">
                                  
                                    
                                    <td> 
                                         @can('create project task')
                                       
                                        <a id="moveUp{{$task->id}}" title="UP" class="btn btn-sm text-white btn-success mr-2 btn-rad" onclick="task_seq_change({{$project->id}},{{$task->id}},{{$task->task_seq}},'up');"><i class="ri-arrow-up-line shadow"></i></a>
                                        <a id="moveDown{{$task->id}}" title="DOWN" class="btn btn-sm text-white btn-danger mr-2 btn-rad" onclick="task_seq_change({{$project->id}},{{$task->id}},{{$task->task_seq}},'down');"><i class="ri-arrow-down-line shadow"></i></a>
                                       
                                        <a href="#" class="btn btn-sm text-white btn-primary mr-2 btn-rad" data-size="lg" data-url="{{ route('projects.tasks.create',[$project->id,$stages[0]->id,'below',$task->task_seq]) }}"  data-ajax-popup="true" title="Insert Below" data-title="{{__('Add Task in ').$stages[0]->name}}" class="btn btn-sm btn-primary p-2">
                                        <i class="ri-menu-add-fill shadow"></i></a>
                                        
                                                               <a href="#" data-size="lg" class=" btn btn-sm text-white btn-dark mr-2  align-items-center btn-rad" data-url="{{ route('projects.tasks.createsubtask',[$project->id,$task->id]) }}" data-ajax-popup="true" data-size="xl" title="{{__('Create Subtask')}}" data-title="{{__('Create Subtask ')}}">
                                                                   <i class="ri-git-merge-line text-white shadow"></i>
                                                               </a>
                                                           
                                          @endcan
                                      
                                    </td>
                                    <td><strong>{{$task->task_seq}}</strong></td>
                                    <td class="text-container"><input  type="hidden" class="borderless-input" id="task_name{{$task->id}}"  value="{{$task->name}}">{{$task->name}}</td>
                                    <td class="text-container">{{$task->description}}</td>
                                    <td>
                                        <select  id="stage_id{{$task->id}}"  class="borderless-input" onChange="update_task({{$task->id}},{{$task->project_id}},'');">
                                            <?php
                                          
                                            foreach($stages as $stage)
                                            { 
                                                if($task->stage_id==$stage->id)
                                                {
                                                    $selected='selected=selected';
                                                }else{
                                                    $selected='';
                                                }
                                                ?>
                                            <option {{$selected}} value="{{$stage->id}}">{{$stage->name}}</option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                  
                                    <td><input class="borderless-input width-data" id="progress{{$task->id}}" onblur="update_task({{$task->id}},{{$task->project_id}},'');" value="{{$task->progress}}"><span style="display:none">{{$task->progress}}</span>
                                        <br>
                                        <span class="text-danger" id="progress_error{{$task->id}}"></span>
                                    </td>
                                    <td>
                                            
                                        <input type="date" class="borderless-input start_date" id="start_date{{$task->id}}" onChange="update_task({{$task->id}},{{$task->project_id}},'');" value="{{$task->start_date}}"><span style="display:none">{{$task->start_date}}</span>
                                    </td>
                                      
                                    <td>
                                        <input type="date" class="borderless-input end_date" id="end_date{{$task->id}}" onChange="update_task({{ $task->id }},{{$task->project_id}},'');" value="{{$task->end_date}}"><span style="display:none">{{$task->end_date}}</span>
                                        <br>
                                        <span class="text-danger" id="end_date_error{{$task->id}}"></span>
                                    </td>
                                    <td><input  class="borderless-input width-data" id="task_predece{{$task->id}}" onblur="update_task({{ $task->id }},{{$task->project_id}},'');" value="{{$task->predece}}"><span style="display:none">{{$task->predece}}</span></td>
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
                                        <td class="text-container">{{$task->comment}}</td>
                                        <td class="text-container">{{$task->remark}}</td>
                                        <td class="Action" width="10%">
                                       
                                                    @can('edit project task')
                                                           
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" data-size="lg" class="mx-3 btn btn-sm  align-items-center" data-url="{{ route('projects.tasks.edit',[$project->id,$task->id]) }}" data-ajax-popup="true" data-size="xl" title="{{__('Edit')}}" data-title="{{__('Edit ').$task->name}}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                    @endcan
                                                       
                                                    @can('delete project task')
                                                            <div class="action-btn bg-danger ms-2">
                                                                 <a href="#" onclick="del_task({{$project->id}},{{$task->id}},{{$task->task_seq}});" id="del_task{{$task->id}}" class="delete-task-btn mx-3 btn btn-sm  align-items-center" title="{{__('Delete')}}"><i class="ti ti-trash text-white"></i></a>
                                                             
                                                            </div>
                                                        @endcan
                                      
                                        </td>
                                    </tr>
                                    <?php $subtask=ProjectSubtask::where('project_id',$task->project_id)->where('task_id',$task->id)->orderBy('subtask_seq', 'asc')->get(); 
                                   
                                   if (!$subtask->isEmpty())
                                    {
                                    
                                   ?>
                                     
                                                           @forelse ($subtask as $subtask)
                                                               <tr class="task-row" data-task-id="{{$task->id}}{{$subtask->id}}" id="high{{$task->id}}{{$subtask->id}}" onclick="highlight({{$project->id}},{{$task->id}}{{$subtask->id}},{{$task->task_seq}});">
                                                               <td></td>
                                                               <td>{{$task->task_seq}}.{{$subtask->subtask_seq}}</td>
                                                               <td class="text-container">
                                                                 <input class="borderless-input" type="hidden" id="subtask_name{{$task->id}}{{$subtask->id}}" value="{{$subtask->subtask_name}}">{{$subtask->subtask_name}}</td>
                                                                <td class="text-container">{{$subtask->description}}</td>
                                                               <td>
                                                                  <select  id="subtask_stage_id{{$task->id}}{{$subtask->id}}"  class="borderless-input" onChange="update_task({{$task->id}},{{$task->project_id}},{{$subtask->id}});">
                                                                    <?php
                                         
                                                                       foreach($stages as $stage)
                                                                       { 
                                                                           if($subtask->stage_id==$stage->id)
                                                                           {
                                                                               $selected='selected=selected';
                                                                           }else{
                                                                               $selected='';
                                                                           }
                                                                           ?>
                                                                       <option {{$selected}} value="{{$stage->id}}">{{$stage->name}}</option>
                                                                       <?php } ?>      
                                       
                                                                   </select>
                                                                  </td>
                                                                
                                                                  <td><input class="borderless-input width-data" id="subtask_progress{{$task->id}}{{$subtask->id}}" onblur="update_task({{$task->id}},{{$task->project_id}},{{$subtask->id}});" value="{{$subtask->progress}}"><span style="display:none">{{$subtask->progress}}</span>
                                                                        <br>
                                                                        <span class="text-danger" id="subtask_progress_error{{$task->id}}{{$subtask->id}}"></span>
                                                                    </td>
                                                                     <td>
                                                                   <input type="date" class="borderless-input" id="subtask_start_date{{$task->id}}{{$subtask->id}}" onChange="update_task({{$task->id}},{{$task->project_id}},{{$subtask->id}});" onclick="check_date({{$task->id}},{{$subtask->id}});" value="{{$subtask->start_date}}"><span style="display:none">{{$subtask->start_date}}</span>
                                                                  </td>
                                                                   <td>  <input type="date" class="borderless-input" id="subtask_end_date{{$task->id}}{{$subtask->id}}" onChange="update_task({{$task->id}},{{$task->project_id}},{{$subtask->id}});" onclick="check_date({{$task->id}},{{$subtask->id}});" value="{{$subtask->end_date}}"><span style="display:none">{{$subtask->end_date}}</span>
                                                                   <br>
                                                                   <span class="text-danger" id="subtask_end_date_error{{$task->id}}{{$subtask->id}}"></span>
                                                                   </td>
                                                                   <td><input  class="borderless-input width-data" id="subtask_predece{{$task->id}}{{$subtask->id}}" onblur="update_task({{ $task->id }},{{$task->project_id}},{{$subtask->id}});" value="{{$subtask->predece}}"><span style="display:none">{{$subtask->predece}}</span></td>
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
                                                                                       <!-- <a href="#"
                                                                                           class="avatar rounded-circle avatar-sm">
                                                                                           <img data-original-title="{{ !empty($users) ? $users->name : '' }}"
                                                                                               @if ($users->avatar) src="{{ asset('/storage/uploads/avatar/' . $users->avatar) }}" @else src="{{ asset('/storage/uploads/avatar/avatar.png') }}" @endif
                                                                                               title="{{ $users->name }}" class="hweb">
                                                                                       </a> -->
                                                                                       <small><strong>{{ $users['name'] }}</strong></small><br><br>
                                                                                   @else
                                                                                   @break
                                                                               @endif
                                                                           @endforeach
                                                                       @endif
                                                                       @if (count($users) > 3)
                                                                           <!-- <a href="#" class="avatar rounded-circle avatar-sm">
                                                                               <img data-original-title="{{ !empty($users) ? $users->name : '' }}"
                                                                                   @if ($users->avatar) src="{{ asset('/storage/uploads/avatar/' . $users->avatar) }}" @else src="{{ asset('/storage/uploads/avatar/avatar.png') }}" @endif
                                                                                   class="hweb">
                                                                           </a> -->
                                                                           <small><strong>{{ $users['name'] }}</strong></small><br><br>
                                                                       @endif
                                                                       @else
                                                                           {{ __('-') }}
                                                                       @endif
                                                                   </div> --}}
                                                                   </td>
                                                                   <td class="text-container">{{$subtask->comment}}</td>
                                                                   <td class="text-container">{{$subtask->remark}}</td>
                                                                  <td>
                                                                 
                                                                  @can('edit project task')
                                                                       <div class="action-btn bg-info ms-2">
                                                                           <a href="#" data-size="lg" class="mx-3 btn btn-sm  align-items-center" data-url="{{ route('projects.tasks.editsubtask',[$project->id,$task->id,$subtask->id]) }}" data-ajax-popup="true" data-size="xl" title="{{__('Edit Subtask')}}" data-title="{{__('Edit Subtask')}}">
                                                                               <i class="ti ti-pencil text-white"></i>
                                                                           </a>
                                                                       </div>
                                                                   @endcan
                                                                   
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
                            @else
                                <tr>
                                    <th scope="col" colspan="7">
                                        <h6 class="text-center">{{ __('No tasks found') }}</h6>
                                    </th>
                                </tr>
                            @endif
                          
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
<div class="" style="text-align:end;">
   
    <div class="btn-group mr-2" id="change_view" role="group">
        <a href="{{route('projects.gantt',[$project->id,'Quarter Day',$task_user_id,$task_stage_id])}}" class="btn btn-primary @if($duration == 'Quarter Day')act-dur @endif" data-value="Quarter Day">{{__('Quarter Day')}}</a>
        <a href="{{route('projects.gantt',[$project->id,'Half Day',$task_user_id,$task_stage_id])}}" class="btn btn-primary @if($duration == 'Half Day')act-dur @endif" data-value="Half Day">{{__('Half Day')}}</a>
        <a href="{{route('projects.gantt',[$project->id,'Day',$task_user_id,$task_stage_id])}}" class="btn btn-primary @if($duration == 'Day')act-dur @endif" data-value="Day">{{__('Day')}}</a>
        <a href="{{route('projects.gantt',[$project->id,'Week',$task_user_id,$task_stage_id])}}" class="btn btn-primary @if($duration == 'Week')act-dur @endif" data-value="Week">{{__('Week')}}</a>
        <a href="{{route('projects.gantt',[$project->id,'Month',$task_user_id,$task_stage_id])}}" class="btn btn-primary @if($duration == 'Month')act-dur @endif" data-value="Month">{{__('Month')}}</a>
    </div>
    @can('manage project')
        <a href="{{ route('projects.show',$project->id) }}" class="btn btn-primary " data-bs-toggle="tooltip" title="{{__('Back')}}">
            <span class="btn-inner--icon"><i class="ti ti-arrow-left"></i></span>
        </a>
    @endcan
</div>
</div><br>

    <div class="row">
        <div class="col-12">
            <div class="card card-stats border-0 scrol">
                
                @if($project)
                    <div class="gantt-target "></div>
                @else
                    <h1>404</h1>
                    <div class="page-description">
                        {{ __('Page Not Found') }}
                    </div>
                    <div class="page-search">
                        <p class="text-muted mt-3">{{ __("It's looking like you may have taken a wrong turn. Don't worry... it happens to the best of us. Here's a little tip that might help you get back on track.")}}</p>
                        <div class="mt-3">
                            <a class="btn-return-home badge-blue" href="{{route('dashboard')}}"><i class="ti ti-reply"></i> {{ __('Return Home')}}</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->

@if($project)
    @push('css-page')
        <link rel="stylesheet" href="{{asset('css/frappe-gantt.css')}}" />
    @endpush
    <script>
        
    </script>
    @push('script-page')
  
        @php
            $currantLang = basename(App::getLocale());
        @endphp
     
<script>
    function get_emp_task() {
            var task_user_id=$('#task_user_id').val();
            var task_stage_id=$('#task_stage_id').val();
           
            // Construct the URL based on the selected user ID
            var url = "{{route('projects.gantt',[$project->id,$duration])}}"; // Replace '/your-url/' with your actual URL
           
                url += '/'+task_user_id+'/'+task_stage_id;
           
           
            // Redirect to the constructed URL
            window.location.href = url;
       
    }
</script>  
  <script>
    
function del_task(project_id,task_id,task_seq)
{
    if (confirm("Are you sure you want to delete this task?")) {
        
        $("#loader").css("display", "flex");

            $.ajax({
                type: 'POST', // Or 'DELETE' if your route expects DELETE requests
                url: "{{route('projects.tasks.destroy')}}",
               
                data: {
                                task_id: task_id,
                                project_id:project_id,
                                task_seq:task_seq,
                                _token: '{{ csrf_token() }}' // Add CSRF token if you're not using Laravel Mix or Blade
                            },
                success: function(response) {
                    $("#loader").css("display", "none");

                    if(response.success)
                  {
                    show_toastr('Success', response.success, 'success');
                    $('#commonModal').modal('hide');
                    update_task_seq(response.tmp_task);
                  }else{
                    show_toastr('Error', response.error, 'error');
                  }
                  
                   
                },
                error: function(error) {
                    // Handle error, show message or retry
                    $("#loader").css("display", "none");

                    console.error('Error deleting task');
                }
            });
        }
}
   

    function update_task_seq(tmp_task)
        {

                $.ajax({
                        url: '{{route('task.seq.update')}}',
                        type: 'post',
                        dataType: 'html',
                        data: {"_token": "{{ csrf_token() }}",tmp_task:tmp_task},
                        success: function (data) {
                        load_data();
                        get_updated_gantt();
                        },
                    });
        }
       
        function task_seq_change(project_id,task_id,task_seq,position)
        {
           
            $("#loader").css("display", "flex");
           
            $.ajax({
                url:'{{route('task.seq.change')}}',
                type: 'post',
                data: {"_token": "{{ csrf_token() }}",
                       project_id:project_id,
                       task_id:task_id,
                       task_seq:task_seq,
                       position:position,
                      },
                     success: function (data) {
                      
                        $("#att_table").load(" #att_table");
                        $("#att_table").load(" #att_table", function(response, status, xhr) {
                            
                                $("#loader").css("display", "none");
                                $('#high'+task_id).addClass('highlight');
                 });
                      get_updated_gantt();
                     
                     
                     },
                     

            });
           
        }
       
</script>
        <script>   
// Reinitialize the DataTable with new options or the same options
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
        function load_data()
        {
            $("#att_table").load(" #att_table");
        }
        </script>
        <script>
            function check_date(task_id,subtask_id)
            {
                var start_date=$("#start_date"+task_id).val();
                var end_date=$("#end_date"+task_id).val();
                $("#subtask_start_date"+task_id+subtask_id).attr("min", start_date);
                $("#subtask_start_date"+task_id+subtask_id).attr("max", end_date);

                var subtask_start_date=$("#subtask_start_date"+task_id+subtask_id).val();
                $("#subtask_end_date"+task_id+subtask_id).attr("min", subtask_start_date);
                $("#subtask_end_date"+task_id+subtask_id).attr("max", end_date);
            }
            var p_start_date=document.getElementById('p_start_date').value;
            var p_end_date=document.getElementById('p_end_date').value;
            
            $(".start_date").attr("min", p_start_date);
            $(".start_date").attr("max", p_end_date);

            $(".end_date").attr("min", p_start_date);
            $(".end_date").attr("max", p_end_date);
            
          function highlight(project_id,task_id,task_seq)
          {
            
            $('tr').removeClass('highlight');
                // Highlight the clicked row
                $('#high'+task_id).addClass('highlight');
               
          }
            const month_names = {
                "{{$currantLang}}": [
                    '{{__('January')}}',
                    '{{__('February')}}',
                    '{{__('March')}}',
                    '{{__('April')}}',
                    '{{__('May')}}',
                    '{{__('June')}}',
                    '{{__('July')}}',
                    '{{__('August')}}',
                    '{{__('September')}}',
                    '{{__('October')}}',
                    '{{__('November')}}',
                    '{{__('December')}}'
                ],
                "en": [
                    'January',
                    'February',
                    'March',
                    'April',
                    'May',
                    'June',
                    'July',
                    'August',
                    'September',
                    'October',
                    'November',
                    'December'
                ],
            };
            console.log(month_names);
        </script>
        <script src="{{asset('js/frappe-gantt.js')}}"></script>
        <script>
  
            function update_task(task_id,project_id,subtask_id)
            {
             
                var task_name=$('#task_name'+task_id).val();
                var end_date=$('#end_date'+task_id).val();
                var start_date=$('#start_date'+task_id).val();
                var stage_id=$('#stage_id'+task_id).val();
                var progress=$('#progress'+task_id).val();
                var task_user_id='{{$task_user_id}}';
               
                if(subtask_id)
                {
                    var task_predece=$('#subtask_predece'+task_id+subtask_id).val();

                }else{
                    var task_predece=$('#task_predece'+task_id).val();
                }
               
         
                var subtask_name=$('#subtask_name'+task_id+subtask_id).val();
                var subtask_start_date=$('#subtask_start_date'+task_id+subtask_id).val();
                var subtask_end_date=$('#subtask_end_date'+task_id+subtask_id).val();
                var subtask_stage_id=$('#subtask_stage_id'+task_id+subtask_id).val();
                var subtask_progress=$('#subtask_progress'+task_id+subtask_id).val();
                

             if(progress>100 || progress<0)
             {
              
                document.getElementById('progress_error'+task_id).innerHTML='Progress should be grater than 0 and less than 100';
                setTimeout(function()
                {
                 document.getElementById('progress_error'+task_id).style.display='none';   
                },3000);
                return false;
             }
             if(subtask_progress>100 || subtask_progress<0)
             {
              
                document.getElementById('subtask_progress_error'+task_id+subtask_id).innerHTML='Progress should be grater than 0 and less than 100';
                setTimeout(function()
                {
                 document.getElementById('subtask_progress_error'+task_id+subtask_id).style.display='none';   
                },3000);
                return false;
             }
           
                if(end_date<start_date)
                {
                
                 document.getElementById('end_date_error'+task_id).innerHTML='End Date should be grater than start date';
                 setTimeout(function() {
                    document.getElementById('end_date_error'+task_id).style.display='none';
                     }, 3000);
                   return false; 
                }else{
                    
                }
                if(subtask_end_date<subtask_start_date)
                {
                
                 document.getElementById('subtask_end_date_error'+task_id+subtask_id).innerHTML='End Date should be grater than start date';
                 setTimeout(function() {
                    document.getElementById('subtask_end_date_error'+task_id+subtask_id).style.display='none';
                     }, 3000);
                   return false; 
                }
                $("#loader").css("display", "flex");
                $.ajax({
                            url: "{{route('projects.tasks.wbsupdate')}}",
                            method: 'POST',
                            data: {
                                task_id: task_id,
                                subtask_id:subtask_id,
                                task_name:task_name,
                                project_id:project_id,
                                end_date:end_date,
                                start_date:start_date,
                                stage_id:stage_id,
                                progress:progress,
                                task_predece:task_predece,
                                subtask_name:subtask_name,
                                subtask_start_date:subtask_start_date,
                                subtask_end_date:subtask_end_date,
                                subtask_stage_id:subtask_stage_id,
                                subtask_progress:subtask_progress,
                               
                                _token: '{{ csrf_token() }}' // Add CSRF token if you're not using Laravel Mix or Blade
                            },
                            success: function(response) {
                                //console.log(response)
                               var task_data = JSON.parse(response);
                              // console.log(task_data)
                               load_data();
                               $("#loader").css("display", "none");
                                
                                if (task_data.status==true) {
                                    var tasks=task_data.task;
                                    var gantt_chart = new Gantt(".gantt-target", tasks, {
                custom_popup_html: function(task) {
                    var status_class = 'success';
                    if(task.custom_class == 'medium'){
                        status_class = 'info'
                    }else if(task.custom_class == 'high'){
                        status_class = 'danger'
                    }
                    return `<div class="details-container">
                                <div class="title">${task.name} <span class="badge badge-${status_class} float-right">${task.extra.priority}</span></div>
                                <div class="subtitle">
                                    <b>${task.progress}%</b> {{ __('Progress')}} <br>
                                    <b>${task.extra.comments}</b> {{ __('Comments')}} <br>
                                    <b>{{ __('Duration')}}</b> ${task.extra.duration}
                                </div>
                            </div>
                          `;
                },
                on_click: function (task) {
                 
                },
                
                view_mode: '{{$duration}}',
                language: '{{$currantLang}}',
               
            });
                                } else {
                                    // Data update failed
                                    console.log('Failed to update data');
                                }
                            },
                            error: function(xhr, status, error) {
                                // Handle AJAX errors
                                console.error(error);
                            }
                        });
            }
            
            get_chart();

            function get_chart()
            {
               
             
            var tasks = JSON.parse('{!! addslashes(json_encode($tasks)) !!}');
      
            var gantt_chart = new Gantt(".gantt-target", tasks, {
                custom_popup_html: function(task) {
                    var status_class = 'success';
                    if(task.custom_class == 'medium'){
                        status_class = 'info'
                    }else if(task.custom_class == 'high'){
                        status_class = 'danger'
                    }
                    return `<div class="details-container">
                                <div class="title">${task.name} <span class="badge badge-${status_class} float-right">${task.extra.priority}</span></div>
                                <div class="subtitle">
                                    <b>${task.progress}%</b> {{ __('Progress')}} <br>
                                    <b>${task.extra.comments}</b> {{ __('Comments')}} <br>
                                    <b>{{ __('Duration')}}</b> ${task.extra.duration}
                                </div>
                            </div>
                          `;
                },
                on_click: function (task) {
                   
                },
                on_drag:function(task){

                },
                
                view_mode: '{{$duration}}',
                language: '{{$currantLang}}',
               
               
            });
        }
          
 
        </script>
        <script>
               $(document).ready(function () {
            /*Set requirement_id Value */
            $(document).on('click', '.add_req', function () {
                var idss = [];
                $(this).toggleClass('selected');
                var crr_idd = $(this).attr('data-id');
                $('#req_txt_' + crr_idd).html($('#req_txt_' + crr_idd).html() == 'Add' ? '{{__('Added')}}' : '{{__('Add')}}');
                if ($('#req_icon_' + crr_idd).hasClass('ti-plus')) {
                    $('#req_icon_' + crr_idd).removeClass('ti-plus');
                    $('#req_icon_' + crr_idd).addClass('ti-check');
                } else {
                    $('#req_icon_' + crr_idd).removeClass('ti-check');
                    $('#req_icon_' + crr_idd).addClass('ti-plus');
                }
              
                $('.add_req.selected').each(function () {
                    idss.push($(this).attr('data-id'));
                });
                
                $('input[name="requirement_id"]').val(idss);
            });
            /*Set assign_to Value*/
            $(document).on('click', '.add_usr', function () {
                var ids = [];
                $(this).toggleClass('selected');
                var crr_id = $(this).attr('data-id');
                $('#usr_txt_' + crr_id).html($('#usr_txt_' + crr_id).html() == 'Add' ? '{{__('Added')}}' : '{{__('Add')}}');
                if ($('#usr_icon_' + crr_id).hasClass('ti-plus')) {
                    $('#usr_icon_' + crr_id).removeClass('ti-plus');
                    $('#usr_icon_' + crr_id).addClass('ti-check');
                } else {
                    $('#usr_icon_' + crr_id).removeClass('ti-check');
                    $('#usr_icon_' + crr_id).addClass('ti-plus');
                }
              
                $('.add_usr.selected').each(function () {
                    ids.push($(this).attr('data-id'));
                });
               
                $('input[name="assign_to"]').val(ids);
            });
        });
            </script>
<script>
   //for updated gantt chart
    function get_updated_gantt()
    {
       var project_id=<?php echo $project->id; ?>
             
                $.ajax({
                            url: "{{route('projects.tasks.get_updated_gantt')}}",
                            method: 'POST',
                            data: {
                               
                                project_id:project_id,
                                
                                _token: '{{ csrf_token() }}' // Add CSRF token if you're not using Laravel Mix or Blade
                            },
                            success: function(response) {
                            // console.log(response);
                               var task_data = JSON.parse(response);
                              
                                if (task_data.status==true) {
                                    var tasks=task_data.task;
                                    var gantt_chart = new Gantt(".gantt-target", tasks, {
                custom_popup_html: function(task) {
                    var status_class = 'success';
                    if(task.custom_class == 'medium'){
                        status_class = 'info'
                    }else if(task.custom_class == 'high'){
                        status_class = 'danger'
                    }
                    return `<div class="details-container">
                                <div class="title">${task.name} <span class="badge badge-${status_class} float-right">${task.extra.priority}</span></div>
                                <div class="subtitle">
                                    <b>${task.progress}%</b> {{ __('Progress')}} <br>
                                    <b>${task.extra.comments}</b> {{ __('Comments')}} <br>
                                    <b>{{ __('Duration')}}</b> ${task.extra.duration}
                                </div>
                            </div>
                          `;
                },
                on_click: function (task) {
                 
                },
                 on_date_change: function(task, start, end) {
                   
                    task_id = task.id;
                    start = moment(start);
                    end = moment(end);
                    $.ajax({
                        url: "{{route('projects.gantt.post',[$project->id])}}",
                        data:{
                            start:start.format('YYYY-MM-DD HH:mm:ss'),
                            end:end.format('YYYY-MM-DD HH:mm:ss'),
                            task_id:task_id,
                            _token : "{{ csrf_token() }}",
                        },
                        type:'POST',
                        success:function (data) {
                           
                        },
                        error:function (data) {
                            show_toastr('Error', '{{ __("Some Thing Is Wrong!")}}', 'error');
                        }
                    });
                 
                },
                view_mode: '{{$duration}}',
                language: '{{$currantLang}}',
               
            });
                                } else {
                                    // Data update failed
                                    console.log('Failed to update data');
                                }
                            },
                            error: function(xhr, status, error) {
                                // Handle AJAX errors
                                console.error(error);
                            }
                        });
    }
    </script>


    @endpush
@endif

