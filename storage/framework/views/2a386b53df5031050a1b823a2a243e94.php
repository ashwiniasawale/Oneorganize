

<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Other Monthly Payment')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('setsalary.index')); ?>"><?php echo e(__('Employee Salary')); ?></a></li>
    <li class="breadcrumb-item">Other Monthly Payment Deduction/Allowance</li>
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
   
       
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create other payment')): ?>
        <?php echo e(Form::month('year_month',$year_month,array('class'=>'selecttt month-btn form-control month-btn','id'=>'year_month','onchange'=>'get_year_month();'))); ?>

        <?php endif; ?>
       
       
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create other payment')): ?>
        <a href="#" data-size="lg" data-url="<?php echo e(route('otherpayments.create')); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" title="<?php echo e(__('Create')); ?>" data-title="<?php echo e(__('Create Other Payment Option')); ?>" class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
        </a>
        <?php endif; ?>
        <a href="<?php echo e(route('setsalary.index')); ?>"
            data-title="<?php echo e(__('Set Salary')); ?>" data-bs-toggle="tooltip" title="Back" class="btn btn-sm btn-primary"
            data-bs-original-title="<?php echo e(__('Set Salary')); ?>">
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
                                <th><?php echo e(__('Employee')); ?></th>
                                <th><?php echo e(__('Title')); ?></th>
                                <th><?php echo e(__('Amount')); ?></th>
                                <th><?php echo e(__(' Year Month')); ?></th>
                                <th><?php echo e(__('Payment Option')); ?></th>
                               
                                <th></th>
                                 
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($other_payment as $other_payment)
                            { ?>
                                <tr>
                                    <td><?php echo e($other_payment->name); ?></td>
                                    <td><?php echo e($other_payment->title); ?></td>
                                    <td>₹ <?php echo e($other_payment->amount); ?></td>
                                    <td><?php echo e($other_payment->year_month); ?></td>
                                    <td><?php echo e($other_payment->payment_option); ?></td>
                                    <td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete other payment')): ?>
                                        <div class="action-btn bg-danger ms-2">
                                            <?php echo Form::open(['method' => 'DELETE', 'route' => ['otherpayment.destroy', $other_payment->id],'id'=>'payment-delete-form-'.$other_payment->id]); ?>

                                              <a href="#" class="mx-3 mt-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="<?php echo e(__('Delete')); ?>" data-original-title="<?php echo e(__('Delete')); ?>" data-confirm="<?php echo e(__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="document.getElementById('payment-delete-form-<?php echo e($other_payment->id); ?>').submit();"><i class="ti ti-trash text-white"></i></a>
                                            <?php echo Form::close(); ?>

                                        </div>
                                    <?php endif; ?>

                                                  
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
  
   
<script>
    function get_year_month() {
            var year_month=$('#year_month').val();
         
            var url = "<?php echo e(route('setsalary.otherpayment')); ?>"; // Replace '/your-url/' with your actual URL
           
                url += '/'+year_month;
           
           
            // Redirect to the constructed URL
            window.location.href = url;
       
    }
</script>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/otherpayment/index.blade.php ENDPATH**/ ?>