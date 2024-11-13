@extends('layouts.admin')
@section('page-title')
    {{__('Manage Test')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('projects.index')}}">{{__('Project')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('projects.show',$project->id)}}">    {{ucwords($project->project_name)}}</a></li>
    <li class="breadcrumb-item">{{__('Test')}}</li>
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dragula.min.css') }}" id="main-style-link">
@endpush
@push('script-page')

    <script src="{{ asset('assets/js/plugins/dragula.min.js') }}"></script>
   
@endpush
@section('action-btn')
    <div class="float-end">
    @can('manage bug report')
            <a href="{{ route('project.testindex',$project->id) }}" data-bs-toggle="tooltip" title="{{__('List')}}" class="btn btn-sm btn-primary">
                <i class="ti ti-list"></i>
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
    @php
        $json = [];
        foreach ($stages as $status){
            $json[] = 'task-list-'.$status->id;
        }
    @endphp
    <div class="row">
        <div class="col-sm-12">
            <div class="row kanban-wrapper horizontal-scroll-cards" data-containers='{{json_encode($json)}}' data-plugin="dragula">
           
            @foreach($stages as $status)
            @php $tests = $status->test($project->id) @endphp
             
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-end">

                                    <span class="btn btn-sm btn-primary btn-icon count">
                                    {{count($tests)}}
                                    </span>
                                </div>
                                <h4 class="mb-0">{{$status->name}}</h4>
                            </div>
                            <div class="card-body kanban-box" id="task-list-{{$status->id}}" data-id="{{$status->id}}">
                              
                                @foreach($tests as $test)
                                
                                    <div class="card draggable-item" id="{{$test->id}}">
                                        <div class="pt-3 ps-3">
                                            @if($test->priority =='low')
                                                <span class="p-2 px-3 rounded badge badge-pill badge-xs bg-success">{{ ucfirst($test->priority) }}</span>
                                            @elseif($test->priority =='medium')
                                                <span class="p-2 px-3 rounded badge badge-pill badge-xs bg-warning">{{ ucfirst($test->priority) }}</span>
                                            @elseif($test->priority =='high')
                                                <span class="p-2 px-3 rounded badge badge-pill badge-xs bg-danger">{{ ucfirst($test->priority) }}</span>
                                          
                                            @elseif($test->priority =='critical')
                                                <span class="p-2 px-3 rounded badge badge-pill badge-xs bg-danger">{{ ucfirst($test->priority) }}</span>
                                            @endif
                                           
                                        </div>
                                        
                                        <div class="card-header border-0 pb-0 position-relative">
                                        
                                            <h6 class="mb-0">{{$test->test_name}}</h6>
                                            <h5>
                                                <a href="#"  data-ajax-popup="true" data-size="lg" data-bs-original-title="{{$test->title}}">{{$test->title}}</a>
                                            </h5>
                                            <div class="card-header-right">
                                                <div class="btn-group card-option">
                                                    <button type="button" class="btn dropdown-toggle"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>
                                                    @if(Gate::check('edit project test') || Gate::check('delete project test'))
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            @can('edit project test')
                                                                <a href="#!" data-size="lg" data-url="{{ route('project.test.edit',[$project->id,$test->id]) }}" data-ajax-popup="true" class="dropdown-item" data-bs-original-title="{{__('Edit ').$test->test_name}}">
                                                                    <i class="ti ti-pencil"></i>
                                                                    <span>{{__('Edit')}}</span>
                                                                </a>
                                                            @endcan
                                                            @can('delete project test')
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['project.test.destroy', [$project->id,$test->id]]]) !!}
                                                                <a href="#!" class="dropdown-item bs-pass-para">
                                                                    <i class="ti ti-archive"></i>
                                                                    <span> {{__('Delete')}} </span>
                                                                </a>
                                                                {!! Form::close() !!}
                                                            @endcan
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <ul class="list-inline mb-0">
                                                    <li class="list-inline-item d-inline-flex align-items-center" data-bs-toggle="tooltip" title="{{__('Start Date')}}">
                                                         {{ \Auth::user()->dateFormat($test->start_date) }}
                                                    </li>

                                                </ul>
                                                <div class="user-group">
                                                    <span data-bs-toggle="tooltip" title="{{__('End Date')}}">  {{ \Auth::user()->dateFormat($test->end_date) }}</span>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                @php $user = $test->users(); @endphp

                                                <div class="user-group">
                                                    <img @if(isset($user[0]->avatar)) src="{{asset('/storage/uploads/avatar/'.$user[0]->avatar)}}" @else src="{{asset('/storage/uploads/avatar/avatar.png')}}" @endif alt="image" data-bs-toggle="tooltip" title="{{(!empty($user[0])?$user[0]->name:'')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
