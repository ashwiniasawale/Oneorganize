@extends('layouts.admin')
@section('page-title')
    {{__('List Review')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('projects.index')}}">{{__('Project')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('projects.show',$project->id)}}">{{ucwords($project->project_name)}}</a></li>
    <li class="breadcrumb-item">{{__('Review')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
      
        @can('create project review')
            <a href="#" data-size="lg" data-url="{{ route('project.review.create',$project->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Review')}}" class="btn btn-sm btn-primary">
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
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th> {{__('Review Date')}}</th>
                                <th> {{__('Attended By')}}</th>
                                <th> {{__('Artifacts of Review')}}</th>
                                <th> {{__('Risk Identified')}}</th>
                                <th> {{__('Is Requirement Sheet Updated')}}</th>
                               
                                <th> {{__('Created By')}}</th>
                                <th width="10%"> {{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($review as $review)
                                <tr>
                                    <td>{{ $review->review_date}}</td>
                                    <td>{{ (!empty($review->attended_by)?$review->attendedBy->name:'') }}</td>
                                    <td>{{ $review->artifacts_of_review}}</td>
                                    <td>{{ (!empty($review->risk_identified)?$review->riskIdentified->name:'') }}</td>
                                    <td>{{ $review->is_updated}}</td>
                                    <td>{{ $review->createdBy->name }}</td>
                                    <td class="Action" width="10%">
                                        @can('edit project review')
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" data-size="lg" class="mx-3 btn btn-sm  align-items-center" data-url="{{ route('project.review.edit',[$project->id,$review->id]) }}" data-ajax-popup="true" data-size="xl" title="{{__('Edit')}}" data-title="{{__('Edit Project Review')}}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                        @endcan
                                        @can('delete project review')
                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['project.review.destroy', $project->id,$review->id]]) !!}
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