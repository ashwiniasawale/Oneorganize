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
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('test_name');
            $table->text('test_description')->nullable();
            $table->integer('estimated_hrs')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('priority', 50)->default('medium');
            $table->string('priority_color', 50)->nullable();
            $table->string('task_activity',200)->nullable();
            $table->string('test_procedures',200)->nullable();
            $table->string('test_input',200)->nullable();
            $table->string('test_accepted_output',200)->nullable();
            $table->string('test_plan',200)->nullable();
            $table->string('test_note',200)->nullable();
            $table->string('test_result',200)->nullable();
            $table->string('test_type',200)->nullable();
            $table->text('assign_to')->nullable();
            $table->text('requirement_id')->nullable();
            $table->integer('project_id')->default(0);
            $table->integer('milestone_id')->default(0);
            $table->integer('stage_id')->default(0);
            $table->string('deliverables');
            
            $table->integer('created_by')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
