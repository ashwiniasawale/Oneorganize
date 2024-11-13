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
        Schema::create('risks', function (Blueprint $table) {
            $table->id();
            $table->integer('project_id');
            $table->text('risk_details');
            $table->string('priority');
            $table->date('identified_on');
            $table->date('mitigation_target_date');
            $table->integer('responsible_person');
            $table->string('risk_classification');
            $table->text('risk_description')->nullable();
            $table->string('risk_impact')->nullable();
            $table->string('risk_severity')->nullable();
            $table->string('risk_probability')->nullable();
            $table->string('status');
            $table->text('risk_consequence')->nullable();
            $table->string('risk_score')->nullable();
            $table->integer('mitigation_person');
            $table->text('critical_dependency')->nullable();
            $table->text('mitigation_resource')->nullable();
            $table->text('financial_impact')->nullable();
            $table->text('timeline_impact')->nullable();
            $table->text('action_item')->nullable();
            $table->text('action_taken')->nullable();
            $table->text('assumptions_made')->nullable();
            $table->text('changes_in_project_plan')->nullable();
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risks');
    }
};
