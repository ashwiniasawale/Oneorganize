<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateLetter extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_type',
        'title',
        'employee_name',
        'offer_date',
        'ref_no',
        'joining_date',
        'address',
        'designation',
        'probation',
        'notice_period',
        'salary',
    ];
}
