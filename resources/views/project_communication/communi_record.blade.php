@extends('layouts.admin')
@section('page-title')
    {{__('List Communication Record')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('projects.index')}}">{{__('Project')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('projects.show',$project->id)}}">{{ucwords($project->project_name)}}</a></li>
    <li class="breadcrumb-item">{{__('Communication Record')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
      
        @can('create communication record')
            <a href="#" data-size="lg" data-url="{{ route('project.communication.create',$project->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Communication Record')}}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
        <a href="{{ route('projects.show',$project->id) }}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="" data-bs-original-title="Back">
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
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th> {{__('Title')}}</th>
                                <th> {{__('Date')}}</th>
                                <th> {{__('Description')}}</th>
                                <th> {{__('Created By')}}</th>
                               
                                <th width="10%"> {{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($communication as $communication)
                                <tr>
                                    <td>{{ $communication->title}}</td>
                                    <td>{{ $communication->date }}</td>
                                    <td>{{ $communication->description}}</td>
                                  
                                    <td>{{ $communication->createdBy->name }}</td>
                                    <td class="Action" width="10%">
                                        @can('edit communication record')
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" data-size="lg" class="mx-3 btn btn-sm  align-items-center" data-url="{{ route('project.communication.edit',[$project->id,$communication->id]) }}" data-ajax-popup="true" data-size="xl" title="{{__('Edit')}}" data-title="{{__('Edit Project Communication Record')}}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                        @endcan
                                        @can('delete communication record')
                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['project.communication.destroy', $project->id,$communication->id]]) !!}
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
