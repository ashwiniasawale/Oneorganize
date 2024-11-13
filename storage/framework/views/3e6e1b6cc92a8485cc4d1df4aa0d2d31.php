
<?php $__env->startSection('page-title'); ?>
<?php echo e(__('Manage Test')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
<li class="breadcrumb-item"><a href="<?php echo e(route('projects.index')); ?>"><?php echo e(__('Project')); ?></a></li>
<li class="breadcrumb-item"><a href="<?php echo e(route('projects.show',$project->id)); ?>"><?php echo e(ucwords($project->project_name)); ?></a></li>
<li class="breadcrumb-item"><?php echo e(__('Test Report')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('action-btn'); ?>
<div class="float-end">
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage bug report')): ?>
            <a href="<?php echo e(route('projects.test.kanban',$project->id)); ?>" data-bs-toggle="tooltip" title="<?php echo e(__('Kanban')); ?>" class="btn btn-sm btn-primary">
                <i class="ti ti-grid-dots"></i>
            </a>
        <?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create bug report')): ?>
    <a href="#" data-size="lg" data-url="<?php echo e(route('projects.test.create',[$project->id,$stages[0]->id])); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" title="<?php echo e(__('Create New Test')); ?>" class="btn btn-sm btn-primary">
        <i class="ti ti-plus"></i>
    </a>
    <?php endif; ?>
    <a href="<?php echo e(route('projects.show',$project->id)); ?>" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="" data-bs-original-title="Back">
        <span class="btn-inner--icon"><i class="ti ti-arrow-left"></i></span>
    </a>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table datatable" id="att_table">
                        <thead>
                            <tr>
                                <th> <?php echo e(__('Test Name')); ?></th>
                                <th> <?php echo e(__('Test Input')); ?></th>
                                <th> <?php echo e(__('Test Accepted output')); ?></th>
                                <th> <?php echo e(__('Status')); ?></th>
                                <th> <?php echo e(__('Priority')); ?></th>
                                <th> <?php echo e(__('Start Date')); ?></th>
                                <th> <?php echo e(__('End Date')); ?></th>
                                <th> <?php echo e(__('Test type')); ?></th>
                                <th> <?php echo e(__('Activity')); ?></th>
                                <th> <?php echo e(__('Activity Type')); ?></th>
                                <th> <?php echo e(__('Assigned To')); ?></th>
                                <th width="10%"> <?php echo e(__('Action')); ?></th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php $__currentLoopData = $tests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($test->test_name); ?></td>
                                <td><?php echo e($test->test_input); ?></td>
                                <td><?php echo e($test->test_accepted_output); ?></td>
                                <td>
                                    <?php foreach ($stages as $status) {

                                        if ($status->id == $test->stage_id) {
                                            echo $status->name;
                                        }
                                    }   ?>
                                </td>
                                <td><?php echo e($test->priority); ?></td>
                                <td><?php echo e($test->start_date); ?></td>
                                <td><?php echo e($test->end_date); ?></td>
                                <td><?php echo e($test->test_type); ?></td>
                                <td><?php echo e($test->task_activity); ?></td>
                                <td><?php echo e($test->task_activity_type); ?></td>
                                <td>

                                    <div class="avatar-group">
                                        <?php
                                        $users = [];
                                        $getUsers = App\Models\ProjectTask::getusers();
                                        if (!empty($test->assign_to)) {
                                        foreach (explode(',', $test->assign_to) as $key_user) {
                                        $user['name'] = $getUsers[$key_user]['name'];
                                        $user['avatar'] = $getUsers[$key_user]['avatar'];

                                        $users[] = $user;
                                        }
                                        $taskuser = $users;
                                        } else {
                                        $taskuser = [];
                                        }
                                        ?>

                                        <?php if(count($taskuser) > 0): ?>
                                        <?php $__currentLoopData = $taskuser; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="#" class="avatar rounded-circle avatar-sm">
                                            <img data-original-title="<?php echo e(!empty($user) ? $user['name'] : ''); ?>" <?php if($user['avatar']): ?> src="<?php echo e(asset('/storage/uploads/avatar/' . $user['avatar'])); ?>" <?php else: ?> src="<?php echo e(asset('/storage/uploads/avatar/avatar.png')); ?>" <?php endif; ?> title="<?php echo e($user['name']); ?>" class="hweb">
                                        </a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                        <?php echo e(__('-')); ?>

                                        <?php endif; ?>
                                    </div>
                                    
                </td>
                <td class="Action" width="10%">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit project test')): ?>
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" data-size="lg" class="mx-3 btn btn-sm  align-items-center" data-url="<?php echo e(route('project.test.edit',[$project->id,$test->id])); ?>" data-ajax-popup="true" data-size="xl" title="<?php echo e(__('Edit')); ?>" data-title="<?php echo e(__('Edit Test')); ?>">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete project test')): ?>
                                            <div class="action-btn bg-danger ms-2">
                                                <?php echo Form::open(['method' => 'DELETE', 'route' => ['project.test.destroy', $project->id,$test->id]]); ?>

                                                <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" title="<?php echo e(__('Delete')); ?>"><i class="ti ti-trash text-white"></i></a>
                                                <?php echo Form::close(); ?>

                                            </div>
                                        <?php endif; ?>
                </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
<?php $__env->stopSection(); ?>
<script>
     function load_data()
        {
           
            $("#att_table").load(" #att_table");
        }
</script>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/projects/testIndex.blade.php ENDPATH**/ ?>