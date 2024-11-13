<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherPayment extends Model
{
    protected $fillable = [
        'employee_id',
        'title',
        'amount',
        'payment_option',
        'year_month',
        'created_by',
    ];

    public function employee()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'employee_id');
    }
    public static $otherPaymenttype=[
        'fixed'=>'Fixed',
        'percentage'=> 'Percentage',
    ];
}
