<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'leads', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject');
            $table->integer('user_id');
            $table->string('location');
            $table->integer('country');
            $table->integer('state');
            $table->string('city');
            $table->string('client_requirements');
            $table->integer('responsible_person');
       
            $table->integer('pipeline_id');
            $table->integer('stage_id');
            $table->string('sources')->nullable();
            $table->string('products')->nullable();
            $table->text('notes')->nullable();
            $table->string('labels')->nullable();
            $table->integer('order')->default(0);
            $table->integer('created_by');
            $table->integer('is_active')->default(1);
            $table->integer('is_converted')->default(0);
            $table->date('date')->nullable();
            $table->timestamps();
        }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads');
    }
}
