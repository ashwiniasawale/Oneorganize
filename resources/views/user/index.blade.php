@extends('layouts.admin')
@php
    // $profile=asset(Storage::url('uploads/avatar/'));
    $profile = \App\Models\Utility::get_file('uploads/avatar');
@endphp
@section('page-title')
    @if (\Auth::user()->type == 'super admin')
        {{ __('Manage Companies') }}
    @else
        {{ __('Manage User') }}
    @endif
@endsection

@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
    </li>
    @if (\Auth::user()->type == 'super admin')
        <li class="breadcrumb-item">{{ __('Companies') }}</li>
    @else
        <li class="breadcrumb-item">{{ __('User') }}</li>
    @endif
@endsection
@section('action-btn')
    <div class="float-end">
        @if (\Auth::user()->type == 'company' || \Auth::user()->type == 'HR')
            <a href="{{ route('user.userlog') }}" class="btn btn-primary btn-sm {{ Request::segment(1) == 'user' }}"
                data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('User Logs History') }}"><i
                    class="ti ti-user-check"></i>
            </a>
        @endif
        @can('create user')
            <a href="#" data-size="lg" data-url="{{ route('users.create') }}" data-ajax-popup="true"
                data-bs-toggle="tooltip" data-title="{{ \Auth::user()->type == 'super admin' ?  __('Create Company')  : __('Create User') }}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-xxl-12">
            <div class="row">
            <div class="table-responsive">
    <table class="table datatable">
        <tbody>
            @foreach ($users as $user)
                @if ($loop->index % 4 === 0)
                    <tr>
                @endif
                    <td class="text-center">
                        <div class="card text-center card-2 mb-3">
                            <div class="card-header border-0 pb-0">
                                <h6 class="mb-0">
                                    @if (\Auth::user()->type == 'super admin')
                                        <div class="badge bg-primary p-2 px-3 rounded">
                                            {{ !empty($user->currentPlan) ? $user->currentPlan->name : '' }}
                                        </div>
                                    @else
                                        <div class="badge bg-primary p-2 px-3 rounded">
                                            {{ ucfirst($user->type) }}
                                        </div>
                                    @endif
                                </h6>
                                @if (Gate::check('edit user') || Gate::check('delete user'))
                                    <div class="card-header-right">
                                        <div class="btn-group card-option">
                                            @if ($user->is_active == 1 && $user->is_disable == 1)
                                                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    @can('edit user')
                                                        <a href="#!" data-size="lg" data-url="{{ route('users.edit', $user->id) }}" data-ajax-popup="true" class="dropdown-item">
                                                            <i class="ti ti-pencil"></i>
                                                            <span>{{ __('Edit') }}</span>
                                                        </a>
                                                    @endcan
                                                    <a href="{{ route('users.reset', \Crypt::encrypt($user->id)) }}" data-ajax-popup="true" data-size="md" class="dropdown-item">
                                                        <i class="ti ti-adjustments"></i>
                                                        <span>{{ __('Reset Password') }}</span>
                                                    </a>
                                                    @if (Auth::user()->type == 'super admin')
                                                        <a href="{{ route('login.with.company', $user->id) }}" class="dropdown-item">
                                                            <i class="ti ti-replace"></i>
                                                            <span>{{ __('Login As Company') }}</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            @else
                                                <a href="#" class="action-item text-lg"><i class="ti ti-lock"></i></a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="card-body full-card">
                                <div class="img-fluid rounded-circle card-avatar">
                                    <img src="{{ !empty($user->avatar) ? asset(Storage::url('uploads/avatar/' . $user->avatar)) : asset(Storage::url('uploads/avatar/avatar.png')) }}" class="img-user wid-80 round-img rounded-circle">
                                </div>
                                <h4 class="mt-3 text-primary">{{ $user->name }}</h4>
                                @if ($user->delete_status == 0)
                                    <h5 class="office-time mb-0">{{ __('Soft Deleted') }}</h5>
                                @endif
                                <small class="text-primary">{{ $user->email }}</small>
                                <div class="text-center" data-bs-toggle="tooltip" title="{{ __('Last Login') }}">
                                    {{ !empty($user->last_login_at) ? $user->last_login_at : '' }}
                                </div>
                                @if (\Auth::user()->type == 'super admin')
                                    <div class="mt-4">
                                        <a href="#" data-url="{{ route('plan.upgrade', $user->id) }}" data-size="lg" data-ajax-popup="true" class="btn btn-outline-primary">{{ __('Upgrade Plan') }}</a>
                                        <a href="#" data-url="{{ route('company.info', $user->id) }}" data-size="lg" data-ajax-popup="true" class="btn btn-outline-primary">{{ __('AdminHub') }}</a>
                                    </div>
                                    <div class="mt-3">
                                        <div class="row">
                                            <div class="col-4">
                                                <p class="text-muted text-sm mb-0"><i class="ti ti-users card-icon-text-space"></i>{{ $user->totalCompanyUser($user->id) }}</p>
                                            </div>
                                            <div class="col-4">
                                                <p class="text-muted text-sm mb-0"><i class="ti ti-users card-icon-text-space"></i>{{ $user->totalCompanyCustomer($user->id) }}</p>
                                            </div>
                                            <div class="col-4">
                                                <p class="text-muted text-sm mb-0"><i class="ti ti-users card-icon-text-space"></i>{{ $user->totalCompanyVender($user->id) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                @if ($loop->index % 4 === 3 || $loop->last)
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
               
            </div>
        </div>
    </div>
@endsection

@push('script-page')
    <script>
        $(document).on('change', '#password_switch', function() {
            if ($(this).is(':checked')) {
                $('.ps_div').removeClass('d-none');
                $('#password').attr("required", true);

            } else {
                $('.ps_div').addClass('d-none');
                $('#password').val(null);
                $('#password').removeAttr("required");
            }
        });
        $(document).on('click', '.login_enable', function() {
            setTimeout(function() {
                $('.modal-body').append($('<input>', {
                    type: 'hidden',
                    val: 'true',
                    name: 'login_enable'
                }));
            }, 2000);
        });
    </script>
   

@endpush