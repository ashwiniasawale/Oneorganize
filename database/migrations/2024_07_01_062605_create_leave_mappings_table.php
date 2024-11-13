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
        if (!Schema::hasTable('leave_mappings')) {
        Schema::create('leave_mappings', function (Blueprint $table) {
            $table->id();
            $table->integer('leave_id');
            $table->date('leave_date');
            $table->string('leave_type');
            $table->string('status');
            $table->string('leave_count')->nullable();
            $table->integer('approved_by');
            $table->timestamps();
        });
    }
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_mappings');
    }
};
