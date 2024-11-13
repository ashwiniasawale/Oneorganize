<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Attendance List')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Attendance')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-btn'); ?>
   <div class="float-end">
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage attendance')): ?>
        <a href="<?php echo e(route('attendance_request')); ?>"
            data-title="<?php echo e(__('Attendance Request')); ?>" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="<?php echo e(__('Attendance Request')); ?>">
           Attendance Request
        </a>
        <?php endif; ?>
   </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>


    <div class="row">
        <div class="col-sm-12">
                    <?php if(session('status')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo session('status'); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
                    <?php endif; ?>
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <?php echo e(Form::open(array('route' => array('attendanceemployee.index'),'method'=>'get','id'=>'attendanceemployee_filter'))); ?>

                        <div class="row align-items-center justify-content-end">
                            <div class="col-xl-10">
                                <div class="row">
                                    <div class="col-2">
                                        <label class="form-label"><?php echo e(__('Type')); ?></label> <br>

                                        <div class="form-check form-check-inline form-group">
                                            <input type="radio" id="monthly" value="monthly" name="type" class="form-check-input" <?php echo e(isset($_GET['type']) && $_GET['type']=='monthly' ?'checked':'checked'); ?>>
                                            <label class="form-check-label" for="monthly"><?php echo e(__('Monthly')); ?></label>
                                        </div>
                                        <!-- <div class="form-check form-check-inline form-group">
                                            <input type="radio" id="daily" value="daily" name="type" class="form-check-input" <?php echo e(isset($_GET['type']) && $_GET['type']=='daily' ?'checked':''); ?>>
                                            <label class="form-check-label" for="daily"><?php echo e(__('Daily')); ?></label>
                                        </div> -->

                                    </div>
                                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 month">
                                        <div class="btn-box">
                                            <?php echo e(Form::label('month',__('Month'),['class'=>'form-label'])); ?>

                                            <?php echo e(Form::month('month',isset($_GET['month'])?$_GET['month']:date('Y-m'),array('class'=>'month-btn form-control month-btn'))); ?>

                                        </div>
                                    </div>
                                    <!-- <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 date">
                                        <div class="btn-box">
                                            <?php echo e(Form::label('date', __('Date'),['class'=>'form-label'])); ?>

                                            <?php echo e(Form::date('date',isset($_GET['date'])?$_GET['date']:'', array('class' => 'form-control month-btn'))); ?>

                                        </div>
                                    </div> -->
                                    <?php if(\Auth::user()->type != 'Employee'): ?>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                            <div class="btn-box">
                                                <?php echo e(Form::label('employee', __('Employee'),['class'=>'form-label'])); ?>

                                                
                                           <select name="employee" id="employee" class="form-control select2">
                                              <?php foreach($employee as $employee)
                                              { 
                                                if(isset($_GET['employee']))
                                                {
                                                    if($_GET['employee']==$employee->id)
                                                    {
                                                        $sel='selected=selected';
                                                    }else{
                                                        $sel='';
                                                    }
                                                }else{
                                                    $sel='';
                                                }
                                            
                                                ?>
                                              <option <?php echo $sel; ?> value="<?php echo $employee->id; ?>"><?php echo $employee->name; ?></option>
                                              <?php } ?>
                                           </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12">
                                            <div class="btn-box">
                                                <?php echo e(Form::label('branch', __('Branch'),['class'=>'form-label'])); ?>

                                                <?php echo e(Form::select('branch', $branch,isset($_GET['branch'])?$_GET['branch']:'', array('class' => 'form-control select2'))); ?>

                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                            <div class="btn-box">
                                                <?php echo e(Form::label('department', __('Department'),['class'=>'form-label'])); ?>

                                                <?php echo e(Form::select('department', $department,isset($_GET['department'])?$_GET['department']:'', array('class' => 'form-control select2'))); ?>

                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-auto mt-4">
                                <div class="row">
                                    <div class="col-auto">
                                        <a href="#" class="btn btn-sm btn-primary" onclick="document.getElementById('attendanceemployee_filter').submit(); return false;" data-bs-toggle="tooltip" title="<?php echo e(__('Apply')); ?>" data-original-title="<?php echo e(__('apply')); ?>">
                                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                        </a>
                                        <a href="<?php echo e(route('attendanceemployee.index')); ?>" class="btn btn-sm btn-danger " data-bs-toggle="tooltip"  title="<?php echo e(__('Reset')); ?>" data-original-title="<?php echo e(__('Reset')); ?>">
                                            <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                                        </a>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create attendance')): ?>
                                        <a href="#" data-size="md"  data-bs-toggle="tooltip" title="<?php echo e(__('Import')); ?>" data-url="<?php echo e(route('attendance.file.import')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Import employee CSV file')); ?>" class="btn btn-sm btn-primary">
                                            <i class="ti ti-file-import"></i>
                                        </a>
                                       
                                        <a data-url="<?php echo e(route('attendanceemployee.create')); ?>" data-size="lg" class="btn btn-sm btn-danger " data-bs-toggle="tooltip"  title="<?php echo e(__('Mark Attendance')); ?>" data-ajax-popup="true" data-original-title="<?php echo e(__('Mark Attendance')); ?>">
                                            <span class="btn-inner--icon"><i class="ti ti-plus text-white"></i></span>
                                        </a>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable" id="att_table">
                            <thead>
                            <tr>
                                    <th><?php echo e(__('Employee')); ?></th>
                               
                                <th><?php echo e(__('Date')); ?></th>
                                <th><?php echo e(__('Status')); ?></th>
                                <th><?php echo e(__('Dashboard Clock In')); ?></th>
                                <th><?php echo e(__('Dashboard Clock Out')); ?></th>
                                <th><?php echo e(__('Biometric Clock In')); ?></th>
                                <th><?php echo e(__('Biometric Clock Out')); ?></th>
                                <th><?php echo e(__('Day Count')); ?></th>
                               
                                <?php if(Gate::check('edit attendance') || Gate::check('delete attendance')): ?>
                                    <th><?php echo e(__('Action')); ?></th>
                                <?php endif; ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $total_days=0; ?>
                            <?php $__currentLoopData = $attendanceEmployee; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php 
                          $total_days +=$attendance->day_count;
                            ?>
                                <tr>
                                   
                                    <td><?php echo e(!empty($attendance->employee)?$attendance->employee->name:''); ?></td>
                                   
                                    <td><?php echo e(\Auth::user()->dateFormat($attendance->date)); ?></td>
                                    <td><?php echo e($attendance->status); ?></td>
                                    <td><?php echo e(($attendance->clock_in !='00:00:00') ?\Auth::user()->timeFormat( $attendance->clock_in):'00:00'); ?> </td>
                                    <td><?php echo e(($attendance->clock_out !='00:00:00') ?\Auth::user()->timeFormat( $attendance->clock_out):'00:00'); ?></td>
                                    <td><?php echo e(($attendance->biometric_clock_in !='00:00:00') ?\Auth::user()->timeFormat($attendance->biometric_clock_in):'00:00'); ?></td>
                                    <td><?php echo e(($attendance->biometric_clock_out !='00:00:00') ?\Auth::user()->timeFormat($attendance->biometric_clock_out):'00:00'); ?></td>
                                    <td><?php echo e($attendance->day_count); ?></td>
                                   
                                    <?php if(Gate::check('edit attendance') || Gate::check('delete attendance')): ?>
                                        <td>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit attendance')): ?>
                                                <div class="action-btn bg-primary ms-2">
                                                    <a href="#" data-url="<?php echo e(URL::to('attendanceemployee/'.$attendance->id.'/edit')); ?>" data-size="lg" data-ajax-popup="true" data-title="<?php echo e(__('Edit Attendance')); ?>" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="<?php echo e(__('Edit')); ?>" data-original-title="<?php echo e(__('Edit')); ?>">
                                                        <i class="ti ti-pencil text-white"></i></a>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete attendance')): ?>
                                                <div class="action-btn bg-danger ms-2">
                                                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['attendanceemployee.destroy', $attendance->id],'id'=>'delete-form-'.$attendance->id]); ?>


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
                            <tr>
                                <td colspan="7"></td>
                                <td >Total : <?php echo $total_days; ?>/<?php echo $daysInMonth; ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
    <script>
        function load_data()
        {
           
            $("#att_table").load(" #att_table");
        }
  
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/attendance/index.blade.php ENDPATH**/ ?>