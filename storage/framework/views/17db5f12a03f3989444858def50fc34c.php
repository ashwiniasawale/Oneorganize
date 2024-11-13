
<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Risk')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('projects.index')); ?>"><?php echo e(__('Project')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('projects.show',$project->id)); ?>"><?php echo e(ucwords($project->project_name)); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Risk')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
        <!-- <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage bug report')): ?>
            <a href="<?php echo e(route('task.bug.kanban',$project->id)); ?>" data-bs-toggle="tooltip" title="<?php echo e(__('Kanban')); ?>" class="btn btn-sm btn-primary">
                <i class="ti ti-grid-dots"></i>
            </a>
        <?php endif; ?> -->
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create project risk')): ?>
            <a href="#" data-size="xl" data-url="<?php echo e(route('project.risk.create',$project->id)); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" title="<?php echo e(__('Create New risk')); ?>" class="btn btn-sm btn-primary ">
                <i class="ti ti-plus"></i>
            </a>
        <?php endif; ?>

        <a href="<?php echo e(route('projects.show',$project->id)); ?>" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="" data-bs-original-title="Back">
               <span class="btn-inner--icon"><i class="ti ti-arrow-left"></i></span>
        </a>

        
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">`
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                             
                                <th> <?php echo e(__('Risk Details')); ?></th>
                                <th> <?php echo e(__('Priority')); ?></th>
                                <th> <?php echo e(__('Identified On')); ?></th>
                                <th> <?php echo e(__('Mitigation Target Date')); ?></th>
                                <th> <?php echo e(__('Responsible Person')); ?></th>
                                <th> <?php echo e(__(' Risk classification ')); ?></th>
                                <th> <?php echo e(__('Risk Description')); ?></th>
                               
                                <th> <?php echo e(__('Status')); ?></th>
                                <th> <?php echo e(__('Risk Consequence')); ?></th>
                                <th> <?php echo e(__('Risk Score')); ?></th>
                                <th> <?php echo e(__('Mitigation Person')); ?></th>
                                <th> <?php echo e(__('Critical Dependency ')); ?></th>
                                <th> <?php echo e(__('Require Resource for Mitigation')); ?></th>
                                <th><?php echo e(__('Created By')); ?></th>

                                <th width="10%"> <?php echo e(__('Action')); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $risk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $risk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                  <td><?php echo e($risk->risk_details); ?></td>
                                    <td><?php echo e($risk->priority); ?></td>
                                    <td><?php echo e(Auth::user()->dateFormat($risk->identified_on)); ?></td>
                                    <td><?php echo e(Auth::user()->dateFormat($risk->mitigation_target_date)); ?></td>
                                    <td><?php echo e((!empty($risk->responsiblePerson)?$risk->responsiblePerson->name:'')); ?></td>
                                    <td><?php echo e($risk->risk_classification); ?></td>
                                    <td><?php echo e($risk->risk_description); ?></td>
                                   
                                    <td><?php echo e($risk->status); ?></td>
                                    <td><?php echo e($risk->risk_consequence); ?></td>
                                    <td><?php echo e($risk->risk_score); ?></td>
                                    <td><?php echo e((!empty($risk->mitigationPerson)?$risk->mitigationPerson->name:'')); ?></td>
                                    <td><?php echo e($risk->critical_dependency); ?></td>
                                    <td><?php echo e($risk->mitigation_resource); ?></td>
                                   
                                    <td><?php echo e($risk->createdBy->name); ?></td>
                                    <td class="Action" width="10%">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit project risk')): ?>
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" data-size="xl" class="mx-3 btn btn-sm  align-items-center" data-url="<?php echo e(route('project.risk.edit',[$project->id,$risk->id])); ?>" data-ajax-popup="true" data-size="xl" title="<?php echo e(__('Edit Risk')); ?>" data-title="<?php echo e(__('Edit Risk')); ?>">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete project risk')): ?>
                                            <div class="action-btn bg-danger ms-2">
                                                <?php echo Form::open(['method' => 'DELETE', 'route' => ['project.risk.delete', $project->id,$risk->id]]); ?>

                                                <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"  title="<?php echo e(__('Delete')); ?>"><i class="ti ti-trash text-white"></i></a>
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
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/projects/riskindex.blade.php ENDPATH**/ ?>