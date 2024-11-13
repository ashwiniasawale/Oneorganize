
<div class="modal-body">
    <div class="row">
        <div class="col-12">
                <table class="table modal-table" id="leave_table">
                    <tr >
                        <th>{{__('Leave Date')}}</th>
                        
                        <th>{{__('Leave Type ')}}</th>
                       
                        <th>{{__('Status')}}</th>
                        <th>{{__('Action')}}</th>
                    </tr>
                    <?php $i=1; ?>
                    @foreach ($leaves as $leave)
                    <tr id="refresh_leave{{$i}}">
                        <td>{{$leave->leave_date}}</td>
                        <?php if(\Auth::user()->type=='company' || \Auth::user()->type=='HR')
                        { ?>
                        <td>
                        <select id="leave_type{{$i}}" name="leave_type" class="form-control select" onchange="update_leave_status({{$leave->id}},{{$i}},'0')">
                            <option <?php if($leave->leave_type=='Paid'){ echo 'selected=selected'; } ?> value="Paid">Paid</option>
                            <option <?php if($leave->leave_type=='Unpaid'){ echo 'selected=selected'; } ?> value="Unpaid">Unpaid</option>
                           
                        </select>
                        </td>
                        <?php }else{ ?>
                        <td>{{$leave->leave_type}}</td>
                        <?php } ?>
                        <td id="lea_status{{$i}}">

                        @if($leave->status=="Pending")<div class="text-warning">{{ $leave->status }}</div>
                                        @elseif($leave->status=="Approved")
                                            <div class="text-success">{{ $leave->status }}</div>
                                        @else($leave->status=="Reject")
                                            <div class="text-danger">{{ $leave->status }}</div>
                                        @endif
                        </td>
                        <td>
                        @can('edit leave')
                        <select id="leave_status{{$i}}" name="leave_status" class="form-control select" onchange="update_leave_status({{$leave->id}},{{$i}},'1')">
                            <option <?php if($leave->status=='Pending'){ echo 'selected=selected'; } ?> value="Pending">Pending</option>
                            <option <?php if($leave->status=='Approved'){ echo 'selected=selected'; } ?> value="Approved">Approved</option>
                            <option <?php if($leave->status=='Reject'){ echo 'selected=selected'; } ?> value="Reject">Reject</option>
                        </select>
                        @endcan 

                     
                        </td>
                        <td>
                        @can('delete leave')
                                        <div class="action-btn bg-danger ms-2">
                                            <a href="#" id="leave_delete{{$i}}" onclick="delete_leave({{$leave->id}},{{$i}});" class="mx-3 btn btn-sm " >
                                            <i class="ti ti-trash text-white"></i></a>
                                           
                                        </div>
                                        @endif
                        </td>
                    </tr>
                    <?php $i++; ?>
                    @endforeach
                </table>
        </div>
    </div>
</div>

<div class="modal-footer">
   
</div>

<script>
    function delete_leave(id,i)
    {
        $.ajax({
            url:'{{route('leave.delete_leave')}}',
            method:'post',
            data:{_token:$('meta[name="csrf-token"]').attr('content'),id:id},
            success:function(data)
            {
               // console.log(data);
                if(data.success)
                {
                    show_toastr('Success',data.success,'success');
                    $("#refresh_leave"+i).load(" #refresh_leave"+i);

                }else{
                    show_toastr('Error',data.error,'error');
                }   
            }

        });
    }
    function update_leave_status(id,i,update_status)
    {
       var status=$('#leave_status'+i).val();
       var leave_type=$('#leave_type'+i).val();
        $.ajax({
                    url: '{{ route('update.leave.update_leave_status') }}',
                    method: 'post',
                    data:{_token: $('meta[name="csrf-token"]').attr('content'),status:status,id:id,leave_type:leave_type,update_status},  
                   
                        success: function (data) {
                            //console.log(data);
                            if(data.success)
                            {
                                show_toastr('Success', data.success, 'success');
                                $("#att_table").load(" #att_table");
                               $('#lea_status'+i).html(status);
                            }else{
                            
                                show_toastr('Error', data.error, 'error');
                            }
                        }
               });
    }
</script>
