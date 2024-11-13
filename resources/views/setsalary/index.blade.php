@extends('layouts.admin')
@section('page-title')
    {{__('Manage Employee Salary')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Employee Salary')}}</li>
@endsection
@section('action-btn')
    <div class="float-end">
     @can('create other payment')
        <a href="{{ route('setsalary.otherpayment') }}"
            data-title="{{ __('Other Payment Deduction') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Other Payment Deduction') }}">
            Other Payment Deduction/Allowance
        </a>
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
                                <th>{{__('Employee Id')}}</th>
                                <th>{{__('Name')}}</th>
                            
                                <th>{{__('Basic Salary (Monthly)') }}</th>
                                <th>{{__('Net Salary (Monthly)') }}</th>
                                <th>{{__('Annual CTC')}}</th>
                                <th width="200px">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($employees as $employee)
                            <?php if($employee->user->is_enable_login=='1')
                            { ?>
                                <tr>
                                    <td class="Id">
                                        <a href="{{route('setsalary.show',$employee->id)}}" class="btn btn-outline-primary" data-toggle="tooltip" data-original-title="{{__('View')}}">
                                            {{ \Auth::user()->employeeIdFormat($employee->employee_id) }}
                                        </a>
                                    </td>
                                    <td>{{ $employee->name }}</td>
                                  
                                    <td>₹ {{  $employee->salary }}</td>
                                    <td>₹ {{ round($employee->get_net_salary())}}</td>
                                    <td>₹ {{ $employee->annual_salary}}</td>
                                    <td>
                                    <div class="action-btn bg-primary ms-2">
                                        <a href="{{route('setsalary.show',$employee->id)}}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Set Salary')}}" data-original-title="{{__('View')}}">
                                            <i class="ti ti-eye text-white"></i>
                                        </a>
                                    </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection


