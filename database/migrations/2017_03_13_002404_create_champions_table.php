<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChampionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('champions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('title_id')->index();
            $table->unsignedInteger('wrestler_id')->index();
            $table->dateTime('won_on');
            $table->dateTime('lost_on')->nullable();
            $table->timestamps();

            $table->unique(['title_id', 'wrestler_id', 'won_on']);
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
