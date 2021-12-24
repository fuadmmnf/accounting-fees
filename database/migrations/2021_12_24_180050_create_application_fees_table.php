<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationFeesTable extends Migration
{
    public function up()
    {
        Schema::create('application_fees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('application_id');
            $table->unsignedBigInteger('field_id')->nullable();
            $table->string('optional_field_name')->default('');
            $table->smallInteger('unit')->default(0); // 0 => %, 1=> tk
            $table->double('amount');
            $table->timestamps();

            $table->foreign('application_id')->references('id')->on('applications');
            $table->foreign('field_id')->references('id')->on('fields');
        });
    }

    public function down()
    {
        Schema::dropIfExists('application_fees');
    }
}
