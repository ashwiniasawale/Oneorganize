<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Risk extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'risk_details',
        'priority',
        'identified_on',
        'mitigation_target_date',
        'responsible_person',
        'risk_classification',
        'risk_description',
        'risk_impact',
        'risk_severity',
        'risk_probability',
        'status',
        'risk_consequence',
        'risk_score',
        'mitigation_person',
        'critical_dependency',
        'mitigation_resource',
        'financial_impact',
        'timeline_impact',
        'action_item',
        'action_taken',
        'assumptions_made',
        'changes_in_project_plan',
        'created_by'
    ];
    public static $risk_impact=[
        '1' => 'Insignificant',
        '2'=>'Minor',
        '3' => 'Significant',
        '4' => 'Major',
        '5' => 'Severe'
    ];
    public static $risk_severity=[
        '1' => 'Little or no impact',
        '2'=>'Minor impact',
        '3' => 'Moderate impact',
        '4' => 'Significantly impacted',
        '5' => 'Highest impact'
    ];
  
    public static $risk_probability=[
        '0.1' => '0% to 20%',
        '0.3'=>'21% to 40%',
        '0.5' => '41% to 60%',
        '0.7' => '61% to 80%',
        '0.9' => '81% to 100%'
    ];
 
    public function createdBy()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }
    public function responsiblePerson()
    {
        return $this->hasOne('App\Models\User', 'id', 'responsible_person');
    }
    public function mitigationPerson()
    {
        return $this->hasOne('App\Models\User','id','mitigation_person');
    }
}
