
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="row">
                    @if(count($tasks) > 0)
                        @foreach($tasks as $task)
                            <div class="col-md-3 col-lg-3 col-sm-3">
                                <div class="card m-3 card-progress border shadow-none" id="{{$task->id}}" style="{{ !empty($task->priority_color) ? 'border-left: 2px solid '.$task->priority_color.' !important' :'' }};">
                                    <div class="card-body">
                                        <div class="row align-items-center mb-2">
                                            <span>{{ $task->stage->name }}</span>
                                            <div class="col-6">
                                                <span class="badge p-2 px-3 rounded bg-{{\App\Models\ProjectTask::$priority_color[$task->priority]}}">{{ \App\Models\ProjectTask::$priority[$task->priority] }}</span>
                                            </div>
                                            <div class="col-6 text-end">
                                                @if(str_replace('%','',$task->taskProgress($task)['percentage']) > 0)
                                                    <span class="text-sm">{{ $task->taskProgress($task)['percentage'] }}</span>
                                                    <div class="progress" style="top:0px">
                                                        <div class="progress-bar bg-{{ $task->taskProgress($task)['color'] }}" role="progressbar"
                                                             style="width: {{ $task->taskProgress($task)['percentage'] }};"></div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <a class="h6 task-name-break" href="{{ route('projects.tasks.index',!empty($task->project)?$task->project->id:'') }}">{{ $task->name }}</a>
                                        <div class="row align-items-center">
                                            <div class="col-12">
                                                <div class="actions d-flex justify-content-between mt-2 mb-2">
                                                    @if(count($task->taskFiles) > 0)
                                                        <div class="action-item mr-2"><i class="ti ti-paperclip mr-2"></i>{{ count($task->taskFiles) }}</div>@endif
                                                    @if(count($task->comments) > 0)
                                                        <div class="action-item mr-2"><i class="ti ti-brand-hipchat mr-2"></i>{{ count($task->comments) }}</div>@endif
                                                    @if($task->checklist->count() > 0)
                                                        <div class="action-item mr-2"><i class="ti ti-list-check mr-2"></i>{{ $task->countTaskChecklist() }}</div>@endif
                                                </div>
                                            </div>
                                            <div class="col-6">@if(!empty($task->end_date) && $task->end_date != '0000-00-00')<small @if(strtotime($task->end_date) < time())class="text-danger"@endif>{{ Utility::getDateFormated($task->end_date) }}</small>@endif</div>
                                            <div class="col-6 text-end">
                                                @if($users = $task->users())
                                                    <div class="avatar-group">
                                                        @foreach($users as $key => $user)
                                                            @if($key<3)
                                                                <a href="#" class="avatar rounded-circle avatar-sm">
                                                                    <img class="hweb" data-original-title="{{(!empty($user)?$user->name:'')}}" @if($user->avatar) src="{{asset('/storage/uploads/avatar/'.$user->avatar)}}" @else src="{{asset('/storage/uploads/avatar/avatar.png')}}" @endif >
                                                                </a>
                                                            @else
                                                                @break
                                                            @endif
                                                        @endforeach
                                                        @if(count($users) > 3)
                                                            <a href="#" class="avatar rounded-circle avatar-sm">
                                                                <img class="hweb" data-original-title="{{(!empty($user)?$user->name:'')}}" @if($user->avatar) src="{{asset('/storage/uploads/avatar/'.$user->avatar)}}" @else src="{{asset('/storage/uploads/avatar/avatar.png')}}" @endif avatar="+ {{ count($users)-3 }}">
                                                            </a>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-md-12">
                            <h6 class="text-center m-3">{{__('No tasks found')}}</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

