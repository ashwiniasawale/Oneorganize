<?php $__env->startSection('page-title'); ?> <?php echo e(__('Gantt Chart')); ?> <?php $__env->stopSection(); ?>
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
<?php
   
    use App\Models\ProjectSubtask;
   ?>
   <link
    href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css"
    rel="stylesheet"
/>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('projects.index')); ?>"><?php echo e(__('Project')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('projects.show',$project->id)); ?>">    <?php echo e(ucwords($project->project_name)); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('WBS- Gantt Chart')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
    <select  id="task_stage_id" name="task_stage_id" onchange="get_emp_task();" class="form-select selecttt mx-1" style="padding-right:2.5rem;">
        <option value="0">--Select Status--</option>
        <?php
          foreach($stages as $stage)
            { 
            ?>
           <option <?php if($task_stage_id==$stage->id){ echo 'selected=selected'; } ?> value="<?php echo e($stage->id); ?>"><?php echo e($stage->name); ?></option>
        <?php } ?>
    </select>
    <select name="task_user_id" id="task_user_id" onchange="get_emp_task();" class="form-select selecttt mx-1" style="padding-right: 2.5rem;" >
        <option value="0">--Select User--</option>
        <?php $__currentLoopData = $project->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($user->id==\Auth::user()->id  && \Auth::user()->type =='Employee')
        { ?>
       <option <?php if($task_user_id==$user->id){ echo 'selected=selected'; } ?> value="<?php echo $user->id; ?>"><?php echo $user->name; ?></option>
      
      <?php }else if(\Auth::user()->type !='Employee'){ ?>
        <option <?php if($task_user_id==$user->id){ echo 'selected=selected'; } ?> value="<?php echo $user->id; ?>"><?php echo $user->name; ?></option>
      <?php } ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create project task')): ?>
          <a href="#" data-size="lg" data-url="<?php echo e(route('projects.tasks.create',[$project->id,$stages[0]->id,'end','0'])); ?>" data-ajax-popup="true"   title="Create Task" data-title="<?php echo e(__('Add Task in ').$stages[0]->name); ?>" class="btn btn-sm btn-primary p-2">
                                            <i class="ti ti-plus"></i></a>
      <?php endif; ?>
                                    <a href="<?php echo e(route('projects.show',$project->id)); ?>" class="btn btn-primary btn-sm p-2" data-ajax-popup="true" title="Back" data-title="Back" >
                                            <span class="btn-inner--icon"><i class="ti ti-arrow-left"></i></span>
                                        </a>
                                       
                             
    </div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
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
                                <th scope="col"><?php echo e(__('Task Name')); ?></th>
                                <th  scope="col"><?php echo e(__('Discription')); ?></th>
                                <th scope="col"><?php echo e(__('Status')); ?></th>
                                
                                <th scope="col"><?php echo e(__('Progress')); ?> (%)</th>
                                <th scope="col"><?php echo e(__('Start Date')); ?></th>
                                <th scope="col"><?php echo e(__('End Date')); ?></th>
                                <th scope="col"><?php echo e(__('Predecessor')); ?></th>
                                <th scope="col"><?php echo e(__('Assigned To')); ?></th>
                                <th scope="col"><?php echo e(__('Comment')); ?></th>
                                <th scope="col"><?php echo e(__('Remark')); ?></th>
                                <th scope="col"><?php echo e(__('Action')); ?></th>
                            </tr>
                        </thead>
                        <tbody class="list" >

                        <?php if($task_list): ?>
                                <?php $__currentLoopData = $task_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                               
                                    <tr  class="task-row task-main" data-task-id="<?php echo e($task->id); ?>" id="high<?php echo e($task->id); ?>" onclick="highlight(<?php echo e($project->id); ?>,<?php echo e($task->id); ?>,<?php echo e($task->task_seq); ?>);">
                                  
                                    
                                    <td> 
                                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create project task')): ?>
                                       
                                        <a id="moveUp<?php echo e($task->id); ?>" title="UP" class="btn btn-sm text-white btn-success mr-2 btn-rad" onclick="task_seq_change(<?php echo e($project->id); ?>,<?php echo e($task->id); ?>,<?php echo e($task->task_seq); ?>,'up');"><i class="ri-arrow-up-line shadow"></i></a>
                                        <a id="moveDown<?php echo e($task->id); ?>" title="DOWN" class="btn btn-sm text-white btn-danger mr-2 btn-rad" onclick="task_seq_change(<?php echo e($project->id); ?>,<?php echo e($task->id); ?>,<?php echo e($task->task_seq); ?>,'down');"><i class="ri-arrow-down-line shadow"></i></a>
                                       
                                        <a href="#" class="btn btn-sm text-white btn-primary mr-2 btn-rad" data-size="lg" data-url="<?php echo e(route('projects.tasks.create',[$project->id,$stages[0]->id,'below',$task->task_seq])); ?>"  data-ajax-popup="true" title="Insert Below" data-title="<?php echo e(__('Add Task in ').$stages[0]->name); ?>" class="btn btn-sm btn-primary p-2">
                                        <i class="ri-menu-add-fill shadow"></i></a>
                                        
                                                               <a href="#" data-size="lg" class=" btn btn-sm text-white btn-dark mr-2  align-items-center btn-rad" data-url="<?php echo e(route('projects.tasks.createsubtask',[$project->id,$task->id])); ?>" data-ajax-popup="true" data-size="xl" title="<?php echo e(__('Create Subtask')); ?>" data-title="<?php echo e(__('Create Subtask ')); ?>">
                                                                   <i class="ri-git-merge-line text-white shadow"></i>
                                                               </a>
                                                           
                                          <?php endif; ?>
                                      
                                    </td>
                                    <td><strong><?php echo e($task->task_seq); ?></strong></td>
                                    <td class="text-container"><input  type="hidden" class="borderless-input" id="task_name<?php echo e($task->id); ?>"  value="<?php echo e($task->name); ?>"><?php echo e($task->name); ?></td>
                                    <td class="text-container"><?php echo e($task->description); ?></td>
                                    <td>
                                        <select  id="stage_id<?php echo e($task->id); ?>"  class="borderless-input" onChange="update_task(<?php echo e($task->id); ?>,<?php echo e($task->project_id); ?>,'');">
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
                                            <option <?php echo e($selected); ?> value="<?php echo e($stage->id); ?>"><?php echo e($stage->name); ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                  
                                    <td><input class="borderless-input width-data" id="progress<?php echo e($task->id); ?>" onblur="update_task(<?php echo e($task->id); ?>,<?php echo e($task->project_id); ?>,'');" value="<?php echo e($task->progress); ?>"><span style="display:none"><?php echo e($task->progress); ?></span>
                                        <br>
                                        <span class="text-danger" id="progress_error<?php echo e($task->id); ?>"></span>
                                    </td>
                                    <td>
                                            
                                        <input type="date" class="borderless-input start_date" id="start_date<?php echo e($task->id); ?>" onChange="update_task(<?php echo e($task->id); ?>,<?php echo e($task->project_id); ?>,'');" value="<?php echo e($task->start_date); ?>"><span style="display:none"><?php echo e($task->start_date); ?></span>
                                    </td>
                                      
                                    <td>
                                        <input type="date" class="borderless-input end_date" id="end_date<?php echo e($task->id); ?>" onChange="update_task(<?php echo e($task->id); ?>,<?php echo e($task->project_id); ?>,'');" value="<?php echo e($task->end_date); ?>"><span style="display:none"><?php echo e($task->end_date); ?></span>
                                        <br>
                                        <span class="text-danger" id="end_date_error<?php echo e($task->id); ?>"></span>
                                    </td>
                                    <td><input  class="borderless-input width-data" id="task_predece<?php echo e($task->id); ?>" onblur="update_task(<?php echo e($task->id); ?>,<?php echo e($task->project_id); ?>,'');" value="<?php echo e($task->predece); ?>"><span style="display:none"><?php echo e($task->predece); ?></span></td>
                                    <td>
                                            <div class="avatar-group">
                                            
                                            
                                            <?php
                                                    $users = [];
                                                   
                                                    if (!empty($task->assign_to)) {
                                                        foreach (explode(',', $task->assign_to) as $key_user) {
                                                           
                                                            $getUsers = App\Models\User::select('id','name')->where('id','=',$key_user)->first();
                                                          ?>
                                                           <small><strong><?php echo e($getUsers->name); ?></strong></small><br><br>
                                                           <?php
                                                          
                                                        }
                                                        
                                                    } 
                                                    ?>

                                              
                                            </div>
                                           
                                        </td>
                                        <td class="text-container"><?php echo e($task->comment); ?></td>
                                        <td class="text-container"><?php echo e($task->remark); ?></td>
                                        <td class="Action" width="10%">
                                       
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit project task')): ?>
                                                           
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" data-size="lg" class="mx-3 btn btn-sm  align-items-center" data-url="<?php echo e(route('projects.tasks.edit',[$project->id,$task->id])); ?>" data-ajax-popup="true" data-size="xl" title="<?php echo e(__('Edit')); ?>" data-title="<?php echo e(__('Edit ').$task->name); ?>">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                    <?php endif; ?>
                                                       
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete project task')): ?>
                                                            <div class="action-btn bg-danger ms-2">
                                                                 <a href="#" onclick="del_task(<?php echo e($project->id); ?>,<?php echo e($task->id); ?>,<?php echo e($task->task_seq); ?>);" id="del_task<?php echo e($task->id); ?>" class="delete-task-btn mx-3 btn btn-sm  align-items-center" title="<?php echo e(__('Delete')); ?>"><i class="ti ti-trash text-white"></i></a>
                                                             
                                                            </div>
                                                        <?php endif; ?>
                                      
                                        </td>
                                    </tr>
                                    <?php $subtask=ProjectSubtask::where('project_id',$task->project_id)->where('task_id',$task->id)->orderBy('subtask_seq', 'asc')->get(); 
                                   
                                   if (!$subtask->isEmpty())
                                    {
                                    
                                   ?>
                                     
                                                           <?php $__empty_1 = true; $__currentLoopData = $subtask; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subtask): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                               <tr class="task-row" data-task-id="<?php echo e($task->id); ?><?php echo e($subtask->id); ?>" id="high<?php echo e($task->id); ?><?php echo e($subtask->id); ?>" onclick="highlight(<?php echo e($project->id); ?>,<?php echo e($task->id); ?><?php echo e($subtask->id); ?>,<?php echo e($task->task_seq); ?>);">
                                                               <td></td>
                                                               <td><?php echo e($task->task_seq); ?>.<?php echo e($subtask->subtask_seq); ?></td>
                                                               <td class="text-container">
                                                                 <input class="borderless-input" type="hidden" id="subtask_name<?php echo e($task->id); ?><?php echo e($subtask->id); ?>" value="<?php echo e($subtask->subtask_name); ?>"><?php echo e($subtask->subtask_name); ?></td>
                                                                <td class="text-container"><?php echo e($subtask->description); ?></td>
                                                               <td>
                                                                  <select  id="subtask_stage_id<?php echo e($task->id); ?><?php echo e($subtask->id); ?>"  class="borderless-input" onChange="update_task(<?php echo e($task->id); ?>,<?php echo e($task->project_id); ?>,<?php echo e($subtask->id); ?>);">
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
                                                                       <option <?php echo e($selected); ?> value="<?php echo e($stage->id); ?>"><?php echo e($stage->name); ?></option>
                                                                       <?php } ?>      
                                       
                                                                   </select>
                                                                  </td>
                                                                
                                                                  <td><input class="borderless-input width-data" id="subtask_progress<?php echo e($task->id); ?><?php echo e($subtask->id); ?>" onblur="update_task(<?php echo e($task->id); ?>,<?php echo e($task->project_id); ?>,<?php echo e($subtask->id); ?>);" value="<?php echo e($subtask->progress); ?>"><span style="display:none"><?php echo e($subtask->progress); ?></span>
                                                                        <br>
                                                                        <span class="text-danger" id="subtask_progress_error<?php echo e($task->id); ?><?php echo e($subtask->id); ?>"></span>
                                                                    </td>
                                                                     <td>
                                                                   <input type="date" class="borderless-input" id="subtask_start_date<?php echo e($task->id); ?><?php echo e($subtask->id); ?>" onChange="update_task(<?php echo e($task->id); ?>,<?php echo e($task->project_id); ?>,<?php echo e($subtask->id); ?>);" onclick="check_date(<?php echo e($task->id); ?>,<?php echo e($subtask->id); ?>);" value="<?php echo e($subtask->start_date); ?>"><span style="display:none"><?php echo e($subtask->start_date); ?></span>
                                                                  </td>
                                                                   <td>  <input type="date" class="borderless-input" id="subtask_end_date<?php echo e($task->id); ?><?php echo e($subtask->id); ?>" onChange="update_task(<?php echo e($task->id); ?>,<?php echo e($task->project_id); ?>,<?php echo e($subtask->id); ?>);" onclick="check_date(<?php echo e($task->id); ?>,<?php echo e($subtask->id); ?>);" value="<?php echo e($subtask->end_date); ?>"><span style="display:none"><?php echo e($subtask->end_date); ?></span>
                                                                   <br>
                                                                   <span class="text-danger" id="subtask_end_date_error<?php echo e($task->id); ?><?php echo e($subtask->id); ?>"></span>
                                                                   </td>
                                                                   <td><input  class="borderless-input width-data" id="subtask_predece<?php echo e($task->id); ?><?php echo e($subtask->id); ?>" onblur="update_task(<?php echo e($task->id); ?>,<?php echo e($task->project_id); ?>,<?php echo e($subtask->id); ?>);" value="<?php echo e($subtask->predece); ?>"><span style="display:none"><?php echo e($subtask->predece); ?></span></td>
                                                                   <td>
                                                                   
                                                                   <div class="avatar-group">
                                                                       <?php
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
                                                                       ?>

                                                                       <?php if(count($taskusers) > 0): ?>
                                                                      
                                                                           <?php $__currentLoopData = $taskusers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $users): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                              
                                                                               <small><strong><?php echo e($users['name']); ?></strong></small><br><br>
                                                                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                       <?php else: ?>
                                                                           <?php echo e(__('-')); ?>

                                                                       <?php endif; ?>
                                                                   </div>
                                                                   
                                                                   </td>
                                                                   <td class="text-container"><?php echo e($subtask->comment); ?></td>
                                                                   <td class="text-container"><?php echo e($subtask->remark); ?></td>
                                                                  <td>
                                                                 
                                                                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit project task')): ?>
                                                                       <div class="action-btn bg-info ms-2">
                                                                           <a href="#" data-size="lg" class="mx-3 btn btn-sm  align-items-center" data-url="<?php echo e(route('projects.tasks.editsubtask',[$project->id,$task->id,$subtask->id])); ?>" data-ajax-popup="true" data-size="xl" title="<?php echo e(__('Edit Subtask')); ?>" data-title="<?php echo e(__('Edit Subtask')); ?>">
                                                                               <i class="ti ti-pencil text-white"></i>
                                                                           </a>
                                                                       </div>
                                                                   <?php endif; ?>
                                                                   
                                                                  </td>
                                                               </tr>
                                                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                               <tr>
                                                                   <td colspan="3">No subtasks found</td>
                                                               </tr>
                                                           <?php endif; ?>
                                         
                                       <?php
                                   } ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <tr>
                                    <th scope="col" colspan="7">
                                        <h6 class="text-center"><?php echo e(__('No tasks found')); ?></h6>
                                    </th>
                                </tr>
                            <?php endif; ?>
                          
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
        <a href="<?php echo e(route('projects.gantt',[$project->id,'Quarter Day',$task_user_id,$task_stage_id])); ?>" class="btn btn-primary <?php if($duration == 'Quarter Day'): ?>act-dur <?php endif; ?>" data-value="Quarter Day"><?php echo e(__('Quarter Day')); ?></a>
        <a href="<?php echo e(route('projects.gantt',[$project->id,'Half Day',$task_user_id,$task_stage_id])); ?>" class="btn btn-primary <?php if($duration == 'Half Day'): ?>act-dur <?php endif; ?>" data-value="Half Day"><?php echo e(__('Half Day')); ?></a>
        <a href="<?php echo e(route('projects.gantt',[$project->id,'Day',$task_user_id,$task_stage_id])); ?>" class="btn btn-primary <?php if($duration == 'Day'): ?>act-dur <?php endif; ?>" data-value="Day"><?php echo e(__('Day')); ?></a>
        <a href="<?php echo e(route('projects.gantt',[$project->id,'Week',$task_user_id,$task_stage_id])); ?>" class="btn btn-primary <?php if($duration == 'Week'): ?>act-dur <?php endif; ?>" data-value="Week"><?php echo e(__('Week')); ?></a>
        <a href="<?php echo e(route('projects.gantt',[$project->id,'Month',$task_user_id,$task_stage_id])); ?>" class="btn btn-primary <?php if($duration == 'Month'): ?>act-dur <?php endif; ?>" data-value="Month"><?php echo e(__('Month')); ?></a>
    </div>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage project')): ?>
        <a href="<?php echo e(route('projects.show',$project->id)); ?>" class="btn btn-primary " data-bs-toggle="tooltip" title="<?php echo e(__('Back')); ?>">
            <span class="btn-inner--icon"><i class="ti ti-arrow-left"></i></span>
        </a>
    <?php endif; ?>
