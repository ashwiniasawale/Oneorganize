<?php $__env->startSection('page-title'); ?>
    <?php echo e(ucwords($project->project_name).__("'s Expenses")); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>

    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('projects.index')); ?>"><?php echo e(__('Project')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('projects.show',$project->id)); ?>">    <?php echo e(ucwords($project->project_name)); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(ucwords($project->project_name).__("'s Expenses")); ?></li>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create expense')): ?>
            <a href="#" class="btn btn-primary btn-sm" data-url="<?php echo e(route('projects.expenses.create',$project->id)); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" title="<?php echo e(__('Create')); ?>" data-size="lg" data-title="<?php echo e(__('Create Expense')); ?>">
                <span class="btn-inner--icon"><i class="ti ti-plus"></i></span>
            </a>
        <?php endif; ?>
        <a href="<?php echo e(route('projects.show',$project->id)); ?>" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="<?php echo e(__('Back')); ?>">
            <span class="btn-inner--icon"><i class="ti ti-arrow-left"></i></span>
        </a>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                        <tr>
                            <th><?php echo e(__('Attachment')); ?></th>
                            <th><?php echo e(__('Name')); ?></th>
                            <th><?php echo e(__('Date')); ?></th>
                            <th><?php echo e(__('Amount')); ?></th>
                            <?php if(Gate::check('edit project expense') || Gate::check('delete project expense')): ?>
                                <th><?php echo e(__('Action')); ?></th>
                            <?php endif; ?>
                        </tr>
                        </thead>
                        <tbody class="">
                            <?php if(isset($project->expense) && !empty($project->expense) && count($project->expense) > 0): ?>
                                <?php $__currentLoopData = $project->expense; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <th>
                                            <?php if(!empty($expense->attachment)): ?>
                                                <a href="<?php echo e(asset(Storage::url($expense->attachment))); ?>" class="btn btn-sm btn-primary btn-icon rounded-pill" data-bs-toggle="tooltip" title="<?php echo e(__('Download')); ?>" download>
                                                    <span class="btn-inner--icon"><i class="ti ti-download"></i></span>
                                                </a>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </th>
                                        <td>
                                            <span class="h6 text-sm font-weight-bold mb-0"><?php echo e($expense->name); ?></span>
                                            <?php if(!empty($expense->task)): ?><span class="d-block text-sm text-muted"><?php echo e($expense->task->name); ?></span><?php endif; ?>
                                        </td>
                                        <td><?php echo e((!empty($expense->date)) ? Utility::getDateFormated($expense->date) : '-'); ?></td>
                                        <td><?php echo e(\Auth::user()->priceFormat($expense->amount)); ?></td>
                                        <?php if(Gate::check('edit project expense') || Gate::check('delete project expense')): ?>
                                            <td class="text-end">
                                                <div class="actions">
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit project expense')): ?>

                                                    <div class="action-btn bg-primary ms-2">
                                                        <a href="#" data-url="<?php echo e(route('projects.expenses.edit',[$project->id,$expense->id])); ?>" data-size="lg" data-ajax-popup="true" data-title="<?php echo e(__('Edit project Expense')); ?>" data-bs-toggle="tooltip" title="<?php echo e(__('Edit')); ?>" data-original-title="<?php echo e(__('Edit')); ?>" class="mx-3 btn btn-sm align-items-center">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete project expense')): ?>
                                                    <div class="action-btn bg-danger ms-2">
                                                        <?php echo Form::open(['method' => 'DELETE', 'route' => ['projects.expenses.destroy',$expense->id],'id'=>'delete-expense-'.$expense->id]); ?>

                                                        <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-confirm="<?php echo e(__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')); ?>" data-bs-toggle="tooltip" title="<?php echo e(__('Delete')); ?>" data-original-title="<?php echo e(__('Delete')); ?>" data-confirm-yes="document.getElementById('delete-expense-<?php echo e($expense->id); ?>').submit();">
                                                            <i class="ti ti-trash text-white"></i>
                                                        </a>
                                                        <?php echo Form::close(); ?>

                                                    </div>
                                                <?php endif; ?>

                                                </div>
                                                <?php echo Form::close(); ?>

                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <tr>
                                    <th scope="col" colspan="5"><h6 class="text-center"><?php echo e(__('No Expense Found.')); ?></h6></th>
                                </tr>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/project_expense/index.blade.php ENDPATH**/ ?>