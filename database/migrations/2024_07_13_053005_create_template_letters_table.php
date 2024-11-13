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
        if (!Schema::hasTable('template_letters')) {
        Schema::create('template_letters', function (Blueprint $table) {
            $table->id();
            $table->string('employee_type');
            $table->string('title');
            $table->string('employee_name');
            $table->date('offer_date');
            $table->date('joining_date');
            $table->string('address');
            $table->string('designation');
            $table->integer('probation');
            $table->integer('notice_period');
            $table->integer('salary');
            $table->string('ref_no');
            $table->timestamps();
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_letters');
    }
};
