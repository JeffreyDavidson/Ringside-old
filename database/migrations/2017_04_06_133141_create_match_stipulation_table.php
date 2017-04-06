<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchStipulationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_stipulation', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('match_id')->index();
            $table->unsignedInteger('stipulation_id')->index();
            $table->timestamps();

            $table->foreign('match_id')->references('id')->on('matches');
            $table->foreign('stipulation_id')->references('id')->on('stipulations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_stipulation');
    }
}
