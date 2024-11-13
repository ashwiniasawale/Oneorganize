@extends('layouts.admin')
@section('page-title')
    {{__('Manage Risk')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('projects.index')}}">{{__('Project')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('projects.show',$project->id)}}">{{ucwords($project->project_name)}}</a></li>
    <li class="breadcrumb-item">{{__('Risk')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
        <!-- @can('manage bug report')
            <a href="{{ route('task.bug.kanban',$project->id) }}" data-bs-toggle="tooltip" title="{{__('Kanban')}}" class="btn btn-sm btn-primary">
                <i class="ti ti-grid-dots"></i>
            </a>
        @endcan -->
        @can('create project risk')
            <a href="#" data-size="xl" data-url="{{ route('project.risk.create',$project->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New risk')}}" class="btn btn-sm btn-primary ">
                <i class="ti ti-plus"></i>
            </a>
        @endcan

        <a href="{{ route('projects.show',$project->id) }}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="" data-bs-original-title="Back">
               <span class="btn-inner--icon"><i class="ti ti-arrow-left"></i></span>
        </a>

        
    </div>
@endsection
@section('content')
    <div class="row">`
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                             
                                <th> {{__('Risk Details')}}</th>
                                <th> {{__('Priority')}}</th>
                                <th> {{__('Identified On')}}</th>
                                <th> {{__('Mitigation Target Date')}}</th>
                                <th> {{__('Responsible Person')}}</th>
                                <th> {{__(' Risk classification ')}}</th>
                                <th> {{__('Risk Description')}}</th>
                               
                                <th> {{__('Status')}}</th>
                                <th> {{__('Risk Consequence')}}</th>
                                <th> {{__('Risk Score')}}</th>
                                <th> {{__('Mitigation Person')}}</th>
                                <th> {{__('Critical Dependency ')}}</th>
                                <th> {{__('Require Resource for Mitigation')}}</th>
                                <th>{{__('Created By')}}</th>

                                <th width="10%"> {{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($risk as $risk)
                                <tr>
                                  <td>{{ $risk->risk_details}}</td>
                                    <td>{{ $risk->priority }}</td>
                                    <td>{{ Auth::user()->dateFormat($risk->identified_on) }}</td>
                                    <td>{{ Auth::user()->dateFormat($risk->mitigation_target_date) }}</td>
                                    <td>{{ (!empty($risk->responsiblePerson)?$risk->responsiblePerson->name:'') }}</td>
                                    <td>{{ $risk->risk_classification}}</td>
                                    <td>{{ $risk->risk_description }}</td>
                                   
                                    <td>{{$risk->status}}</td>
                                    <td>{{$risk->risk_consequence}}</td>
                                    <td>{{$risk->risk_score}}</td>
                                    <td>{{(!empty($risk->mitigationPerson)?$risk->mitigationPerson->name:'')}}</td>
                                    <td>{{$risk->critical_dependency}}</td>
                                    <td>{{$risk->mitigation_resource}}</td>
                                   
                                    <td>{{ $risk->createdBy->name }}</td>
                                    <td class="Action" width="10%">
                                        @can('edit project risk')
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" data-size="xl" class="mx-3 btn btn-sm  align-items-center" data-url="{{ route('project.risk.edit',[$project->id,$risk->id]) }}" data-ajax-popup="true" data-size="xl" title="{{__('Edit Risk')}}" data-title="{{__('Edit Risk')}}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                        @endcan
                                        @can('delete project risk')
                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['project.risk.delete', $project->id,$risk->id]]) !!}
                                                <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"  title="{{__('Delete')}}"><i class="ti ti-trash text-white"></i></a>
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