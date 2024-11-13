<div class="modal-body">
    <div class="row">
        <?php $__currentLoopData = $leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col text-center">
                <div class="card p-4 mb-4">
                    <h7 class="report-text gray-text mb-0"><?php echo e($leave->title); ?> :</h7>
                    <h6 class="report-text mb-0"><?php echo e($leave->total); ?></h6>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="row mt-2">
        <div class="table-responsive">
        <table class="table datatable">
            <thead>
            <tr>
                <th><?php echo e(__('Leave Type')); ?></th>
                <th><?php echo e(__('Leave Date')); ?></th>
              
                <th><?php echo e(__('Leave Reason')); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $leaveData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $startDate               = new \DateTime($leave->leave_date);
                 
                   
                ?>
                <tr>
                    <td><?php echo e(!empty($leave->leave_type)?$leave->leave_type:''); ?></td>
                    <td><?php echo e($leave->leave_date); ?></td>
                  
                    <td><?php echo e($leave->leave_reason); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="4" class="text-center"><?php echo e(__('No Data Found.!')); ?></td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    </div>
</div>
<?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/report/leaveShow.blade.php ENDPATH**/ ?>