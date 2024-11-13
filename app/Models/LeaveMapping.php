<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveMapping extends Model
{
    protected $fillable = [
        'leave_id',
       
        'status',
        'leave_count',
        'leave_type',
        'leave_date',
       'approved_by',
    ];

}
