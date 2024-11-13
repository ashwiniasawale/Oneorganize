@extends('layouts.admin')

@section('page-title')
    {{__('Other Monthly Payment')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('setsalary.index')}}">{{__('Employee Salary')}}</a></li>
    <li class="breadcrumb-item">Other Monthly Payment Deduction/Allowance</li>
@endsection
<style>
.selecttt
{
        display:inline-block !important;
        width:auto !important;
}
    </style>
@section('action-btn')
    <div class="float-end">
   
       
        @can('create other payment')
        {{Form::month('year_month',$year_month,array('class'=>'selecttt month-btn form-control month-btn','id'=>'year_month','onchange'=>'get_year_month();'))}}
        @endcan
       
       
        @can('create other payment')
        <a href="#" data-size="lg" data-url="{{ route('otherpayments.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create Other Payment Option')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
        </a>
        @endcan
        <a href="{{ route('setsalary.index') }}"
            data-title="{{ __('Set Salary') }}" data-bs-toggle="tooltip" title="Back" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Set Salary') }}">
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
                                <th>{{__('Employee')}}</th>
                                <th>{{__('Title')}}</th>
                                <th>{{__('Amount')}}</th>
                                <th>{{__(' Year Month')}}</th>
                                <th>{{__('Payment Option')}}</th>
                               
                                <th></th>
                                 
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($other_payment as $other_payment)
                            { ?>
                                <tr>
                                    <td>{{ $other_payment->name }}</td>
                                    <td>{{$other_payment->title}}</td>
                                    <td>₹ {{$other_payment->amount}}</td>
                                    <td>{{$other_payment->year_month}}</td>
                                    <td>{{$other_payment->payment_option}}</td>
                                    <td>
                                    @can('delete other payment')
                                        <div class="action-btn bg-danger ms-2">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['otherpayment.destroy', $other_payment->id],'id'=>'payment-delete-form-'.$other_payment->id]) !!}
                                              <a href="#" class="mx-3 mt-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('payment-delete-form-{{$other_payment->id}}').submit();"><i class="ti ti-trash text-white"></i></a>
                                            {!! Form::close() !!}
                                        </div>
                                    @endcan

                                                  
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
  
   
<script>
    function get_year_month() {
            var year_month=$('#year_month').val();
         
            var url = "{{route('setsalary.otherpayment')}}"; // Replace '/your-url/' with your actual URL
           
                url += '/'+year_month;
           
           
            // Redirect to the constructed URL
            window.location.href = url;
       
    }
</script>