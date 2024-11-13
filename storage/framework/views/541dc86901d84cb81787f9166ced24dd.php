
<?php $__env->startSection('page-title'); ?>
<?php echo e(__('Manage Bug Report')); ?>

<?php $__env->stopSection(); ?>

<style>
    .form-select{
        display:inline-block !important;
        width:auto !important;
    }
</style>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
<li class="breadcrumb-item"><?php echo e(__('Project')); ?></li>
<li class="breadcrumb-item"><?php echo e(__('Bug Report')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-btn'); ?>
<div class="float-end">
<label><strong>Project Name : </strong></label>
<select name="p_id" id="p_id" class="form-select mx-1" style="padding-right: 2.5rem;" onchange="ajaxFilterBugView();">
    <?php foreach($projects as $key=>$value)
    { ?>
    <option value="<?php echo e($key); ?>"><?php echo e($value); ?></option>
    <?php } ?>
</select>
    <?php if($view == 'grid'): ?>
    <a href="<?php echo e(route('bugs.view', 'list')); ?>" class="btn btn-primary btn-sm p-2 ms-xs-2" data-bs-toggle="tooltip" title="<?php echo e(__('List View')); ?>">
        <span class="btn-inner--text"><i class="ti ti-list"></i></span>
    </a>
    <?php else: ?>
    <a href="<?php echo e(route('bugs.view', 'grid')); ?>" class="btn btn-primary btn-sm p-2 ms-xs-2" data-bs-toggle="tooltip" title="<?php echo e(__('Card View')); ?>">
        <span class="btn-inner--text"><i class="ti ti-table"></i></span>
    </a>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage project')): ?>
    <a href="<?php echo e(route('projects.index')); ?>" class="btn btn-primary btn-sm p-2 " data-bs-toggle="tooltip" title="<?php echo e(__('Back')); ?>">
        <span class="btn-inner--icon"><i class="ti ti-arrow-left"></i></span>
    </a>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row min-750" id="bug_list"></div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
    <script>
        // ready
        $(function () {
            ajaxFilterBugView();
        });
        </script>
        <script>
   
   function ajaxFilterBugView() {
      
   var mainEle = $('#bug_list');
   
   var view = '<?php echo e($view); ?>';
    var project_id=$('#p_id').val();
 
    
    var data = {
       view: view,
       project_id:project_id,
    }

    $.ajax({
        url: '<?php echo e(route('project.buglist.view')); ?>',
        data: data,
        success: function (data) {
        
            mainEle.html(data.html);
           
        }
    });
}
</script>
        <?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/projects/bugList.blade.php ENDPATH**/ ?>