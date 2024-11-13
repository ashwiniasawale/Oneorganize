@extends('layouts.admin')

@section('page-title')
    {{$project->project_name.__("'s Timesheet")}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('projects.index')}}">{{__('Project')}}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('projects.show',$project->id) }}">{{ucwords($project->project_name)}}</a></li>
    <li class="breadcrumb-item">{{__('Timesheet')}}</li>
@endsection

@section('action-btn')
    <div class="row gy-3 justify-content-end align-items-center">
       
        @can('create timesheet')
            <div class="col-auto project_tasks_select text-end">
                <div class="dropdown btn btn-sm p-0">
                   
                    <a href="#" data-size="lg" data-url="{{ route('timesheet.create',$project->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Add Task on Timesheet')}}" class="btn btn-sm btn-primary">
                        <i class="ti ti-plus"></i>
                    </a>
            
                    <a href="{{ route('projects.show',$project->id) }}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="" data-bs-original-title="Back">
                        <i class="ti ti-arrow-left"></i>
                    </a>
                   
                </div>
            </div>
        @endcan
    </div>
@endsection


@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th> {{__('Task Name')}}</th>
                                <th> {{__('Estimated Hours')}}</th>
                                <th> {{__('Actual Hours')}}</th>
                                <th> {{__('Description')}}</th>
                                
                                <th> {{__('Created By')}}</th>
                                <th width="10%"> {{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($timesheets as $timesheets)
                                <tr>
                                  <td>{{$timesheets->name}}</td>
                                  <td>{{$timesheets->estimated_hrs}}</td>
                                  <td>{{$timesheets->actual_hours}}</td>
                                  <td>{{$timesheets->description}}</td>
                                  <td>{{ $timesheets->createdBy->name }}</td>
                                  <td class="Action" width="10%">
                                        @can('edit timesheet')
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" data-size="lg" class="mx-3 btn btn-sm  align-items-center" data-url="{{ route('timesheet.edit',[$project->id,$timesheets->id]) }}" data-ajax-popup="true" data-size="xl" title="{{__('Edit')}}" data-title="{{__('Edit Timesheet')}}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                        @endcan
                                        @can('delete timesheet')
                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['timesheet.destroy', $project->id,$timesheets->id]]) !!}
                                                <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" title="{{__('Delete')}}"><i class="ti ti-trash text-white"></i></a>
                                                {!! Form::close() !!}
                                            </div>
                                        @endcan
                                     
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script-page')
    <script>
        function ajaxFilterTimesheetTableView() {
            var mainEle = $('.project-timesheet');
            var notfound = $('.notfound-timesheet');
            var week = parseInt($('#weeknumber').val());
            var project_id = '{{ $project->id }}';
            var isowner = '';
            var data = {
                week: week,
                project_id: project_id,
            }

            $.ajax({
                url: '{{ route('filter.timesheet.table.view') }}',
                data: data,
                success: function (data) {
                    console.log(data)

                    $('.weekly-dates-div .weekly-dates').text(data.onewWeekDate);
                    $('.weekly-dates-div #selected_dates').val(data.selectedDate);

                    $('.project_tasks_select .tasks-list .dropdown-item').remove();

                    $.each(data.sectiontasks, function (i, item) {

                        var optionhtml = '';

                        if (item.section_id != 0 && item.section_name != '' && item.tasks.length > 0) {
                            optionhtml += `<a href="#" class="dropdown-item select-sub-heading" data-tasks-count="` + item.tasks.length + `">` + item.section_name + `</a>`;
                        }
                        $.each(item.tasks, function (ji, jitem) {
                            optionhtml += `<a href="#" class="dropdown-item select-task" data-task-id="` + jitem.task_id + `">` + jitem.task_name + `</a>`;
                        });
                        $('.project_tasks_select .tasks-list').append(optionhtml);
                    });

                    if (data.totalrecords == 0) {
                        mainEle.hide();
                        notfound.css('display', 'block');
                    } else {
                        notfound.hide();
                        mainEle.show();
                    }
                    mainEle.html(data.html);
                }
            });
        }

        $(function () {
            ajaxFilterTimesheetTableView();
        });

        $(document).on('click', '.weekly-dates-div .action-item', function () {
            var weeknumber = parseInt($('#weeknumber').val());
            if ($(this).hasClass('previous')) {
                weeknumber--;
                $('#weeknumber').val(weeknumber);
            } else if ($(this).hasClass('next')) {
                weeknumber++;
                $('#weeknumber').val(weeknumber);
            }
            ajaxFilterTimesheetTableView();
        });

        $(document).on('click', '[data-ajax-timesheet-popup="true"]', function (e) {
            e.preventDefault();

            var data = {};
            var url = $(this).data('url');
            var type = $(this).data('type');
            var date = $(this).data('date');
            var task_id = $(this).data('task-id');
            var user_id = $(this).data('user-id');

            data.date = date;
            data.task_id = task_id;

            if (user_id != undefined) {
                data.user_id = user_id;
            }
            if (type == 'create') {
                var title = '{{ __("Create Timesheet") }}';
                data.project_id = '{{ $project->id }}';
            } else if (type == 'edit') {
                var title = '{{ __("Edit Timesheet") }}';
            }

            $("#commonModal .modal-title").html(title + ` <small>(` + moment(date).format("ddd, Do MMM YYYY") + `)</small>`);
            $.ajax({
                url: url,
                data: data,
                dataType: 'html',
                success: function (data) {
                    $('#commonModal .body').html(data);
                    $('#commonModal').modal('show');

                    if ($('#date').length > 0) {
                        $('#date').daterangepicker({
                            singleDatePicker: true,
                            locale: {
                                format: 'YYYY-MM-DD'
                            }
                        });
                    }

                    $('#commonModal').modal({backdrop: 'static', keyboard: false});
                }
            });
        });

        $('.project_tasks_select .tasks-box').on('click', '.select-task', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var mainEle = $('.project-timesheet');
            var notfound = $('.notfound-timesheet');

            var task_id = $(this).attr('data-task-id');
            var selected_dates = $('#selected_dates').val();

            $.ajax({
                url: '{{route('append.timesheet.task.html')}}',
                data: {
                    project_id: '{{ $project->id }}',
                    task_id: task_id,
                    selected_dates: selected_dates,
                },
                success: function (data) {
                    notfound.hide();
                    mainEle.show();
                    $('.project-timesheet .tbody').append(data.html);
                    $('.project_tasks_select .tasks-list .select-task[data-task-id="' + task_id + '"]').remove();
                }
            });
        });

        $(document).on('change', '#time_hour, #time_minute', function () {

            var hour = $('#time_hour').children("option:selected").val();
            var minute = $('#time_minute').children("option:selected").val();
            var total = $('#totaltasktime').val().split(':');

            if (hour == '00' && minute == '00') {
                $(this).val('');
                return;
            }

            hour = hour != '' ? hour : 0;
            hour = parseInt(hour) + parseInt(total[0]);

            minute = minute != '' ? minute : 0;
            minute = parseInt(minute) + parseInt(total[1]);

            if (minute > 50) {
                minute = minute - 60;
                hour++;
            }

            hour = hour < 10 ? '0' + hour : hour;
            minute = minute < 10 ? '0' + minute : minute;

            $('.display-total-time small').text('{{ __("Total Time worked on this task") }} : ' + hour + ' {{ __("Hours") }} ' + minute + ' {{ __("Minutes") }}');
        });


    </script>
@endpush


