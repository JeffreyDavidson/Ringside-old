<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInjuriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('injuries', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('wrestler_id')->index();
            $table->timestamp('injured_at');
            $table->timestamp('healed_at')->nullable();
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
        Schema::dropIfExists('injuries');
    }
}
