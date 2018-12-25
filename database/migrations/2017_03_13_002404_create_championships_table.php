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
            $table->morphs('champion');
            $table->datetime('won_on');
            $table->datetime('lost_on')->nullable();
            $table->unsignedInteger('successful_defenses')->default(0);
            $table->timestamps();

            $table->unique(['title_id', 'champion_id', 'champion_type', 'won_on']);
            $table->foreign('title_id')->references('id')->on('titles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('championships');
    }
}
