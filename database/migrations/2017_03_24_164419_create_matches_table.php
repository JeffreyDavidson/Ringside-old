<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('event_id')->index()->nullable();
            $table->unsignedInteger('match_number');
            $table->unsignedInteger('match_type_id')->index();
            $table->unsignedInteger('match_stipulation_id')->index()->nullable();
            $table->unsignedInteger('match_decision_id')->index()->nullable();
            $table->text('preview');
            $table->timestamps();

            $table->unique(['event_id', 'match_number']);
            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('match_type_id')->references('id')->on('match_types');
            $table->foreign('match_stipulation_id')->references('id')->on('match_stipulations');
            $table->foreign('match_decision_id')->references('id')->on('match_decisions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matches');
    }
}
