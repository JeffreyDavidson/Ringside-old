<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWrestlerBiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wrestler_bios', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('wrestler_id');
            $table->string('hometown');
            $table->integer('height');
            $table->integer('weight');
            $table->string('signature_move')->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wrestler_bios');
    }
}
