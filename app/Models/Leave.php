<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = [
        'employee_id',
       
        'applied_on',
        
        'total_leave_days',
        'leave_reason',
        'duration',
        'created_by',
    ];

    public function leaveType()
    {
        return $this->hasOne('App\Models\LeaveType', 'id', 'leave_type_id');
    }

    public function employees()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'employee_id');
    }
}
