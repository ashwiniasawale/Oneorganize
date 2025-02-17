@foreach($project->users as $user)

    <li class="list-group-item px-0">
        <div class="row align-items-center justify-content-between">
            <div class="col-sm-auto mb-3 mb-sm-0">
                <div class="d-flex align-items-center">
                    <div class="avatar rounded-circle avatar-sm me-3">
                        @php
                        $avatar = \App\Models\Utility::get_file('uploads/avatar/');
                            
                        @endphp
{{--                        <img src="@if($user->avatar) src="{{asset('/storage/uploads/avatar/'.$user->avatar)}}" @else src="{{asset('/storage/uploads/avatar/avatar.png')}}" @endif " alt="kal" class="img-user">--}}
                        <img @if($user->avatar) src="{{$avatar.$user->avatar}}" @else src="{{$avatar. 'avatar.png'}}" @endif  alt="image" >

                    </div>
                    <div class="div">
                        <h5 class="m-0">{{ $user->name }} <?php if($project->manager_id==$user->id){ ?> (TeamLead)<?php } ?></h5>
                        <small class="text-muted">{{ $user->email }}</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-auto text-sm-end d-flex align-items-center">
                <div class="action-btn bg-danger d-flex m-2">
                @can('delete project')
                    {!! Form::open(['method' => 'DELETE', 'class'=>'d-flex m-1' ,'route' => ['projects.user.destroy',  [$project->id,$user->id]]]) !!}
                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="ti ti-trash text-white"></i></a>

                    {!! Form::close() !!}
                    @endcan
                </div>
            </div>
        </div>
    </li>
  
@endforeach
