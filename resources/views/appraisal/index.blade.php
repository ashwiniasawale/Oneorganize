@extends('layouts.admin')
@section('page-title')
    {{__('Manage Appraisal/Increament')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Appraisal/Increment')}}</li>
@endsection
@push('css-page')
    <style>
        @import url({{ asset('css/font-awesome.css') }});
    </style>
@endpush


@section('action-btn')
    <div class="float-end">
    @can('create appraisal')
       <a href="#" data-size="lg" data-url="{{ route('appraisal.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create New Appraisal/Increament')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
        </a>
        @endcan
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-body table-border-style">
                    <div class="table-responsive">
                    <table class="table datatable" id="att_table">
                            <thead>
                            <tr>
                               <th>{{__('Employee')}}</th>
                                <th>{{__('Type')}}</th>
                                
                                <th>{{__('Appraisal Date')}}</th>
                                <th>{{__('Annual Salary')}}</th>
                                <th>{{__('Appraisal')}}</th>
                                <th>{{__('Increament')}}</th>
                                @if( Gate::check('edit appraisal') ||Gate::check('delete appraisal') ||Gate::check('show appraisal'))
                                    <th width="200px">{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody class="font-style">
                            @foreach ($appraisals as $appraisal)

                              
                                <tr>
                                <td>{{!empty($appraisal->employees)?$appraisal->employees->name:'' }}</td>
                                <td>{{$appraisal->type}}</td>
                                <td>{{$appraisal->appraisal_date}}</td>
                                <td>{{$appraisal->appraisal_salary}}</td>
                                <td>
                                    <?php if($appraisal->type=='Appraisal')
                                    { ?>
                                        <a href="{{route('appraisal.download.pdf',$appraisal->id)}}" class=" btn-icon btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top"  target="_blanks"><i class="ti ti-download ">&nbsp;</i>{{__('PDF')}}</a>
                                    <?php } ?>
                                </td>
                                <td>
                                <?php if($appraisal->type=='Increament')
                                    { ?>
                                    <a href="{{route('increment.download.pdf',$appraisal->id)}}" class=" btn-icon btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top"  target="_blanks"><i class="ti ti-download ">&nbsp;</i>{{__('PDF')}}</a>
                                    <?php  
                                    } ?>
                                </td>
                                    @if( Gate::check('edit appraisal') ||Gate::check('delete appraisal') || Gate::check('show appraisal'))
                                        <td>
                                          
                                            <!-- @can('edit appraisal')
                                                <div class="action-btn bg-primary ms-2">
                                                    <a href="#" data-url="{{ route('appraisal.edit',$appraisal->id) }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Appraisal')}}" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}" class="mx-3 btn btn-sm align-items-center">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan -->
                                            @can('delete appraisal')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['appraisal.destroy', $appraisal->id],'id'=>'delete-form-'.$appraisal->id]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm-yes="document.getElementById('delete-form-{{$appraisal->id}}').submit();">
                                                        <i class="ti ti-trash text-white"></i>
                                                    </a>
                                                    {!! Form::close() !!}
                                                </div>
                                            @endcan
                                        </td>
                                    @endif
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
