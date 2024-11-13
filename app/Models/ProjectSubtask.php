<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Utility;
class ProjectSubtask extends Model
{
    protected $fillable = [
        'subtask_name',
        
        'task_id',
        'description',
        
        'start_date',
        'end_date',
        'priority',
        'priority_color',
        'task_activity',
        'task_activity_type',
        'assign_to',
        'requirement_id',
        'project_id',
        'milestone_id',
        'stage_id',
        'deliverables',
        
        'created_by',
        'predece',
        'subtask_seq',
        'progress',
        'comment',
        'remark',
        
    ];

    public function users()
    {
        return User::whereIn('id', explode(',', $this->assign_to))->get();
    }
    private static $user = NULL;
    private static $data = NULL;

    public static function getusers()
    {
        $data = [];
        if (self::$user == null) {
            $user = User::get();
            self::$user = $user;
            foreach (self::$user as $user) {
                $data[$user->id]['id'] = $user->id;
                $data[$user->id]['name'] = $user->name;
                $data[$user->id]['avatar'] = $user->avatar;

            }
            self::$data = $data;
        }
        return self::$data;
    }
    public function stage()
    {
        return $this->hasOne('App\Models\TaskStage', 'id', 'stage_id');
    }
}
