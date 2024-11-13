@extends('layouts.admin')
@section('page-title')
    {{__('Dashboard')}}
@endsection
@push('script-page')
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
                data: {"_token": "{{ csrf_token() }}",'calender_type':calender_type},
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
                                timeGridDay: "{{__('Day')}}",
                                timeGridWeek: "{{__('Week')}}",
                                dayGridMonth: "{{__('Month')}}",
                               
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
                            {{--events: {!! json_encode($arrEvents) !!},--}}
                            events: data,
                            locale: '{{basename(App::getLocale())}}',
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
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('HRM')}}</li>
@endsection
@php
    $setting = \App\Models\Utility::settings();
@endphp
@section('content')
    @if(\Auth::user()->type != 'client')
   
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-xxl-6">
                        <?php if(!empty($emp))
                        { ?>
                        <div class="card">
                            <div class="card-header">
                                <h4>Welcome {{\Auth::user()->name}}</h4>
                            </div>
                            <div class="card-body dash-card-body">
                                <h5>{{\Auth::user()->name}}</h5>
                                <p><?php if(!empty($emp->designation)){ ?>{{$emp->designation->name}} <?php } ?></p>
                                <p class="text-muted">{{__('Employee Id: '.$settings['employee_prefix'].''.$emp['employee_id'])}}</p>
                                <p class="text-muted ">{{__('My Office Time: '.$officeTime['startTime'].' to '.$officeTime['endTime'])}}</p>
                             
                                <center>
                                    <div class="row" id="dash_log">
                                        <div class="col-md-6" >
                                            
                                            {{Form::open(array('url'=>'attendanceemployee/attendance','method'=>'post','id'=>'create_dash_att'))}}
                                            @if(empty($employeeAttendance) || $employeeAttendance->clock_in == '00:00:00')
                                                <button type="submit"  value="0" name="in" id="clock_in" class="btn btn-success ">{{__('CLOCK IN')}}</button>
                                            @else
                                                <button type="submit" value="0" name="in" id="clock_in" class="btn btn-success disabled" disabled>{{__('CLOCK IN')}}</button>
                                            @endif
                                            {{Form::close()}}
                                            <?php if(!empty($employeeAttendance))
                                            { ?>
                                            Clock In At- {{ ($employeeAttendance->clock_in !='00:00:00') ?\Auth::user()->timeFormat( $employeeAttendance->clock_in):'00:00' }} 
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-6 ">
                                            @if(!empty($employeeAttendance) && $employeeAttendance->clock_out == '00:00:00')
                                                {{ Form::open([
                                                    'url' => ['attendanceemployee/attendance_update', $employeeAttendance->id],
                                                    'method' => 'post',
                                                    'id' => 'update_dash_att'
                                                ]) }}
                                                <button type="submit" value="1" name="out" id="clock_out" class="btn btn-danger">{{__('CLOCK OUT')}}</button>
                                                {{Form::close()}}
                                            @else
                                                <button type="submit" value="1" name="out" id="clock_out" class="btn btn-danger disabled" disabled>{{__('CLOCK OUT')}}</button>
                                            @endif
                                            <br>
                                            <?php if(!empty($employeeAttendance))
                                            { ?>
                                            Clock Out At- {{ ($employeeAttendance->clock_out !='00:00:00') ?\Auth::user()->timeFormat( $employeeAttendance->clock_out):'00:00' }} 
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
                                        <h5>{{ __('Birthdays') }}</h5>
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="">
                            <table class="table  mb-0">
                                       
                                       <tbody>
                                       @forelse($birthday as $birthday)
                                           <tr>
                                               <td>{{ $birthday->name }}</td>
                                               <td><i class="ti ti-gift"></i>{{ \Carbon\Carbon::parse($birthday->dob)->format('d M') }}</td>
                                             
                                           </tr>
                                       @empty
                                           <tr>
                                               <td colspan="4">
                                                   <div class="text-center">
                                                       <h6>{{__('There is no Birthdays')}}</h6>
                                                   </div>
                                               </td>
                                           </tr>
                                       @endforelse
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
                                        @forelse($joining_date as $joining_date)
                                        <tr>
                                            <td>{{$joining_date->name}}<br>
                                        <small class="text-muted">{{$joining_date->designation->name}}</small></td>
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
                                         <span class="badge bg-info"> Completed {{$years}} year</span>
                                        <?php } ?>
                                        </td>
                                        </tr>
                                        @empty
                                        <tr>
                                        <td colspan="3">
                                            <div class="text-center">
                                                <h6>{{__('There is no record.')}}</h6>
                                            </div>
                                        </td>
                                        </tr>
                                        @endforelse
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
                                        @forelse($notice_period as $notice_period)
                                        <tr>
                                            <td>{{$notice_period->name}}<br>
                                               
                                            </td>
                                            <td>
                                                <small>Last Working Day</small>
                                                <span class="btn btn-danger">{{\Carbon\Carbon::parse($notice_period->resignation_date)->format('d M')}} </span>
                                            </td>

                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="2">
                                                <div class="text-center">
                                                <h6>{{__('There is no record.')}}</h6>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
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
                                        @forelse($probation_date as $probation_date)
                                        <?php
                                         $date = new DateTime($probation_date->company_doj); // Example date
                                        $date->add(new DateInterval('P3M')); // Add 3 months
                                        $probation=$date->format('Y-m-d');?>
                                        <tr>
                                            <td>{{$probation_date->name}}<br>
                                                <small>{{$probation_date->designation->name}}</small>
                                            </td>
                                            <td >{{\Carbon\Carbon::parse($probation)->format('d M Y')}}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="2">
                                                <div class="text-center">
                                                <h6>{{__('There is no record.')}}</h6>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
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
                                        <h5>{{ __('On Leave Today') }}</h5>
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="">
                            <table class="table  mb-0">
                                       
                                      <tbody>
                                        @forelse($todays_leave as $todays_leave)
                                        <tr>
                                            <td>{{$todays_leave->name}}
                                            </td>
                                            <td><span class="btn btn-dark">{{$todays_leave->duration}}</span>
                                            <?php if(\Auth::user()->type=='company' || \Auth::user()->type=='HR')
                                             { ?>
                                            <td><span class="btn btn-success">{{$todays_leave->leave_type}}</span>
                                            </td>
                                            <?php } ?>
                                        </tr>
                                        @empty
                                        <tr>
                                        <td colspan="3">
                                                   <div class="text-center">
                                                       <h6>{{__('There is no Leave')}}</h6>
                                                   </div>
                                               </td>
                                        </tr>
                                        @endforelse
                                      </tbody>
                            </table>
                            </div>
                        </div>
                        <div class="card list_card">
                            <div class="card-header">
                                <h4>{{__('Announcement List')}}</h4>
                            </div>
                            <div class="card-body dash-card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                        <tr>
                                            <th>{{__('Title')}}</th>
                                            <th>{{__('Start Date')}}</th>
                                            <th>{{__('End Date')}}</th>
                                            <th>{{__('description')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($announcements as $announcement)
                                            <tr>
                                                <td>{{ $announcement->title }}</td>
                                                <td>{{ \Auth::user()->dateFormat($announcement->start_date)  }}</td>
                                                <td>{{ \Auth::user()->dateFormat($announcement->end_date) }}</td>
                                                <td>{{ $announcement->description }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">
                                                    <div class="text-center">
                                                        <h6>{{__('There is no Announcement List')}}</h6>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card list_card">
                            <div class="card-header">
                                <h4>{{__('Meeting List')}}</h4>
                            </div>
                            <div class="card-body dash-card-body">
                                @if(count($meetings) > 0)
                                    <div class="table-responsive">
                                        <table class="table align-items-center">
                                            <thead>
                                            <tr>
                                                <th>{{__('Meeting title')}}</th>
                                                <th>{{__('Meeting Date')}}</th>
                                                <th>{{__('Meeting Time')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($meetings as $meeting)
                                                <tr>
                                                    <td>{{ $meeting->title }}</td>
                                                    <td>{{ \Auth::user()->dateFormat($meeting->date) }}</td>
                                                    <td>{{ \Auth::user()->timeFormat($meeting->time) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="p-2">
                                        {{__('No meeting scheduled yet.')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   
    @endif
@endsection


