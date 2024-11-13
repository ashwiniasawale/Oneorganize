<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Leave')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Manage Leave')); ?></li>
<?php $__env->stopSection(); ?>
<style>
.selecttt
{
        display:inline-block !important;
        width:auto !important;
}
    </style>
<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage leave')): ?>
        <select  id="leave_year" name="leave_year" onchange="get_leave_year();" class="form-select selecttt mx-1" style="padding-right:2.5rem;">
            <?php 
            $start_year=date('Y')-10;
            $end_year=date('Y');
            for($i=$start_year;$i<=$end_year;$i++)
            {
                ?>
                <option <?php if($i==$year){ echo 'selected=selected'; } ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php
            }
            ?>
        </select>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage leave')): ?>
        <a href="<?php echo e(route('leave.leave_details')); ?>"
            data-title="<?php echo e(__('Leave Details')); ?>" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="<?php echo e(__('Leave Details')); ?>">
           Leave Details
        </a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create leave')): ?>
        <a href="#" data-size="lg" data-url="<?php echo e(route('leave.create')); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" title="<?php echo e(__('Create')); ?>" data-title="<?php echo e(__('Create Leave')); ?>" class="btn btn-sm btn-primary">
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
                                <?php if(\Auth::user()->type!='Employee'): ?>
                                    <th><?php echo e(__('Employee')); ?></th>
                                <?php endif; ?>
                                <th><?php echo e(__('Leave Date')); ?></th>
                                <th><?php echo e(__('Duration')); ?></th>
                                <th><?php echo e(__('status')); ?></th>
                                <th><?php echo e(__('Leave Type')); ?></th>
                               
                                <th><?php echo e(__('Leave Reason')); ?></th>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit leave')): ?>
                                        <th width="200px"><?php echo e(__('Action')); ?></th>
                                    <?php endif; ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <?php if(\Auth::user()->type!='Employee'): ?>
                                        <td><?php echo e(!empty($leave->employees) ? $leave->employees->name : '-'); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo e(\Auth::user()->dateFormat($leave->leave_date )); ?></td>
                                    <td><?php echo e($leave->duration); ?></td>
                                    <td>
                                        <?php if($leave->duration=='multiple')
                                        { ?>
                                         <div class=" ">
                                            <a href="#" data-url="<?php echo e(URL::to('leave/'.$leave->id.'/action')); ?>" data-size="lg" data-ajax-popup="true" data-title="<?php echo e(__('Total Leave')); ?> (<?php echo e($leave->employees->name); ?>)" class="align-items-center" title="<?php echo e(__('View Leaves')); ?>" data-original-title="<?php echo e(__('Total Leave')); ?> (<?php echo e($leave->employees->name); ?>)">
                                                View Status</a>
                                        </div>
                                        <?php }else{ ?>
                                    <?php if($leave->status=="Pending"): ?><div class=" text-warning"><?php echo e($leave->status); ?></div>
                                        <?php elseif($leave->status=="Approved"): ?>
                                            <div class=" text-success"><?php echo e($leave->status); ?></div>
                                        <?php else: ?>
                                            <div class="text-danger"><?php echo e($leave->status); ?></div>
                                        <?php endif; ?>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo e($leave->leave_type); ?></td>
                                   
                                    <td><?php echo e($leave->leave_reason); ?></td>
                                      
                                    <td>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage leave')): ?>
                                        <div class="action-btn bg-warning ms-2">
                                            <a href="#" data-url="<?php echo e(URL::to('leave/'.$leave->id.'/action')); ?>" data-size="lg" data-ajax-popup="true" data-title="<?php echo e(__('Total Leave')); ?> (<?php echo e($leave->employees->name); ?>)" class="mx-3 btn btn-sm  align-items-center" title="<?php echo e(__('View Leaves')); ?>" data-original-title="<?php echo e(__('Total Leave')); ?> (<?php echo e($leave->employees->name); ?>)">
                                                <i class="ti ti-eye text-white"></i> </a>
                                        </div>
                                         
                                            <!-- <div class="action-btn bg-primary ms-2">
                                                <a href="#" data-url="<?php echo e(URL::to('leave/'.$leave->id.'/edit')); ?>" data-size="lg" data-ajax-popup="true" data-title="<?php echo e(__('Edit Leave')); ?>" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="<?php echo e(__('Edit')); ?>" data-original-title="<?php echo e(__('Edit')); ?>">
                                                <i class="ti ti-pencil text-white"></i></a>
                                            </div> -->
                                        <?php endif; ?>
                                       
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete leave')): ?>
                                        <div class="action-btn bg-danger ms-2">
                                            <?php echo Form::open(['method' => 'DELETE', 'route' => ['leave.destroy', $leave->id],'id'=>'delete-form-'.$leave->id]); ?>

                                            <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="<?php echo e(__('Delete')); ?>" data-original-title="<?php echo e(__('Delete')); ?>" data-confirm="<?php echo e(__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="document.getElementById('delete-form-<?php echo e($leave->id); ?>').submit();">
                                            <i class="ti ti-trash text-white"></i></a>
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
    function get_leave_year() {
            var year=$('#leave_year').val();
         
            var url = "<?php echo e(route('leave.leave_year')); ?>"; // Replace '/your-url/' with your actual URL
           
                url += '/'+year;
           
           
            // Redirect to the constructed URL
            window.location.href = url;
       
    }
</script>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/leave/index.blade.php ENDPATH**/ ?>