

<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Default Holiday')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('holiday.index')); ?>"><?php echo e(__('Holiday')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Mark Default Holiday')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-btn'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create holiday')): ?>
        <div class="float-end">
           
           
            <a href="#" data-size="lg" data-url="<?php echo e(route('holiday.create_default_holiday')); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" title="<?php echo e(__('Create')); ?>" data-title="<?php echo e(__('Create Default Holiday')); ?>" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
            <a href="<?php echo e(route('holiday.index')); ?>"
            data-title="<?php echo e(__('Holiday')); ?>" data-bs-toggle="tooltip" title="Back" class="btn btn-sm btn-primary"
            data-bs-original-title="<?php echo e(__('Holiday')); ?>">
            <span class="btn-inner--icon"><i class="ti ti-arrow-left"></i></span>
        </a>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    

    <div class="row mt-1">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable" id="att_table">
                            <thead>
                            <tr>
                                <th><?php echo e(__('Occasion')); ?></th>
                                <th><?php echo e(__('Start Date')); ?></th>
                                <th><?php echo e(__('Holiday Year')); ?></th>
                                <?php if(Gate::check('edit holiday') || Gate::check('delete holiday')): ?>
                                    <th><?php echo e(__('Action')); ?></th>
                                <?php endif; ?>
                            </tr>
                            </thead>
                            <tbody class="font-style">
                          
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/holiday/mark_holiday.blade.php ENDPATH**/ ?>