
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="row">
                    <?php if(count($tasks) > 0): ?>
                        <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-3 col-lg-3 col-sm-3">
                                <div class="card m-3 card-progress border shadow-none" id="<?php echo e($task->id); ?>" style="<?php echo e(!empty($task->priority_color) ? 'border-left: 2px solid '.$task->priority_color.' !important' :''); ?>;">
                                    <div class="card-body">
                                        <div class="row align-items-center mb-2">
                                            <span><?php echo e($task->stage->name); ?></span>
                                            <div class="col-6">
                                                <span class="badge p-2 px-3 rounded bg-<?php echo e(\App\Models\ProjectTask::$priority_color[$task->priority]); ?>"><?php echo e(\App\Models\ProjectTask::$priority[$task->priority]); ?></span>
                                            </div>
                                            <div class="col-6 text-end">
                                                <?php if(str_replace('%','',$task->taskProgress($task)['percentage']) > 0): ?>
                                                    <span class="text-sm"><?php echo e($task->taskProgress($task)['percentage']); ?></span>
                                                    <div class="progress" style="top:0px">
                                                        <div class="progress-bar bg-<?php echo e($task->taskProgress($task)['color']); ?>" role="progressbar"
                                                             style="width: <?php echo e($task->taskProgress($task)['percentage']); ?>;"></div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <a class="h6 task-name-break" href="<?php echo e(route('projects.tasks.index',!empty($task->project)?$task->project->id:'')); ?>"><?php echo e($task->name); ?></a>
                                        <div class="row align-items-center">
                                            <div class="col-12">
                                                <div class="actions d-flex justify-content-between mt-2 mb-2">
                                                    <?php if(count($task->taskFiles) > 0): ?>
                                                        <div class="action-item mr-2"><i class="ti ti-paperclip mr-2"></i><?php echo e(count($task->taskFiles)); ?></div><?php endif; ?>
                                                    <?php if(count($task->comments) > 0): ?>
                                                        <div class="action-item mr-2"><i class="ti ti-brand-hipchat mr-2"></i><?php echo e(count($task->comments)); ?></div><?php endif; ?>
                                                    <?php if($task->checklist->count() > 0): ?>
                                                        <div class="action-item mr-2"><i class="ti ti-list-check mr-2"></i><?php echo e($task->countTaskChecklist()); ?></div><?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-6"><?php if(!empty($task->end_date) && $task->end_date != '0000-00-00'): ?><small <?php if(strtotime($task->end_date) < time()): ?>class="text-danger"<?php endif; ?>><?php echo e(Utility::getDateFormated($task->end_date)); ?></small><?php endif; ?></div>
                                            <div class="col-6 text-end">
                                                <?php if($users = $task->users()): ?>
                                                    <div class="avatar-group">
                                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if($key<3): ?>
                                                                <a href="#" class="avatar rounded-circle avatar-sm">
                                                                    <img class="hweb" data-original-title="<?php echo e((!empty($user)?$user->name:'')); ?>" <?php if($user->avatar): ?> src="<?php echo e(asset('/storage/uploads/avatar/'.$user->avatar)); ?>" <?php else: ?> src="<?php echo e(asset('/storage/uploads/avatar/avatar.png')); ?>" <?php endif; ?> >
                                                                </a>
                                                            <?php else: ?>
                                                                <?php break; ?>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if(count($users) > 3): ?>
                                                            <a href="#" class="avatar rounded-circle avatar-sm">
                                                                <img class="hweb" data-original-title="<?php echo e((!empty($user)?$user->name:'')); ?>" <?php if($user->avatar): ?> src="<?php echo e(asset('/storage/uploads/avatar/'.$user->avatar)); ?>" <?php else: ?> src="<?php echo e(asset('/storage/uploads/avatar/avatar.png')); ?>" <?php endif; ?> avatar="+ <?php echo e(count($users)-3); ?>">
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="col-md-12">
                            <h6 class="text-center m-3"><?php echo e(__('No tasks found')); ?></h6>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/project_task/grid.blade.php ENDPATH**/ ?>