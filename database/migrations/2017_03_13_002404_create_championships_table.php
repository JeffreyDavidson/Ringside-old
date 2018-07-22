<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChampionshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('championships', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('title_id')->index();
            $table->unsignedInteger('wrestler_id')->index();
            $table->datetime('won_on');
            $table->timestamp('lost_on')->nullable();
            $table->unsignedInteger('successful_defenses')->default(0);
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
