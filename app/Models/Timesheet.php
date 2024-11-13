<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    protected $fillable = [
        'project_id',
        'task_id',
        'estimated_hrs',
        'actual_hours',
        'date',
        'time',
        'description',
        'created_by',
    ];

    public function project()
    {
        return $this->hasOne('App\Models\Project', 'id', 'project_id');
    }

    public function task()
    {
        return $this->hasOne('App\Models\ProjectTask', 'id', 'task_id');
    }
    public function createdBy()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }
}
