<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Test extends Model
{
    protected $fillable = [
        'test_name',
        'test_description',
        'estimated_hrs',
        'start_date',
        'end_date',
        'priority',
        'test_procedures',
        'test_input',
        'test_accepted_output',
        'test_plan',
        'test_note',
        'test_result',
        'test_type',
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
       
    ];
    
    public static $priority = [
        'critical' => 'Critical',
        'high' => 'High',
        'medium' => 'Medium',
        'low' => 'Low',
    ];
    public static $hardware_activity_type=[
        'hardware_architecture'=>'H/W Architecture',
        'hardware_detailed_design_description'=>'H/W Detailed Design Description',
        'hardware_interface_requirement'=>'H/W interface requirement',
        'schematics'=>'Schematics',
        'WCCA'=>'WCCA',
        'bill_and_materials'=>'Bill & Materials',
        'pcb_layout'=>'PCB Layout',
        'analysis_report'=>'Analysis Report',
        'hardware_production_data'=>'Hardware Production Data',
        'prototyping'=>'Prototyping',
        'assembly'=>'Assembly',
        'bordbringup'=>'Bordbringup'
    ];
    public static $software_activity_type=[
        'software_architecture'=>'S/W Architecture',
        'software_design_document'=>'S/W Design Document',
        'low_level_driver_development'=>'Low Level Driver Development',
        'middle_level_driver_development'=>'Middle Level Driver Development',
        'device_driver_development'=>'Device Driver Development',
        'application_development'=>'Application Development',
        'testcode_generation'=>'Testcode Generation'

    ];
    public static $test_type=[
        'functiona_testing'=>'Functional testing',
        'integration_testing'=>'Integration Testing',
        'unit_testing'=>'Unit Testing',
        'validation testing'=>'Validation Testing',
        'certification_testing'=>'Certification Testing',
        'manual_testing'=>'Manual Testing',
        'regration_testing'=>'Regration Testing',
        'verification_testing'=>'Verification Testing'
    ];
    public static $priority_color = [
        'critical' => 'danger',
        'high' => 'warning',
        'medium' => 'primary',
        'low' => 'info',
    ];

    public function milestone()
    {
        return $this->hasOne('App\Models\Milestone', 'id', 'milestone_id');
    }
    
    public function users()
    {
        return User::whereIn('id', explode(',', $this->assign_to))->get();
    }

    public static function get_task_activity_type($task_activity)
    {
        $get_task_activity_type=DB::select('select task_activity_type from project_tasks where task_activity="'.$task_activity.'"');
        return $get_task_activity_type;
    }
}
