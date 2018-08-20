<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchWinnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_winner', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('match_id')->index();
            $table->unsignedInteger('wrestler_id')->index();

            $table->unique(['match_id', 'wrestler_id']);
            $table->foreign('match_id')->references('id')->on('matches');
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
        Schema::dropIfExists('match_winner');
    }
}
