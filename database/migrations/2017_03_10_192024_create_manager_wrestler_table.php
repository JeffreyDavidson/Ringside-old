<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagerWrestlerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manager_wrestler', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('manager_id')->index();
            $table->unsignedInteger('wrestler_id')->index();
            $table->dateTime('hired_on');
            $table->dateTime('fired_on')->nullable();
            $table->timestamps();

            $table->foreign('manager_id')->references('id')->on('managers');
            $table->foreign('wrestler_id')->references('id')->on('wrestlers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manager_wrestler');
    }
}
