
<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Test')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('projects.index')); ?>"><?php echo e(__('Project')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('projects.show',$project->id)); ?>">    <?php echo e(ucwords($project->project_name)); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Test')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('css-page'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/plugins/dragula.min.css')); ?>" id="main-style-link">
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script-page'); ?>

    <script src="<?php echo e(asset('assets/js/plugins/dragula.min.js')); ?>"></script>
   
<?php $__env->stopPush(); ?>
<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage bug report')): ?>
            <a href="<?php echo e(route('project.testindex',$project->id)); ?>" data-bs-toggle="tooltip" title="<?php echo e(__('List')); ?>" class="btn btn-sm btn-primary">
                <i class="ti ti-list"></i>
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
    <?php
        $json = [];
        foreach ($stages as $status){
            $json[] = 'task-list-'.$status->id;
        }
    ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="row kanban-wrapper horizontal-scroll-cards" data-containers='<?php echo e(json_encode($json)); ?>' data-plugin="dragula">
           
            <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $tests = $status->test($project->id) ?>
             
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-end">

                                    <span class="btn btn-sm btn-primary btn-icon count">
                                    <?php echo e(count($tests)); ?>

                                    </span>
                                </div>
                                <h4 class="mb-0"><?php echo e($status->name); ?></h4>
                            </div>
                            <div class="card-body kanban-box" id="task-list-<?php echo e($status->id); ?>" data-id="<?php echo e($status->id); ?>">
                              
                                <?php $__currentLoopData = $tests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                
                                    <div class="card draggable-item" id="<?php echo e($test->id); ?>">
                                        <div class="pt-3 ps-3">
                                            <?php if($test->priority =='low'): ?>
                                                <span class="p-2 px-3 rounded badge badge-pill badge-xs bg-success"><?php echo e(ucfirst($test->priority)); ?></span>
                                            <?php elseif($test->priority =='medium'): ?>
                                                <span class="p-2 px-3 rounded badge badge-pill badge-xs bg-warning"><?php echo e(ucfirst($test->priority)); ?></span>
                                            <?php elseif($test->priority =='high'): ?>
                                                <span class="p-2 px-3 rounded badge badge-pill badge-xs bg-danger"><?php echo e(ucfirst($test->priority)); ?></span>
                                          
                                            <?php elseif($test->priority =='critical'): ?>
                                                <span class="p-2 px-3 rounded badge badge-pill badge-xs bg-danger"><?php echo e(ucfirst($test->priority)); ?></span>
                                            <?php endif; ?>
                                           
                                        </div>
                                        
                                        <div class="card-header border-0 pb-0 position-relative">
                                        
                                            <h6 class="mb-0"><?php echo e($test->test_name); ?></h6>
                                            <h5>
                                                <a href="#"  data-ajax-popup="true" data-size="lg" data-bs-original-title="<?php echo e($test->title); ?>"><?php echo e($test->title); ?></a>
                                            </h5>
                                            <div class="card-header-right">
                                                <div class="btn-group card-option">
                                                    <button type="button" class="btn dropdown-toggle"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>
                                                    <?php if(Gate::check('edit project test') || Gate::check('delete project test')): ?>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit project test')): ?>
                                                                <a href="#!" data-size="lg" data-url="<?php echo e(route('project.test.edit',[$project->id,$test->id])); ?>" data-ajax-popup="true" class="dropdown-item" data-bs-original-title="<?php echo e(__('Edit ').$test->test_name); ?>">
                                                                    <i class="ti ti-pencil"></i>
                                                                    <span><?php echo e(__('Edit')); ?></span>
                                                                </a>
                                                            <?php endif; ?>
                                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete project test')): ?>
                                                                <?php echo Form::open(['method' => 'DELETE', 'route' => ['project.test.destroy', [$project->id,$test->id]]]); ?>

                                                                <a href="#!" class="dropdown-item bs-pass-para">
                                                                    <i class="ti ti-archive"></i>
                                                                    <span> <?php echo e(__('Delete')); ?> </span>
                                                                </a>
                                                                <?php echo Form::close(); ?>

                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <ul class="list-inline mb-0">
                                                    <li class="list-inline-item d-inline-flex align-items-center" data-bs-toggle="tooltip" title="<?php echo e(__('Start Date')); ?>">
                                                         <?php echo e(\Auth::user()->dateFormat($test->start_date)); ?>

                                                    </li>

                                                </ul>
                                                <div class="user-group">
                                                    <span data-bs-toggle="tooltip" title="<?php echo e(__('End Date')); ?>">  <?php echo e(\Auth::user()->dateFormat($test->end_date)); ?></span>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <?php $user = $test->users(); ?>

                                                <div class="user-group">
                                                    <img <?php if(isset($user[0]->avatar)): ?> src="<?php echo e(asset('/storage/uploads/avatar/'.$user[0]->avatar)); ?>" <?php else: ?> src="<?php echo e(asset('/storage/uploads/avatar/avatar.png')); ?>" <?php endif; ?> alt="image" data-bs-toggle="tooltip" title="<?php echo e((!empty($user[0])?$user[0]->name:'')); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/projects/testKanban.blade.php ENDPATH**/ ?>