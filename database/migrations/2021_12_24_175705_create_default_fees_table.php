<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultFeesTable extends Migration
{
    public function up()
    {
        Schema::create('default_fees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('field_id');
            $table->smallInteger('unit')->default(0); // 0 => %, 1=> tk
            $table->double('amount');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('field_id')->references('id')->on('fields');
        });
    }

    public function down()
    {
        Schema::dropIfExists('default_fees');
    }
}
