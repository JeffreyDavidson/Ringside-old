<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTitleWrestlerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('title_wrestler', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('title_id')->index();
            $table->unsignedInteger('wrestler_id')->index();
            $table->dateTime('won_on');
            $table->dateTime('lost_on')->nullable();
            $table->timestamps();

            $table->unique(['title_id', 'won_on']);
            $table->foreign('title_id')->references('id')->on('titles');
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
        Schema::dropIfExists('title_wrestler');
    }
}
