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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('project_id');
            $table->date('review_date')->nullable();
            
            $table->integer('attended_by');
            $table->text('artifacts_of_review')->nullable();
            $table->text('checklist');
            
            $table->string('review_criteria');
            $table->text('requirement');
            $table->text('non_conf_list');
            $table->text('improvement_suggestions');
            $table->integer('risk_identified');
            $table->text('problem_discover');
       
            $table->string('deviation_taken');
            $table->string('is_updated');
           
            $table->integer('created_by');
          
           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
