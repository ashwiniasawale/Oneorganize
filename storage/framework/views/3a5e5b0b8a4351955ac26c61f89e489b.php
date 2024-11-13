<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Tasks')); ?>

<?php $__env->stopSection(); ?>
<style>
    .form-select{
        display:inline-block !important;
        width:auto !important;
    }
</style>


<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('projects.index')); ?>"><?php echo e(__('Project')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Task')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-btn'); ?>
<div class="float-end">

<select name="p_id" id="p_id" class="form-select mx-1" onchange="ajaxFilterTaskView();" style="padding-right: 2.5rem;">
    <?php foreach($projects as $key=>$value)
    { ?>
    <option value="<?php echo e($key); ?>"><?php echo e($value); ?></option>
    <?php } ?>
    <option value="all">--All--</option>
</select>
<select  id="task_stage_id" name="task_stage_id" onchange="ajaxFilterTaskView();" class="form-select selecttt mx-1" style="padding-right:2.5rem;">
        <option value="0" disabled>--Select Status--</option>
        <?php
          foreach($stages as $stage)
            { 
            ?>
           <option  value="<?php echo e($stage->id); ?>"><?php echo e($stage->name); ?></option>
        <?php } ?>
    </select>
    

    <?php if($view == 'grid'): ?>
        <a href="<?php echo e(route('taskBoard.view', 'list')); ?>" class="btn btn-primary btn-sm p-2" data-bs-toggle="tooltip" title="<?php echo e(__('List View')); ?>">
            <span class="btn-inner--text"><i class="ti ti-list"></i></span>
        </a>
    <?php else: ?>
        <!-- <a href="<?php echo e(route('taskBoard.view', 'grid')); ?>" class="btn btn-primary btn-sm p-2" data-bs-toggle="tooltip" title="<?php echo e(__('Grid View')); ?>">
            <span class="btn-inner--text"><i class="ti ti-table"></i></span>
        </a> -->
    <?php endif; ?>
    <a data-bs-toggle="tooltip"  onclick="export_task();" title="<?php echo e(__('Export')); ?>" class="btn btn-sm btn-primary text-white">
            Export & Email
        </a>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row min-750" id="taskboard_view"></div>
    
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
    <script>
     function export_task()
     {
        var mainEle = $('#taskboard_view');
            var view = '<?php echo e($view); ?>';
            var project_id=$('#p_id').val();
            var task_stage_id=$('#task_stage_id').val();
            
            var data = {
                view: view,
               task_stage_id:task_stage_id,
               project_id:project_id,
            }

          

            $.ajax({
                url: '<?php echo e(route('task.export')); ?>',
               
                data: data,
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(data) {
                    // Create a temporary anchor element to trigger the download
                    console.log(data);
                    var currentDate = new Date();
        var dateString = currentDate.toISOString().slice(0, 10); // Get YYYY-MM-DD format

                    var a = document.createElement('a');
                var url = window.URL.createObjectURL(data);
                a.href = url;
                a.download = 'tasklist_'+dateString+'.csv';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);

              
                },
                error: function(xhr, status, error) {
                    console.error('Error exporting tasks:', error);
                    alert('Failed to export tasks. Please try again.');
                }
            });
     }

        // For Filter
        ajaxFilterTaskView();
        function ajaxFilterTaskView() {
         
            var mainEle = $('#taskboard_view');
            var view = '<?php echo e($view); ?>';
            var project_id=$('#p_id').val();
            var task_stage_id=$('#task_stage_id').val();
            
            var data = {
                view: view,
               task_stage_id:task_stage_id,
               project_id:project_id,
            }

            $.ajax({
                url: '<?php echo e(route('project.taskboard.view')); ?>',
                data: data,
                success: function (data) {
               // console.log(data);
                    mainEle.html(data.html);
                 
                    $('.cell').each(function() {
                $(this).css('max-width', '50px');
                $(this).css('white-space', 'nowrap');
                $(this).css('overflow', 'hidden');
                $(this).css('text-overflow', 'ellipsis');
            });
                }
            });
        }
    </script>
   
        
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/project_task/taskboard.blade.php ENDPATH**/ ?>