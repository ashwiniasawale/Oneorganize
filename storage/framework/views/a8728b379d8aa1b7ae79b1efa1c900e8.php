<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Holiday')); ?>

<?php $__env->stopSection(); ?>
<?php
    $setting = Utility::settings();
?>
<?php $__env->startPush('css-page'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/libs/fullcalendar/dist/fullcalendar.min.css')); ?>">
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Holiday')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-btn'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create holiday')): ?>
        <div class="float-end">
            <a href="<?php echo e(route('holiday.index')); ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="<?php echo e(__('List View')); ?>" data-original-title="<?php echo e(__('List View')); ?>">
                <i class="ti ti-list"></i>
            </a>
            <a href="#" data-size="lg" data-url="<?php echo e(route('holiday.create')); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" title="<?php echo e(__('Create')); ?>" data-title="<?php echo e(__('Create New Holiday')); ?>" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="mt-2" id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <?php echo e(Form::open(array('route' => array('holiday.calender'),'method'=>'get','id'=>'holiday_filter'))); ?>

                            <div class="row align-items-center justify-content-end">
                                <div class="col-xl-10">
                                    <div class="row">
                                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                            <div class="btn-box"></div>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                            <div class="btn-box"></div>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                            <div class="btn-box">
                                                <?php echo e(Form::label('start_date',__('Start Date'),['class'=>'form-label'])); ?>

                                                <?php echo e(Form::date('start_date',isset($_GET['start_date'])?$_GET['start_date']:'',array('class'=>'month-btn form-control'))); ?>

                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                            <div class="btn-box">
                                                <?php echo e(Form::label('end_date',__('End Date'),['class'=>'form-label'])); ?>

                                                <?php echo e(Form::date('end_date',isset($_GET['end_date'])?$_GET['end_date']:'',array('class'=>'month-btn form-control '))); ?>                                        </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="row">
                                            <div class="col-auto mt-4">
                                                <a href="#" class="btn btn-sm btn-primary" onclick="document.getElementById('holiday_filter').submit(); return false;" data-bs-toggle="tooltip" title="<?php echo e(__('Apply')); ?>" data-original-title="<?php echo e(__('apply')); ?>">
                                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                                </a>
                                                <a href="<?php echo e(route('holiday.calender')); ?>" class="btn btn-sm btn-danger " data-bs-toggle="tooltip"  title="<?php echo e(__('Reset')); ?>" data-original-title="<?php echo e(__('Reset')); ?>">
                                                    <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                                                </a>
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
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-6">
                            <h5><?php echo e(__('Calendar')); ?></h5>
                        </div>
                        <div class="col-lg-6">
                            <?php if(isset($setting['google_calendar_enable']) && $setting['google_calendar_enable'] == 'on'): ?>
                                <select class="form-control" name="calender_type" id="calender_type" onchange="get_data()">
                                    <option value="goggle_calender"><?php echo e(__('Google Calender')); ?></option>
                                    <option value="local_calender" selected="true"><?php echo e(__('Local Calender')); ?></option>
                                </select>
                            <?php endif; ?>
                            <input type="hidden" id="holiday_calendar" value="<?php echo e(url('/')); ?>">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id='calendar' class='calendar'></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4"><?php echo e(__('Holiday List')); ?></h4>
                    <ul class="event-cards list-group list-group-flush mt-3 w-100">
                        <li class="list-group-item card mb-3">
                            <div class="row align-items-center justify-content-between">
                                <div class=" align-items-center">
                                    <?php if(!$holidays->isEmpty()): ?>
                                        <?php $__currentLoopData = $holidays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $holiday): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="card mb-3 border shadow-none">
                                                <div class="px-3">
                                                    <div class="row align-items-center">
                                                        <div class="col ml-n2">
                                                            <h5 class="text-sm mb-0">
                                                            </h5>
                                                            <p class="card-text small text-primary">
                                                                <?php echo e(($holiday->occasion)); ?>

                                                            </p>
                                                            <p class="card-text small text-dark">
                                                                <?php echo e(__('Start Date :')); ?>

                                                                <?php echo e(\Auth::user()->dateFormat($holiday->date)); ?><br>
                                                                <?php echo e(__('End Date :')); ?>

                                                                <?php echo e(\Auth::user()->dateFormat($holiday->end_date)); ?>

                                                            </p>
                                                        </div>
                                                        <div class="col-auto text-right">
                                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit interview schedule')): ?>
                                                                <div class="action-btn bg-primary ms-2">
                                                                    <a href="#" data-url="<?php echo e(route('holiday.edit',$holiday->id)); ?>" data-title="<?php echo e(__('Edit Interview Schedule')); ?>" data-ajax-popup="true" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="<?php echo e(__('Edit')); ?>" data-original-title="<?php echo e(__('Edit')); ?>"><i class="ti ti-pencil text-white"></i></a>
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete interview schedule')): ?>
                                                                    <div class="action-btn bg-danger ms-2">
                                                                        <?php echo Form::open(['method' => 'DELETE', 'route' => ['holiday.destroy', $holiday->id],'id'=>'delete-form-'.$holiday->id]); ?>

                                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="<?php echo e(__('Delete')); ?>" data-original-title="<?php echo e(__('Delete')); ?>" data-confirm="<?php echo e(__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="document.getElementById('delete-form-<?php echo e($holiday->id); ?>').submit();"><i class="ti ti-trash text-white"></i></a>
                                                                        <?php echo Form::close(); ?>

                                                                    </div>
                                                                <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <div class="text-center">
                                            <?php echo e(__('No Interview Scheduled!')); ?>

                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
    <script src="<?php echo e(asset('assets/js/plugins/main.min.js')); ?>"></script>

    <script type="text/javascript">
        $(document).ready(function()
        {
            get_data();
        });
        function get_data()
        {
            var calender_type=$('#calender_type :selected').val();
            $('#calendar').removeClass('local_calender');
            $('#calendar').removeClass('goggle_calender');
            if(calender_type==undefined){
                $('#calendar').addClass('local_calender');
            }
            $('#calendar').addClass(calender_type);
            $.ajax({
                url: $("#holiday_calendar").val()+"/holiday/get_holiday_data" ,
                method:"POST",
                data: {"_token": "<?php echo e(csrf_token()); ?>",'calender_type':calender_type},
                success: function(data) {
                    (function () {
                    var etitle;
                    var etype;
                    var etypeclass;
                    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'timeGridDay,timeGridWeek,dayGridMonth'
                        },
                        buttonText: {
                            timeGridDay: "<?php echo e(__('Day')); ?>",
                            timeGridWeek: "<?php echo e(__('Week')); ?>",
                            dayGridMonth: "<?php echo e(__('Month')); ?>"
                        },
                        themeSystem: 'bootstrap',
                        initialDate: '<?php echo e($transdate); ?>',
                        slotDuration: '00:10:00',
                        navLinks: true,
                        droppable: true,
                        selectable: true,
                        selectMirror: true,
                        editable: true,
                        dayMaxEvents: true,
                        handleWindowResize: true,
                        events: data,
                    });
                    calendar.render();
                })();
                }
            });
        }
    </script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/holiday/calender.blade.php ENDPATH**/ ?>