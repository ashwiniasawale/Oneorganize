<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Employee Salary')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Employee Salary')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
     <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create other payment')): ?>
        <a href="<?php echo e(route('setsalary.otherpayment')); ?>"
            data-title="<?php echo e(__('Other Payment Deduction')); ?>" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="<?php echo e(__('Other Payment Deduction')); ?>">
            Other Payment Deduction/Allowance
        </a>
        <?php endif; ?>
      
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
    <div class="col-xl-12">
            <div class="card">
            <div class="card-body table-border-style">
                    <div class="table-responsive">
                    <table class="table datatable">
                            <thead>
                            <tr>
                                <th><?php echo e(__('Employee Id')); ?></th>
                                <th><?php echo e(__('Name')); ?></th>
                            
                                <th><?php echo e(__('Basic Salary (Monthly)')); ?></th>
                                <th><?php echo e(__('Net Salary (Monthly)')); ?></th>
                                <th><?php echo e(__('Annual CTC')); ?></th>
                                <th width="200px"><?php echo e(__('Action')); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($employee->user->is_enable_login=='1')
                            { ?>
                                <tr>
                                    <td class="Id">
                                        <a href="<?php echo e(route('setsalary.show',$employee->id)); ?>" class="btn btn-outline-primary" data-toggle="tooltip" data-original-title="<?php echo e(__('View')); ?>">
                                            <?php echo e(\Auth::user()->employeeIdFormat($employee->employee_id)); ?>

                                        </a>
                                    </td>
                                    <td><?php echo e($employee->name); ?></td>
                                  
                                    <td>₹ <?php echo e($employee->salary); ?></td>
                                    <td>₹ <?php echo e(round($employee->get_net_salary())); ?></td>
                                    <td>₹ <?php echo e($employee->annual_salary); ?></td>
                                    <td>
                                    <div class="action-btn bg-primary ms-2">
                                        <a href="<?php echo e(route('setsalary.show',$employee->id)); ?>" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="<?php echo e(__('Set Salary')); ?>" data-original-title="<?php echo e(__('View')); ?>">
                                            <i class="ti ti-eye text-white"></i>
                                        </a>
                                    </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/setsalary/index.blade.php ENDPATH**/ ?>