<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'assets', function (Blueprint $table){
            $table->bigIncrements('id');
           
            $table->string('name');
            $table->text('asset_picture')->nullable();
            $table->string('serial_number');
            $table->integer('total_count');
            $table->string('status');
            $table->text('description')->nullable();
            $table->integer('created_by')->default(0);
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
        Schema::dropIfExists('assets');
    }
}
