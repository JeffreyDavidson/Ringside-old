<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchRefereeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_referee', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('match_id')->index();
            $table->unsignedInteger('referee_id')->index();
            $table->timestamps();

            $table->unique(['match_id', 'referee_id']);
            $table->foreign('match_id')->references('id')->on('matches');
            $table->foreign('referee_id')->references('id')->on('referees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_referee');
    }
}
