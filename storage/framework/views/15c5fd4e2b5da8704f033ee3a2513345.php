

<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Offer Letter')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Offer Letter')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
        
      
       
        <a href="#" data-size="lg" data-url="<?php echo e(route('letters.create_offer')); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" title="<?php echo e(__('Create')); ?>" data-title="<?php echo e(__('Create Offer Letter')); ?>" class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
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
                                <th><?php echo e(__('Name')); ?></th>
                                
                                <th><?php echo e(__('Employee Type')); ?></th>
                                <th><?php echo e(__('Joining Date')); ?></th>
                                <th><?php echo e(__('Designation')); ?></th>
                                <th><?php echo e(__('Ref. No.')); ?></th>
                                <th><?php echo e(__('Annual CTC')); ?></th>
                               
                                <th><?php echo e(__('Offer Letter')); ?></th>
                                 <th><?php echo e(__('Appointment Letter')); ?></th>
                                 <th></th>
                            </tr>
                            </thead>
                            <tbody>
                           <?php foreach($letter as $letter)
                           { ?>
                           <tr>
                           <td><?php echo e($letter->employee_name); ?></td>
                           <td><?php echo e($letter->employee_type); ?></td>
                           <td><?php echo e($letter->joining_date); ?></td>
                           <td><?php echo e($letter->designation); ?></td>
                           <td><?php echo e($letter->ref_no); ?></td>
                           <td><?php echo e($letter->salary); ?></td>
                           <td> <a href="<?php echo e(route('offerletter.download.pdf',$letter->id)); ?>" class=" btn-icon btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top"  target="_blanks"><i class="ti ti-download ">&nbsp;</i><?php echo e(__('PDF')); ?></a>
                           </td>
                           <td> <a href="<?php echo e(route('appointment.download.pdf',$letter->id)); ?>" class=" btn-icon btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top"  target="_blanks"><i class="ti ti-download ">&nbsp;</i><?php echo e(__('PDF')); ?></a></td>
                           <td>
                           <div class="action-btn bg-danger ms-2">
                                                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['offer.destroy', $letter->id],'id'=>'delete-form-'.$letter->id]); ?>

                                                    <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="<?php echo e(__('Delete')); ?>" data-original-title="<?php echo e(__('Delete')); ?>" data-confirm="<?php echo e(__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="document.getElementById('delete-form-<?php echo e($letter->id); ?>').submit();">
                                                        <i class="ti ti-trash text-white"></i>
                                                    </a>
                                                    <?php echo Form::close(); ?>

                                                </div>
                           </td>
                           <tr>
                           <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/template_letter/offer_letter.blade.php ENDPATH**/ ?>