@extends('layouts.admin')
@section('page-title')
{{__('Manage Test')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item"><a href="{{route('projects.index')}}">{{__('Project')}}</a></li>
<li class="breadcrumb-item"><a href="{{route('projects.show',$project->id)}}">{{ucwords($project->project_name)}}</a></li>
<li class="breadcrumb-item">{{__('Test Report')}}</li>
@endsection
@section('action-btn')
<div class="float-end">
    @can('manage bug report')
            <a href="{{ route('projects.test.kanban',$project->id) }}" data-bs-toggle="tooltip" title="{{__('Kanban')}}" class="btn btn-sm btn-primary">
                <i class="ti ti-grid-dots"></i>
            </a>
        @endcan
    @can('create bug report')
    <a href="#" data-size="lg" data-url="{{ route('projects.test.create',[$project->id,$stages[0]->id]) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Test')}}" class="btn btn-sm btn-primary">
        <i class="ti ti-plus"></i>
    </a>
    @endcan
    <a href="{{route('projects.show',$project->id)}}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="" data-bs-original-title="Back">
        <span class="btn-inner--icon"><i class="ti ti-arrow-left"></i></span>
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table datatable" id="att_table">
                        <thead>
                            <tr>
                                <th> {{__('Test Name')}}</th>
                                <th> {{__('Test Input')}}</th>
                                <th> {{__('Test Accepted output')}}</th>
                                <th> {{__('Status')}}</th>
                                <th> {{__('Priority')}}</th>
                                <th> {{__('Start Date')}}</th>
                                <th> {{__('End Date')}}</th>
                                <th> {{__('Test type')}}</th>
                                <th> {{__('Activity')}}</th>
                                <th> {{__('Activity Type')}}</th>
                                <th> {{__('Assigned To')}}</th>
                                <th width="10%"> {{__('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($tests as $test)
                            <tr>
                                <td>{{ $test->test_name}}</td>
                                <td>{{ $test->test_input}}</td>
                                <td>{{ $test->test_accepted_output}}</td>
                                <td>
                                    <?php foreach ($stages as $status) {

                                        if ($status->id == $test->stage_id) {
                                            echo $status->name;
                                        }
                                    }   ?>
                                </td>
                                <td>{{ $test->priority}}</td>
                                <td>{{ $test->start_date}}</td>
                                <td>{{ $test->end_date}}</td>
                                <td>{{ $test->test_type}}</td>
                                <td>{{ $test->task_activity}}</td>
                                <td>{{ $test->task_activity_type}}</td>
                                <td>

                                    <div class="avatar-group">
                                        @php
                                        $users = [];
                                        $getUsers = App\Models\ProjectTask::getusers();
                                        if (!empty($test->assign_to)) {
                                        foreach (explode(',', $test->assign_to) as $key_user) {
                                        $user['name'] = $getUsers[$key_user]['name'];
                                        $user['avatar'] = $getUsers[$key_user]['avatar'];

                                        $users[] = $user;
                                        }
                                        $taskuser = $users;
                                        } else {
                                        $taskuser = [];
                                        }
                                        @endphp

                                        @if (count($taskuser) > 0)
                                        @foreach ($taskuser as $key => $user)
                                        <a href="#" class="avatar rounded-circle avatar-sm">
                                            <img data-original-title="{{ !empty($user) ? $user['name'] : '' }}" @if ($user['avatar']) src="{{ asset('/storage/uploads/avatar/' . $user['avatar']) }}" @else src="{{ asset('/storage/uploads/avatar/avatar.png') }}" @endif title="{{ $user['name'] }}" class="hweb">
                                        </a>
                                        @endforeach
                                        @else
                                        {{ __('-') }}
                                        @endif
                                    </div>
                                    {{-- <div class="avatar-group">
    @php
        $users = $test->users();
    @endphp
    @if ($users->count() > 0)
        @if ($users)
            @foreach ($users as $key => $user)
                @if ($key < 3)
                    <a href="#"
                        class="avatar rounded-circle avatar-sm">
                        <img data-original-title="{{ !empty($user) ? $user->name : '' }}"
                                    @if ($user->avatar) src="{{ asset('/storage/uploads/avatar/' . $user->avatar) }}" @else src="{{ asset('/storage/uploads/avatar/avatar.png') }}" @endif
                                    title="{{ $user->name }}" class="hweb">
                                    </a>
                                    @else
                                    @break
                                    @endif
                                    @endforeach
                                    @endif
                                    @if (count($users) > 3)
                                    <a href="#" class="avatar rounded-circle avatar-sm">
                                        <img data-original-title="{{ !empty($user) ? $user->name : '' }}" @if ($user->avatar) src="{{ asset('/storage/uploads/avatar/' . $user->avatar) }}" @else src="{{ asset('/storage/uploads/avatar/avatar.png') }}" @endif
                                        class="hweb">
                                    </a>
                                    @endif
                                    @else
                                    {{ __('-') }}
                                    @endif
                </div> --}}
                </td>
                <td class="Action" width="10%">
                                        @can('edit project test')
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" data-size="lg" class="mx-3 btn btn-sm  align-items-center" data-url="{{ route('project.test.edit',[$project->id,$test->id]) }}" data-ajax-popup="true" data-size="xl" title="{{__('Edit')}}" data-title="{{__('Edit Test')}}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                        @endcan
                                        @can('delete project test')
                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['project.test.destroy', $project->id,$test->id]]) !!}
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
<script>
     function load_data()
        {
           
            $("#att_table").load(" #att_table");
        }
</script>