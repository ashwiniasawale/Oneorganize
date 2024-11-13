<div class="modal-body">
    <div class="row">
        @foreach($leaves as $leave)
            <div class="col text-center">
                <div class="card p-4 mb-4">
                    <h7 class="report-text gray-text mb-0">{{$leave->title}} :</h7>
                    <h6 class="report-text mb-0">{{$leave->total}}</h6>
                </div>
            </div>
        @endforeach
    </div>
    <div class="row mt-2">
        <div class="table-responsive">
        <table class="table datatable">
            <thead>
            <tr>
                <th>{{__('Leave Type')}}</th>
                <th>{{__('Leave Date')}}</th>
              
                <th>{{__('Leave Reason')}}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($leaveData as $leave)
                @php
                    $startDate               = new \DateTime($leave->leave_date);
                 
                   
                @endphp
                <tr>
                    <td>{{!empty($leave->leave_type)?$leave->leave_type:''}}</td>
                    <td>{{$leave->leave_date}}</td>
                  
                    <td>{{$leave->leave_reason}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">{{__('No Data Found.!')}}</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    </div>
</div>
