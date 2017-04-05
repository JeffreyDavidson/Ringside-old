<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWrestlerInjuriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wrestler_injuries', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('wrestler_id')->index();
            $table->dateTime('injured_at');
            $table->dateTime('healed_at')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('wrestler_injuries');
    }
}
