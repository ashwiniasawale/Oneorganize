<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Dashboard')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
    <script>
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
                url: $("#event_dashboard").val()+"/event/get_event_data" ,
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
                                dayGridMonth: "<?php echo e(__('Month')); ?>",
                               
                            },
                            slotLabelFormat: {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: false,
                            },
                            themeSystem: 'bootstrap',
                            navLinks: true,
                            droppable: true,
                            selectable: true,
                            selectMirror: true,
                            editable: true,
                            dayMaxEvents: true,
                            handleWindowResize: true,
                            height: 'auto',
                            timeFormat: 'H(:mm)',
                            
                            events: data,
                            locale: '<?php echo e(basename(App::getLocale())); ?>',
                            dayClick: function (e) {
                                var t = moment(e).toISOString();
                                $("#new-event").modal("show"), $(".new-event--title").val(""), $(".new-event--start").val(t), $(".new-event--end").val(t)
                            },
                            eventResize: function (event) {
                                var eventObj = {
                                    start: event.start.format(),
                                    end: event.end.format(),
                                };
                            },
                            viewRender: function (t) {
                                e.fullCalendar("getDate").month(), $(".fullcalendar-title").html(t.title)
                            },
                            eventClick: function (e, t) {
                                var title = e.title;
                                var url = e.url;

                                if (typeof url != 'undefined') {
                                    $("#commonModal .modal-title").html(title);
                                    $("#commonModal .modal-dialog").addClass('modal-md');
                                    $("#commonModal").modal('show');
                                    $.get(url, {}, function (data) {
                                        $('#commonModal .modal-body').html(data);
                                    });
                                    return false;
                                }
                            }
                        });
                        calendar.render();
                    })();
                }
            });
        }
    </script>
    
<script>
  
  $('#create_dash_att').on('submit', function (e) {
      e.preventDefault(); // Prevent the default form submission

      // Gather form data
      var formData = $(this).serialize();

      $.ajax({
          type: $(this).attr('method'), // Get the HTTP method (POST or GET)
          url: $(this).attr('action'), // Get the form's action attribute value
          data: formData, // Set the form data
          success: function (response) {
              // Handle the success response from the server
             console.log(response); // Log the response to the console
              
             if(response.success)
            {
              show_toastr('Success', response.success, 'success');
              $("#dash_log").load(" #dash_log");
            }else{
              show_toastr('Error', response.error, 'error');
            }
              $('#commonModal').modal('hide');
              
              // You can perform any further actions based on the response
          },
          error: function (xhr, status, error) {
              // Handle errors
              console.error(error); // Log the error to the console
          }
      });
  });

