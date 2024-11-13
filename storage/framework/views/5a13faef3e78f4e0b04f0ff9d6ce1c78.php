

<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Attendance Request')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(url('attendanceemployee')); ?>"><?php echo e(__('Attendance')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Attendance Request')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
     
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create leave')): ?>
        <a href="#" data-size="lg" data-url="<?php echo e(route('attendance_request.create')); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" title="<?php echo e(__('Create')); ?>" data-title="<?php echo e(__('Create Attendance Request')); ?>" class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
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
                    <table class="table datatable" id="att_table">
                            <thead>
                            <tr>
                            <th><?php echo e(__('Employee')); ?></th>
                               
                               <th><?php echo e(__('Date')); ?></th>
                              
                               <th><?php echo e(__('Clock In')); ?></th>
                               <th><?php echo e(__('Clock Out')); ?></th>
                               <th><?php echo e(__('Status')); ?></th>
                               <th><?php echo e(__('Attendance Reason')); ?></th>
                               <th><?php echo e(__('Created At')); ?></th>
                               <th><?php echo e(__('Updated At')); ?></th>
                               <?php if(Gate::check('edit attendance') || Gate::check('delete attendance')): ?>
                                   <th><?php echo e(__('Action')); ?></th>
                               <?php endif; ?>
                            </tr>
                            </thead>
                            <tbody>
                           
                            <?php $__currentLoopData = $attendance_request; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          
                                <tr>
                                   
                                    <td><?php echo e(!empty($attendance->employee)?$attendance->employee->name:''); ?></td>
                                   
                                    <td><?php echo e(\Auth::user()->dateFormat($attendance->date)); ?></td>
                                   
                                    <td><?php echo e(($attendance->clock_in !='00:00:00') ?\Auth::user()->timeFormat( $attendance->clock_in):'00:00'); ?> </td>
                                    <td><?php echo e(($attendance->clock_out !='00:00:00') ?\Auth::user()->timeFormat( $attendance->clock_out):'00:00'); ?></td>
                                    <td><?php echo e($attendance->status); ?></td>
                                    <td><?php echo e($attendance->attendance_reason); ?></td>
                                    <td><?php echo e($attendance->created_at); ?></td>
                                    <td><?php echo e($attendance->updated_at); ?></td>
                                    <?php if(Gate::check('edit attendance') || Gate::check('delete attendance')): ?>
                                        <td>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit attendance')): ?>
                                                <div class="action-btn bg-primary ms-2">
                                                    <a href="#" data-url="<?php echo e(route('attendance_request.edit', ['id' => $attendance->id])); ?>" data-size="lg" data-ajax-popup="true" data-title="<?php echo e(__('Edit Attendance Request')); ?>" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="<?php echo e(__('Edit')); ?>" data-original-title="<?php echo e(__('Edit')); ?>">
                                                        <i class="ti ti-pencil text-white"></i></a>
                                                        
                                                </div>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete attendance')): ?>
                                                <div class="action-btn bg-danger ms-2">
                                                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['attendance_request.destroy', $attendance->id],'id'=>'delete-form-'.$attendance->id]); ?>


                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="<?php echo e(__('Delete')); ?>"
                                                       data-original-title="<?php echo e(__('Delete')); ?>" data-confirm="<?php echo e(__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="document.getElementById('delete-form-<?php echo e($attendance->id); ?>').submit();">
                                                        <i class="ti ti-trash text-white"></i></a>
                                                    <?php echo Form::close(); ?>

                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>
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



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/attendanceRequest/index.blade.php ENDPATH**/ ?>