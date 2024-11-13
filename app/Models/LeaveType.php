<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $fillable = [
        'title',
        'days',
        'monthly_limit',
        'leave_paid_status',
        'leave_year',
        'created_by',
    ];
}
