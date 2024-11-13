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
        Schema::create('requirement_matrices', function (Blueprint $table) {      
            $table->id();
            $table->integer('project_id');
            $table->string('requirement_id');
            $table->text('requirement_details');
            $table->string('categories');
            $table->string('implementable');
            $table->string('testable');
            $table->string('implementation_status');
            $table->string('testing_status');
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requirement_matrices');
    }
};
