<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RequirementMatrix extends Model
{
 
    protected $fillable = [
        'project_id',
        'requirement_id',
        'requirement_details',
        'categories',
        'implementable',
        'testable',
        'implementation_status',
        'testing_status',
        'created_by'
    ];
    public function createdBy()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }

    public static function get_req($project_id, $reqq)
    {
$get_req=DB::select('select requirement_details,requirement_id from requirement_matrices where project_id="'.$project_id.'" and id="'.$reqq.'"');

return $get_req;
    }
    
}
