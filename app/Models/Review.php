<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
   
    protected $fillable = [
        'project_id',
        'review_date',
        'attended_by',
        'artifacts_of_review',
        'checklist',
        'review_criteria',
        'requirement',
        'non_conf_list',
        'improvement_suggestions',
        'risk_identified',
        'problem_discover',
        'deviation_taken',
        'is_updated',
        'created_by',
    ];
    public function attendedBy()
    {
        return $this->hasOne('App\Models\User', 'id', 'attended_by');
    }
    public function riskIdentified()
    {
        return $this->hasOne('App\Models\User', 'id', 'risk_identified');
    }
    public function createdBy()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }
    public function assignTo()
    {
        return $this->hasOne('App\Models\User', 'id', 'assign_to');
    }
    public function users()
    {
        return User::whereIn('id', explode(',', $this->attended_by))->get();
    }
}
