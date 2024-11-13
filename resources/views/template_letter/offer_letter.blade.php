@extends('layouts.admin')

@section('page-title')
    {{__('Manage Offer Letter')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Offer Letter')}}</li>
@endsection

@section('action-btn')
    <div class="float-end">
        
      
       
        <a href="#" data-size="lg" data-url="{{ route('letters.create_offer') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create Offer Letter')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
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
                                <th>{{__('Name')}}</th>
                                
                                <th>{{__('Employee Type')}}</th>
                                <th>{{__('Joining Date')}}</th>
                                <th>{{__('Designation')}}</th>
                                <th>{{__('Ref. No.')}}</th>
                                <th>{{__('Annual CTC')}}</th>
                               
                                <th>{{__('Offer Letter')}}</th>
                                 <th>{{__('Appointment Letter')}}</th>
                                 <th></th>
                            </tr>
                            </thead>
                            <tbody>
                           <?php foreach($letter as $letter)
                           { ?>
                           <tr>
                           <td>{{$letter->employee_name}}</td>
                           <td>{{$letter->employee_type}}</td>
                           <td>{{$letter->joining_date}}</td>
                           <td>{{$letter->designation}}</td>
                           <td>{{$letter->ref_no}}</td>
                           <td>{{$letter->salary}}</td>
                           <td> <a href="{{route('offerletter.download.pdf',$letter->id)}}" class=" btn-icon btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top"  target="_blanks"><i class="ti ti-download ">&nbsp;</i>{{__('PDF')}}</a>
                           </td>
                           <td> <a href="{{route('appointment.download.pdf',$letter->id)}}" class=" btn-icon btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top"  target="_blanks"><i class="ti ti-download ">&nbsp;</i>{{__('PDF')}}</a></td>
                           <td>
                           <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['offer.destroy', $letter->id],'id'=>'delete-form-'.$letter->id]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$letter->id}}').submit();">
                                                        <i class="ti ti-trash text-white"></i>
                                                    </a>
                                                    {!! Form::close() !!}
                                                </div>
                           </td>
                           <tr>
                           <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


