<div class="modal-body">
 <?php 
use App\Models\RequirementMatrix;
use Illuminate\Support\Facades\DB;
?> 
    <div class="row">
    @php
                                $reqq = explode(',',$task->requirement_id);
                            @endphp
                            <div class="">
  @foreach($reqq as $reqq)
  <?php     
              $get_req= RequirementMatrix::where('project_id',$task->project_id)->where('id',$reqq)->get();
// $get_req = RequirementMatrix::get_req($task->project_id, $reqq);
//$get_req=DB::table('requirement_matrices')->where('project_id',$task->project_id)->where('id',$reqq)->get();
//select('select requirement_details,requirement_id from requirement_matrices where project_id="'.$task->project_id.'" and id="'.$reqq.'"');
//$get_req->getRows();
//print_r($get_req['id'].'hhh');
  ?>
                                              <li>
                                                <?php echo 'REQUIREMENT'. sprintf("%05d", $reqq); ?> - 
</li>

                                        
                @endforeach
                </div>
</div> 
</div>