$('#update_dash_att').on('submit',function(e)
{
    e.preventDefault();
    var formData=$(this).serialize();
    $.ajax({
        type:$(this).attr('method'),
        url:$(this).attr('action'),
        data:formData,
        success:function(response)
        {
            if(response.success)
            {
                show_toastr('Success',response.success,'success');
                $("#dash_log").load(" #dash_log");
            }else{
                show_toastr('Error',response.error,'error');
            }
            
        },error:function(xhr,status,error)
        {
            console.error(error);
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('HRM')); ?></li>
<?php $__env->stopSection(); ?>
<?php
    $setting = \App\Models\Utility::settings();
?>
<?php $__env->startSection('content'); ?>
    <?php if(\Auth::user()->type != 'client'): ?>
   
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-xxl-6">
                        <?php if(!empty($emp))
                        { ?>
                        <div class="card">
                            <div class="card-header">
                                <h4>Welcome <?php echo e(\Auth::user()->name); ?></h4>
                            </div>
                            <div class="card-body dash-card-body">
                                <h5><?php echo e(\Auth::user()->name); ?></h5>
                                <p><?php if(!empty($emp->designation)){ ?><?php echo e($emp->designation->name); ?> <?php } ?></p>
                                <p class="text-muted"><?php echo e(__('Employee Id: '.$settings['employee_prefix'].''.$emp['employee_id'])); ?></p>
                                <p class="text-muted "><?php echo e(__('My Office Time: '.$officeTime['startTime'].' to '.$officeTime['endTime'])); ?></p>
                             
                                <center>
                                    <div class="row" id="dash_log">
                                        <div class="col-md-6" >
                                            
                                            <?php echo e(Form::open(array('url'=>'attendanceemployee/attendance','method'=>'post','id'=>'create_dash_att'))); ?>

                                            <?php if(empty($employeeAttendance) || $employeeAttendance->clock_in == '00:00:00'): ?>
                                                <button type="submit"  value="0" name="in" id="clock_in" class="btn btn-success "><?php echo e(__('CLOCK IN')); ?></button>
                                            <?php else: ?>
                                                <button type="submit" value="0" name="in" id="clock_in" class="btn btn-success disabled" disabled><?php echo e(__('CLOCK IN')); ?></button>
                                            <?php endif; ?>
                                            <?php echo e(Form::close()); ?>

                                            <?php if(!empty($employeeAttendance))
                                            { ?>
                                            Clock In At- <?php echo e(($employeeAttendance->clock_in !='00:00:00') ?\Auth::user()->timeFormat( $employeeAttendance->clock_in):'00:00'); ?> 
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-6 ">
                                            <?php if(!empty($employeeAttendance) && $employeeAttendance->clock_out == '00:00:00'): ?>
                                                <?php echo e(Form::open([
                                                    'url' => ['attendanceemployee/attendance_update', $employeeAttendance->id],
                                                    'method' => 'post',
                                                    'id' => 'update_dash_att'
                                                ])); ?>

                                                <button type="submit" value="1" name="out" id="clock_out" class="btn btn-danger"><?php echo e(__('CLOCK OUT')); ?></button>
                                                <?php echo e(Form::close()); ?>

                                            <?php else: ?>
                                                <button type="submit" value="1" name="out" id="clock_out" class="btn btn-danger disabled" disabled><?php echo e(__('CLOCK OUT')); ?></button>
                                            <?php endif; ?>
                                            <br>
                                            <?php if(!empty($employeeAttendance))
                                            { ?>
                                            Clock Out At- <?php echo e(($employeeAttendance->clock_out !='00:00:00') ?\Auth::user()->timeFormat( $employeeAttendance->clock_out):'00:00'); ?> 
                                            <?php } ?>
                                          
                                        </div>
                                    </div>
                                </center>

                            </div>
                        </div>
                        <?php } ?>
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h5><?php echo e(__('Birthdays')); ?></h5>
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="">
                            <table class="table  mb-0">
                                       
                                       <tbody>
                                       <?php $__empty_1 = true; $__currentLoopData = $birthday; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $birthday): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                           <tr>
                                               <td><?php echo e($birthday->name); ?></td>
                                               <td><i class="ti ti-gift"></i><?php echo e(\Carbon\Carbon::parse($birthday->dob)->format('d M')); ?></td>
                                             
                                           </tr>
                                       <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                           <tr>
                                               <td colspan="4">
                                                   <div class="text-center">
                                                       <h6><?php echo e(__('There is no Birthdays')); ?></h6>
                                                   </div>
                                               </td>
                                           </tr>
                                       <?php endif; ?>
                                       </tbody>
                            </table>
                            </div>
                        </div>

                      

                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h5>Today's Joinings & Work Anniversary</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                            <table class="table mb-0">
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $joining_date; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $joining_date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($joining_date->name); ?><br>
                                        <small class="text-muted"><?php echo e($joining_date->designation->name); ?></small></td>
                                        <td>
                                            <?php if($joining_date->company_doj==date('Y-m-d'))
                                            { ?>
                                       <span class="badge  bg-info"> Joined Today</span>
                                       <?php }else{
                                        $date1 = new DateTime($joining_date->company_doj);
                                        $date2 = new DateTime(date('Y-m-d')); // or any other date
                                        $interval = $date1->diff($date2);
                                        $years = $interval->y; 
                                        ?>
                                         <span class="badge bg-info"> Completed <?php echo e($years); ?> year</span>
                                        <?php } ?>
                                        </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                        <td colspan="3">
                                            <div class="text-center">
                                                <h6><?php echo e(__('There is no record.')); ?></h6>
                                            </div>
                                        </td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                            </table>
                            </div>
                        </div>
                        <?php if(\Auth::user()->type=='company' || \Auth::user()->type=='HR')
                        { ?>
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                <div class="col-lg-6">
                                        <h5>Notice Period Duration</h5>
                                </div>
                                </div>
                            </div>
                            <div class="">
                                <table class="table mb-0">
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $notice_period; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notice_period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($notice_period->name); ?><br>
                                               
                                            </td>
                                            <td>
                                                <small>Last Working Day</small>
                                                <span class="btn btn-danger"><?php echo e(\Carbon\Carbon::parse($notice_period->resignation_date)->format('d M')); ?> </span>
                                            </td>

                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="2">
                                                <div class="text-center">
                                                <h6><?php echo e(__('There is no record.')); ?></h6>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                <div class="col-lg-6">
                                    <h5>Probation Date</h5>
                                </div>
                                </div>
                            </div>
                            <div class="">
                                <table class="table mb-0">
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $probation_date; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $probation_date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php
                                         $date = new DateTime($probation_date->company_doj); // Example date
                                        $date->add(new DateInterval('P3M')); // Add 3 months
                                        $probation=$date->format('Y-m-d');?>
                                        <tr>
                                            <td><?php echo e($probation_date->name); ?><br>
                                                <small><?php echo e($probation_date->designation->name); ?></small>
                                            </td>
                                            <td ><?php echo e(\Carbon\Carbon::parse($probation)->format('d M Y')); ?></td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="2">
                                                <div class="text-center">
                                                <h6><?php echo e(__('There is no record.')); ?></h6>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php } ?>

                        
                    </div>
                    <div class="col-xxl-6">
                    <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h5><?php echo e(__('On Leave Today')); ?></h5>
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="">
                            <table class="table  mb-0">
                                       
                                      <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $todays_leave; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $todays_leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($todays_leave->name); ?>

                                            </td>
                                            <td><span class="btn btn-dark"><?php echo e($todays_leave->duration); ?></span>
                                            <?php if(\Auth::user()->type=='company' || \Auth::user()->type=='HR')
                                             { ?>
                                            <td><span class="btn btn-success"><?php echo e($todays_leave->leave_type); ?></span>
                                            </td>
                                            <?php } ?>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                        <td colspan="3">
                                                   <div class="text-center">
                                                       <h6><?php echo e(__('There is no Leave')); ?></h6>
                                                   </div>
                                               </td>
                                        </tr>
                                        <?php endif; ?>
                                      </tbody>
                            </table>
                            </div>
                        </div>
                        <div class="card list_card">
                            <div class="card-header">
                                <h4><?php echo e(__('Announcement List')); ?></h4>
                            </div>
                            <div class="card-body dash-card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                        <tr>
                                            <th><?php echo e(__('Title')); ?></th>
                                            <th><?php echo e(__('Start Date')); ?></th>
                                            <th><?php echo e(__('End Date')); ?></th>
                                            <th><?php echo e(__('description')); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td><?php echo e($announcement->title); ?></td>
                                                <td><?php echo e(\Auth::user()->dateFormat($announcement->start_date)); ?></td>
                                                <td><?php echo e(\Auth::user()->dateFormat($announcement->end_date)); ?></td>
                                                <td><?php echo e($announcement->description); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="4">
                                                    <div class="text-center">
                                                        <h6><?php echo e(__('There is no Announcement List')); ?></h6>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card list_card">
                            <div class="card-header">
                                <h4><?php echo e(__('Meeting List')); ?></h4>
                            </div>
                            <div class="card-body dash-card-body">
                                <?php if(count($meetings) > 0): ?>
                                    <div class="table-responsive">
                                        <table class="table align-items-center">
                                            <thead>
                                            <tr>
                                                <th><?php echo e(__('Meeting title')); ?></th>
                                                <th><?php echo e(__('Meeting Date')); ?></th>
                                                <th><?php echo e(__('Meeting Time')); ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $__empty_1 = true; $__currentLoopData = $meetings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td><?php echo e($meeting->title); ?></td>
                                                    <td><?php echo e(\Auth::user()->dateFormat($meeting->date)); ?></td>
                                                    <td><?php echo e(\Auth::user()->timeFormat($meeting->time)); ?></td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="p-2">
                                        <?php echo e(__('No meeting scheduled yet.')); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   
    <?php endif; ?>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/dashboard/dashboard.blade.php ENDPATH**/ ?>