<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Branch extends Model
{
    protected $fillable = [
        'name','created_by'
    ];

    public static function get_branch()
    {
        $get_branch=DB::select('select id,name from branches where 1');
        return $get_branch;
    }
}