</div>
</div><br>

    <div class="row">
        <div class="col-12">
            <div class="card card-stats border-0 scrol">
                
                <?php if($project): ?>
                    <div class="gantt-target "></div>
                <?php else: ?>
                    <h1>404</h1>
                    <div class="page-description">
                        <?php echo e(__('Page Not Found')); ?>

                    </div>
                    <div class="page-search">
                        <p class="text-muted mt-3"><?php echo e(__("It's looking like you may have taken a wrong turn. Don't worry... it happens to the best of us. Here's a little tip that might help you get back on track.")); ?></p>
                        <div class="mt-3">
                            <a class="btn-return-home badge-blue" href="<?php echo e(route('dashboard')); ?>"><i class="ti ti-reply"></i> <?php echo e(__('Return Home')); ?></a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->

<?php if($project): ?>
    <?php $__env->startPush('css-page'); ?>
        <link rel="stylesheet" href="<?php echo e(asset('css/frappe-gantt.css')); ?>" />
    <?php $__env->stopPush(); ?>
    <script>
        
    </script>
    <?php $__env->startPush('script-page'); ?>
  
        <?php
            $currantLang = basename(App::getLocale());
        ?>
     
<script>
    function get_emp_task() {
            var task_user_id=$('#task_user_id').val();
            var task_stage_id=$('#task_stage_id').val();
           
            // Construct the URL based on the selected user ID
            var url = "<?php echo e(route('projects.gantt',[$project->id,$duration])); ?>"; // Replace '/your-url/' with your actual URL
           
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
                url: "<?php echo e(route('projects.tasks.destroy')); ?>",
               
                data: {
                                task_id: task_id,
                                project_id:project_id,
                                task_seq:task_seq,
                                _token: '<?php echo e(csrf_token()); ?>' // Add CSRF token if you're not using Laravel Mix or Blade
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
                        url: '<?php echo e(route('task.seq.update')); ?>',
                        type: 'post',
                        dataType: 'html',
                        data: {"_token": "<?php echo e(csrf_token()); ?>",tmp_task:tmp_task},
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
                url:'<?php echo e(route('task.seq.change')); ?>',
                type: 'post',
                data: {"_token": "<?php echo e(csrf_token()); ?>",
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
                "<?php echo e($currantLang); ?>": [
                    '<?php echo e(__('January')); ?>',
                    '<?php echo e(__('February')); ?>',
                    '<?php echo e(__('March')); ?>',
                    '<?php echo e(__('April')); ?>',
                    '<?php echo e(__('May')); ?>',
                    '<?php echo e(__('June')); ?>',
                    '<?php echo e(__('July')); ?>',
                    '<?php echo e(__('August')); ?>',
                    '<?php echo e(__('September')); ?>',
                    '<?php echo e(__('October')); ?>',
                    '<?php echo e(__('November')); ?>',
                    '<?php echo e(__('December')); ?>'
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
        <script src="<?php echo e(asset('js/frappe-gantt.js')); ?>"></script>
        <script>
  
            function update_task(task_id,project_id,subtask_id)
            {
             
                var task_name=$('#task_name'+task_id).val();
                var end_date=$('#end_date'+task_id).val();
                var start_date=$('#start_date'+task_id).val();
                var stage_id=$('#stage_id'+task_id).val();
                var progress=$('#progress'+task_id).val();
                var task_user_id='<?php echo e($task_user_id); ?>';
               
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
                            url: "<?php echo e(route('projects.tasks.wbsupdate')); ?>",
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
                               
                                _token: '<?php echo e(csrf_token()); ?>' // Add CSRF token if you're not using Laravel Mix or Blade
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
                                    <b>${task.progress}%</b> <?php echo e(__('Progress')); ?> <br>
                                    <b>${task.extra.comments}</b> <?php echo e(__('Comments')); ?> <br>
                                    <b><?php echo e(__('Duration')); ?></b> ${task.extra.duration}
                                </div>
                            </div>
                          `;
                },
                on_click: function (task) {
                 
                },
                
                view_mode: '<?php echo e($duration); ?>',
                language: '<?php echo e($currantLang); ?>',
               
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
               
             
            var tasks = JSON.parse('<?php echo addslashes(json_encode($tasks)); ?>');
      
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
                                    <b>${task.progress}%</b> <?php echo e(__('Progress')); ?> <br>
                                    <b>${task.extra.comments}</b> <?php echo e(__('Comments')); ?> <br>
                                    <b><?php echo e(__('Duration')); ?></b> ${task.extra.duration}
                                </div>
                            </div>
                          `;
                },
                on_click: function (task) {
                   
                },
                on_drag:function(task){

                },
                
                view_mode: '<?php echo e($duration); ?>',
                language: '<?php echo e($currantLang); ?>',
               
               
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
                $('#req_txt_' + crr_idd).html($('#req_txt_' + crr_idd).html() == 'Add' ? '<?php echo e(__('Added')); ?>' : '<?php echo e(__('Add')); ?>');
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
                $('#usr_txt_' + crr_id).html($('#usr_txt_' + crr_id).html() == 'Add' ? '<?php echo e(__('Added')); ?>' : '<?php echo e(__('Add')); ?>');
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
                            url: "<?php echo e(route('projects.tasks.get_updated_gantt')); ?>",
                            method: 'POST',
                            data: {
                               
                                project_id:project_id,
                                
                                _token: '<?php echo e(csrf_token()); ?>' // Add CSRF token if you're not using Laravel Mix or Blade
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
                                    <b>${task.progress}%</b> <?php echo e(__('Progress')); ?> <br>
                                    <b>${task.extra.comments}</b> <?php echo e(__('Comments')); ?> <br>
                                    <b><?php echo e(__('Duration')); ?></b> ${task.extra.duration}
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
                        url: "<?php echo e(route('projects.gantt.post',[$project->id])); ?>",
                        data:{
                            start:start.format('YYYY-MM-DD HH:mm:ss'),
                            end:end.format('YYYY-MM-DD HH:mm:ss'),
                            task_id:task_id,
                            _token : "<?php echo e(csrf_token()); ?>",
                        },
                        type:'POST',
                        success:function (data) {
                           
                        },
                        error:function (data) {
                            show_toastr('Error', '<?php echo e(__("Some Thing Is Wrong!")); ?>', 'error');
                        }
                    });
                 
                },
                view_mode: '<?php echo e($duration); ?>',
                language: '<?php echo e($currantLang); ?>',
               
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


    <?php $__env->stopPush(); ?>
<?php endif; ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/projects/gantt.blade.php ENDPATH**/ ?>