<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('project_subtasks')) {
        Schema::create('project_subtasks', function (Blueprint $table) {
            $table->id();
            $table->string('subtask_name');
            $table->integer('task_id')->default(0);
            $table->text('description')->nullable();
              
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->string('priority', 50)->default('medium');
                $table->string('priority_color', 50)->nullable();
                $table->string('task_activity',200)->nullable();
                $table->string('task_activity_type',200)->nullable();
                $table->text('assign_to')->nullable();
                $table->text('requirement_id')->nullable();
                $table->text('comment')->nullable();
                $table->text('remark')->nullable();
                $table->integer('project_id')->default(0);
                $table->integer('subtask_seq');
                $table->integer('milestone_id')->default(0);
                $table->integer('stage_id')->default(0);
                $table->string('deliverables');
                $table->string('predece')->nullable();
                $table->integer('created_by')->default(0);
               
                $table->string('progress', 5)->default(0);
                $table->timestamps();
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_subtasks');
    }
};
