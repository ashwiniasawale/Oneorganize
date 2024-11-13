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
        if (!Schema::hasTable('employee_assets')) {
        Schema::create('employee_assets', function (Blueprint $table) {
            $table->id();
            $table->text('asset_id');
            $table->integer('employee_id');
            $table->integer('created_by')->default(0);
            $table->timestamps();
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_assets');
    }
};
