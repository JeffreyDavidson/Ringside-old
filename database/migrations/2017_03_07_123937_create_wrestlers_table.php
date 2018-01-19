<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWrestlersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wrestlers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->unsignedInteger('status_id')->index();
            $table->dateTime('hired_at')->nullable();
            $table->string('hometown');
            $table->integer('height');
            $table->integer('weight');
            $table->string('signature_move')->unique();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('status_id')->references('id')->on('wrestler_statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wrestlers');
    }
}
