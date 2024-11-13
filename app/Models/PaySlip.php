<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaySlip extends Model
{
    protected $fillable = [
        'employee_id',
        'total_days',
        'present_days',
        'total',
        'net_payble',
        'basic_salary',
        'salary_month',
        'status',
        'allowance',
        'actual_allowance',
        'commission',
        'loan',
        'saturation_deduction',
        'actual_saturation_deduction',
        'other_payment',
        'actual_other_payment',
        'overtime',
        'created_by',
    ];

    public static function employee($id)
    {
        return Employee::find($id);
    }

    public function employees()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'employee_id');
    }
}
