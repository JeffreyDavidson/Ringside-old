<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchTitleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_title', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('match_id')->index();
            $table->unsignedInteger('title_id')->index();
            $table->timestamps();

            $table->unique(['match_id', 'title_id']);
            $table->foreign('match_id')->references('id')->on('matches');
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
        Schema::dropIfExists('match_title');
    }
}
